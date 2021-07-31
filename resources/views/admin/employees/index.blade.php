@php
    $active_employee = 'active';
@endphp

@extends('layouts.app_admin')

@section('content')
    <div class="content-top">
        <h3>ユーザー管理</h3>
        <x-admin.common.flash_message />
        <div class="ui segment">
            <form class="ui form" action="{{$users->url(1)}}">
                <h4 class="ui dividing header">検索条件</h4>
                <div class="field">
                    <label>名前</label>
                    <div class="two fields">
                        <div class="field">
                            <input type="text" name="name" placeholder="名前" value="{{(empty($pagenate_params['name'])) ? '' : $pagenate_params['name']}}">
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>削除済を含む</label>
                    <div class="two fields">
                        <div class="field">
                            <input type="checkbox" name="deleted_at" {{(empty($pagenate_params['deleted_at'])) ? '' : 'checked'}}>
                        </div>
                    </div>
                </div>
                <button class="ui button blue search-btn" tabindex="0">検索</button>
            </form>
        </div>
    </div>
    <div class="content-main">
        <div class="ui segment">
            <div class="container ui right aligned">
                <button class="ui button green new-entry" tabindex="0">新規作成</button>
            </div>
            <table class="ui striped table">
                <thead>
                <tr>
                    <th>名前</th>
                    <th>メールアドレス</th>
                    <th class="action">アクション</th>
                </tr>
                </thead>
                @foreach ($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td class="action">
                        <button class="ui button orange edit-entry" data-id="{{$user->id}}" tabindex="0">
                            編集
                        </button>
                        <button class="ui button delete-entry" data-id="{{$user->id}}" data-url="{{ route('users.destroy', ['user' => $user->id]) }}" tabindex="0">
                            削除
                        </button>
                    </td>
                </tr>
                @endforeach
            </table>
            <div class="container ui center aligned">
                <div class="ui pagination menu">
                    {{-- $pagenate->appends() を使うことでパラメータを追加できる --}}
                    @for ($i = 0; $i < $users->lastPage(); $i++)
                        @if ($i === 0)
                            <a class="item {{ ($users->currentPage() === 1) ? 'disabled' : 'active' }}" href="{{ ($users->currentPage() === 1) ? 'javascript:void(0);' : $users->appends($pagenate_params)->url($i + 1) }}">
                                最初
                            </a>
                        @elseif($i > 0 && $i + 1 !== $users->lastPage())
                            <a class="item {{ ($users->currentPage() === $i + 1) ? 'disabled' : 'active' }}" href="{{ ($users->currentPage() === $i + 1) ? 'javascript:void(0);' : $users->appends($pagenate_params)->url($i + 1) }}">
                                {{ $i + 1 }}
                            </a>
                        @elseif($i + 1 === $users->lastPage())
                            <a class="item {{ ($users->currentPage() === $users->lastPage()) ? 'disabled' : 'active' }}" href="{{ ($users->currentPage() === $users->lastPage()) ? 'javascript:void(0);' : $users->appends($pagenate_params)->url($i + 1) }}">
                                最後
                            </a>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </div>
    {{--  削除確認ダイアログ  --}}
    <x-admin.common.delete_modal />
@endsection
