# Proテスト　フリマアプリ

## 環境構築

### Docker ビルド

1. リポジトリをクローン

   `git clone git@github.com:O-Yukine/ProTest-FleamMarket.git`

2. Docker アプリを立ち上げる

3. Docker ビルド

   `docker-compose up -d --build`

### Laravel 環境構築

1. PHP コンテナに入る

   `docker-compose exec php bash`

2. Composer パッケージをインストール

   `composer install`

3. 環境設定ファイルをコピー

   `cp .env.example .env`

4. .env ファイルを編集

   DB_HOST=mysql  
   DB_DATABASE=laravel_db  
   DB_USERNAME=laravel_user  
   DB_PASSWORD=laravel_pass

5. アプリケーションキーの作成

   `php artisan key:generate`

6. マイグレーションの実行

   `php artisan migrate`

7. シーディングの実行

   `php artisan db:seed`

### ユーザー登録時のメール認証システムの導入

\*以下 mailtrap を利用しています

1. mailtrap のアカウントを作成

2. mailtrap より SMTP を取得して、以下の情報を.env ファイルに追加

   MAIL_MAILER=  
   MAIL_HOST=  
   MAIL_PORT=
   MAIL_USERNAME=  
   MAIL_PASSWORD=
   MAIL_ENCRYPTION=  
   MAIL_FROM_ADDRESS=任意のメールアドレス  
   MAIL_FROM_NAME="${APP_NAME}"

3. ユーザー登録時に mailtrap にメールが送られてくるので、そのメールよりメール認証を完了させてください

### stripe 決済システムの導入

1. Stripe アカウントの作成

2. API キーを取得して .env に設定

   Stripe ダッシュボード →「開発者」→「API キー」より取得し、.env に追加

   STRIPE_KEY=pk_test_xxxxx
   STRIPE_SECRET=sk_test_xxxxx

3. コンビニ決済を有効化

   Stripe ダッシュボード →「支払い方法」より **コンビニ決済** を ON にする

4. テスト用カード・コンビニ決済情報

### カード払い

- カード番号:4242 4242 4242 4242
- 有効期限: 未来の日付
- CVV: 任意の 3 桁の番号
- 名義: 任意のもの(架空のもので可)

### コンビニ決済

- コンビニ払いを確定すると支払いコードが発行される

---

## ダミーユーザー情報

| 名前      | メールアドレス     | パスワード |
| --------- | ------------------ | ---------- |
| 香川 太郎 | taro@example.com   | password   |
| 高松 花子 | hanako@example.com | password   |
| 丸亀 二郎 | jiro@example.com   | password   |

※ パスワードは開発・テスト用です。

### ダミーユーザープロフィール

| ユーザー  | プロフィール例                 |
| --------- | ------------------------------ |
| 香川 太郎 | ランダム生成のプロフィール情報 |
| 高松 花子 | ランダム生成のプロフィール情報 |
| 丸亀 二郎 | ランダム生成のプロフィール情報 |

※ プロフィールは `ProfileFactory` で自動生成されます。

---

### ダミー商品情報

#### 香川 太郎 の商品

| 商品名    | 価格     | ブランド | 商品画像     | 商品説明                         | コンディション | カテゴリ |
| --------- | -------- | -------- | ------------ | -------------------------------- | -------------- | -------- |
| 腕時計    | 15,000円 | Rolax    | clock.jpg    | スタイリッシュなデザインの腕時計 | 1              | 1, 5     |
| HDD       | 5,000円  | 西芝     | harddisk.jpg | 高速で信頼性の高いハードディスク | 2              | 3        |
| 玉ねぎ3束 | 300円    | なし     | onion.jpg    | 新鮮な玉ねぎ3束のセット          | 3              | 10       |
| 革靴      | 4,000円  | なし     | shoes.jpg    | クラシックなデザインの革靴       | 4              | 1, 5     |
| ノートPC  | 45,000円 | なし     | laptop.jpg   | 高性能なノートパソコン           | 1              | 2        |

#### 高松 花子 の商品

| 商品名           | 価格    | ブランド  | 商品画像           | 商品説明                       | コンディション | カテゴリ |
| ---------------- | ------- | --------- | ------------------ | ------------------------------ | -------------- | -------- |
| マイク           | 8,000円 | なし      | mic.jpg            | 高音質のレコーディング用マイク | 2              | 2        |
| ショルダーバッグ | 3,500円 | なし      | bag.jpg            | おしゃれなショルダーバッグ     | 3              | 1, 4, 12 |
| タンブラー       | 500円   | なし      | tumbler.jpg        | 使いやすいタンブラー           | 4              | 10       |
| コーヒーミル     | 4,000円 | Starbacks | coffee_grinder.jpg | 手動のコーヒーミル             | 1              | 10       |
| メイクセット     | 2,500円 | なし      | makeup_set.jpg     | 便利なメイクアップセット       | 2              | 1, 4, 6  |

---

#### 丸亀 二郎 の商品

※ 今回は商品なし（必要に応じて追加可能）

## ユニットテストとテスト環境構築

1.  テスト用のデータベースを作る

    MySQL のコンテナ内に入る（パスワードは docker-compose.yml に設定されているものを使用)

    `docker-compose exec mysql bash`  
     `mysql -u root -p`

    laravel_test を作成

    `CREATE DATABASE laravel_test;`

2.  テスト用.env を作る (Docker コンテナ内)

    `cp .env .env.testing`

3.  .env.testing を編集

    APP_ENV=test  
    APP_KEY=

    DB_DATABASE=laravel_test  
    DB_USERNAME=root  
    DB_PASSWORD=root

4.  テスト用アプリケーションキーの作成

    `php artisan key:generate --env=testing`

5.  マイグレーションの実行

    `php artisan migrate --env=testing`

6.  テストの実行は以下のコマンド

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

- 開発環境: http://localhost/
- phpMyAdmin: http://localhost:8080/
