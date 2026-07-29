<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Services\ItineraryGeneratorService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TripStreamController extends Controller
{
    public function stream(Request $request, Trip $trip, ItineraryGeneratorService $itineraryService): StreamedResponse
    {
        abort_unless($trip->user_id === $request->user()->id, 403);

        return response()->stream(function () use ($trip, $itineraryService) {
            if ($trip->status !== 'generating') {
                return;
            }

            try {
                foreach ($itineraryService->streamDays($trip->destination, $trip->days, $trip->budget, $trip->interests) as $line) {
                    $decoded = json_decode($line, true);

                    if (! is_array($decoded) || ! isset($decoded['day_number'])) {
                        continue;
                    }

                    $day = $trip->tripDays()->create([
                        'day_number' => $decoded['day_number'],
                        'title' => $decoded['title'] ?? ('Day '.$decoded['day_number']),
                        'summary' => $decoded['summary'] ?? '',
                        'estimated_cost' => $decoded['estimated_cost'] ?? 0,
                        'activities' => $decoded['activities'] ?? [],
                    ]);

                    echo json_encode($day)."\n";
                    ob_flush();
                    flush();
                }

                $trip->update(['status' => 'completed']);
            } catch (\Throwable $e) {
                $trip->update(['status' => 'failed']);
                echo json_encode(['error' => true, 'message' => 'Generation failed'])."\n";
                flush();
            }
        }, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
