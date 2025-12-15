## 環境構築
### dockerビルド

1. リポジトリのクローン

   `git clone https://github.com/tomo1583gh/inventory-management.git`

2. 階層を変更

    `cd inventory-management`

3. Dockerコンテナのビルド・起動

    `docker compose up -d --build`

    ※  MySQLは、OSによって起動しない場合があるのでそれぞれのPCに合わせてdocker-compose.ymlファイルを編集して下さい。

    ※　Linux / WSL 環境で以下のような警告が出る場合は、「UID/GID の設定（Linux/WSL 推奨）」を参照してください。

```text
    WARN The "UID" variable is not set. Defaulting to a blank string.
    WARN The "GID" variable is not set. Defaulting to a blank string.
```    

### Laravelセットアップ

1. PHPコンテナに入る

    `docker compose exec php bash`

2. Composerで依存パッケージをインストール

    `composer install`

3. .envファイルを作成

    `cp .env.example .env`

    必要に応じて環境変数を編集

4. アプリケーションキーを生成

    `php artisan key:generate`

5. マイグレーションを実行

    `php artisan migrate`

6. 初期データを投入

    `php artisan db:seed`

7. Mailhog起動（別途インストール必要）

    http://localhost:8025 にアクセスし、送信メールを確認出来ます  
    `.env`のMAIL_HOST=mailhogを設定してください

8. ブラウザでアプリにアクセス

    `http://localhost`