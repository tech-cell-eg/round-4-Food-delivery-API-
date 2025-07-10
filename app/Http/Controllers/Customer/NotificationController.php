<?php

namespace App\Http\Controllers\Customer;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()->notifications;

        return ApiResponse::success($notifications);
    }

    public function markAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return ApiResponse::success(null, 'Notifications marked as read');
    }
    public function destroy(Request $request, $id)
    {
        $request->user()->notifications()->findOrFail($id)->delete();

        return ApiResponse::success(null, 'Notification deleted');
    }

    public function getUnreadNotifications(Request $request)
    {
        $notifications = $request->user()->unreadNotifications;

        return ApiResponse::success($notifications);
    }
    
    
    
}
