<div class="container py-5">
    <h2 class="mb-4 fw-bold">勤怠詳細</h2>

    {{-- 成功メッセージ --}}
    @if (session('message'))
        <div class="alert alert-success text-center">
            {{ session('message') }}
        </div>
    @endif

    {{-- ユーザー名と日付 --}}
    <div class="mb-4">
        <p><strong>名前：</strong> {{ $attendance->user->name ?? '不明' }}</p>
        <p><strong>日付：</strong> {{ \Carbon\Carbon::parse($attendance->date)->format('Y年m月d日 (D)') }}</p>
    </div>

    {{-- 編集モードならフォーム開始 --}}
    @if ($showForm ?? false)
    <form action="{{ $editRoute }}" method="POST">
        @csrf
        {{-- @method('PUT') は不要！ --}}
    @endif

    {{-- 出勤時刻 --}}
    <div class="mb-3">
        <label>出勤時刻</label>
        @if ($showForm ?? false)
            <input type="time" name="clock_in" class="form-control"
                value="{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}">
        @else
            <div class="form-control bg-light">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</div>
        @endif
    </div>

    {{-- 退勤時刻 --}}
    <div class="mb-3">
        <label>退勤時刻</label>
        @if ($showForm ?? false)
            <input type="time" name="clock_out" class="form-control"
                value="{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '' }}">
        @else
            <div class="form-control bg-light"> {{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') : '未退勤' }}</div>
        @endif
    </div>

    {{-- 備考 --}}
    <div class="mb-3">
        <label>備考</label>
        @if ($showForm ?? false)
            <textarea name="note" class="form-control">{{ $attendance->note }}</textarea>
        @else
            <div class="form-control bg-light">{{ $attendance->note }}</div>
        @endif
    </div>

    {{-- 休憩記録 --}}
    <h5>休憩記録</h5>
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
            @foreach ($breakTimes as $i => $break)
                @php
                    $start = $break->break_start ? \Carbon\Carbon::parse($break->break_start)->format('H:i') : '';
                    $end = $break->break_end ? \Carbon\Carbon::parse($break->break_end)->format('H:i') : '';
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    @if ($showForm ?? false)
                        <td><input type="time" name="break_times[{{ $i }}][start]" class="form-control" value="{{ $start }}"></td>
                        <td><input type="time" name="break_times[{{ $i }}][end]" class="form-control" value="{{ $end }}"></td>
                    @else
                        <td>{{ $start }}</td>
                        <td>{{ $end ?: '休憩中' }}</td>
                    @endif
                    <td>
                        @if ($end)
                            @php
                                $duration = \Carbon\Carbon::parse($end)->diffInMinutes(\Carbon\Carbon::parse($start));
                            @endphp
                            {{ floor($duration / 60) . ':' . str_pad($duration % 60, 2, '0', STR_PAD_LEFT) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ボタン表示部（編集 or 閲覧） --}}
    @if ($showForm ?? false)
        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ $backRoute }}" class="btn btn-outline-secondary">← 一覧に戻る</a>
            <button type="submit" class="btn btn-primary">{{ $submitLabel ?? '修正申請する' }}</button>
        </div>
    </form>
    @else
        @if (isset($request) && !$request->is_approved)
            <div class="text-danger text-end px-4 pt-2" style="font-size: 0.875rem;">
                ※承認待ちのため修正はできません。
            </div>
        @endif

        <div class="mt-4">
            <a href="{{ $backRoute }}" class="btn btn-outline-secondary">← 一覧に戻る</a>
        </div>
    @endif
</div>

{{-- メッセージ自動消去 --}}
<script>
    setTimeout(() => {
        document.querySelector('.alert')?.remove();
    }, 3000);
</script>
