@extends('layouts.app')

@section('title', $user->name . 'さんの勤怠')

@section('content')
<div class="container">
    <h2>{{ $user->name }} さんの勤怠</h2>

    <div class="d-flex justify-content-between align-items-center my-3">
        <a href="{{ route('admin.users.monthly', ['user' => $user->id, 'month' => $prevMonth]) }}" class="btn btn-outline-secondary">← 前月</a>
        <span>{{ $currentMonth->format('Y年m月') }}</span>
        <a href="{{ route('admin.users.monthly', ['user' => $user->id, 'month' => $nextMonth]) }}" class="btn btn-outline-secondary">翌月 →</a>
    </div>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                @php
                    $breakMinutes = $attendance->breakTimes->sum(function ($break) {
                        return $break->break_end
                            ? \Carbon\Carbon::parse($break->break_end)->diffInMinutes($break->break_start)
                            : 0;
                    });
                    $clockIn = $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') : '-';
                    $clockOut = $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '-';
                    $workTime = '-';

                    if ($attendance->clock_in && $attendance->clock_out) {
                        $workMinutes = \Carbon\Carbon::parse($attendance->clock_in)->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_out)) - $breakMinutes;
                        $workTime = number_format($workMinutes / 60, 1) . '時間';
                    }
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('m/d(D)') }}</td>
                    <td>{{ $clockIn }}</td>
                    <td>{{ $clockOut }}</td>
                    <td>{{ number_format($breakMinutes / 60, 1) }}時間</td>
                    <td>{{ $workTime }}</td>
                    <td>
                        @php
                            $isAdmin = Auth::user()->is_admin;
                            $route = $isAdmin
                                ? route('admin.attendance.show', [
                                    'attendance' => $attendance->id,
                                    'from' => 'admin.users.monthly',
                                    'user' => $user->id,
                                    'month' => $currentMonth->format('Y-m')
                                ])
                                : route('attendance.show', [
                                    'attendance' => $attendance->id,
                                    'from' => 'attendance.monthly',
                                    'month' => $currentMonth->format('Y-m')
                                ]);
                        @endphp
                        <a class="btn btn-outline-primary btn-sm" href="{{ $route }}">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form method="GET" action="{{ route('admin.users.monthly.export', ['user' => $user->id, 'month' => $currentMonth->format('Y-m')]) }}">
        <button type="submit" class="btn btn-dark mt-4">CSV出力</button>
    </form>
</div>
@endsection
