@extends('admin.chat.app')

@section('chat_content')
<div class="chat-container">
    <div class="chat-header">
        <h4 class="mb-0">{{ __('Conversations') }}</h4>
    </div>

    <div class="row g-0">
        <!-- قائمة المحادثات -->
        <div class="col-md-4">
            <div class="chat-sidebar">
                @if($conversations->count() > 0)
                <div class="list-group">
                    @foreach($conversations as $conversation)
                    @php
                    // تحديد المستخدم الآخر في المحادثة
                    $otherUser = auth()->user()->type === 'customer'
                    ? $conversation->chef->user
                    : $conversation->customer->user;

                    // حساب عدد الرسائل غير المقروءة
                    $unreadCount = $conversation->messages()
                    ->where('sender_type', '!=', auth()->user()->type)
                    ->whereNull('read_at')
                    ->count();
                    @endphp

                    <a href="{{ route('chat.show', $conversation->id) }}" class="text-decoration-none">
                        <div
                            class="conversation-item {{ (request()->route('conversation') == $conversation->id) || (is_null(request()->route('conversation')) && $loop->first) ? 'active' : '' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $otherUser->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherUser->name) }}" alt="{{ $otherUser->name }}"
                                        class="rounded-circle me-2" width="50" height="50">
                                    <div>
                                        <h5 class="mb-0">{{ $otherUser->name }}</h5>
                                        <small class="text-muted">
                                            @if($conversation->lastMessage)
                                            {{ Str::limit($conversation->lastMessage->message, 30) }}
                                            @else
                                            لا توجد رسائل بعد
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                @if($unreadCount > 0)
                                <span class="unread-count">{{ $unreadCount }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                    <p class="text-muted">{{ __('No conversations yet') }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- منطقة المحادثة -->
        <div class="col-md-8">
            @php
            $activeConversation = $conversations->first();
            @endphp

            @if($activeConversation)
            @php
            // تحديد المستخدم الآخر فى المحادثة النشطة
            $otherActive = auth()->user()->type === 'customer'
            ? $activeConversation->chef->user
            : $activeConversation->customer->user;

            // جلب الرسائل (آخر 50 رسالة)
            $messages = $activeConversation->messages()->orderBy('created_at', 'asc')->take(50)->get();
            @endphp

            <div class="d-flex flex-column h-100">
                <!-- رأس المحادثة النشطة -->
                <div class="chat-header d-flex align-items-center">
                    <img src="{{ $otherActive->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($otherActive->name) }}" class="rounded-circle me-2" width="45" height="45"
                        alt="{{ $otherActive->name }}">
                    <h5 class="mb-0">{{ $otherActive->name }}</h5>
                </div>

                <!-- الرسائل -->
                <div class="chat-messages flex-grow-1" id="chat-messages">
                    @forelse($messages as $msg)
                    @php $isSent = $msg->sender_type === auth()->user()->type; @endphp
                    <div class="message {{ $isSent ? 'sent text-start' : 'received text-end' }}">
                        <div class="message-bubble">
                            {!! nl2br(e($msg->message)) !!}
                        </div>
                        <small class="message-time text-muted">
                            {{ $msg->created_at->format('H:i') }}
                        </small>
                    </div>
                    @empty
                    <div class="text-center text-muted mt-5">
                        <i class="fas fa-comments fa-3x mb-3"></i>
                        <p>{{ __('No messages yet') }}</p>
                    </div>
                    @endforelse
                </div>

                <!-- نموذج الإرسال -->
                <form action="{{ route('chat.send', $activeConversation->id) }}" method="POST" class="p-3 border-top d-flex align-items-center">
                    @csrf
                    <input type="text" class="form-control me-2" name="message" placeholder="اكتب رسالة..." required>
                    <button class="btn btn-primary" type="submit">إرسال</button>
                </form>
            </div>
            @else
            <div class="d-flex flex-column h-100">
                <div class="chat-messages flex-grow-1" id="chat-messages">
                    <div class="text-center text-muted mt-5">
                        <i class="fas fa-comments fa-3x mb-3"></i>
                        <p>{{ __('No conversations yet') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection