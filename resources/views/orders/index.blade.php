@extends('adminlte::page')

@section('title', 'Orders')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


@section('content_header')
    <h1>Orders</h1>
@stop

@section('content')


    <div class="row">

        <div class="col-md-1">
            <label> Product </label><br>
            <select name="product" id="selectProduct" class="form-control" style="width:100%">
                <option value="0"> All </option>
                @foreach( \App\Product::get(['name', 'sku']) as $product )
                    <option value="{{ $product -> sku }}"> {{ $product -> name }} </option>
                @endforeach
            </select>
        </div>


        <div class="col-md-1">
            <label> Bonus </label><br>

            <select name="bonus" id="selectBonus" class="form-control" style="width:100%">
                <option value="0" > All </option>
                <option> All </option>
                <option value="package_safety">  1 (package safety) </option>
                <option value="priority_delivery" >  2 (priority delivery) </option>
                <option value="one_year_warranty">  3 (one year warranty)</option>
                <option value="surprise_package">  4 (surprise package) </option>
            </select>
        </div>
        <!--
                    <div class="col-md-1">
                        <label> Upsell </label><br>

                        <select name="upsell" id="orderUpsell" class="form-control" style="width:100%">
                            <option value="-1" > All </option>
                            <option value="1"> Upsell </option>
                            <option value="0"> Regular page </option>
                        </select>
                    </div>

        -->

        <div class="col-md-1">
            <label> Status </label><br>

            <select name="status" id="selectStatus" class="form-control" style="width:100%">
                <option value="0" > All </option>
                @foreach( \App\Status::get('name') as $status )
                    <option value="{{ $status -> name }}"> {{ $status -> name }} </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-1">
            <label>Order type</label><br>

            <select name="order_type" id="orderType" class="form-control" style="width:100%">
                <option value="0" > All </option>
                <option value="page_order"> Page order </option>
                <option value="support_order"> Support order </option>

            </select>
        </div>

        <div class="col-md-1">

            <label>Date from</label>
            <input name="date_from" type="date" id="dateFrom"><br>
            <label style="padding-right: 19px;">Date to</label>
            <input name="date_to" type="date" id="dateTo">

        </div>

    </div>

    <br>

    <label>Change status</label><br>
    <div class="row">
        <div class="col-md-1">
            <div class="form-group">
                <select id="changeStatuses" class="form-control" >

                    <option> All </option>

                    @foreach( \App\Status::all() as $status )

                        <option> {{ $status -> name }}</option>

                    @endforeach

                </select>
                <small>(Check orders and change status to all checked)</small>
            </div>
        </div>
        <div class="col-md-1">
            <button class="btn btn-primary" id="changeStatus">Change</button>
        </div>
    </div>

    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    <table id="orders" class="display table table-responsive errors-first-table" style="width:100%;">
        <thead>
        <tr>
            <th>All<input type="checkbox" id="checkAll"></th>
            <th>ID</th>
            <th>Date of Order</th>
            <th>Status </th>
            <th>Full Name</th>
            <th>Address</th>
            <th>Postal</th>
            <th>Location</th>
            <th>Phone number</th>

            <th>History</th>
            <th>Page URL</th>
            <th>Product</th>
            <th>Variation 1</th>
            <th>Variation 2</th>
            <th>Variation 3</th>
            <th>Q</th>
            <th>Price</th>
            <th>Bonus #1</th>
            <th>Bonus #2</th>
            <th>Bonus #3</th>
            <th>Bonus #4</th>
            <th>Postage</th>
            {{--<th>Bundle Product</th>--}}
            {{--<th>Upsell Product</th>--}}
            <th>Total Price</th>
             {{--<<th>Order Type </th>--}}
   <th>Comment</th>
   <th>Mange</th>
</tr>
</thead>
<tbody>
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
   min-width: 1400px;
   width: MAX-CONTENT;
}
.linkClass{
   max-width: 280px;
   word-break: break-all;
   text-overflow: ellipsis;
   white-space: nowrap;
   overflow: hidden;

}
#loader {
   border: 16px solid #f3f3f3;
   border-radius: 50%;
   border-top: 16px solid #3498db;
   width: 120px;
   height: 120px;
   -webkit-animation: spin 2s linear infinite;
   animation: spin 2s linear infinite;
   margin-left:30%;
   margin-top:5%;
}


