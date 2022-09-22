@extends('adminlte::page')

@section('title', 'Orders')

@section('content_header')
    <h1>Deleted Orders</h1>
@stop

@section('content')

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <button class="btn btn-success" id="restore">Restore</button>
                <button class="btn btn-danger" id="deleteForever">Delete Forever</button><br>
                <small>(For these actions you need to check orders)</small>


            </div>
        </div>


    </div>


    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    <table id="orders" class="display" style="width:100%">
        <thead>
        <tr>
            <th>All<input type="checkbox" id="checkAll"></th>
            <th>Full Name</th>
            <th>Address</th>
            <th>Location</th>
            <th>Postal</th>
            <th>Phone number</th>
            <th>Page URL</th>
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Postage</th>
            <th>Bonuses</th>
            {{--<th>Bundle Product</th>--}}
            {{--<th>Upsell Product</th>--}}
            <th>Total Price</th>
            <th>Status</th>
            <th>Comment</th>
            <th>Date of Order</th>
            <th>Deleted at</th>
            {{--<th>Mange</th>--}}
        </tr>
        </thead>
        <tbody>

        @foreach( $orders as $order )

            @php $order -> status ? $status = $order -> status : $status = 'Pending'; @endphp

            <tr>
                <td><input name='orderd' type="checkbox" class="checkbox" value="{{ $order -> id }}"></td>
                <td>{{ $order -> name }}</td>
                <td>{{ $order -> address }}</td>
                <td>{{ $order -> location }}</td>
                <td>{{ $order -> postal }}</td>
                <td>{{ $order -> phone_number }}</td>
                <td>{{ $order -> order_page_url }}</td>
                <td>{{ $order -> product }}</td>
                <td>{{ $order -> price }}</td>
                <td>{{ $order -> quantity }}</td>
                <td>{{ $order -> postage }}</td>
                <td>{{ $order -> bonuses }}</td>
                {{--<td>{{ $order -> bundle_product }}</td>--}}
                {{--<td>{{ $order -> upsell_product }}</td>--}}
                <td>{{ $order -> total_price }}</td>
                <td>{{ $status }}</td>
                <td>{{ $order -> comment }}</td>
                <td>{{ $order -> created_at }}</td>
                <td>{{ $order -> updated_at }}</td>

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
            width: 110%;
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

        $('#restore').click( function (e) {

            ordersArray = [];


            if( confirm("Are you sure?")){

                $("input:checkbox[name=orderd]:checked").each(function(){
                    ordersArray.push($(this).val());
                });

            }

            $.ajax({
                url: '{{ route('orders.restore') }}',
                method: 'POST',
                data: {ordersArray: ordersArray},
                success: function () {

                    location.reload();

                }
            })
        })

        $('#deleteForever').click( function (e) {

            ordersArray = [];


            if( confirm("Are you sure?")){

                $("input:checkbox[name=orderd]:checked").each(function(){
                    ordersArray.push($(this).val());
                });

            }

            $.ajax({
                url: '{{ route('orders.deleteForever') }}',
                method: 'POST',
                data: {ordersArray: ordersArray},
                success: function () {

                    location.reload();

                }
            })
        })


        $('#checkAll').on('change',function(){

            var _val = $(this).is(':checked') ? 1 : 0;

            if( _val == 1 ){
                $('.checkbox').prop('checked', true);
            }else{

                $('.checkbox').prop('checked', false);
            }

        });



    </script>
@stop