<x-layout>
    <h1>{{ $currencyFrom }} -> {{ $currencyTo }}</h1>
    <hr/>

    @foreach ($exchangeStrategies as $index => $exchangeStrategy)
        <h2>Strategy {{ ($index+1) }}) Received: {{ $exchangeStrategy['result_amount'] }} {{ $currencyTo }}</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SYMBOL</th>
                    <th>ACTION</th>
                    <th>AMOUNT</th>
                    <th>PRICE</th>
                    <th>FEE</th>
                    <th>RESULT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($exchangeStrategy['steps'] as $step)
                    @foreach ($step['history'] as $historyItem)
                        <tr>
                            <td>{{ $step['symbol'] }}</td>
                            <td>{{ $step['action_name'] }}</td>
                            <td>{{ $historyItem['amount'] }}</td>
                            <td>{{ $historyItem['price'] }}</td>
                            <td>{{ $historyItem['fee_amount'] }}</td>
                            <td>{{ $historyItem['result_amount'] }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endforeach
</x-layout>
