<?php

namespace App\Http\Controllers\Api;

use App\Events\MessagesSeenEvent;
use App\Events\UserTypingEvent;
use App\Helpers\ApiResponse;
use App\Http\Requests\StoreNewMessageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Events\NewConversationMessageEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Customer;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;


class ChatController extends Controller
{
    public function typingStatus(Request $request)
    {
        try {
            $request->validate([
                'conversation_id' => 'required|integer|exists:conversations,id',
                'status' => 'required|string|in:typing,stopped_typing,recording,stopped_recording',
            ]);
        } catch (ValidationException $exception) {
            return ApiResponse::validationError($exception->errors());
        }

        event(new UserTypingEvent(
            $request->conversation_id,
            auth()->id(),
            $request->status
        ));

        return ApiResponse::success();
    }

    public function getConversations()
    {
        $authenticatedUser = Auth::user();

        if (!$authenticatedUser) {
            return ApiResponse::class::error('User not authenticated', 401);
        }

        $conversations = collect();

        if ($authenticatedUser->type === 'customer') {
            $customer = $authenticatedUser->customer;
            if ($customer) {
                $conversations = Conversation::with([
                    'chef.user:id,name,type,email,profile_image',
                    'messages' => function ($query) {
                        $query->latest()->limit(1);
                    }
                ])
                ->where('customer_id', $customer->id)
                ->orderByDesc('last_message_at')
                ->get();
            }
        } elseif ($authenticatedUser->type === 'chef') {
            $chef = $authenticatedUser->chef;
            if ($chef) {
                $conversations = Conversation::with([
                    'customer.user:id,name,type,email,profile_image',
                    'messages' => function ($query) {
                        $query->latest()->limit(1);
                    }
                ])
                ->where('chef_id', $chef->id)
                ->orderByDesc('last_message_at')
                ->get();
            }
        }

        $conversationsData = $conversations->map(function ($conversation) use ($authenticatedUser) {
            $lastMessage = $conversation->messages->first();

            $unreadCount = $conversation->messages()
                ->where('sender_id', '!=', $authenticatedUser->id)
                ->whereNull('seen_at')
                ->count();

            $otherParty = $authenticatedUser->type === 'customer'
                ? $conversation->chef->user
                : $conversation->customer->user;

            return [
                'id' => $conversation->id,
                'other_party' => [
                    'id' => $otherParty->id,
                    'name' => $otherParty->name,
                    'email' => $otherParty->email,
                    'profile_image' => $otherParty->profile_image,
                    'type' => $otherParty->type,
                ],
                'last_message' => $lastMessage ? new MessageResource($lastMessage) : null,
                'unread_count' => $unreadCount,
                'last_message_at' => $conversation->last_message_at ?
                    $conversation->last_message_at->diffForHumans() : null,
                'created_at' => $conversation->created_at->diffForHumans(),
            ];
        });

        return ApiResponse::success([
            'conversations' => $conversationsData,
            'total_count' => $conversationsData->count(),
        ], 'Conversations retrieved successfully');
    }

    public function show($conversationId)
    {
        $authenticatedUser = Auth::user();

        $conversation = Conversation::with([
                'customer.user:id,name,email',
                'chef.user:id,name,email',
                'messages' => function ($query) {
                    $query->orderBy('created_at', 'asc');
                }
            ])->find($conversationId);

        if (!$conversation || ! in_array($authenticatedUser->id, [$conversation->chef->id, $conversation->customer->id])) {
            return ApiResponse::notFound('Conversation not found');
        }

        $messagesUpdatedCount = $this->markMessagesAsSeen($conversation, $authenticatedUser->id);

        if($messagesUpdatedCount > 0){
            broadcast(new MessagesSeenEvent($conversation->id, $authenticatedUser))->toOthers();
        }

        return ApiResponse::success([
            'conversation' => [
                'id' => $conversation->id,
                'customer' => [
                    'id' => $conversation->customer->user->id,
                    "name" => $conversation->customer->user->name,
                    "email" => $conversation->customer->user->email,
                ],
                'chef' => [
                    'id' => $conversation->chef->user->id,
                    "name" => $conversation->chef->user->name,
                    "email" => $conversation->chef->user->email,
                ],
                'messages' => MessageResource::collection($conversation->messages()->orderBy('created_at')->get()),
            ]
        ]);
    }


    public function sendMessage(StoreNewMessageRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $sender = Auth::user();
            $receiver = User::find($validatedData['receiver_id']);
            [$customerId, $chefId] = $this->resolveParticipants($sender, $receiver);

            if (is_null($customerId) || is_null($chefId)) {
                return ApiResponse::error("Invalid sender/receiver combination", 422);
            }

            $conversation = $this->getOrCreateConversation($chefId, $customerId);

            $content = null;
            $messageType = $validatedData['type'];

            // تحديد المحتوى حسب نوع الرسالة
            switch ($messageType) {
                case 'text':
                    // للرسائل النصية، content هو النص نفسه
                    $content = $validatedData['content'];
                    break;

                case 'voice':
                    // للرسائل الصوتية، content هو ملف الصوت
                    if (!$request->hasFile('content')) {
                        return ApiResponse::validationError(['content' => ['ملف الصوت مطلوب للرسائل الصوتية']], 'Error in data sent');
                    }
                    $file = $request->file('content');
                    
                    // التحقق من نوع الملف
                    $allowedAudioTypes = ['mp3', 'wav', 'ogg', 'webm'];
                    if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedAudioTypes)) {
                        return ApiResponse::validationError(['content' => ['نوع الملف يجب أن يكون صوت (mp3, wav, ogg, webm)']], 'Error in data sent');
                    }
                    
