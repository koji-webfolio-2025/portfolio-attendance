@extends('layouts.app')

@section('title', '勤怠打刻')

@section('content')
    <div class="container">
        <h1>勤怠打刻画面</h1>
        @php
            $latestBreak = $attendance?->breakTimes->last();
        @endphp

        <div class="status-label">
            @if ($status === 'none')
                <span class="badge bg-secondary">勤務外</span>
            @elseif ($status === 'working')
                <span class="badge bg-success">出勤中</span>
            @elseif ($status === 'on_break')
                <span class="badge bg-warning text-dark">休憩中</span>
            @elseif ($status === 'finished')
                <span class="badge bg-dark">退勤済</span>
            @endif
        </div>

        <!-- 日付と時刻表示（共通） -->
        <div class="text-center my-4">
            <h2>{{ now()->format('Y年n月j日（D）') }}</h2>
            <h1>{{ now()->format('H:i') }}</h1>
        </div>

        @if (session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <!-- 状態に応じたボタン表示 -->
        <div class="text-center mt-4">
            @if ($status === 'none')
                <form method="POST" action="{{ route('attendance.start') }}">
                    @csrf
                    <button class="btn btn-dark btn-lg">出勤</button>
                </form>

            @elseif ($status === 'working')
                <form method="POST" action="{{ route('attendance.clockOut') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-dark btn-lg me-3">退勤</button>
                </form>
                <form method="POST" action="{{ route('attendance.break.start') }}" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-dark btn-lg">休憩入</button>
                </form>

            @elseif ($status === 'on_break')
                <form method="POST" action="{{ route('attendance.break.end') }}">
                    @csrf
                    <button class="btn btn-outline-dark btn-lg">休憩戻</button>
                </form>

            @elseif ($status === 'finished')
                <p class="fs-4 mt-4">お疲れ様でした。</p>
            @endif
        </div>

    </div>

    <script>
        setTimeout(() => {
        document.querySelector('.alert')?.remove();
        }, 3000);

        // 現在時刻の更新（JS）
        function updateCurrentTime() {
            const now = new Date();
            document.getElementById('current-time').textContent = now.toLocaleString();
        }
        setInterval(updateCurrentTime, 1000);
        updateCurrentTime();
    </script>
@endsection
