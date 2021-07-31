@extends('layouts.app_admin')

@section('title', '書籍の編集')


@php
    $active_book = 'active';
    $thumbnail = old('thumbnail') ? old('thumbnail') : $book->thumbnail;
    $thumbnailUrl = asset('storage/thumbnails/' . $thumbnail);
@endphp

@section('content')
    <div class="content-top">
        <h3>書籍管理 - @yield('title')</h3>
        <div class="ui segment">
            <form class="ui form edit-entry-form" method="POST" action="{{route('books.update', ['book' => $book->id])}}">
                @method('PUT')
                <h4 class="ui dividing header">編集する書籍</h4>
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
                            <input type="text" name="name" placeholder="名前" value="{{(old('name')) ? old('name') : $book->name}}">
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
                        <textarea name="description" rows="2">{{ (old('description')) ? old('description') : $book->description }}</textarea>
                        @error('description')
                        <div class="ui pointing red basic label">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="field">
                    <label>保管場所</label>
                    <div class="two fields">
                        <div class="field @error('place_id') error @enderror">
                            <select name="place_id" class="ui fluid dropdown">
                                <option value="0"></option>
                                @foreach ($places as $place)
                                    <option value="{{$place->id}}" {{($book->place_id === $place->id) ? 'selected' : ''}}>{{$place->name}}</option>
                                @endforeach
                            </select>
                            @error('place_id')
                            <div class="ui pointing red basic label">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>使用者</label>
                    <div class="two fields">
                        <div class="field @error('employee_id') error @enderror">
                            <select class="ui fluid dropdown">
                                <option value=""></option>
                                @foreach ($employees as $emp)
                                    <option value="{{$emp->id}}" {{($book->user_id === $emp->id) ? 'selected' : ''}}>{{$emp->name}}</option>
                                @endforeach
                            </select>
                            @error('employee_id')
                            <div class="ui pointing red basic label">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>サムネイル</label>
                    <div class="ui three column grid">
                        <div class="column top aligned content">
                            <input type="file" name="thumbnail_file" accept="image/*" placeholder="サムネイル" value="{{ old('thumbnail') }}" >
                            <input type="hidden" name="thumbnail" value="{{$thumbnail}}">
                            @error('thumbnail')
                            <div class="ui pointing below red basic label">
                                {{ $message }}
                            </div>
                            @enderror
                            <div class="ui segment thumbnail-area">
                                <img class="ui middle aligned tiny image thumbnail-img" src="{{($thumbnail) ? $thumbnailUrl : asset('storage/thumbnails/no-image.png')}}" alt="サムネイル">
                            </div>
                            <a class="ui button mini green thumbnail-add" tabindex="0" >追加</a>
                            <a class="ui button mini orange thumbnail-clear {{($thumbnail) ? '' : 'disabled'}}" tabindex="0" >クリア</a>
                        </div>
                    </div>
                </div>
                <div class="ui divider"></div>
                <div class="ui container button-group">
                    <button class="ui button green edit-entry-save" type="submit" tabindex="0">編集</button>
                    <a class="ui button" tabindex="0" href="{{route('books.index')}}">戻る</a>
                </div>
                <!-- CSRF保護 -->
                @csrf
            </form>
        </div>
    </div>
@endsection
