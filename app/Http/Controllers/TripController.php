<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TripController extends Controller
{
    public function index(Request $request): Response
    {
        return Inertia::render('Trips/Index', [
            'trips' => $request->user()->trips()->latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Trips/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'destination' => ['required', 'string', 'max:120'],
            'days' => ['required', 'integer', 'min:1', 'max:14'],
            'budget' => ['required', 'integer', 'min:1'],
            'interests' => ['nullable', 'string', 'max:255'],
        ]);

        $trip = $request->user()->trips()->create($data);

        return redirect()->route('trips.show', $trip);
    }

    public function show(Request $request, Trip $trip): Response
    {
        abort_unless($trip->user_id === $request->user()->id, 403);

        return Inertia::render('Trips/Show', [
            'trip' => $trip,
            'days' => $trip->tripDays,
        ]);
    }

    public function shared(string $token): Response
    {
        $trip = Trip::where('share_token', $token)->firstOrFail();

        return Inertia::render('Trips/Show', [
            'trip' => $trip,
            'days' => $trip->tripDays,
            'shared' => true,
        ]);
    }
}
