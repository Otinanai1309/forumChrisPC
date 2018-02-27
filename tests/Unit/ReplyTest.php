<?php
namespace Tests\Unit;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
class ReplyTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function it_has_an_owner()
    {
        $reply = factory('App\Reply')->create();
        $this->assertInstanceOf('App\User', $reply->owner);
    }

    function it_knows_if_it_was_just_published()
    {
        // Create a Reply
        $reply = create('App\Reply');

        // and since the reply was just created
        // i expect to be true that reply was just Published
        $this->assertTrue($reply->wasJustPublished());

        $reply->created_at = Carbon::now()->subMonth();
        $this->assertFalse($reply->wasJustPublished());
        //  it fails because the method does not exist yet.
        //  So i go to Reply model and create the method.

    }

    function it_can_detect_all_mentioned_users_in_the_body()
    {
        $reply = new Reply([
            'body' => '@JaneDoe wants to talk to @JohnDoe'
        ]);

        $this->assertEquals(['JaneDoe', 'JohnDoe'], $reply->mentionedUsers());
    }

    function it_wraps_mentioned_usernames_in_the_body_within_anchor_tags()
    {
        $reply = new Reply([
            'body' => 'Hello @JaneDoe'
        ]);

        $this->assertEquals(
            'Hello <a href= "/profiles/JaneDoe"@JaneDoe</a>.',
            $reply->body
        );
    }
}
