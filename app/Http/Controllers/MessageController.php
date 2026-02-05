<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class MessageController extends Controller {
    public function index(Request $request, Board $board) {
        $messages = $board->messages()->with('user:id,name')->latest()->take(50)->get();

        return response()->json($messages);
    }

    public function store(Request $request, Board $board) {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $message = $board->messages()->create([
            'user_id' => $request->user()->id,
            'message' => $validated['message'],
        ]);

        logger('MESSAGE STORED');

        broadcast(new MessageSent(
            $message->load('user:id,name'),
            $board->id
        ));

        logger('EVENT BROADCASTED');

        return response()->json($message, 201);
    }
}