@-webkit-keyframes spin {
   0% { -webkit-transform: rotate(0deg); }
   100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
   0% { transform: rotate(0deg); }
   100% { transform: rotate(360deg); }
}
#orders_filter{
   float: left;
   padding-left: 30px;
}

</style>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$("#selectProduct, #selectBonus, #orderUpsell, #selectStatus, #orderType").select2();

$(document).ready(function() {

   let ordersTable = $('#orders').DataTable({

       "columnDefs": [

           { "className":'linkClass', "targets": 7 },
           { "type": 'num-fmt', "targets": 22 }


       ],


       lengthMenu: [
           [10, 25, 50, 100, 200],
           [10, 25, 50, 100, 200],
       ],

       "pageLength": 50,
       "processing": true,
       "serverSide": true,
       "order": [[2, 'desc']],

       oLanguage: {sProcessing: "<div id='loader'></div>"},
       "ajax":{
           "url": '{{ route('orders.table') }}',
           "data": function (d) {

               d._token     = '{{ csrf_token() }}';
               d.product    = $('#selectProduct').val();
               d.bonus      = $('#selectBonus').val();
               d.upsell     = $('#orderUpsell').val();
               d.status     = $('#selectStatus').val();
               d.order_type = $('#orderType').val();
               d.date_from  = $('#dateFrom').val();
               d.date_to    = $('#dateTo').val();

           },
           "dataType": "json",
           "type" : "POST",
       },
       "createdRow": function( row, data, dataIndex ) {



           if(  $( row ).find( 'td:eq(9)' ).find('.badge-danger').text() ){
               $(row).css('background-color', 'red');
           }


           if(  $( row ).find( 'td:eq(23)' ).find('.comented').text() ){
               $(row).css('background-color', 'yellow');
           }


       },
       "columns": [
           { "data": "all" },
           { "data": "id" },
           { "data": "created_at"},
           { "data": "status"},
           { "data": "name"},
           { "data": "address"},
           { "data": "postal"},
           { "data": "location"},
           { "data": "phone_number"},
           { "data": "history"},
           { "data": "order_page_url"},
           { "data": "product"},
           { "data": "variation"},
           { "data": "variation_2"},
           { "data": "variation_3"},
           { "data": "quantity"},
           { "data": "price"},
           { "data": "package_safety"}, //bonus 1
           { "data": "priority_delivery"}, // bonus 2
           { "data": "one_year_warranty"},  // bonus 3
           { "data": "surprise_package"},  // bonus 4
           { "data": "postage"},
           { "data": "total_price"},
          // { "data": "order_type"},
           { "data": "comment"},
           { "data": "manage"},

       ]

   })



   var prev_val;

   $(document).on('focus', '.optionsSelect', function (e) {
       prev_val = $(this).val();
   })

   $(document).on('change', '.optionsSelect', function (e) {
       if( $(this).val() == 'Returned'){

           $(this).blur() // Firefox fix as suggested by AgDude
           var success = confirm('Are you sure this order is returned?');
           if(success)
           {}
           else {
               $(this).val(prev_val);
               return false;
           }
       }
   });


   $("#selectProduct, #selectBonus, #orderUpsell, #selectStatus, #orderType, #dateFrom, #dateTo").change( function (){
       ordersTable.ajax.reload();
   });

});



$(document).on( 'change', '.optionsSelect', function(e){

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


$(document).on( 'change', '.orderType', function(e){

   var type  = $(this).val();
   var orderId = $(this).data('order-id');

   $.ajax({
       url: '{{ route('orders.changeType') }}',
       method: 'POST',
       data: {type:type, orderId: orderId},
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

           location.reload();


       }
   })
})

$(document).on( 'click', '.edit-order', function(e){

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

$(document).on( 'click', '.delete-order', function(e){

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


$('#checkAll').on('change',function(){

   var _val = $(this).is(':checked') ? 1 : 0;

   if( _val == 1 ){
       $('.checkbox').prop('checked', true);
   }else{

       $('.checkbox').prop('checked', false);
   }

});

</script>
<script src="https://cdn.datatables.net/plug-ins/1.12.1/sorting/formatted-numbers.js"></script>

@stop