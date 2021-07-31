@php
    $active_employee = 'active';
@endphp

@extends('layouts.app_admin')

@php
    // route(アクション名) => アクション名は、php artisan route:list で出したときの Name
@endphp

@section('content')
    <div class="content-top">
        <h3>ユーザー管理 - ユーザーの編集</h3>
        <div class="ui segment">
            <form class="ui form edit-entry-form" method="post" action="{{route('users.update', ['user' => $user['id']])}}">
                @method('PUT')
                <h4 class="ui dividing header">編集するユーザー</h4>
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
                            <input type="text" name="name" placeholder="名前" @if (old('name'))value="{{old('name')}}@else value="{{$user['name']}}@endif">
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
                            <input type="text" name="email" placeholder="メールアドレス" @if (old('email'))value="{{old('email')}}@else value="{{$user['email']}}@endif">
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
                    <button class="ui button green edit-entry-save" type="submit" tabindex="0">編集</button>
                    <a class="ui button" tabindex="0" href="{{route('employee.index')}}">戻る</a>
                </div>
                <!-- CSRF保護 -->
                @csrf
            </form>
        </div>
    </div>
@endsection
