<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Models\Shower\ShowerReport;
use App\Services\Shower\ShowerScale;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class ShowerCommentController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'shower_number' => ['nullable', 'string'],
            'temperature_label' => ['nullable', Rule::in(array_keys(ShowerScale::CONDITION_TEMPERATURE_LEVELS))],
            'pressure_label' => ['nullable', Rule::in(array_keys(ShowerScale::CONDITION_PRESSURE_LEVELS))],
            'page' => ['nullable', 'integer', 'min:1'],
        ]);

        $gender = $request->user()->gender;
        $perPage = 5; // 1ページあたりの表示件数

        $query = ShowerReport::query()
            ->with('user')
            ->where('gender', $gender)
            ->orderByDesc('created_at');

        if (! empty($validated['shower_number']) && $validated['shower_number'] !== 'all') {
            $query->where('shower_number', (int) $validated['shower_number']);
        }

        if (! empty($validated['temperature_label'])) {
            [$min, $max] = ShowerScale::zoneRange($validated['temperature_label'], ShowerScale::CONDITION_TEMPERATURE_LEVELS);
            $query->whereBetween('temperature', [$min, $max]);
        }

        if (! empty($validated['pressure_label'])) {
            [$min, $max] = ShowerScale::zoneRange($validated['pressure_label'], ShowerScale::CONDITION_PRESSURE_LEVELS);
            $query->whereBetween('pressure', [$min, $max]);
        }

        // paginate を利用して取得
        $paginator = $query->paginate($perPage);

        // ページネーション内のデータを加工
        $paginator->getCollection()->transform(fn ($report) => [
            'id' => $report->id,
            'shower_number' => $report->shower_number,
            'created_at' => $report->created_at->setTimezone('Asia/Manila')->format('Y/m/d H:i'),
            'temperature_label' => ShowerScale::zoneLabel((float) $report->temperature, ShowerScale::CONDITION_TEMPERATURE_LEVELS),
            'pressure_label' => ShowerScale::zoneLabel((float) $report->pressure, ShowerScale::CONDITION_PRESSURE_LEVELS),
            'comment' => $report->comment,
            'user_name' => $report->user->name ?? '不明',
            'user_avatar_url' => $report->user->avatar_url ?? null,
        ]);

        return response()->json($paginator);
    }
}