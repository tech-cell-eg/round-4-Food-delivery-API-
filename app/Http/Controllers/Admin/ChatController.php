<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * عرض صفحة الدردشة الرئيسية
     */
    public function index()
    {
        $user = Auth::user();
        $conversations = [];

        if ($user->type === 'customer') {
            $conversations = Conversation::where('customer_id', $user->id)
                ->with(['chef.user', 'lastMessage'])
                ->latest('updated_at')
                ->get();
        } elseif ($user->type === 'chef') {
            $conversations = Conversation::where('chef_id', $user->id)
                ->with(['customer.user', 'lastMessage'])
                ->latest('updated_at')
                ->get();
        }

        return view('admin.chat.index', compact('conversations'));
    }

    /**
     * عرض محادثة معينة
     */
    public function show($conversationId)
    {
        $user = Auth::user();
        
        // جلب المحادثات للمستخدم الحالي
        $conversations = [];
        if ($user->type === 'customer') {
            $conversations = Conversation::where('customer_id', $user->id)
                ->with(['chef.user', 'lastMessage'])
                ->latest('updated_at')
                ->get();
        } elseif ($user->type === 'chef') {
            $conversations = Conversation::where('chef_id', $user->id)
                ->with(['customer.user', 'lastMessage'])
                ->latest('updated_at')
                ->get();
        }

        // جلب المحادثة المحددة مع رسائلها
        $conversation = Conversation::with(['messages' => function($query) {
            $query->orderBy('created_at', 'asc')->take(50);
        }, 'customer.user', 'chef.user'])->findOrFail($conversationId);

        // التحقق من صلاحيات المستخدم
        if (($user->type === 'customer' && $conversation->customer_id !== $user->id) ||
            ($user->type === 'chef' && $conversation->chef_id !== $user->id)) {
            abort(403);
        }

        // تحديث الرسائل كمقروءة
        $this->markMessagesAsRead($conversation, $user);

        return view('admin.chat.show', [
            'conversation' => $conversation,
            'conversations' => $conversations
        ]);
    }

    /**
     * الحصول على رسائل المحادثة (API)
     */
    public function getMessages($conversationId, Request $request)
    {
        $conversation = Conversation::findOrFail($conversationId);

        // التحقق من صلاحيات المستخدم
        $user = Auth::user();
        if (($user->type === 'customer' && $conversation->customer_id !== $user->id) ||
            ($user->type === 'chef' && $conversation->chef_id !== $user->id)) {
            abort(403);
        }

        // جلب الرسائل بناءً على المعلمات المطلوبة
        $lastUpdated = $request->input('last_updated', 0);
        $beforeId = $request->input('before_id');
        $limit = $request->input('limit', 50);
        
        $query = $conversation->messages();
        
        // تصفية الرسائل حسب المعلمات
        if ($lastUpdated) {
            $query->where('updated_at', '>', \Carbon\Carbon::createFromTimestamp($lastUpdated));
        }
        
        if ($beforeId) {
            $query->where('id', '<', $beforeId);
        }
        
        // ترتيب وتحديد عدد الرسائل
        $messages = $query->orderBy('created_at', 'desc')
                         ->limit($limit)
                         ->get();
        
        // إذا كنا نحمل رسائل أقدم، نعيدها بالترتيب العكسي
        if ($beforeId) {
            $messages = $messages->sortBy('created_at');
        }

        // تحديث الرسائل كمقروءة إذا كان هناك رسائل جديدة
        if ($messages->isNotEmpty()) {
            $this->markMessagesAsRead($conversation, $user);
        }

        return response()->json([
            'messages' => $messages,
            'last_updated' => $conversation->fresh()->updated_at->timestamp
        ]);
    }

    /**
     * إرسال رسالة جديدة
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $conversation = Conversation::findOrFail($conversationId);

        // التحقق من صلاحيات المستخدم
        $user = Auth::user();
        if (($user->type === 'customer' && $conversation->customer_id !== $user->id) ||
            ($user->type === 'chef' && $conversation->chef_id !== $user->id)) {
            abort(403);
        }

        // إنشاء الرسالة الجديدة
        $message = $conversation->messages()->create([
            'sender_type' => $user->type,
            'sender_id' => $user->id,
            'message' => $request->input('message'),
        ]);

        // تحديث وقت تحديث المحادثة
        $conversation->touch();

        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $message->load('sender')
            ]);
        }

        return redirect()->back()->with('success', 'تم إرسال الرسالة بنجاح');
    }

    /**
     * تحديد الرسائل كمقروءة
     */
    private function markMessagesAsRead($conversation, $user)
    {
        $senderType = $user->type === 'customer' ? 'chef' : 'customer';
        
        $conversation->messages()
            ->where('sender_type', $senderType)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
