<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceEditRequest;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    /**
     * 管理者用 日次勤怠一覧画面
     */
    public function daily(Request $request)
    {
        // クエリパラメータ ?date=2025-04-11 があれば使う。なければ今日。
        $date = $request->input('date', Carbon::today()->toDateString());

        // 指定日の勤怠一覧を取得（ユーザーと休憩時間を取得済にしておく）
        $attendances = Attendance::with(['user', 'breakTimes'])
            ->whereDate('date', $date)
            ->orderBy('user_id') // 任意（名前順にしたいならuser.nameのjoinが必要）
            ->get();

        return view('admin.attendance.daily', [
            'attendances' => $attendances,
            'date' => $date,
            'prevDate' => Carbon::parse($date)->subDay()->toDateString(),
            'nextDate' => Carbon::parse($date)->addDay()->toDateString(),
        ]);
    }

    public function show(Attendance $attendance, Request $request)
    {
        $attendance->load('user', 'breakTimes');
        $breakTimes = $attendance->breakTimes;
        if ($breakTimes->isEmpty()) {
            $breakTimes = collect([(object) [
                'break_start' => null,
                'break_end' => null,
            ]]);
        }
        $showForm = false;
        $from = $request->query('from');
        $month = $request->query('month');
        $userId = $request->query('user');
        $date = $attendance->date;

        $backRoute = match ($from) {
            'admin.users.monthly' => route('admin.users.monthly', [
                'user' => $userId,
                'month' => $month,
            ]),
            'admin.requests.index' => route('admin.requests.index'),
            'admin.attendance.daily' => route('admin.attendance.daily', ['date' => $date]),
            default => route('admin.attendance.daily', ['date' => $date]),
        };

        return view('admin.attendance.show', compact('attendance', 'breakTimes', 'backRoute'));

    }

    public function edit(Attendance $attendance)
    {
        $attendance->load('breakTimes');
        return view('admin.attendance.edit', compact('attendance'));
    }

    public function update(AttendanceEditRequest $request, Attendance $attendance)
    {
        $date = $attendance->date ?? now()->toDateString();

        // 出退勤・備考の更新
        $attendance->update([
            'clock_in' => Carbon::parse($date . ' ' . $request->clock_in),
            'clock_out' => Carbon::parse($date . ' ' . $request->clock_out),
            'note' => $request->note,
        ]);

        // 既存休憩を全削除 → 入れ直し（編集操作としてシンプル）
        $attendance->breakTimes()->delete();

        foreach ($request->input('break_times', []) as $break) {
            if (!empty($break['start']) && !empty($break['end'])) {
                $attendance->breakTimes()->create([
                    'break_start' => Carbon::parse($date . ' ' . $break['start']),
                    'break_end' => Carbon::parse($date . ' ' . $break['end']),
                ]);
            }
        }

        return redirect()->route('admin.attendance.show', $attendance)->with('message', '勤怠情報を更新しました');
    }

}
