<div class="ui mini modal transition hidden delete-modal">
    <div class="header">
        確認
    </div>
    <div class="content">
        <p>選択したデータを削除していいでしょうか?</p>
    </div>
    <div class="actions">
        <div class="ui negative button">
            キャンセル
        </div>
        <div class="ui positive right labeled icon button delete-ok-btn">
            OK
            <i class="checkmark icon"></i>
        </div>
    </div>
    <form method="post" class="delete-form">
        <input type="hidden" name="_method" value="DELETE">
        <!-- CSRF保護 -->
        @csrf
    </form>
</div>
