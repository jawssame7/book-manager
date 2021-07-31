@php
    $flashStatus = [
        'success' => 'info',
        'error' => 'negative',
    ];
@endphp
@foreach($flashStatus as $key => $value)
    @if (Session::has($key))
        <div class="ui message {{ $value }}">
            <i class="close icon"></i>
            <div class="header">
                {{ session($key) }}
            </div>
        </div>
    @endif
@endforeach
