@extends('layouts.app')

@section('title', '商品CSVインポート')

@section('content')

<h2>商品インポート</h2>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form
    action="{{ route('items.import.store') }}"
    method="POST"
    enctype="multipart/form-date"
>
    @csrf

    <div>
        <label for="csv_file">CSVファイル</label>

        <input
            type="file"
            name="csv_file"
            id="file"
            accept=".csv,text/csv"
            required
        >
    </div>

    <button type="submit">
        インポート
    </button>
</form>

<p>
    CSVの列順：
    カテゴリー、商品名、管理番号、単位、最低在庫数、商品メモ
</p>

<p>
    <a href="{{ route('items.index') }}">
        商品一覧へ戻る
    </a>
</p>

@endsection

