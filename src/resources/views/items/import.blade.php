@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('title', '商品CSVインポート')

@section('content')

<h2>商品インポート</h2>

<div class="import-actions">
    <a
        href="{{ route('items.import.template') }}"
        class="btn btn-secondary"
    >
        CSVテンプレートをダウンロード
    </a>
</div>

<form
    action="{{ route('items.import.store') }}"
    method="POST"
    enctype="multipart/form-data"
    class="form-card"
>
    @csrf

<p class="help-text">
    ※ テンプレートには入力例が1行含まれています。<br>
    不要な場合は削除してから使用してください。
</p>

<p class="help-text">
    CSVの列順：<br>
    カテゴリー、商品名、管理番号、単位、最低在庫数、商品メモ
</p>

<p class="help-text">
    ※ エラーが表示された場合は、修正したCSVファイルをもう一度選択してください。
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

        @if ($errors->any())
            <div class="error-list">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="form-actions">
        <button
            type="submit"
            class="btn btn-primary"
        >
            インポート
        </button>

        <a
            href="{{ route('items.index') }}"
            class="btn-light"
        >
            商品一覧へ戻る
        </a>
    </div>
</form>

@endsection

