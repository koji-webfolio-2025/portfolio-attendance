@extends('layouts.app')

@section('title', '修正申請一覧（管理者）')

@section('content')
    @include('components.attendance.request-tab-table', [
        'pending' => $pendingRequests,
        'approved' => $approvedRequests,
        'title' => '修正申請一覧（管理者）',
        'detailRoutePrefix' => 'admin.requests.',
        'btnClass' => 'btn-outline-secondary',
    ])
@endsection
