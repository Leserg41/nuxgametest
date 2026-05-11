<?php

namespace App\Services;

use App\Http\Requests\StoreRegistrationRequest;
use App\Models\History;
use App\Models\Registration;
use Illuminate\Support\Str;

class RegistrationService
{
    private const LUCKY_NUMBER_MIN = 0;

    private const LUCKY_NUMBER_MAX = 1000;

    private const WIN_DIVISOR = 2;

    private const WIN_REMAINDER = 0;

    private const TOP_PRIZE_THRESHOLD = 900;

    private const HIGH_PRIZE_THRESHOLD = 600;

    private const MEDIUM_PRIZE_THRESHOLD = 300;

    private const TOP_PRIZE_MULTIPLIER = 0.7;

    private const HIGH_PRIZE_MULTIPLIER = 0.5;

    private const MEDIUM_PRIZE_MULTIPLIER = 0.3;

    private const LOW_PRIZE_MULTIPLIER = 0.1;

    public function createWithData(array $data): Registration
    {
        return Registration::create($data);
    }

    public function create(StoreRegistrationRequest $request): Registration
    {
        $validated = $request->validated();

        $validated['unique_code'] = Str::random(10);

        return $this->createWithData($validated);
    }

    public function regenerate(string $uniqueCode): string
    {
        $registration = Registration::where('unique_code', $uniqueCode)->firstOrFail();

        if ($registration->created_at->addDays(7)->isPast() || ! $registration->is_active) {
            throw new \Exception('Page expired or deactivated');
        }

        $newCode = Str::random(10);
        $registration->update(['unique_code' => $newCode]);

        return $newCode;
    }

    public function deactivate(string $uniqueCode): bool
    {
        $registration = Registration::where('unique_code', $uniqueCode)->firstOrFail();

        if ($registration->created_at->addDays(7)->isPast()) {
            throw new \Exception('Page expired');
        }

        $registration->update(['is_active' => false]);

        return true;
    }

    public function lucky(string $uniqueCode): array
    {
        $registration = Registration::where('unique_code', $uniqueCode)->firstOrFail();

        if ($registration->created_at->addDays(7)->isPast() || ! $registration->is_active) {
            throw new \Exception('Page expired or deactivated');
        }

        $number = rand(self::LUCKY_NUMBER_MIN, self::LUCKY_NUMBER_MAX);
        $isWin = $number % self::WIN_DIVISOR == self::WIN_REMAINDER;
        $winSum = null;

        if ($isWin) {
            if ($number > self::TOP_PRIZE_THRESHOLD) {
                $winSum = $number * self::TOP_PRIZE_MULTIPLIER;
            } elseif ($number > self::HIGH_PRIZE_THRESHOLD) {
                $winSum = $number * self::HIGH_PRIZE_MULTIPLIER;
            } elseif ($number > self::MEDIUM_PRIZE_THRESHOLD) {
                $winSum = $number * self::MEDIUM_PRIZE_MULTIPLIER;
            } else {
                $winSum = $number * self::LOW_PRIZE_MULTIPLIER;
            }
        }

        History::create([
            'registration_id' => $registration->id,
            'random_number' => $number,
            'is_win' => $isWin,
            'win_sum' => $winSum,
        ]);

        return ['number' => $number, 'isWin' => $isWin, 'winSum' => $winSum];
    }
}
