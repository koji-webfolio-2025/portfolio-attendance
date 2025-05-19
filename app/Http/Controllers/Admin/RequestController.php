<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AttendanceRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    public function index()
    {
        $pendingRequests = AttendanceRequest::with(['attendance.user'])
            ->where('is_approved', false)
            ->orderByDesc('created_at')
            ->get();

        $approvedRequests = AttendanceRequest::with(['attendance.user'])
            ->where('is_approved', true)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.requests.index', [
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
        ]);

    }

    public function show(AttendanceRequest $request, Request $httpRequest)
    {
        $request->load(['attendance.user', 'attendance.breakTimes']);
        $attendance = $request->attendance;
        $breakTimes = $attendance->breakTimes;
        if ($breakTimes->isEmpty()) {
            $breakTimes = collect([(object) [
                'break_start' => null,
                'break_end' => null,
            ]]);
        }
        $from = $httpRequest->query('from');
        $month = $httpRequest->query('month');
        $userId = $httpRequest->query('user');
        $date = $attendance->date;

        $backRoute = match ($from) {
            'admin.users.monthly' => route('admin.users.monthly', [
                'user' => $userId,
                'month' => $month,
            ]),
            'admin.attendance.daily' => route('admin.attendance.daily', ['date' => $date]),
            'admin.requests.index' => route('admin.requests.index'),
            default => route('admin.requests.index'),
        };

        return view('admin.requests.show', [
            'request' => $request,
            'attendanceRequest' => $request,
            'breakTimes' => $breakTimes,
            'backRoute' => $backRoute,
        ]);

    }

    public function approve(AttendanceRequest $request)
    {
        if ($request->is_approved) {
            return back()->with('message', 'すでに承認済みです');
        }

        DB::transaction(function () use ($request) {
            $attendance = $request->attendance;

            // 出退勤＋備考の更新（時刻結合あり）
            $attendance->update([
                'clock_in' => Carbon::parse($attendance->date . ' ' . $request->requested_clock_in),
                'clock_out' => Carbon::parse($attendance->date . ' ' . $request->requested_clock_out),
                'note' => $request->requested_note,
            ]);

            // 現在の休憩記録を削除
            $attendance->breakTimes()->delete();

            // 申請された休憩時間を反映
            foreach ($request->breakTimeRequests as $break) {
                $attendance->breakTimes()->create([
                    'break_start' => $attendance->date . ' ' . $break->break_start,
                    'break_end' => $break->break_end ? $attendance->date . ' ' . $break->break_end : null,
                ]);
            }

            // 承認フラグを更新
            $request->update(['is_approved' => true]);
        });

        return redirect()->route('admin.requests.index')->with('message', '承認が完了しました');
    }
}
