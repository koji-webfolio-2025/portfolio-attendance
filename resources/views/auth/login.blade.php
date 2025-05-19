@extends('layouts.app')

@section('title', request()->is('admin/*') ? '管理者ログイン' : 'ログイン')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="p-4" style="width: 100%; max-width: 500px;">
        <h2 class="text-center mb-4">
            {{ request()->is('admin/*') ? '管理者ログイン' : 'ログイン' }}
        </h2>

        @if ($errors->any())
            <div class="alert alert-danger text-center">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            {{-- メールアドレス --}}
            <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input type="email" name="email" id="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- パスワード --}}
            <div class="mb-4">
                <label for="password" class="form-label">パスワード</label>
                <input type="password" name="password" id="password"
                    class="form-control @error('password') is-invalid @enderror">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-dark">ログインする</button>
            </div>

            @unless(request()->is('admin/*'))
                <div class="text-center">
                    <a href="{{ route('register') }}">会員登録はこちら</a>
                </div>
            @endunless
        </form>
    </div>
</div>
@endsection
