<table>
    <thead>
    <tr></tr>
    <tr>
        <th>Name</th>
        <th>Total Amount</th>
        <th>Tax</th>
        <th>Discounted Amount</th>
        <th>Paid</th>
        <th>Vouchers Redemned</th>
        <th>Promotion Codes</th>
        <th>Created Date</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $order)
        <tr>
            <td>{{ $order->customer ? $order->customer->lastname : "" }} {{ $order->customer ? $order->customer->firstname : "" }}</td>
            <td>{{ $order->total_amount }}</td>
            <td>{{ $order->total_tax }}</td>
            <td>{{ $order->total_discount }}</td>
            <td>{{ $order->total_amount - $order->total_tax - $order->total_discount }}</td>
            <td>{{ $order->vouchers_redemned }}</td>
            <td>{{ $order->promotion_codes }}</td>
            <td>{{ $order->created_at->format('d-m-Y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>