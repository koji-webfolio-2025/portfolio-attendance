@extends('layouts.app')

@section('title', '勤怠一覧')

@section('content')
    <div class="container py-5">
        <h2 class="mb-4 fw-bold">勤怠一覧</h2>

        {{-- 月の切り替え --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ route('attendance.monthly', ['month' => $prevMonth]) }}" class="btn btn-outline-secondary">← 前月</a>
            <span class="fs-5">{{ $currentMonth->format('Y年m月') }}</span>
            <a href="{{ route('attendance.monthly', ['month' => $nextMonth]) }}" class="btn btn-outline-secondary">翌月 →</a>
        </div>

        {{-- 勤怠テーブル --}}
        <table class="table table-bordered text-center align-middle bg-white">
            <thead class="table-light">
                <tr>
                    <th>日付</th>
                    <th>出勤</th>
                    <th>退勤</th>
                    <th>休憩</th>
                    <th>実働</th>
                    <th>詳細</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $attendance)
                    @php
                        $clockIn = $attendance->clock_in ? \Carbon\Carbon::parse($attendance->clock_in) : null;
                        $clockOut = $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out) : null;
                        $totalBreak = $attendance->breakTimes->reduce(function ($carry, $break) {
                            if ($break->break_end) {
                                return $carry + \Carbon\Carbon::parse($break->break_end)->diffInMinutes($break->break_start);
                            }
                            return $carry;
                        }, 0);
                        $totalBreakStr = $totalBreak ? floor($totalBreak / 60) . ':' . str_pad($totalBreak % 60, 2, '0', STR_PAD_LEFT) : '-';

                        $workDuration = ($clockIn && $clockOut)
                            ? $clockOut->diffInMinutes($clockIn) - $totalBreak
                            : null;
                        $workDurationStr = $workDuration !== null
                            ? floor($workDuration / 60) . ':' . str_pad($workDuration % 60, 2, '0', STR_PAD_LEFT)
                            : '-';
                    @endphp
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($attendance->date)->format('m/d(D)') }}</td>
                        <td>{{ $clockIn ? $clockIn->format('H:i') : '' }}</td>
                        <td>{{ $clockOut ? $clockOut->format('H:i') : '' }}</td>
                        <td>{{ $totalBreakStr }}</td>
                        <td>{{ $workDurationStr }}</td>
                        <td>
                            <a href="{{ route('attendance.show', ['attendance' => $attendance->id, 'from' => 'attendance.monthly', 'month' => $currentMonth->format('Y-m')]) }}" class="btn btn-outline-dark btn-sm">詳細</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">勤怠情報がありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
