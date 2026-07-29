<?php

namespace App\Services;

use Generator;
use Illuminate\Support\Facades\Http;

class ItineraryGeneratorService
{
    public function streamDays(string $destination, int $days, int $budget, ?string $interests): Generator
    {
        $systemPrompt = <<<PROMPT
You are a travel itinerary generator. Output ONLY newline-delimited JSON (NDJSON): exactly one complete JSON object per line, one line per day, nothing else — no markdown fences, no commentary, no leading/trailing text.

Each line must match this shape exactly:
{"day_number": 1, "title": "short day title", "summary": "1-2 sentence overview of the day", "estimated_cost": 1500, "activities": [{"time": "09:00", "place": "name of place", "description": "short description", "lat": 41.0082, "lng": 28.9784, "cost": 200}]}

Rules:
- Produce exactly the requested number of days, in order starting at day_number 1.
- lat/lng are your best approximate coordinates for that place (it's fine if not perfectly precise).
- estimated_cost is the sum of that day's activity costs, in the same currency as the given budget.
- Keep the total of all days' estimated_cost reasonably close to the given budget.
- Flush a full, valid, complete JSON object per day before starting the next — never split one day across lines.
PROMPT;

        $userPrompt = sprintf(
            'Destination: %s. Number of days: %d. Total budget: %d. Interests: %s.',
            $destination,
            $days,
            $budget,
            $interests ?: 'general sightseeing',
        );

        $response = Http::withToken(config('services.groq.key'))
            ->withOptions(['stream' => true])
            ->timeout(180)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => config('services.groq.model'),
                'stream' => true,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Itinerary generation request failed with status '.$response->status());
        }

        $body = $response->toPsrResponse()->getBody();
        $sseBuffer = '';
        $contentBuffer = '';

        while (! $body->eof()) {
            $sseBuffer .= $body->read(1024);

            while (($pos = strpos($sseBuffer, "\n")) !== false) {
                $line = trim(substr($sseBuffer, 0, $pos));
                $sseBuffer = substr($sseBuffer, $pos + 1);

                if ($line === '' || $line === 'data: [DONE]' || ! str_starts_with($line, 'data: ')) {
                    continue;
                }

                $delta = json_decode(substr($line, 6), true)['choices'][0]['delta']['content'] ?? '';
                $contentBuffer .= $delta;

                while (($dayPos = strpos($contentBuffer, "\n")) !== false) {
                    $dayLine = trim(substr($contentBuffer, 0, $dayPos));
                    $contentBuffer = substr($contentBuffer, $dayPos + 1);

                    if ($dayLine !== '') {
                        yield $dayLine;
                    }
                }
            }
        }

        if (trim($contentBuffer) !== '') {
            yield trim($contentBuffer);
        }
    }
}
