@extends('adminlte::page')

@section('title', 'Chart')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


@section('content_header')
    <h1>Chart</h1>
@stop

@section('content')



    <div class="row">

        <div class="col-md-2">
            <label> Product </label><br>
            <select name="product" id="selectProduct" class="form-control" style="width:100%">
                <option value="0"> All </option>
                @foreach( \App\Product::get(['name', 'sku']) as $product )
                    <option value="{{ $product -> sku }}"> {{ $product -> name }} </option>
                @endforeach
            </select>
        </div>


        <div class="col-md-2">
            <label> Bonus </label><br>

            <select name="bonus" id="selectBonus" class="form-control" style="width:100%">
                <option value="0" > Select bonus </option>
                <option> All </option>
                <option value="package_safety">  1 (package safety) </option>
                <option value="priority_delivery" >  2 (priority delivery) </option>
                <option value="one_year_warranty">  3 (one year warranty)</option>
                <option value="surprise_package">  4 (surprise package) </option>
            </select>
        </div>

        <!--
        <div class="col-md-2">
            <label> Upsell </label><br>

            <select name="upsell" id="orderUpsell" class="form-control" style="width:100%">
                <option value="-1" > All </option>
                <option value="1"> Upsell </option>
                <option value="0"> Regular page </option>
            </select>
        </div>
        -->

        <div class="col-md-2">
            <label> Status </label><br>

            <select name="status" id="selectStatus" class="form-control" style="width:100%">
                <option value="0" > Select status </option>
                @foreach( \App\Status::get('name') as $status )
                    <option value="{{ $status -> name }}"> {{ $status -> name }} </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Order type</label><br>

            <select name="order_type" id="orderType" class="form-control" style="width:100%">
                <option value="0" > All </option>
                <option value="page_order"> Page order </option>
                <option value="support_order"> Support order </option>

            </select>
        </div>

        <div class="col-md-2">

            <label>Date from</label>
            <input name="date_from" type="date" id="dateFrom"><br>
            <label style="padding-right: 19px;">Date to</label>
            <input name="date_to" type="date" id="dateTo">

        </div>

    </div>

    <br>


    <div class="col-md-12" id="mainChart" style="width:100%;"></div>


@stop

@section('css')
    <style>
        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc {
            background : none;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
        $("#selectProduct, #selectBonus, #orderUpsell, #selectStatus, #orderType").select2();


        function mainChart() {
            $.ajax({
                url: '{{route('orders.mainChart')}}',
                type: 'POST',
                data:{
                    _token:    '{{ csrf_token() }}',
                    product:    $('#selectProduct').val(),
                    bonus:      $('#selectBonus').val(),
                    upsell:     $('#orderUpsell').val(),
                    status:     $('#selectStatus').val(),
                    order_type: $('#orderType').val(),
                    date_from:  $('#dateFrom').val(),
                    date_to:    $('#dateTo').val(),
                },
                success(response){

                    let result = JSON.parse(response)
                    let ordersPerDay = result.ordersPerDay

                    let days = [];
                    let quantity = [];

                    ordersPerDay.forEach( function(key, value){
                        days.push(key.day)
                        quantity.push(key.quantity)
                    })


                    Highcharts.chart('mainChart', {
                        chart: {
                            type: 'line',
                            zoomType: 'x',
                        },
                        title:{
                            text: 'Orders Chart'
                        },
                        xAxis:{
                            categories: days
                        },
                        yAxis:{
                            title:{
                                text: null
                            }
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        },
                        series: [
                            {
                                name: 'orders per day',
                                data: quantity,
                                color: '#3380FF'
                            }
                            ]


                    })
                }

            })

        }

        mainChart()

        $("#selectProduct, #selectBonus, #orderUpsell, #selectStatus, #orderType, #dateFrom, #dateTo").change( function (){
            mainChart()
        });

    </script>
@stop