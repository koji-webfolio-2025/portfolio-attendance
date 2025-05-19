@extends('layouts.app')

@section('title', '会員登録')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="p-4" style="width: 100%; max-width: 500px;">
        <h2 class="text-center mb-4">会員登録</h2>

        <form method="POST" action="{{ route('register') }}" novalidate>
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">お名前</label>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}">
                @error('name')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}">
                @error('email')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="form-label">パスワード</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required>
                @error('password')
                    <span class="text-danger small">{{ $message }}</span>
                @enderror
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-dark">登録</button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}">ログインはこちら</a>
            </div>
        </form>
    </div>
</div>
@endsection
