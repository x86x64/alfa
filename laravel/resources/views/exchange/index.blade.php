<x-layout>
    <form method="POST" action="{{ route('exchange.check') }}">
        @csrf
        <div class="mb-3">
            <label for="currency_from" class="form-label">Какая валюта у нас есть</label>
            <select id="currency_from" class="form-select" name="currency_from">
                <option value="ETH">ETH</option>
                <option value="USDT">USDT</option>
                <option value="COTI">COTI</option>
                <option value="BTC">BTC</option>
                <option value="XEM">XEM</option>
                <option value="GBP">GBP</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Сумма имеющейся валюты</label>
            <input name="amount" type="text" class="form-control" id="amount" placeholder="0.1">
        </div>
        <div class="mb-3">
            <label for="currency_to" class="form-label">Какую валюту хотим получить</label>
            <select id="currency_to" class="form-select" name="currency_to">
                <option value="ETH">ETH</option>
                <option value="USDT">USDT</option>
                <option value="COTI">COTI</option>
                <option value="BTC">BTC</option>
                <option value="XEM">XEM</option>
                <option value="GBP">GBP</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Рассчитать возможные варианты обмена</button>
    </form>
</x-layout>
