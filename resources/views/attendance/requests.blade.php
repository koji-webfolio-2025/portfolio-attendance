@extends('layouts.app')

@section('title', '修正申請一覧')

@section('content')
    @include('components.attendance.request-tab-table', [
        'pending' => $pending,
        'approved' => $approved,
        'title' => '修正申請一覧',
        'detailRoutePrefix' => 'requests.',
        'btnClass' => 'btn-outline-secondary',
    ])
@endsection