                    // التحقق من حجم الملف (10 ميجابايت)
                    if ($file->getSize() > 10 * 1024 * 1024) {
                        return ApiResponse::validationError(['content' => ['حجم ملف الصوت يجب أن لا يتجاوز 10 ميجابايت']], 'Error in data sent');
                    }
                    
                    $uniqueName = 'audio_' . ($sender->id ?? 'user') . '_' . time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $content = $file->storeAs('audio', $uniqueName, 'public');
                    break;

                case 'image':
                    // للرسائل المصورة، content هو ملف الصورة
                    if (!$request->hasFile('content')) {
                        return ApiResponse::validationError(['content' => ['ملف الصورة مطلوب للرسائل المصورة']], 'Error in data sent');
                    }
                    $file = $request->file('content');
                    
                    // التحقق من نوع الملف
                    $allowedImageTypes = ['jpeg', 'jpg', 'png', 'gif', 'webp'];
                    if (!in_array(strtolower($file->getClientOriginalExtension()), $allowedImageTypes)) {
                        return ApiResponse::validationError(['content' => ['نوع الملف يجب أن يكون صورة (jpeg, jpg, png, gif, webp)']], 'Error in data sent');
                    }
                    
                    // التحقق من حجم الملف (5 ميجابايت)
                    if ($file->getSize() > 5 * 1024 * 1024) {
                        return ApiResponse::validationError(['content' => ['حجم ملف الصورة يجب أن لا يتجاوز 5 ميجابايت']], 'Error in data sent');
                    }
                    
                    $uniqueName = 'image_' . ($sender->id ?? 'user') . '_' . time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $content = $file->storeAs('images', $uniqueName, 'public');
                    break;

                default:
                    return ApiResponse::error("نوع رسالة غير معروف", 422);
            }

            $message = $this->storeMessage($conversation, $sender, $content, $messageType);

            if (! $message) {
                return ApiResponse::error("Something went wrong", 422);
            }

            $conversation->updateLastMessageAt();

            $messageResource = new MessageResource($message);
            Broadcast(new NewConversationMessageEvent($messageResource));

            return ApiResponse::success($messageResource, 'Message sent successfully');

        } catch (\Throwable $e) {
            return ApiResponse::error('An unexpected error occurred: ' . $e->getMessage(), 500);
        }
    }

    public function destroyMessage($messageId)
    {
        $authenticatedUser = Auth::user();

        $message = Message::find($messageId);
        if (! $message) {
            return ApiResponse::notFound("Message not found");
        }

        if($message->sender_id != $authenticatedUser->id){
            return ApiResponse::error("You can't delete this message");
        }

        if($message->type == "voice"){
            $this->deleteAudioFile($message);
        } elseif($message->type == "image"){
            $this->deleteImageFile($message);
        }

        $message->delete();

        return ApiResponse::success([], 'Message deleted successfully');
    }

    protected function deleteAudioFile($message)
    {
        if ($message->content && $message->type === 'voice' && Storage::disk('public')->exists($message->content)) {
            Storage::disk('public')->delete($message->content);
        }
    }

    protected function deleteImageFile($message)
    {
        if ($message->content && $message->type === 'image' && Storage::disk('public')->exists($message->content)) {
            Storage::disk('public')->delete($message->content);
        }
    }


    protected function markMessagesAsSeen($conversation, $authenticatedUserId)
    {
        $updatedCount = $conversation->messages()
            ->where('sender_id', '!=', $authenticatedUserId)
            ->whereNull('seen_at')
            ->update(['seen_at' => now()]);

        return $updatedCount;
    }


    protected function isAudioFile($type, $request)
    {
        return $type === 'voice' && $request->hasFile('audio');
    }

    protected function isImageFile($type, $request)
    {
        return $type === 'image' && $request->hasFile('image');
    }


    protected function handleAudioFiles($senderId, $request)
    {
        $file = $request->file('audio');
        $uniqueName = 'audio_' . ($senderId ?? 'user') . '_' . time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $audioPath = $file->storeAs('audio', $uniqueName, 'public');

        return $content = $audioPath;
    }

    protected function handleImageFiles($senderId, $request)
    {
        $file = $request->file('image');
        $uniqueName = 'image_' . ($senderId ?? 'user') . '_' . time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $imagePath = $file->storeAs('images', $uniqueName, 'public');

        return $content = $imagePath;
    }


    protected function resolveParticipants($sender, $receiver)
    {
        if ($sender->type === "customer" && $receiver->type === 'chef') {
            return [$sender->id, $receiver->id];
        }

        if ($sender->type === "chef" && $receiver->type === 'customer') {
            return [$receiver->id, $sender->id];
        }

        return [null, null];
    }


    protected function getOrCreateConversation($chefId, $customerId)
    {
        return Conversation::where('chef_id', $chefId)
            ->where('customer_id', $customerId)
            ->firstOr(function () use ($chefId, $customerId) {
                return Conversation::create([
                    'chef_id' => $chefId,
                    'customer_id' => $customerId,
                ]);
            });
    }


    protected function storeMessage($conversation, $sender, $content, $type)
    {
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $sender->id,
            'content' => $content,
            'type' => $type,
        ]);

        return $message ? $message : null;
    }

}
