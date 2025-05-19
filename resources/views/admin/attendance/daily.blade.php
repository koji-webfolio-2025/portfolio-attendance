@extends('layouts.app')

@section('title', '勤怠一覧画面（管理者）')

@section('content')
<div class="container">
    <h2>{{ \Carbon\Carbon::parse($date)->format('Y年n月j日') }}の勤怠</h2>

    <form method="GET" action="{{ route('admin.attendance.daily') }}" class="d-flex align-items-center my-3">
        <a href="{{ route('admin.attendance.daily', ['date' => \Carbon\Carbon::parse($date)->subDay()->toDateString()]) }}" class="btn btn-outline-secondary me-2">← 前日</a>
        <input type="date" name="date" value="{{ $date }}" class="form-control w-auto" onchange="this.form.submit()">
        <a href="{{ route('admin.attendance.daily', ['date' => \Carbon\Carbon::parse($date)->addDay()->toDateString()]) }}" class="btn btn-outline-secondary ms-2">翌日 →</a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</td>
                    <td>{{ $attendance->clock_out
                        ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i')
                        : '-' }}</td>
                    <td>
                        {{ number_format($attendance->breakTimes->sum(function ($break) {
                            return $break->break_end
                                ? \Carbon\Carbon::parse($break->break_end)->diffInMinutes($break->break_start)
                                : 0;
                        }) / 60, 1) }}時間
                    </td>
                    <td>
                        @if ($attendance->clock_in && $attendance->clock_out)
                            {{ number_format(
                                \Carbon\Carbon::parse($attendance->clock_in)->diffInMinutes(\Carbon\Carbon::parse($attendance->clock_out)) / 60
                                - $attendance->breakTimes->sum(function ($break) {
                                return $break->break_end
                                    ? \Carbon\Carbon::parse($break->break_end)->diffInMinutes($break->break_start)
                                    : 0;
                            }) / 60
                            , 1) }}時間
                        @else
                            -
                        @endif
                    </td>
                    <td><a class="btn btn-outline-primary btn-sm" href="{{ route('admin.attendance.show', ['attendance' => $attendance->id]) }}">詳細</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
