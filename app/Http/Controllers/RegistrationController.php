<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RegistrationService;
use App\Models\Registration;

class RegistrationController extends Controller
{
    private RegistrationService $service;

    public function __construct()
    {
        $this->service = new RegistrationService();
    }

    public function index()
    {
        return view('registration');
    }

    public function store(Request $request)
    {
        $registration = $this->service->create($request);

        return redirect('/registration/' . $registration->unique_code)->with('success', 'Registration successful!');
    }

    public function show(string $uniqueCode)
    {
        $registration = Registration::where('unique_code', $uniqueCode)->firstOrFail();

        if ($registration->created_at->addDays(7)->isPast() || !$registration->is_active) {
            abort(404, 'This page has expired or is deactivated.');
        }

        $history = null;
        $showHistory = false;

        return view('page_a', compact('registration', 'history', 'showHistory'));
    }

    public function regenerate(string $uniqueCode)
    {
        try {
            $newCode = $this->service->regenerate($uniqueCode);
            return redirect('/registration/' . $newCode)->with('success', 'Unique number regenerated!');
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function deactivate(string $uniqueCode)
    {
        try {
            $this->service->deactivate($uniqueCode);
            return redirect('/')->with('success', 'Registration deactivated!');
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function lucky(string $uniqueCode)
    {
        try {
            $result = $this->service->lucky($uniqueCode);
            $message = 'Number: ' . $result['number'] . ' - ' . ($result['isWin'] ? 'Win! Sum: ' . $result['winSum'] : 'Lost');
            return redirect('/registration/' . $uniqueCode)->with('lucky', $message);
        } catch (\Exception $e) {
            abort(404);
        }
    }

    public function history(string $uniqueCode)
    {
        $registration = Registration::where('unique_code', $uniqueCode)->firstOrFail();

        if ($registration->created_at->addDays(7)->isPast() || !$registration->is_active) {
            abort(404, 'This page has expired or is deactivated.');
        }

        $history = $registration->history()->orderBy('created_at', 'desc')->take(3)->get();
        $showHistory = true;

        return view('page_a', compact('registration', 'history', 'showHistory'));
    }
}
