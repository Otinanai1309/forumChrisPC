<?php

namespace App\Listeners;

use App\Events\ThreadReceivedNewReply;
use App\User;
use App\Notifications\YouWereMentioned;

class NotifyMentionedUsers
{
    /**
     * Handle the event.
     *
     * @param  ThreadReceivedNewReply  $event
     * @return void
     */
    public function handle(ThreadReceivedNewReply $event)
    {
        // Inspect the body of the reply for username mentions
        // preg_match_all('/\@([^\s\.]+)/', $event->reply->body, $matches);
        // $mentionedUsers = $event->reply->mentionedUsers();

        collect($event->reply->mentionedUsers())
            ->map(function($name){
                return User::where('name', $name)->first();
            })
            ->filter()
            ->each(function($user) use ($event) {
                $user->notify(new YouWereMentioned($event->reply));
            });

        // $names = $matches[1];
        // And then for each mentioned user, notify them.
        // foreach ($mentionedUsers as $name) {
        //     if ($user = User::where('name', $name)->first()) {
        //         $user->notify(new YouWereMentioned($event->reply));
        //     };
        // }
    }
}
