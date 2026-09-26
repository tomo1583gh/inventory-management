@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/stocks.css') }}">
@endsection

@section('title', '出庫登録')

@section('content')

    <h2>出庫登録</h2>

    <form
        action="{{ route('stocks.out.store') }}"
        method="POST"
        class="form-card"
    >
        @csrf

        <div class="form-group">
            <label for="item_id">商品</label>

            <select id="item_id" name="item_id">
                <option value="">選択してください</option>

                @foreach ($items as $item)
                    <option
                        value="{{ $item->id }}"
                        data-stock="{{ $item->current_qty }}"
                        data-unit="{{ $item->unit }}"
                        @selected(old('item_id') == $item->id)
                    >
                        {{ $item->name }}
                        ({{ $item->sku }})
                    </option>
                @endforeach
            </select>

            <p id="current-stock" class="stock-info">
                現在庫：-
            </p>

            @error('item_id')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="qty">数量</label>

            <input
                id="qty"
                type="number"
                name="qty"
                value="{{ old('qty') }}"
                step="0.01"
                min="0.01"
            >

            <p id="remaining-stock" class="stock-info">
                出庫後の予定在庫数：-
            </p>

            <p id="stock-warning" class="error stock-warning">
                現在庫を超えています。
            </p>

            @error('qty')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="acted_at">作業日時</label>

            <input
                id="acted_at"
                type="datetime-local"
                name="acted_at"
                value="{{ old('acted_at', now()->format('Y-m-d\TH:i')) }}"
            >

            @error('acted_at')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="note">出庫メモ</label>

            <textarea
                id="note"
                name="note"
                rows="4"
            >{{ old('note') }}</textarea>

            @error('note')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-actions">
            <button
                id="submit-button"
                type="submit"
                class="btn btn-primary"
            >
                出庫を登録
            </button>

            <a
                href="{{ route('stocks.index') }}"
                class="btn-light"
            >
                在庫一覧へ戻る
            </a>
        </div>
    </form>

    <script>
    const itemSelect = document.getElementById('item_id');
    const qtyInput = document.getElementById('qty');

    const currentStock = document.getElementById('current-stock');
    const remainingStock = document.getElementById('remaining-stock');
    const stockWarning = document.getElementById('stock-warning');
    const submitButton = document.getElementById('submit-button');

    function updateStockDisplay() {
        const selectedOption =
            itemSelect.options[itemSelect.selectedIndex];

        const stock = parseFloat(selectedOption.dataset.stock);
        const unit = selectedOption.dataset.unit;
        const qty = parseFloat(qtyInput.value);

        if (Number.isNaN(stock)) {
            currentStock.textContent = '現在庫：-';
            remainingStock.textContent = '出庫後の予定在庫数：-';

            stockWarning.classList.remove('is-visible');
            submitButton.disabled = false;

            return;
        }

        currentStock.textContent =
            `現在庫：${stock} ${unit}`;

        if (Number.isNaN(qty)) {
            remainingStock.textContent =
                '出庫後の予定在庫数：-';

            stockWarning.classList.remove('is-visible');
            submitButton.disabled = false;

            return;
        }

        const remaining = stock - qty;

        remainingStock.textContent =
            `出庫後の予定在庫数：${remaining} ${unit}`;

        if (remaining < 0) {
            stockWarning.classList.add('is-visible');
            submitButton.disabled = true;
        } else {
            stockWarning.classList.remove('is-visible');
            submitButton.disabled = false;
        }
    }

    itemSelect.addEventListener('change', updateStockDisplay);
    qtyInput.addEventListener('input', updateStockDisplay);

    updateStockDisplay();
</script>

@endsection