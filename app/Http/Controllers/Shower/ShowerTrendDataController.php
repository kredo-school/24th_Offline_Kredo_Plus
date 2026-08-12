<?php

namespace App\Http\Controllers\Shower;

use App\Http\Controllers\Controller;
use App\Models\Shower\ShowerReport;
use Illuminate\Http\Request;

class ShowerTrendDataController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'shower_number' => ['required', 'integer', 'between:1,7'],
            'days' => ['required', 'integer', 'in:3,7,14'],
        ]);

        $gender = $request->user()->gender;

        $points = ShowerReport::query()
            ->where('gender', $gender)
            ->where('shower_number', $validated['shower_number'])
            ->where('created_at', '>=', now()->subDays($validated['days'])->startOfDay())
            ->selectRaw('DATE(created_at) as date, AVG(temperature) as temperature, AVG(pressure) as pressure')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn ($row) => [
                'date' => $row->date,
                'temperature' => round((float) $row->temperature, 1),
                'pressure' => round((float) $row->pressure, 1),
            ]);

        return response()->json(['points' => $points]);
    }
}
