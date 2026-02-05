<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller {
    public function index(Request $request) {
        $boards = $request->user()->boards()->latest()->get();

        return response()->json($boards);
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $board = $request->user()->boards()->create($validated);

        return response()->json($board, 201);
    }

    public function show(Request $request, Board $board) {
        if ($board->user_id !== $request->user()->id && !$board->is_public) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($board->load('strokes'));
    }

    public function destroy(Request $request, Board $board) {
        if ($board->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $board->delete();

        return response()->json(['message' => 'Board deleted']);
    }

    public function joinByCode(Request $request) {
        $validated = $request->validate([
            'room_code' => 'required|string|size:8',
        ]);

        $board = Board::where('room_code', $validated['room_code'])->first();

        if (!$board) {
            return response()->json(['message' => 'Board not found'], 404);
        }

        if (!$board->is_public && $board->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Board is private'], 403);
        }

        return response()->json($board);
    }
}