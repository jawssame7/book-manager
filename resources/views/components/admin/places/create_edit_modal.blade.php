<div class="ui mini modal transition create-modal edit-modal">
    <div class="header">
        保管場所の登録
    </div>
    <div class="content">
        <h5>登録する保管場所</h5>
        <form method="POST" action="{{route('places.store')}}" class="ui form place-create-edit-form">
            <input type="hidden" name="_method" value="POST">
            <!-- CSRF保護 -->
            @csrf
            <input type="hidden" name="id">
            <div class="field">
                <label>名前</label>
                <div class="field @error('name') error @enderror">
                    <input type="text" name="name" placeholder="名前" value="{{ old('name') }}">
                    @error('name')
                    <div class="ui pointing red basic label">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
            </div>
        </form>
    </div>
    <div class="actions">
        <div class="ui negative button">
            キャンセル
        </div>
        <div class="ui positive right labeled icon button place-create-edit-ok-btn">
            OK
            <i class="checkmark icon"></i>
        </div>
    </div>
</div>
@error('name')
<script type="text/javascript">
    $(function () {
        {{--  エラーメッセージがあったときモーダル表示  --}}
        $('.mini.modal.create-modal')
            .modal({
                onHide: function () {
                    // リセット処理
                    placeModalReset(this);
                }
            }).modal('show');
    });
</script>
@enderror
