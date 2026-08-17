<?php

namespace App\Models\Shower;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ShowerMalfunctionReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'gender',
        'shower_number',
        'status',
        'user_id',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 指定した性別・シャワー番号が、現在「故障中」かどうかを判定する。
     * その番号の最新の報告が 'broken' なら true。
     */
    public static function isBroken(string $gender, int $showerNumber): bool
    {
        $latest = static::query()
            ->where('gender', $gender)
            ->where('shower_number', $showerNumber)
            ->latest('created_at')
            ->first();

        return $latest !== null && $latest->status === 'broken';
    }

    /**
     * 指定した性別で、現在「故障中」のシャワー番号一覧を取得する。
     *
     * @return Collection<int, int>
     */
    public static function brokenShowerNumbers(string $gender): Collection
    {
        // 性別内の全番号について、番号ごとの最新レコードだけを取り出す
        return static::query()
            ->where('gender', $gender)
            ->orderBy('shower_number')
            ->orderByDesc('created_at')
            ->get()
            ->unique('shower_number')
            ->where('status', 'broken')
            ->pluck('shower_number')
            ->values();
    }


    // 
    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * 性別・番号ごとの最新レコード(=現在の状態)を一覧取得。
     * 現在「故障中」のものだけに絞る。
     *
     * @return \Illuminate\Support\Collection<int, self>
     */
    public static function currentlyBroken()
    {
        return static::query()
            ->with('user')
            ->orderBy('gender')
            ->orderBy('shower_number')
            ->orderByDesc('created_at')
            ->get()
            ->unique(fn ($report) => $report->gender . '-' . $report->shower_number)
            ->where('status', 'broken')
            ->sortBy(['gender', 'shower_number'])
            ->values();
    }
}