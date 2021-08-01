@section('title', '使用者管理')
@php
    $active_employee = 'active';
@endphp

@extends('layouts.app_admin')

@section('content')
    <div class="content-top">
        <h3>@yield('title')</h3>
        <x-admin.common.flash_message />
        <div class="ui segment">
            <form class="ui form" action="{{$employees->url(1)}}">
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
                <input type="file" name="emp_csv_file" accept=".csv" placeholder="csvファイル" >
                <button class="ui button teal new-entry-emp-csv" tabindex="0">CSVインポート</button>
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
                @foreach ($employees as $employee)
                <tr>
                    <td>{{$employee->name}}</td>
                    <td>{{$employee->email}}</td>
                    <td class="action">
                        <button class="ui button orange edit-entry" data-id="{{$employee->id}}" tabindex="0">
                            編集
                        </button>
                        <button class="ui button delete-entry" data-id="{{$employee->id}}" data-url="{{ route('employee.destroy', ['employee' => $employee->id]) }}" tabindex="0">
                            削除
                        </button>
                    </td>
                </tr>
                @endforeach
            </table>
            <div class="container ui center aligned">
                <div class="ui pagination menu">
                    {{-- $pagenate->appends() を使うことでパラメータを追加できる --}}
                    @for ($i = 0; $i < $employees->lastPage(); $i++)
                        @if ($i === 0)
                            <a class="item {{ ($employees->currentPage() === 1) ? 'disabled' : 'active' }}" href="{{ ($employees->currentPage() === 1) ? 'javascript:void(0);' : $employees->appends($pagenate_params)->url($i + 1) }}">
                                最初
                            </a>
                        @elseif($i > 0 && $i + 1 !== $employees->lastPage())
                            <a class="item {{ ($employees->currentPage() === $i + 1) ? 'disabled' : 'active' }}" href="{{ ($employees->currentPage() === $i + 1) ? 'javascript:void(0);' : $employees->appends($pagenate_params)->url($i + 1) }}">
                                {{ $i + 1 }}
                            </a>
                        @elseif($i + 1 === $employees->lastPage())
                            <a class="item {{ ($employees->currentPage() === $employees->lastPage()) ? 'disabled' : 'active' }}" href="{{ ($employees->currentPage() === $employees->lastPage()) ? 'javascript:void(0);' : $employees->appends($pagenate_params)->url($i + 1) }}">
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
    {{--  ajaxエラーダイアログ  --}}
    <x-admin.common.ajax_err_message />
@endsection
