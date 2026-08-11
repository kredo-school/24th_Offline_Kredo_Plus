<?php

namespace App\Models\Shower;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowerReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'gender',
        'shower_number',
        'user_id',
        'temperature',
        'pressure',
        'comment',
    ];

    protected function casts(): array
    {
        return [
            'temperature' => 'decimal:1',
            'pressure' => 'decimal:1',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 指定した性別・シャワー番号・期間で絞り込むスコープ
     * $hours が null の場合は絞り込みなし(全期間)
     */
    public function scopeForShower($query, string $gender, int $showerNumber)
    {
        return $query->where('gender', $gender)->where('shower_number', $showerNumber);
    }

    public function scopeWithinHours($query, ?int $hours)
    {
        if ($hours === null) {
            return $query;
        }

        return $query->where('created_at', '>=', now()->subHours($hours));
    }
}