<?php

namespace App\Services\Shower;

use App\Models\Notification;

class ShowerNotificationService
{
    public function capacityFull(string $gender): void
    {
        Notification::create([
            'gender' => $gender,
            'type' => 'capacity_full',
            'message' => '現在シャワーが満室です。',
        ]);
    }

    public function capacityVacant(string $gender): void
    {
        Notification::create([
            'gender' => $gender,
            'type' => 'capacity_vacant',
            'message' => 'シャワーの満室が解除されました。',
        ]);
    }

    public function malfunctionBroken(string $gender, int $showerNumber): void
    {
        Notification::create([
            'gender' => $gender,
            'type' => 'malfunction_broken',
            'shower_number' => $showerNumber,
            'message' => "{$showerNumber}番のシャワーが故障中です。",
        ]);
    }

    public function malfunctionFixed(string $gender, int $showerNumber): void
    {
        Notification::create([
            'gender' => $gender,
            'type' => 'malfunction_fixed',
            'shower_number' => $showerNumber,
            'message' => "{$showerNumber}番のシャワーが修理完了しました。",
        ]);
    }
}