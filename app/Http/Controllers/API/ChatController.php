<?php

namespace App\Http\Controllers\Api;

use App\Events\MessagesSeenEvent;
use App\Http\Requests\StoreNewMessageRequest;
use Illuminate\Support\Str;
use App\Events\NewConversationMessageEvent;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Customer;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Storage;


class ChatController extends Controller
{
    public function show($conversationId)
    {
        // $authenticatedUser = Auth::user();
        $authenticatedUser = User::find(1);

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
        $validatedData = $request->validated();

        $sender = User::where("type", "chef")->first();

        // $sender = Auth::user();
        $receiver = User::find($validatedData['receiver_id']);
        [$customerId, $chefId] = $this->resolveParticipants($sender, $receiver);
        if (is_null($customerId) || is_null($chefId)) {
            return ApiResponse::error("Invalid sender/receiver combination", 422);
        }

        $conversation = $this->getOrCreateConversation($chefId, $customerId);

        $content = $validatedData['content'] ?? null;
        if ($validatedData['type'] === 'voice' && $request->hasFile('audio')) {
            $file = $request->file('audio');
            $uniqueName = 'audio_' . ($sender->id ?? 'user') . '_' . time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
            $audioPath = $file->storeAs('audio', $uniqueName, 'public');
            $content = $audioPath;
        } elseif ($validatedData['type'] === 'text' && !$content) {
            return ApiResponse::validationError(['content' => ['محتوى الرسالة مطلوب للرسائل النصية']], 'خطأ في البيانات المرسلة');
        }

        $message = $this->storeMessage($conversation, $sender, $content, $validatedData['type']);

        if (! $message) {
            return ApiResponse::error("Something went wrong", 422);
        }

        $conversation->updateLastMessageAt();

        $messageResource = new MessageResource($message);
        Broadcast(new NewConversationMessageEvent($messageResource));

        return ApiResponse::success($messageResource, 'Message sent successfully');
    }


    public function destroyMessage($messageId)
    {
        // $authenticatedUser = Auth::user();
        $authenticatedUser = User::find(1);

        $message = Message::find($messageId);
        if (! $message) {
            return ApiResponse::notFound("Message not found");
        }

        if($message->sender_id != $authenticatedUser->id){
            return ApiResponse::error("You can't delete this message");
        }

        if($message->type == "voice"){
            $this->deleteAudioFile($message);
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


    protected function handleAudioFiles($senderId, $request)
    {
        $file = $request->file('audio');
        $uniqueName = 'audio_' . ($senderId ?? 'user') . '_' . time() . '_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
        $audioPath = $file->storeAs('audio', $uniqueName, 'public');

        return $content = $audioPath;
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
