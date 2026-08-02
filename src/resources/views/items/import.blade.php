@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('title', '商品CSVインポート')

@section('content')

<h2>商品インポート</h2>

<p>
    <a href="{{ route('items.import.template') }}">
        CSVテンプレートをダウンロード
    </a>
</p>

<p class="help-text">
    ※　テンプレートには入力例が1列含まれています。
    不要な場合は削除してから使用してください。
</p>

<p>
    CSVの列順：
    カテゴリー、商品名、管理番号、単位、最低在庫数、商品メモ
</p>

<p class="help-text">
    ※　エラーが表示された場合は、修正したCSVファイルをもう一度選択してください。
</p>

<form
    action="{{ route('items.import.store') }}"
    method="POST"
    enctype="multipart/form-data"
>
    @csrf

    <div>
        <label for="csv_file">CSVファイル</label>

        <input
            type="file"
            name="csv_file"
            id="file"
            accept=".csv,text/csv"
        >

        @if ($errors->any())
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <button type="submit">
        インポート
    </button>
</form>

<p>
    <a href="{{ route('items.index') }}">
        商品一覧へ戻る
    </a>
</p>

@endsection

