<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_fetch_their_most_recent_reply()
    {
        // lets create a user
        $user = create('App\User');

        // lets create a reply and associate the user with the reply
        $reply = create('App\Reply', ['user_id' => $user->id]);

        // if we want the users last reply, i expect the id of
        // that recent reply to equal this very reply
        $this->assertEquals($reply->id, $user->lastReply->id);

        //  ofcourse if we run this it fails because
        // last reply doesn't exist yet.
        //  so lets go over User model and create last $reply
    }
}
