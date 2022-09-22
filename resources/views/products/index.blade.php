@extends('adminlte::page')

@section('title', 'Products')

@section('content_header')
    <h1 style="display: inline-block; padding-right: 10px">Products</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addProduct"> Add Product  </button>

@stop

@section('content')

    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    <table id="products" class="display" style="width:100%">
        <thead>
        <tr>
            <th>Name</th>
            <th>SKU</th>
            <th>Price 0</th>
            <th>Price 1</th>
            <th>Stock</th>
            <th>Orders</th>
            <th>Created</th>
            <th>Manage</th>

        </tr>
        </thead>
        <tbody>

        @foreach( \App\Product::all() as $product )

            @php $ordersCount = \App\Order::where('product', $product->sku)->where('deleted', '!=', 1)->count(); @endphp

            <tr>

                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->price_0 }}</td>
                <td>{{ $product->price_1 }}</td>
                <td>{{ $product->stock }}</td>
                <td>{{ $ordersCount }}</td>
                <td>{{ $product->created_at }}</td>

                <td>
                    <button type="button" class="btn btn-primary edit-product" data-toggle="modal" data-target="#editProduct" data-product-id="{{ $product -> id }}"> Edit </button>
                    <button type="button" class="btn btn-danger delete-product" data-href="{{ route('products.destroy', $product->id) }}" data-product-id="{{ $product -> id }}"> Delete </button>

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
    <div class="modal fade" id="editProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" id="editProducModal">


        </div>
    </div>



    <!-- Modal - Add Product -->
    <div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="saveProduct" method="post" action="{{ route('products.store') }}">
                        @csrf

                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" required>

                        <label for="name">SKU</label>
                        <input type="text" name="sku" class="form-control" required>

                        <label for="name">Price 1</label>
                        <input type="text" name="price_0" class="form-control" required>

                        <label for="name">Price 2</label>
                        <input type="text" name="price_1" class="form-control" required>

                        <label for="name">Stock</label>
                        <input type="text" name="stock" class="form-control" required>



                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="saveUserBtn" type="submit" class="btn btn-primary">Save Product</button>
                </div>

                </form>
            </div>
        </div>
    </div
@stop

@section('css')
    <style>

    </style>
@stop

@section('js')
    <script>

        $(document).ready(function() {
            $('#products').DataTable();
        } );


        $('.edit-product').click( function (e) {

            var productId = $(this).data('product-id');

            $.ajax({
                url: "{{ route('product.edit.modal') }}",
                type: 'POST',
                data: {  productId: productId },
                success: function( response ) {
                    $( "#editProducModal" ).html( response );
                }

            })

        })

        $('.delete-product').click( function (e) {

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