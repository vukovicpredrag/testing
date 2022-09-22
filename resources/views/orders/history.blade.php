@extends('adminlte::page')

@section('title', 'Orders')

@section('content_header')
    <h1>Orders History</h1>
@stop

@section('content')


    <table id="orders" class="display" style="width:100%">
        <thead>
        <tr>
            {{--<th>#</th>--}}
            <th>Full Name</th>
            <th>Address</th>
            <th>Location</th>
            <th>Postal</th>
            <th>Phone number</th>
            <th>Page URL</th>
            <th>Product</th>
            <th>Price</th>
            <th>Bonus #1</th>
            <th>Bonus #2</th>
            <th>Bonus #3</th>
            <th>Bonus #4</th>
            <th>Quantity</th>
            <th>Postage</th>
            {{--<th>Bundle Product</th>--}}
            {{--<th>Upsell Product</th>--}}
            <th>Total Price</th>
            <th>Status</th>
            <th>Comment</th>
            <th>Date of Order</th>
            <th>Deleted</th>
            {{--<th>Mange</th>--}}
        </tr>
        </thead>
        <tbody>

        @foreach( $orders as $order )

            @php $order -> status ? $status = $order -> status : $status = 'Pending'; @endphp


            <tr @if($order->deleted == 1) style="background-color: #ffe6e6;width:auto "@endif>
                {{--<td><input name='orderd' type="checkbox" class="form-control" value="{{ $order -> id }}"></td>--}}
                <td>{{ $order -> name }}</td>
                <td>{{ $order -> address }}</td>
                <td>{{ $order -> location }}</td>
                <td>{{ $order -> postal }}</td>
                <td>{{ $order -> phone_number }}</td>
                <td>{{ $order -> order_page_url }}</td>
                <td>{{ $order -> product }}</td>
                <td>{{ $order -> price }}</td>
                <td>{{ $order -> package_safety }}</td>
                <td>{{ $order -> priority_delivery }}</td>
                <td>{{ $order -> one_year_warranty }}</td>
                <td>{{ $order -> surprise_package }}</td>
                <td>{{ $order -> quantity }}</td>
                <td>{{ $order -> postage }}</td>
                {{--<td>{{ $order -> bundle_product }}</td>--}}
                {{--<td>{{ $order -> upsell_product }}</td>--}}
                <td>{{ $order -> total_price }}</td>
                <td>{{ $status  }}</td>
                <td>{{ $order -> comment }}</td>
                <td>{{ $order -> created_at }}</td>
                <td>{{ $order -> deleted }}</td>

                <td>
                    {{--<button  style="width:70px" class="btn btn-primary edit-order" data-order-id="{{ $order -> id }}"  data-toggle="modal" data-target="#editUser">Edit</button>--}}
                    {{--<button class="btn btn-danger delete-order" data-order-id="{{ $order -> id }}"  data-href="{{ route('orders.destroy', $order -> id) }}" >Delete</button>--}}
                </td>
            </tr>

        @endforeach


        </tbody>
        <tfoot>
        <tr>

        </tr>
        </tfoot>
    </table>

    <!-- Modal - Edit product -->
    <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" id="editProducModal">

        </div>
    </div>


@stop

@section('css')
    <style>
        .content-wrapper  {
            width: MAX-CONTENT;
        }
    </style>
@stop

@section('js')
    <script>

        $(document).ready(function() {
            $('#orders').DataTable({
                "columnDefs": [
                    { "width": '10%', "targets": 12 }
                ]
            });
        } );


        $('.optionsSelect').change( function (e) {

            var status  = $(this).val();
            var orderId = $(this).data('order-id');

            $.ajax({
                url: '{{ route('orders.changeStatus') }}',
                method: 'POST',
                data: {status:status, orderId: orderId},
                success: function () {

                }
            })

        })

        $('#changeStatus').click( function (e) {

            ordersArray = [];

            var status = $('#changeStatuses').val();

            if( confirm("Are you sure?")){

                $("input:checkbox[name=orderd]:checked").each(function(){
                    ordersArray.push($(this).val());
                });

            }

            $.ajax({
                url: '{{ route('orders.changeStatuses') }}',
                method: 'POST',
                data: {status:status, ordersArray: ordersArray},
                success: function () {

                }
            })
            console.log(ordersArray)
        })


        $('.edit-order').click( function (e) {

            var orderId = $(this).data('order-id');

            $.ajax({
                url: "{{ route('orders.edit.modal') }}",
                type: 'POST',
                data: {  orderId: orderId },
                success: function( response ) {
                    $( "#editProducModal" ).html( response );
                }

            })
        })

        $('.delete-order').click( function (e) {

            e.preventDefault();

            var href = $(this).data('href');

            if( confirm('Are you sure?') ){

                $.ajax({
                    url: href,
                    type: 'DELETE',
                    success: function(){

                        location.reload();
                    }

                })
            }
        })

    </script>
@stop