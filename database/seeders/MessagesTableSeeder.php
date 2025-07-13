<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conversations = Conversation::all();

        if ($conversations->isEmpty()) {
            
            Message::factory()
                ->count(100)
                ->create();

            Message::factory()
                ->count(50)
                ->text()
                ->create();

            Message::factory()
                ->count(20)
                ->voice()
                ->create();

            Message::factory()
                ->count(30)
                ->unread()
                ->create();
        } else {
            $conversations->each(function ($conversation) {
                $messageCount = rand(3, 15); 
                
                $participants = collect([
                    $conversation->chef->id ?? null,
                    $conversation->customer->id ?? null
                ])->filter()->values();

                if ($participants->isEmpty()) {
                    $users = User::inRandomOrder()->take(2)->pluck('id');
                    $participants = $users;
                }

                for ($i = 0; $i < $messageCount; $i++) {
                    $senderId = $participants->random();
                    $messageType = rand(1, 10) > 8 ? 'voice' : 'text';
                    
                    $message = Message::factory()->create([
                        'conversation_id' => $conversation->id,
                        'sender_id' => $senderId,
                        'type' => $messageType,
                        'seen_at' => rand(1, 10) > 3 ? now()->subHours(rand(1, 48)) : null,
                    ]);

                    $conversation->updateLastMessageAt();
                }
            });

            Message::factory()
                ->count(50)
                ->recent()
                ->create();
        }

    }
} 
