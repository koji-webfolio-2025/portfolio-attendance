@extends('layouts.app')

@section('title', '申請詳細')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 fw-bold">修正申請詳細</h1>

    <table class="table table-bordered">
        <tr>
            <th>ユーザー名</th>
            <td>{{ $attendanceRequest->attendance->user->name ?? '不明' }}</td>
        </tr>
        <tr>
            <th>日付</th>
            <td>{{ \Carbon\Carbon::parse($attendanceRequest->attendance->date)->format('Y年n月j日') }}</td>
        </tr>
        <tr>
            <th>申請時刻（出勤）</th>
            <td>{{ $attendanceRequest->requested_clock_in }}</td>
        </tr>
        <tr>
            <th>申請時刻（退勤）</th>
            <td>{{ $attendanceRequest->requested_clock_out }}</td>
        </tr>
        <tr>
            <th>申請理由</th>
            <td>{{ $attendanceRequest->requested_note }}</td>
        </tr>
        <tr>
            <th>ステータス</th>
            <td>
                @if ($attendanceRequest->is_approved)
                    <span class="badge bg-success">承認済み</span>
                @else
                    <span class="badge bg-warning text-dark">承認待ち</span>
                @endif
            </td>
        </tr>
    </table>

    <h4 class="mt-5">休憩記録</h4>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>#</th>
                <th>開始</th>
                <th>終了</th>
                <th>休憩時間</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendanceRequest->breakTimeRequests as $i => $break)
                @php
                    $start = $break->break_start ? \Carbon\Carbon::parse($break->break_start) : null;
                    $end = $break->break_end ? \Carbon\Carbon::parse($break->break_end) : null;
                    $duration = ($start && $end) ? $end->diffInMinutes($start) : null;
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $start->format('H:i') }}</td>
                    <td>{{ $end ? $end->format('H:i') : '休憩中' }}</td>
                    <td>
                        {{ $duration ? floor($duration / 60) . ':' . str_pad($duration % 60, 2, '0', STR_PAD_LEFT) : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-end">
        @if (!$attendanceRequest->is_approved)
            <form method="POST" action="{{ route('admin.requests.approve', $attendanceRequest->id) }}">
                @csrf
                <button type="submit" class="btn btn-primary">承認する</button>
            </form>
            <p class="text-danger mt-3">※承認後は元に戻せません。</p>
        @else
            <p class="text-muted">この申請はすでに承認済みです。</p>
        @endif
    </div>
    <div class="mt-4">
        <a href="{{ $backRoute }}" class="btn btn-outline-secondary">← 一覧に戻る</a>
    </div>
</div>
@endsection
