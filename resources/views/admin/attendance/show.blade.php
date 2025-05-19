{{-- resources/views/admin/attendance/show.blade.php --}}
@extends('layouts.app')

@section('title', '勤怠詳細')

@section('content')
    @include('components.attendance.detail', [
        'attendance' => $attendance,
        'breakTimes' => $breakTimes,
        'showForm' => true,
        'editRoute' => route('admin.attendance.update', $attendance->id),
        'submitLabel' => '修正する',
        'backRoute' => route('admin.attendance.daily', ['date' => $attendance->date])
    ])
@endsection
