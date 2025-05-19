@extends('layouts.app')

@section('title', 'スタッフ一覧')

@section('content')
<div class="container">
    <h2 class="mb-4">スタッフ一覧</h2>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="{{ route('admin.users.monthly', ['user' => $user->id, 'month' => now()->format('Y-m')]) }}" class="btn btn-outline-primary btn-sm">詳細</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
