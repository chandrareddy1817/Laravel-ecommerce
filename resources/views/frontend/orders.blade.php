@extends('layouts.frontend')

@section('title', 'My Orders')

@section('content')

<h1 class="mb-4">
    My Orders
</h1>

@if($orders->count())

    <div class="card shadow-sm">

        <div class="table-responsive">

            <table class="table table-hover mb-0">

                <thead>

                    <tr>

                        <th>Order</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th></th>

                    </tr>

                </thead>

                <tbody>

                    @foreach($orders as $order)

                        <tr>

                            <td>

                                <strong>
                                    #{{ $order->order_number }}
                                </strong>

                            </td>

                            <td>

                                {{ $order->created_at->format(
                                    'd M Y'
                                ) }}

                            </td>

                            <td>

                                ₹{{ number_format(
                                    $order->total_amount,
                                    2
                                ) }}

                            </td>

                            <td>

                                @if($order->payment_status === 'paid')

                                    <span class="badge bg-success">
                                        Paid
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        {{ ucfirst(
                                            $order->payment_status
                                        ) }}
                                    </span>

                                @endif

                            </td>

                            <td>

                                <span class="badge bg-primary">

                                    {{ ucfirst(
                                        $order->status
                                    ) }}

                                </span>

                            </td>

                            <td>

                                <a href="{{ route(
                                    'orders.show',
                                    $order
                                ) }}"
                                   class="btn btn-sm btn-outline-primary">

                                    View

                                </a>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>


    <div class="mt-4">

        {{ $orders->links() }}

    </div>

@else

    <div class="text-center py-5">

        <i class="fa-solid fa-box-open fa-4x text-muted mb-3"></i>

        <h3>
            No orders yet
        </h3>

        <a href="{{ route('shop') }}"
           class="btn btn-primary mt-3">

            Start Shopping

        </a>

    </div>

@endif

@endsection