<div class="card">
    {{-- <div class="card-header">
        <div class="row">
            <div class="col-md-5 float-right">
                <label for="">Order Status</label>
                <select name="order_status" id="order_status" class="form-control" data-order_id="{{ $order->id }}">
                    <option value="pending" @if ($order->order_status == 'pending') selected @endif>Pending</option>
                    <option value="shipped" @if ($order->order_status == 'shipped') selected @endif>Shipped</option>
                    <option value="completed" @if ($order->order_status == 'completed') selected @endif>Completed</option>
                    <option value="cancelled" @if ($order->order_status == 'cancelled') selected @endif>Cancelled</option>
                </select>
            </div>
        </div>
    </div> --}}
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center">
                            <i class="fa fa-shopping-cart"></i>
                            Order Details
                        </h3>
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 2%;">
                                    <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                        data-original-title="Date Added"><i class="fa fa-info-circle fa-fw"></i>
                                    </button>
                                </th>
                                <td>#{{ $order->order_no }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                        data-original-title="Date Added"><i class="fa fa-calendar fa-fw"></i></button>
                                </td>
                                <td>{{ date('d-M-Y', strtotime($order->created_at)) }}</td>
                            </tr>
                            {{-- <tr>
                                <td>
                                    <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                        data-original-title="Shipping Method">
                                        <i class="fa fa-truck fa-fw"></i>
                                    </button>
                                </td>
                                <td>{{ $order->shipping_name }} (Tracking No : {{ $order->tracking_number }})</td>
                            </tr> --}}
                            {{-- @if ($order->shipping_doc_name !== null)
                                <tr>
                                    <td>
                                        <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                            data-original-title="Shipping Method">
                                            <i class="far fa-file-pdf"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <a href="{{ asset('uploads/shipmentDocs/' . $order->shipping_doc_name) }}"
                                            target="_blank">Shipment Label</a>
                                    </td>
                                </tr>
                            @endif --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title text-center"><i class="fa fa-user"></i> Buyer Details
                        </h3>
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td style="width: 1%;">
                                    <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                        data-original-title="Customer">
                                        <i class="fa fa-user fa-fw"></i>
                                    </button>
                                </td>
                                <td>
                                    @if ($order->buyer_id == null)
                                        {{ $order->buyer_name }}
                                    @else
                                        {{ $order->buyer->name }}
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                        data-original-title="E-Mail">
                                        <i class="fas fa-envelope-open-text"></i>
                                    </button>
                                </td>
                                <td>
                                    @if ($order->buyer_id == null)
                                        <a href="mailto:{{ $order->buyer_email }}">{{ $order->buyer_email }}</a>
                                    @else
                                        <a
                                            href="mailto:{{ $order->buyer->user->email }}">{{ $order->buyer->user->email }}</a>
                                    @endif

                                </td>
                            </tr>
                            <tr>
                                <td><button data-toggle="tooltip" title="" class="btn btn-info btn-xs"
                                        data-original-title="Telephone"><i class="fa fa-phone fa-fw"></i></button></td>
                                <td>
                                    @if ($order->buyer_id == null)
                                        {{ $order->phone_no }}
                                    @else
                                        {{ $order->buyer->phone_number }}
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><i class="fa fa-user"></i> Address</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr class="text-left">
                            <th style="width:15% ">Street Line:</th>
                            <td>{{ $order->address1 }}</td>
                        </tr>
                        {{-- <tr>
                            <th>Address2:</th>
                            <td>{{ $order->address2 }}</td>
                        </tr> --}}
                        <tr>
                            <th>City:</th>
                            <td>{{ $order->city_name->name }}</td>
                        </tr>
                        <tr>
                            <th>State:</th>
                            <td>{{ $order->state_name->name }}</td>
                        </tr>
                        <tr>
                            <th>Country:</th>
                            <td>{{ $order->country_name->name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        {{-- <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center"><i class="fa fa-truck fa-fw"></i> Shipping Details</h3>
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tracking No #</th>
                            <th>Shipping Label</th>
                        </tr>
                    </thead>{{dd($order->orderItems)}}
                    <tbody>
                        @forelse ($order->orderItems as $item)
                            <tr>
                                <td>{{$item->tracking_number}}</td>
                                <td>{{$item->shipping_doc_name}}</td>
                            </tr>   
                        @empty
                            
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div> --}}
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-center"> Order Item Details</h3>
            </div>
            <div class="table-responsive-sm">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th>Item</th>
                            <th>Shipping</th>
                            <th class="right">Unit Cost</th>
                            <th class="center">Qty</th>
                            <th class="right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $counter = 1;
                            $subTotal = 0;
                            $shippingCharges = 0;
                        @endphp
                        @forelse($order->orderItems as $orderItems)
                            @php  $shippingCharges += $orderItems->shipping_cost; @endphp
                            <tr>
                                <td class="center">{{ $counter++ }}</td>
                                <td class="left strong">
                                    <a href="{{ URL::to('/') . '/product-details/' . $orderItems->product->id }}"
                                        target="_blank">
                                        {{ $orderItems->product->product_name }}
                                    </a>
                                </td>
                                <td>
                                    @if ($orderItems->shipping_name !== null || $orderItems->shipping_cost > 0)
                                        @forelse ($orderItems->shippingLabels as $item)
                                            @if ($orderItems->shipping_name !== null)
                                                {{ $orderItems->shipping_name }}<br>
                                            @endif
                                            (Tracking # : {{ $item->tracking_number }}) <br>
                                            @if ($item->shipping_doc_name !== null)
                                                <a href="{{ asset('uploads/shipmentDocs/' . $item->shipping_doc_name) }}"
                                                    target="_blank">Shipment Label</a><br>
                                            @endif
                                        @empty
                                        @endforelse
                                    @endif
                                </td>
                                <td class="right">{{ presentPrice($orderItems->product_per_price) }}
                                </td>
                                <td class="center">{{ $orderItems->product_qty }}</td>
                                <td class="right">
                                    ${{ number_format($orderItems->product_per_price * $orderItems->product_qty, 2) }}
                                </td>
                            </tr>
                            @php
                                $subTotal += $orderItems->product_per_price;
                            @endphp
                            @empty

                            @endforelse

                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-sm-5">
                    </div>
                    <div class="col-lg-4 col-sm-5 ml-auto">
                        <table class="table table-clear">
                            <tbody>

                                <tr>
                                    <td class="left">
                                        <strong>Subtotal</strong>
                                    </td>
                                    <td class="right">{{ presentPrice($order->sub_total) }}</td>
                                </tr>
                                @if ($order->discount > 0)
                                    <tr>
                                        <td class="left">
                                            <strong>Discount</strong>
                                        </td>
                                        <td class="right">{{ presentPrice($order->discount) }}</td>
                                    </tr>
                                @endif


                                {{-- <tr>
                                    <td class="left">
                                        <strong>Service Charges</strong>
                                    </td>
                                    <td class="right">{{ presentPrice($order->service_charges) }}</td>
                                </tr> --}}
                                <tr>
                                    <td class="left">
                                        <strong>Tax</strong>
                                    </td>
                                    <td class="right">{{ presentPrice($order->vat_charges) }}</td>
                                </tr>
                                <tr>
                                    <td class="left">
                                        <strong>Shipping Rate</strong>
                                    </td>
                                    <td class="right">{{ presentPrice($shippingCharges) }}</td>
                                </tr>
                                <tr>
                                    <td class="left">
                                        <strong>Total</strong>
                                    </td>
                                    <td class="right">
                                        <strong>${{ number_format($order->total_amount, 2) }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('change', '#order_status', function() {
            let id = $(this).data('order_id');
            let val = $(this).val();
            if (confirm('Are you want to change this Order Status?')) {
                $.ajax({
                    type: "get",
                    url: `{{ url('seller/changeOrderStatus') }}/${id}`,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        val: val
                    },
                    success: function(data) {
                        if (data == 0) {
                            toastr.error('Exception Here !');
                        } else {
                            toastr.success('Record Status Updated Successfully');
                            location.reload();
                        }
                    }
                })
            }
        });
    </script>
