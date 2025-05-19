# 勤怠管理アプリ（Laravel）

## 📌 概要
このアプリは、従業員の出勤・退勤・休憩・修正申請などを記録・管理し、管理者がそれらを承認できる勤怠管理システムです。

- Laravel 10 + Bladeテンプレート
- 管理者と一般ユーザーで画面が分かれています
- Fortifyによるユーザー認証（メール認証付き）
- 申請と承認のワークフローも実装

## 🔗 デモURL
- https://kintai.codeshift-lab.com  
（ログイン可能なテストアカウントは下記参照）

## 👤 テストアカウント（閲覧用）
- 一般ユーザー  
  Email: test@example.com  
  Password: password123  
- 管理者ユーザー  
  Email: admin@example.com  
  Password: password123  

## 🛠 使用技術
- PHP 8.2 / Laravel 10.x
- MySQL 8.0
- Nginx + Let's Encrypt（本番環境）
- Docker（開発用）

## 🧪 主な機能一覧
- 打刻（出勤／退勤／休憩）
- 勤怠一覧・詳細表示（月別・日別）
- 勤怠修正申請／管理者による承認
- ログイン／ユーザー登録／メール認証
- 管理画面：ユーザー別勤怠状況の閲覧と申請承認

## 🖥 ローカル開発環境のセットアップ

```bash
git clone git@github.com:koji-webfolio-2025/portfolio-attendance.git
cd portfolio-attendance
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
