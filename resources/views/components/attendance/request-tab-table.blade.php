{{-- 修正申請一覧（タブ付き）共通コンポーネント --}}
{{-- 受け取り変数：
    $pending, $approved → 各コレクション
    $title → タイトル
    $detailRoutePrefix → 例: 'requests.' や 'admin.requests.'
    $btnClass → ボタン色（例: btn-primary / btn-outline-primary）
--}}

<div class="container py-5">
    <h1 class="mb-4">{{ $title ?? '修正申請一覧' }}</h1>

    <!-- タブメニュー -->
    <ul class="nav nav-tabs mb-3" id="requestTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending"
                type="button" role="tab" aria-controls="pending" aria-selected="true">
                承認待ち
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved"
                type="button" role="tab" aria-controls="approved" aria-selected="false">
                承認済み
            </button>
        </li>
    </ul>

    <!-- タブコンテンツ -->
    <div class="tab-content" id="requestTabsContent">
        @foreach (['pending' => $pending, 'approved' => $approved] as $key => $requests)
            @php
                $label = $key === 'pending' ? '承認待ち' : '承認済み';
                $badgeClass = $key === 'pending' ? 'bg-warning text-dark' : 'bg-success';
                $tabId = $key;
                $tabActive = $key === 'pending' ? 'show active' : '';
            @endphp

            <div class="tab-pane fade {{ $tabActive }}" id="{{ $tabId }}" role="tabpanel" aria-labelledby="{{ $tabId }}-tab">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>状態</th>
                                <th>名前</th>
                                <th>対象日時</th>
                                <th>申請理由</th>
                                <th>申請日時</th>
                                <th>詳細</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($requests as $request)
                                <tr>
                                    <td><span class="badge {{ $badgeClass }}">{{ $label }}</span></td>
                                    <td>{{ $request->attendance->user->name }}</td>
                                    <td>{{ $request->attendance->date }}</td>
                                    <td>{{ $request->requested_note }}</td>
                                    <td>{{ $request->created_at }}</td>
                                    <td>
                                        <a href="{{ route($detailRoutePrefix . 'show', ['request' => $request->id, 'from' => 'requests.index']) }}"
                                           class="btn btn-sm {{ $btnClass ?? 'btn-primary' }}">詳細</a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6">{{ $label }}の申請はありません。</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</div>
