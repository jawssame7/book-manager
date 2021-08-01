$(function () {


    // 新規作成ボタン押下時 画面遷移
    $('button.new-entry').click(function () {
        var el = this;
        console.log(el)
        location.href = location.pathname + '/create';
    });

    // 新規作成ボタン押下時 モーダル
    $('button.new-entry-place').click(function () {
        var el = this,
            data = $(el).data();

        $('.mini.modal.create-modal')
            .modal({
                onHide: function () {
                    // リセット処理
                    placeModalReset(this);
                }
            }).modal('show');
    });

    // 編集ボタン押下時
    $('button.edit-entry').click(function () {
        var el = this,
            data = $(el).data();
        console.log(el)
        location.href = location.pathname + '/' + data.id + '/edit';
    });

    // 編集ボタン押下時 モーダル
    $('button.edit-entry-place').click(function () {
        var el = this,
            data = $(el).data(),
            mode = data.mode,
            id = data.id,
            name = data.name;

        $('.mini.modal.edit-modal')
            .modal({
                onShow: function () {
                    var el = this,
                        $el = $(el),
                        $form = $el.find('form.place-create-edit-form'),
                        $id = $el.find('input[name=id]'),
                        $name = $el.find('input[name=name]'),
                        $method = $el.find('input[name=_method]');

                    if (mode === 'edit') {
                        $form.attr('action', '/admin/places/' + id);
                        $method.val('PUT');
                        $id.val(id);
                    }

                    $name.val(name);
                },
                onHide: function () {
                    // リセット処理
                    placeModalReset(this);
                }
            }).modal('show');
    });

    // 削除ボタン押下時
    $('button.delete-entry').click(function () {
        var el = this,
            data = $(el).data();
        console.log(el)
        $('.mini.modal.delete-modal')
            .attr('data-id', data.id)
            .attr('data-url', data.url)
            .modal('show');

    });

    //
    $('.delete-ok-btn').click(function () {
        var el = this,
            $form = $('.delete-form'),
            data = $('.mini.modal.delete-modal').data(),
            csrf = $('input[type=hidden][name=_token]'),// csrf
            action = location.pathname.replace('/index', '') // /index のときも考慮;

        // 動的にform作成
        // $('<form/>', {action: data.url, method: 'post'})
        //     .append($('<input/>', {type: 'hidden', name: '_method', value: 'DELETE'}))
        //     .append($('<input/>', {type: 'hidden', name: '_token', value: csrf.val()}))
        //     .appendTo(document.body)
        //     .submit();

        // ajax メソッドで同期リクエスト この場合、flashメッセージは出ない
        // $.ajax(data.url, {
        //     async: false,
        //     method: 'POST',
        //     data : {
        //         '_method': 'DELETE',
        //         '_token' : csrf.val()
        //     }
        // }).done(function(data) {
        //     location.href = location.pathname;
        // });

        // form送信
        $form.attr('action', data.url);
        $form.submit();

    });

    // 新規作成画面 保存
    $('.button.new-entry-save').click(function () {
        var el = this,
            $form = $('.new-entry-form');

        console.log($form)
        if ($form) {
            $form.attr('action', location.pathname.replace('/create', ''));
            $form.submit();
        }
        return false;
    });


    // 保管場所 新規作成・編集 OKボタン
    $('div.button.place-create-edit-ok-btn').click(function () {
        var el = this,
            $form = $('.place-create-edit-form');

        if ($form) {
            $form.submit();
        }
        return false;
    });

    // 書籍管理 サムネイルファイルフィールド
    $('input[name=thumbnail_file]').change(function () {
        var el = this,
            $el = $(el),
            $thumbnail = $('input[name=thumbnail]'),
            $img = $('img.thumbnail-img'),
            $addBtn = $('.thumbnail-add'),
            $clearBtn = $('.thumbnail-clear'),
            file,
            url;

        file = el.files.length > 0 ? el.files[0] : null;
        $img.addClass('loading');

        if (location.pathname.indexOf('/create') > 0) {
            url = location.pathname.replace('/create', '/uploadThumbnail');
        } else {
            var retUrl = location.pathname.split('/');
            var ret = [];
            $(retUrl).each(function (i, item) {
                if (retUrl && !item.match(/\d/) && item !== 'edit') {
                    ret.push(item);
                }
            });
            url = ret.join('/') + '/uploadThumbnail';
        }

        fileUpload(file, url, function (res) {
            if (res.success) {
                $thumbnail.val(res.fileName);
                $img.attr('src', location.origin + res.filePath);
                $addBtn.removeClass('loading');
                $clearBtn.removeClass('disabled');
            }
        });
    });

    // 書籍管理 サムネイル追加ボタン
    $('.thumbnail-add').click(function () {
        var el = this,
            $el = $(el);

        $el.addClass('loading');
        // ファイルフィールドをクリック
        $('input[name=thumbnail_file]').click();
        return false;
    });

    // 書籍管理 サムネイルクリアボタン
    $('.thumbnail-clear').click(function () {
        var el = this,
            $el = $(el),
            $thumbnail = $('input[name=thumbnail]'),
            $img = $('img.thumbnail-img'),
            fileName;

        $el.addClass('loading');
        $img.addClass('loading');
        fileName = $thumbnail.val();

        fileDelete(fileName, location.pathname.replace('/create', '/clearThumbnail'), function (res) {
            if (res.success) {
                $thumbnail.val('');
                $img.attr('src', location.origin + '/storage/thumbnails/no-image.png');
                $el.addClass('disabled');
                $el.removeClass('loading');
            }
        });

        return false;
    });

    // 使用者管理 csvインポートボタン
    $('.new-entry-emp-csv').click(function () {
        var el = this,
            $el = $(el),
            $fileFeild = $('input[name=emp_csv_file]');

        $el.addClass('disabled');
        $el.addClass('loading');

        // ファイルフィールドをクリック
        $fileFeild.click();
        return false;
    });

    // 書使用者管理 csvファイルフィールド
    $('input[name=emp_csv_file]').change(function () {
        var el = this,
            $el = $(el),
            $addBtn = $('.new-entry-emp-csv'),
            file,
            url,
            $modal,
            $msg;

        file = el.files.length > 0 ? el.files[0] : null;
        if (!file) {
            return;
        }

        url = location.pathname  + '/empImportCsv';

        fileUpload(file, url, function (res) {
            if (res.success) {
                $addBtn.removeClass('loading');
                $modal = $('.mini.modal.ajax-err-modal');
                $msg = $modal.find('.message');
                $msg.text(res.message);

            } else {
                $modal = $('.mini.modal.ajax-err-modal');
                $msg = $modal.find('.message');

                $msg.text(res.error);
            }

            $modal.modal({
                onHide: function () {
                    location.reload();
                }
            }).modal('show');
        });
    });

});

