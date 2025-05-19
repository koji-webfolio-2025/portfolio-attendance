<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $pending = AttendanceRequest::whereHas('attendance', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('is_approved', false)->with('attendance')->get();

        $approved = AttendanceRequest::whereHas('attendance', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('is_approved', true)->with('attendance')->get();

        return view('attendance.requests', compact('pending', 'approved'));
    }

    public function show(AttendanceRequest $request, Request $httpRequest)
    {
        $attendance = $request->attendance;
        if ($attendance->user_id !== Auth::id()) {
            abort(403);
        }

        $attendance->load('user', 'breakTimes');
        $breakTimes = $attendance->breakTimes;
        $showForm = !($request && !$request->is_approved);

        $from = $httpRequest->query('from');
        $month = $httpRequest->query('month');

        $backRoute = match ($from) {
            'attendance.monthly' => route('attendance.monthly', ['month' => $month]),
            'requests.index' => route('requests.index'),
            default => route('requests.index'),
        };

        return view('attendance.show', compact('attendance', 'breakTimes', 'request', 'backRoute', 'showForm'));
    }
}
