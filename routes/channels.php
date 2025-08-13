<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Conversation;


//Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
//    $conversation = Conversation::find($conversationId);
//
//    if (!$conversation) {
//        return false;
//    }
//
//    $isParticipant = false;
//
//    if ($user->type === 'customer') {
//        $customer = $user->customer;
//        $isParticipant = $customer && $conversation->customer_id === $customer->id;
//    } elseif ($user->type === 'chef') {
//        $chef = $user->chef;
//        $isParticipant = $chef && $conversation->chef_id === $chef->id;
//    }
//
//    if ($isParticipant) {
//        return [
//            'id' => $user->id,
//            'name' => $user->name,
//            'email' => $user->email,
//            'profile_image' => $user->profile_image,
//            'type' => $user->type,
//        ];
//    }
//
//    return false;
//});