/**
 * 保管場所のモーダルリセット処理
 * @param el
 */
function placeModalReset(el) {
    var $el = $(el),
        $errLabel = $el.find('.ui.pointing.red.basic.label'),
        $feild = $el.find('.field'),
        $form = $el.find('form.place-create-edit-form'),
        $id = $el.find('input[name=id]'),
        $name = $el.find('input[name=name]'),
        $method = $el.find('input[name=_method]');

    $errLabel.hide();
    $feild.removeClass('error');

    $form.attr('action', '/admin/places');
    $id.val('');
    $name.val('');
    $method.val('POST');
}

/**
 * 非同期通信でのファイルアップロード
 * @param file
 * @param url
 * @param callback
 * @returns {boolean}
 */
function fileUpload(file, url, callback) {
    var $token = $('input[name=_token]'),
        fd = new FormData(),
        xhr;

    if (!file) {
        return false;
    }

    if (file.size === 0) {
        return false;
    }

    fd.append('file', file, file.name);

    if ($token.length) {
        fd.append('_token', $token.val());
    }

    xhr = new XMLHttpRequest();

    xhr.open('POST', url, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            var res = xhr.response;
            res = $.parseJSON(res);
            callback(res);
        } else {

        }
    };

    xhr.send(fd);
}

/**
 * 非同期通信でのファイル削除
 * @param fileName
 * @param url
 * @param callback
 * @returns {boolean}
 */
function fileDelete(fileName, url, callback) {
    var $token = $('input[name=_token]'),
        fd = new FormData(),
        xhr;

    if (!fileName) {
        return false;
    }

    fd.append('fileName', fileName);

    if ($token.length) {
        fd.append('_token', $token.val());
    }

    xhr = new XMLHttpRequest();

    xhr.open('POST', url, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            var res = xhr.response;
            res = $.parseJSON(res);
            callback(res);
        } else {

        }
    };

    xhr.send(fd);
}
