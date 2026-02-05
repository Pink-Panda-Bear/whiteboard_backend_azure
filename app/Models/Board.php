<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Board extends Model {
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'room_code', 'is_public'];

    protected static function boot() {
        parent::boot();

        static::creating(function ($board) {
            if (empty($board->room_code)) {
                $board->room_code = strtoupper(Str::random(8));
            }
        });
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function strokes() {
        return $this->hasMany(Stroke::class);
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }
}
