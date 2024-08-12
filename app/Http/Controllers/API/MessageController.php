<?php

namespace App\Http\Controllers\API;

use Pusher\Pusher;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class MessageController extends Controller
{
    protected $pusher;


    public function __construct(){
        $this->pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                'useTLS' => false,
            ]
        );
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Retrieve messages between two users
        $all_messages = Messages::get_messages_between_two_users($request, $request->receiver_id, $request->sender_id);
        $all_result = $all_messages->orderBy('created_at', 'desc')
            ->limit($request->limit ?? 10)
            ->get();

        // Mark messages as seen
        if ($all_result->count()) {
            foreach ($all_result as $message) {
                $message->update(['seen' => 0]);
            }
        }

        // Return the retrieved messages as JSON
        return response()->json(["status" => true, "message" => $all_result], 200);
    }

    public function store(Request $request)
    {
        if (!$request->message_content){
            return response()->json(['status'=>false], 500);
        }

        $all_message = new Messages();
        $all_message->sender_id = $request->current_user;
        $all_message->receiver_id  = $request->receiver_id;
        $all_message->content  = $request->message_content;
        $all_message->save();

        $updated_message = Messages::with(['sender', 'receiver'])->find($all_message->id);

        $this->pusher->trigger('chat.' . $request->receiver_id, 'message', [
            'message' => $updated_message,
        ]);
        return response()->json(['status'=>true, "message"=>$updated_message], 201);

    }
}
