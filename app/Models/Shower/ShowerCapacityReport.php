<?php

namespace App\Models\Shower;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowerCapacityReport extends Model
{
    use HasFactory;

    public const FULL_STATUS_DURATION_MINUTES = 30;

    protected $fillable = [
        'gender',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 現在「満室」状態かどうかを判定する。
     * - 直近の報告が 'vacant' なら false
     * - 直近の報告が 'full' で、かつ30分以内なら true
     * - 直近の報告が 'full' でも30分を過ぎていれば false
     * - 報告が1件も無ければ false
     */
    public static function isCurrentlyFull(string $gender): bool
    {
        $latest = static::query()
            ->where('gender', $gender)
            ->latest('created_at')
            ->first();

        if ($latest === null || $latest->status !== 'full') {
            return false;
        }

        return $latest->created_at->greaterThan(now()->subMinutes(self::FULL_STATUS_DURATION_MINUTES));
    }

    /**
     * 「満室」報告が何分前にされたかを返す。満室でなければnull。
     */
    public static function fullReportedMinutesAgo(string $gender): ?int
    {
        if (! static::isCurrentlyFull($gender)) {
            return null;
        }

        $latest = static::query()
            ->where('gender', $gender)
            ->latest('created_at')
            ->first();

        return (int) $latest->created_at->diffInMinutes(now());
    }
}