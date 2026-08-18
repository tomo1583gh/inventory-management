@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/stocks.css') }}">
@endsection

@section('title', '入出庫CSVインポート')

@section('content')

<h2>入出庫CSVインポート</h2>

<div class="import-actions">
    <a 
        href="{{ route('stocks.import.template') }}"
        class="btn btn-secondary"
    >
        CSVテンプレートをダウンロード
    </a>
</div>

<form
    action="{{ route('stocks.import.store') }}"
    method="POST"
    enctype="multipart/form-data"
    class="form-card"
>

    @csrf

<p class="help-text">
    ※ CSVの列順は「管理番号、区分、数量、作業日時、入出庫メモ」です。<br>
    区分には「入庫」または「出庫」を入力してください。
</p>

    <div class="form-group">
        <label for="csv_file">
            CSVファイル
        </label>

        <input
            type="file"
            name="csv_file"
            id="csv_file"
            accept=".csv,text/csv"
        >

        @error('csv_file')
            <p class="error">
                {{ $message }}
            </p>
        @enderror
    </div>

    <div class="form-actions">
        <button
            type="submit"
            class="btn btn-primary"
        >
            インポート
        </button>

        <a
            href="{{ route('stocks.logs') }}"
            class="btn-light"
        >
            入出庫履歴へ戻る
        </a>
    </div>
</form>

@endsection
