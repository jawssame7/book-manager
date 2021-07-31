@php
    $active_employee = 'active';
@endphp

@extends('layouts.app_admin')

@section('content')
    <div class="content-top">
        <h3>ユーザー管理 - ユーザーの登録</h3>
        <div class="ui segment">
            <form class="ui form new-entry-form" method="POST">
                <h4 class="ui dividing header">登録するユーザー</h4>
                <div class="ui error message" style="@if ($errors->any()) display:block; @endif">
                    <i class="close icon"></i>
                    <div class="header">
                        入力エラーがあります。
                    </div>
                    <ul class="list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="field">
                    <label>名前</label>
                    <div class="two fields">
                        <div class="field @error('name') error @enderror">
                            <input type="text" name="name" placeholder="名前" value="{{ old('name') }}">
                            @error('name')
                            <div class="ui pointing red basic label">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>メールアドレス</label>
                    <div class="two fields">
                        <div class="field @error('email') error @enderror">
                            <input type="text" name="email" placeholder="メールアドレス" value="{{ old('email') }}">
                            @error('email')
                                <div class="ui pointing red basic label">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="ui divider"></div>
                <div class="ui container button-group">
                    <button class="ui button green new-entry-save" tabindex="0">新規作成</button>
                    <a class="ui button" tabindex="0" href="{{route('employee.index')}}">戻る</a>
                </div>
                <!-- CSRF保護 -->
                @csrf
            </form>
        </div>
    </div>
@endsection
