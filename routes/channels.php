<?php

use Illuminate\Support\Facades\Broadcast;

/*Broadcast::channel('board.{boardId}', function ($user, $boardId) {
    \Log::info('🔑 Broadcasting auth attempt:', [
        'user_id' => $user ? $user->id : 'NULL',
        'board_id' => $boardId,
    ]);
    
    // Za testiranje - DOZVOLI SVIMA
    return true;
    
    // Ili provjeri vlasništvo:
    // return $user->boards()->where('id', $boardId)->exists();
});*/