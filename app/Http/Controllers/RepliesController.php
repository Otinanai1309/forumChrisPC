<?php

namespace App\Http\Controllers;

use App\Inspections\Spam;
use App\Reply;
use App\Thread;

class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }

    /**
     * Fetch all relevant replies.
     *
     * @param int    $channelId
     * @param Thread $thread
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    /**
     * Persist a new reply.
     *
     * @param  integer $channelId
     * @param  Thread  $thread
     * @param Spam     $spam
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread)
    {
        // *********until now we catch the spam at the database
        // level but we don't have any front-end response for that.
        // so lets try to implement the front-end
        // **************************************************

        try {
            $this->validateReply();

            $reply = $thread->addReply([
                'body' => request('body'),
                'user_id' => auth() -> id()
            ]);
        }   catch (\Exception $e) {
                return response(
                    'Sorry, your reply could not be saved at this time.', 422
                );
        }


        // if (request()->expectsJson()) {
        //     return $reply->load('owner');
        // }

        // **************Or we could just write***************
        return $reply->load('owner');
        // ***************************************************

        // return back()->with('flash', 'Your reply has been left.');
    }

    /**
     * Update an existing reply.
     *
     * @param Reply $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        try {
            $this->validateReply();
            $reply->update(request(['body']));
        } catch (\Exception $e) {
            return response(
                'Sorry, your reply could not be saved at this time.',422
            );
        }
    }

    /**
     * Delete the given reply.
     *
     * @param  Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }

        return back();
    }

    protected function validateReply()
    {
        // ***** instead of adding to the __construct function
        // the (Spam $spam) we can resolve the Spam class ****

        $this->validate(request(), ['body'=> 'required']);
        resolve(Spam::class)->detect(Request('body'));

    }
}
