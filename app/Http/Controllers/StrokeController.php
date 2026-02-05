<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Stroke;
use App\Events\StrokeAdded;
use App\Events\StrokeDeleted;
use App\Events\StrokeUpdated;
use Illuminate\Http\Request;

class StrokeController extends Controller {
    public function index(Request $request, Board $board) {
        $strokes = $board->strokes()->with('user:id,name')->get();

        return response()->json($strokes);
    }

    public function store(Request $request, Board $board) {
        $validated = $request->validate([
            'type' => 'required|string|in:line,rectangle,circle,arrow,text,image,eraser',
            'data' => 'required|array',
        ]);

        $stroke = $board->strokes()->create([
            'user_id' => $request->user()->id,
            'type' => $validated['type'],
            'data' => $validated['data'],
        ]);
        
        $stroke->load('user:id,name');

        broadcast(new StrokeAdded($stroke, $board->id))->toOthers();

        return response()->json($stroke, 201);
    }

    public function destroy(Request $request, Board $board, Stroke $stroke) {
        if ($stroke->board_id !== $board->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $strokeId = $stroke->id;

        $stroke->delete();

        \Log::info('🗑️ Broadcasting StrokeDeleted event', [
            'stroke_id' => $strokeId,
            'board_id' => $board->id
        ]);

        broadcast(new StrokeDeleted($stroke->id, $board->id));

        return response()->json(['message' => 'Stroke deleted']);
    }

    public function update(Request $request, Board $board, Stroke $stroke) {
        if ($stroke->board_id !== $board->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'type' => 'required|string',
            'data' => 'required|array',
        ]);

        $stroke->update([
            'type' => $validated['type'],
            'data' => $validated['data'],
        ]);

        $stroke->load('user:id,name');

        \Log::info('✏️ Broadcasting StrokeUpdated event', [
            'stroke_id' => $stroke->id,
            'board_id' => $board->id
        ]);

        broadcast(new StrokeUpdated($stroke, $board->id));

        return response()->json($stroke);
    }
}
