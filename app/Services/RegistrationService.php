<?php

namespace App\Services;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrationService
{
    public function createWithData(array $data)
    {
        return Registration::create($data);
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'phonenumber' => 'required|string|max:255',
        ]);

        $validated['unique_code'] = Str::random(10);

        return $this->createWithData($validated);
    }

    public function regenerate($uniqueCode)
    {
        $registration = Registration::where('unique_code', $uniqueCode)->firstOrFail();

        if ($registration->created_at->addDays(7)->isPast() || !$registration->is_active) {
            throw new \Exception('Page expired or deactivated');
        }

        $newCode = Str::random(10);
        $registration->update(['unique_code' => $newCode]);

        return $newCode;
    }

    public function deactivate($uniqueCode)
    {
        $registration = Registration::where('unique_code', $uniqueCode)->firstOrFail();

        if ($registration->created_at->addDays(7)->isPast()) {
            throw new \Exception('Page expired');
        }

        $registration->update(['is_active' => false]);

        return true;
    }

    public function lucky($uniqueCode)
    {
        $registration = Registration::where('unique_code', $uniqueCode)->firstOrFail();

        if ($registration->created_at->addDays(7)->isPast() || !$registration->is_active) {
            throw new \Exception('Page expired or deactivated');
        }

        $number = rand(0, 1000);
        $isWin = $number % 2 == 0;
        $winSum = null;

        if ($isWin) {
            if ($number > 900) {
                $winSum = $number * 0.7;
            } elseif ($number > 600) {
                $winSum = $number * 0.5;
            } elseif ($number > 300) {
                $winSum = $number * 0.3;
            } else {
                $winSum = $number * 0.1;
            }
        }

        \App\Models\History::create([
            'registration_id' => $registration->id,
            'random_number' => $number,
            'is_win' => $isWin,
            'win_sum' => $winSum,
        ]);

        return ['number' => $number, 'isWin' => $isWin, 'winSum' => $winSum];
    }
}