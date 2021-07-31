@extends('layouts.app_admin')

@section('title', '書籍の登録')
@php
    $active_book = 'active';
    $thumbnail = old('thumbnail') ? old('thumbnail') : '';
    // $thumbnailUrl = asset('storage/thumbnails/' . $thumbnail);
@endphp

@section('content')
    <div class="content-top">
        <h3>書籍管理 - @yield('title')</h3>
        <div class="ui segment">
            <form class="ui form new-entry-form" method="POST">
                <h4 class="ui dividing header">登録する書籍</h4>
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
                    <label>詳細</label>
                    <div class="field @error('description') error @enderror">
                        <textarea name="description" rows="2">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="ui pointing red basic label">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="field">
                    <label>サムネイル</label>
                    <div class="ui three column grid">
                        <div class="column top aligned content">
                            <input type="file" name="thumbnail_file" accept="image/*" placeholder="サムネイル" >
                            <input type="hidden" name="thumbnail" value="{{ old('thumbnail') }}">
                            @error('thumbnail')
                            <div class="ui pointing below red basic label">
                                {{ $message }}
                            </div>
                            @enderror
                            <div class="ui segment thumbnail-area">
                                <img class="ui middle aligned tiny image thumbnail-img" src="{{(old('thumbnail')) ? asset('storage/thumbnails/'.old('thumbnail')) : asset('storage/thumbnails/no-image.png')}}" alt="サムネイル">
                            </div>
                            <a class="ui button mini green thumbnail-add" tabindex="0" >追加</a>
                            <a class="ui button mini orange thumbnail-clear disabled" tabindex="0" >クリア</a>
                        </div>
                    </div>
                </div>
                <div class="ui divider"></div>
                <div class="ui container button-group">
                    <button class="ui button green new-entry-save" tabindex="0">新規作成</button>
                    <a class="ui button" tabindex="0" href="{{route('books.index')}}">戻る</a>
                </div>
                <!-- CSRF保護 -->
                @csrf
            </form>
        </div>
    </div>
@endsection
