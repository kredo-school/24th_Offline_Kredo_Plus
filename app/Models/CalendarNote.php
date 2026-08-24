<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarNote extends Model
{
    protected $fillable = [
        'user_id',
        'note_date',
        'title',
        'memo',
    ];

    protected function casts(): array
    {
        return [
            'note_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
