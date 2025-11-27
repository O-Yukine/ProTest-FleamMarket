# 模擬案件１　フリマアプリ

## 環境構築

### Docker ビルド

1. リポジトリをクローン  
   `git clone git@github.com:`
2. Docker アプリを立ち上げる
3. Docker ビルド  
   `docker-compose up -d --build`

### Laravel 環境構築

1. PHP コンテナに入る

   `docker-compose exec php bash`

2. Composer パッケージをインストール

   `compose install`

3. 環境設定ファイルをコピー

   `cp .env.example .env`

4. .env ファイルを編集

   `DB_HOST=mysql`  
   `DB_DATABASE=laravel_db`  
   `DB_USERNAME=laravel_user`  
   `DB_PASSWORD=laravel_pass`

5. アプリケーションキーの作成

   `php artisan key:generate`

6. マイグレーションの実行

   `php artisan migrate`

7. シーディングの実行

   `php artisan db:seed`

## ユーザー登録時のメール認証システムの導入

\*以下 mailtrap を利用しています

1. mailtrap のアカウントを作成
2. mailtrap より SMTP を取得して、以下の情報を.env ファイルに追加

   `MAIL_MAILER=`  
   `MAIL_HOST=`  
   `MAIL_PORT=`  
   `MAIL_USERNAME=`  
   `MAIL_PASSWORD=`  
   `MAIL_ENCRYPTION=`

3.ユーザー登録時に mailtrap にメールが送られてくるので、そのメールよりメール認証を完了させてください

## stripe 決済システムの導入

1. stripe アカウントを作る
2. ダッシュボードの設定よりコンビニ決済を有効にする
3. 開発者ページより API キーを取得し、env.に追加

### stripe CLI (webhook 開発用)

1. Stripe CLI をインストール

   macOS(Homebrew)

   `brew install stripe/stripe-cli` codechacke

   Windows / Linux は公式サイト参照

2. ログイン

   `stripe login`

3. Webhook リッスン

   `stripe listen --forward-to http://localhost/stripe/webhook`

4. secretKey を.env に追加

5. webhook からの支払い完了のイベントを受け取るには、このリッスンは常に開いておいてください

### テストカード/コンビニ決済情報

#### カード払い

- カード番号:4242 4242 4242 4242
- 有効期限: 未来の日付
- CVV: 任意の 3 桁の番号
- 名義: 任意のもの(架空のもので可)

#### コンビニ決済

- コンビニ払いを確定すると支払いコードが発行される

## ユニットテストとテスト環境構築

1. テスト用.env を作る  
   `cp .env .env.testing`

2. .env.testing を編集  
   ` APP_ENV =test`  
   `APP_KEY =`

   `DB_DATABASE = laravel_test`  
   `UserName = root`  
   `Password = root   `

3. テスト用アプリケーションキーの作成  
   `php artisan key:generate　--env=testing`

4. マイグレーションの実行  
   `php artisan migrate --env=testing`

5. テストの実行は以下のコマンド  
    `php artisan test tests/Feature`
   または  
    `vendor/bin/phpunit tests/Feature`

   もしくは

   `php artisan test tests/Feature/行いたいテストファイル`

   でファイルごとにテストを行えます

## 使用技術（実行環境）

- PHP8.1
- Laravel8.83.8
- MySQL8.0.26

## ER 図

![ER図](./flea_market.drawio.png)

## URL

- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/
