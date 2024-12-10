<h1>Thank you for your purchase!</h1>
<p><strong>Code:</strong> {{ $purchase->purchase_code }}</p>
<h3>Items:</h3>
<ul>
    @php $totalCost = 0; @endphp
    @foreach ($purchase->items as $item)
        <li>
            {{ $item->item_name }} - {{ $item->quantity }} x {{ number_format($item->price, 2) }} UAH
            @php $totalCost += $item->quantity * $item->price; @endphp
        </li>
    @endforeach
</ul>
<h3>Total Cost: {{ number_format($totalCost, 2) }} UAH</h3>
<p>Thank you for choosing MovieApp!</p>
