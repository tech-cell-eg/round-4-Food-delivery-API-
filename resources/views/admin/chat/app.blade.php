@extends('layouts.app')

@push('styles')
<link href="{{ asset('css/chat.css') }}" rel="stylesheet">
<style>
    .chat-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .chat-header {
        background-color: #0d6efd;
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chat-header .back-button {
        color: white;
        font-size: 18px;
        margin-right: 10px;
        display: none;
    }

    @media (max-width: 768px) {
        .chat-header .back-button {
            display: inline-block;
        }
    }

    .chat-header .user-info {
        display: flex;
        align-items: center;
    }

    .chat-header .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }

    .chat-header .user-status {
        font-size: 12px;
        font-weight: normal;
        opacity: 0.8;
    }

    .chat-header .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }

    .status-online {
        background-color: #52c41a;
    }

    .status-offline {
        background-color: #f5222d;
    }

    .chat-sidebar {
        border-left: 1px solid #e3e6f0;
        height: calc(100vh - 200px);
        overflow-y: auto;
    }

    .chat-messages {
        height: calc(100vh - 250px);
        overflow-y: auto;
        padding: 20px;
        background-color: #f8f9fc;
    }

    .message {
        margin-bottom: 15px;
        display: flex;
        flex-direction: column;
    }

    .message.sent {
        align-items: flex-start;
    }

    .message.received {
        align-items: flex-end;
    }

    .message-bubble {
        max-width: 70%;
        padding: 10px 15px;
        border-radius: 15px;
        margin-bottom: 5px;
    }

    .sent .message-bubble {
        background-color: #e3f2fd;
        border-bottom-right-radius: 0;
    }

    .received .message-bubble {
        background-color: #4e73df;
        color: white;
        border-bottom-left-radius: 0;
    }

    .message-time {
        font-size: 0.8em;
        color: #6c757d;
    }

    .conversation-item {
        padding: 10px;
        border-bottom: 1px solid #e3e6f0;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .conversation-item:hover {
        background-color: #f8f9fc;
    }

    .conversation-item.active {
        background-color: #e3f2fd;
    }

    .unread-count {
        background-color: #e74a3b;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7em;
        margin-right: 5px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="chat-container
                @if(Request::is('admin/chat*'))
                    @if(!Request::is('admin/chat'))
                        d-md-flex
                    @endif
                @endif">

                @yield('chat_content')

            </div>
        </div>
    </div>
</div>
@endsection