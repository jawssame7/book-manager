@section('title', '保管場所管理')

@php
    $active_place = 'active';
@endphp

@extends('layouts.app_admin')

@section('content')
    <div class="content-top">
        <h3>@yield('title')</h3>
        <x-admin.common.flash_message />
        <div class="ui segment">
            <form class="ui form" action="{{route('places.index')}}">
                <h4 class="ui dividing header">検索条件</h4>
                <div class="field">
                    <label>名前</label>
                    <div class="two fields">
                        <div class="field">
                            <input type="text" name="search_name" placeholder="名前" value="{{(empty($pagenate_params['search_name'])) ? '' : $pagenate_params['search_name']}}">
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
                <button class="ui button green new-entry-place" tabindex="0">新規作成</button>
            </div>
            <table class="ui striped table">
                <thead>
                <tr>
                    <th>名前</th>
                    <th class="action">アクション</th>
                </tr>
                </thead>
                @foreach ($places as $place)
                    <tr>
                        <td>{{$place->name}}</td>
                        <td class="action">
                            <button class="ui button orange edit-entry-place" data-id="{{$place->id}}" data-name="{{$place->name}}" data-mode="edit" tabindex="0">
                                編集
                            </button>
                            <button class="ui button delete-entry" data-id="{{$place->id}}" data-url="{{ route('places.destroy', ['place' => $place->id]) }}" tabindex="0">
                                削除
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    {{--  削除確認モーダル  --}}
    <x-admin.common.delete_modal />
    {{--  新規作成・編集モーダル  --}}
    <x-admin.places.create_edit_modal />
@endsection
