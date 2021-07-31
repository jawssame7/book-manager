@section('title', '書籍管理')

    @php
    $active_book = 'active';
    $page_params_place_val = empty($pagenate_params['place_id']) ? '' : $pagenate_params['place_id'];
    $page_params_emp_val = empty($pagenate_params['emp_id']) ? '' : $pagenate_params['emp_id'];
    @endphp

    @extends('layouts.app_admin')

@section('content')
    <div class="content-top">
        <h3>@yield('title')</h3>
        <x-admin.common.flash_message />
        <div class="ui segment">
            <form class="ui form" action="{{ $books->url(1) }}">
                <h4 class="ui dividing header">検索条件</h4>
                <div class="field">
                    <label>名前</label>
                    <div class="two fields">
                        <div class="field">
                            <input type="text" name="name" placeholder="名前"
                                value="{{ empty($pagenate_params['name']) ? '' : $pagenate_params['name'] }}">
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>保管場所</label>
                    <div class="two fields">
                        <div class="field">
                            <select name="place_id" class="ui fluid dropdown">
                                <option value="0"></option>
                                @foreach ($places as $place)
                                    <option value="{{ $place->id }}"
                                        {{ $page_params_place_val === strval($place->id) ? 'selected' : '' }}>
                                        {{ $place->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>使用者</label>
                    <div class="two fields">
                        <div class="field">
                            <select name="employee_id" class="ui fluid dropdown">
                                <option value="0"></option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employees->id }}"
                                        {{ $page_params_emp_val === strval($employees->id) ? 'selected' : '' }}>
                                        {{ $employees->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label>削除済を含む</label>
                    <div class="two fields">
                        <div class="field">
                            <input type="checkbox" name="deleted_at"
                                {{ empty($pagenate_params['deleted_at']) ? '' : 'checked' }}>
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
                        <th>サムネイル</th>
                        <th>保管場所</th>
                        <th>使用者</th>
                        <th>詳細</th>
                        <th class="action">アクション</th>
                    </tr>
                </thead>
                @foreach ($books as $book)
                    <tr>
                        <td>{{ $book->name }}</td>
                        <td>
                            @if ($book->thumbnail)
                                <img class="ui middle aligned tiny image"
                                    src="{{ asset('storage/thumbnails/' . $book->thumbnail) }}" alt="{{ $book->name }}">
                            @else
                                <img class="ui middle aligned tiny image"
                                    src="{{ asset('storage/thumbnails/no-image.png') }}" alt="{{ $book->name }}">
                            @endif
                        </td>
                        <td>{{ $book->place_name }}</td>
                        <td>{{ $book->user_name }}</td>
                        <td>{{ $book->description }}</td>
                        <td class="action">
                            <button class="ui button orange edit-entry" data-id="{{ $book->id }}" tabindex="0">
                                編集
                            </button>
                            <button class="ui button delete-entry" data-id="{{ $book->id }}"
                                data-url="{{ route('books.destroy', ['book' => $book->id]) }}" tabindex="0">
                                削除
                            </button>
                        </td>
                    </tr>
                @endforeach
            </table>
            <div class="container ui center aligned">
                <div class="ui pagination menu">
                    {{-- $pagenate->appends() を使うことでパラメータを追加できる --}}
                    @for ($i = 0; $i < $books->lastPage(); $i++)
                        @if ($i === 0)
                            <a class="item {{ $books->currentPage() === 1 ? 'disabled' : 'active' }}"
                                href="{{ $books->currentPage() === 1 ? 'javascript:void(0);' : $books->appends($pagenate_params)->url($i + 1) }}">
                                最初
                            </a>
                        @elseif($i > 0 && $i + 1 !== $books->lastPage())
                            <a class="item {{ $books->currentPage() === $i + 1 ? 'disabled' : 'active' }}"
                                href="{{ $books->currentPage() === $i + 1 ? 'javascript:void(0);' : $books->appends($pagenate_params)->url($i + 1) }}">
                                {{ $i + 1 }}
                            </a>
                        @elseif($i + 1 === $books->lastPage())
                            <a class="item {{ $books->currentPage() === $books->lastPage() ? 'disabled' : 'active' }}"
                                href="{{ $books->currentPage() === $books->lastPage() ? 'javascript:void(0);' : $books->appends($pagenate_params)->url($i + 1) }}">
                                最後
                            </a>
                        @endif
                    @endfor
                </div>
            </div>
        </div>
    </div>
    {{-- 削除確認ダイアログ --}}
    <x-admin.common.delete_modal />
@endsection
