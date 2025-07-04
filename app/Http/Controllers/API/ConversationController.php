<?php

namespace App\Http\Controllers\API;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    /**
     * Get or create a conversation between customer and chef
     */
    public function getOrCreateConversation($chefId)
    {
        $customer = Auth::user()->customer;
        
        if (!$customer) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        $conversation = Conversation::where('customer_id', $customer->id)
            ->where('chef_id', $chefId)
            ->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'customer_id' => $customer->id,
                'chef_id' => $chefId,
                'status' => 'active',
            ]);
        }

        return response()->json([
            'conversation' => $conversation->load(['messages' => function($query) {
                $query->latest()->take(20);
            }])
        ]);
    }

    /**
     * Send a message in a conversation
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $conversation = Conversation::findOrFail($conversationId);

        // Check if user is part of the conversation
        if ($user->customer && $conversation->customer_id !== $user->customer->id ||
            $user->chef && $conversation->chef_id !== $user->chef->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_type' => $user->user_type, // 'customer' or 'chef'
            'sender_id' => $user->id,
            'message' => $request->message,
        ]);

        // Broadcast the message to the other participant
        // You can implement broadcasting here if needed

        return response()->json(['message' => $message->load('sender')]);
    }

    /**
     * Get user's conversations
     */
    public function getUserConversations()
    {
        $user = Auth::user();
        
        $conversations = [];
        
        if ($user->user_type === 'customer') {
            $conversations = Conversation::where('customer_id', $user->customer->id)
                ->with(['chef.user', 'lastMessage'])
                ->latest('updated_at')
                ->get();
        } elseif ($user->user_type === 'chef') {
            $conversations = Conversation::where('chef_id', $user->chef->id)
                ->with(['customer.user', 'lastMessage'])
                ->latest('updated_at')
                ->get();
        }

        return response()->json(['conversations' => $conversations]);
    }

    /**
     * Get messages in a conversation
     */
    public function getMessages($conversationId)
    {
        $user = Auth::user();
        $conversation = Conversation::findOrFail($conversationId);

        // Check if user is part of the conversation
        if (($user->customer && $conversation->customer_id !== $user->customer->id) ||
            ($user->chef && $conversation->chef_id !== $user->chef->id)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $messages = $conversation->messages()
            ->with('sender')
            ->latest()
            ->paginate(20);

        // Mark messages as read
        if ($user->user_type === 'customer') {
            $conversation->messages()
                ->where('sender_type', 'chef')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        } else {
            $conversation->messages()
                ->where('sender_type', 'customer')
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return response()->json(['messages' => $messages]);
    }
}
