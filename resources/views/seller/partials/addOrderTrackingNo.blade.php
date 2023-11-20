<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title text-center">Share Tracking No</h3>
    </div>
    <div class="table-responsive-sm">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="center">#</th>
                    <th>Item</th>
                    <th>Shipping</th>
                    {{-- <th class="right">Unit Cost</th> --}}
                    <th class="center">Qty</th>
                    <th class="right">Total</th>
                    <th class="right">Action</th>
                </tr>
            </thead>
            <tbody>
                <form action="{{ url('seller/addOrderTracking') }}" method="post" class="row formStyle"
                    id="addTrackingNoForm">
                    @csrf
                    {{-- <div class="col-md-12">
                        <h3>Add Tracking No</h3>
                    </div> --}}
                    <div class="col-md-12">
                        @php
                            $counter = 1;
                            $subTotal = 0;
                            $shippingCharges = 0;
                            $isShippingLabel = 0;
                        @endphp
                        @forelse($order->orderItems as $orderItems)
                            <tr>
                                <input type="hidden" class="form-control" name="order_item_id[]"
                                    value="{{ $orderItems->id }}">
                                <td class="center">{{ $counter++ }}</td>
                                <td class="left strong">
                                    {{ $orderItems->product->product_name }}
                                </td>
                                <td class="trackingArea">
                                    @if ($orderItems->shipping_name !== null || $orderItems->shipping_cost > 0)
                                        @php $isShippingLabel++ @endphp
                                        @forelse ($orderItems->shippingLabels as $index => $item)

                                            <input type="hidden" value="{{ $item->id }}" name="label_id[]">

                                            <input type="text" class="form-control tracking_no_{{ $orderItems->id }}"
                                                value="{{ $item->tracking_number }}" placeholder="Tracking No"
                                                name="tracking_no[{{ $item->id }}][]" required
                                                data-id="{{ $item->id }}" data-orderItem="{{ $orderItems->id }}">
                                            {{-- <input type="file" class="form-control"
                                                name="shipping_doc_name[{{ $item->id }}][]"
                                                title="Upload Shipping Label"> --}}
                                            <br>
                                            (Tracking # : {{ $item->tracking_number }})

                                        @empty
                                            {{-- @for ($i = 0; $i < $orderItems->product_qty; $i++) --}}

                                            <input type="text" class="form-control tracking_no_{{ $orderItems->id }}"
                                                value="" name="tracking_no[{{ $orderItems->id }}][]"
                                                placeholder="Tracking No" required data-id="0"
                                                id="tracking_no_{{ $orderItems->id }}"
                                                data-orderItem="{{ $orderItems->id }}">

                                            {{-- @endfor --}}
                                        @endforelse
                                        {{-- @elseif($orderItems->shipping_cost > 0)
                                        <input type="text" class="form-control tracking_no_{{ $orderItems->id }}"
                                            value="" name="tracking_no[{{ $orderItems->id }}][]"
                                            placeholder="Tracking No" required data-id="0"
                                            id="tracking_no_{{ $orderItems->id }}"
                                            data-orderItem="{{ $orderItems->id }}"> --}}
                                    @endif
                                </td>

                                <td class="center">{{ $orderItems->product_qty }}</td>
                                <td class="right">
                                    {{ presentPrice($orderItems->product_per_price * $orderItems->product_qty) }}
                                </td>
                                <td>
                                    @if ($orderItems->shipping_cost > 0 && $orderItems->product_type == 'Physical')
                                        <button class="btn btn-sm btn-primary updateTrackingBtn" id="updateTrackingBtn"
                                            data-orderItem="{{ $orderItems->id }}">
                                            <i class="fa fa-paper-plane" aria-hidden="true"></i>
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @php
                                $subTotal += $orderItems->product_per_price;
                            @endphp
                            @empty

                            @endforelse
                        </div>

                    </form>
        </div>
        </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('.updateTrackingBtn').click(function() {

                let orderItemId = $(this).data('orderitem');
                let tracking_no = [];
                $(".tracking_no_" + orderItemId).each(function() {
                    if ($(this).val() !== "") {
                        tracking_no.push($(this).val() + '=' + $(this).data('id'));
                    }

                });
                console.log(tracking_no);
                if (tracking_no.legth > 0) {
                    alert('Please Fill Tracking No');
                    return;
                }
                $.ajax({
                    type: 'get',
                    url: "{{ url('seller/updateOrderTrackingNo') }}",
                    data: {
                        tracking_no: tracking_no,
                        orderItemId: orderItemId,
                    },
                    success: function(data) {
                        //Clearing all errors
                        // console.log(data);
                        if (!data.status) {
                            if (Object.keys(data.msg).length > 0) {
                                if (typeof data.msg.tracking_no != 'undefined') {
                                    toastr.error(data.msg.tracking_no);
                                }
                                if (typeof data.msg.orderItemId != 'undefined') {
                                    toastr.error(data.msg.orderItemId);
                                }
                            }

                        } else {
                            toastr.success(data.msg);
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        }
                    },
                    error: function(data) {
                        toastr.error(data.msg);
                    }
                });
            });
        });
    </script>
