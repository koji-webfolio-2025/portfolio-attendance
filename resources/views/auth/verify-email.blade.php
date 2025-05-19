@extends('layouts.app')

@section('title', 'メール認証誘導画面')

@section('content')
    <div class="container text-center py-5">

        <h2>登録していただいたメールアドレスに認証メールを送付しました。</h2>
        <p>メール認証を完了してください。</p>

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-secondary mt-3">認証はこちらから</button>
        </form>

        <div class="mt-3">
            <a href="{{ route('verification.send') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                認証メールを再送する
            </a>
        </div>
    </div>
@endsection
