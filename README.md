# 実行環境作成

`composer`を使って`vendor`のパッケージをインストールする必要があるため
`PHP`と`composer`が必要


## Composer

https://getcomposer.org/download/


1. `composer install`(初回のみ)
1．`docker-compose up -d`(初回のみ)
1. dockerのsailの中に入り、`/var/www/html`に移動(初回のみ)
1. `php artisan storage:link` (初回のみ)
1. `docker-compose up -d`