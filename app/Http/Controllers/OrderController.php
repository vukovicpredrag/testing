<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class OrderController extends Controller
{

    public function __construct()
    {
        $this -> middleware( 'auth', [ 'except' => [ 'store', 'storeCrossSale' ] ] );
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $orders = Order::where('deleted', 0)->orderBy('created_at', 'desc')->take(1)->get();

        return view('orders.index', compact('orders', 'orders'));

    }


    public function deletedOrders()
    {

        $orders = Order::where('deleted', 1)->get();

        return view('orders.deleted', compact('orders', 'orders'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store( Request $request )
    {


        $full_name      = $request -> full_name;
        $email          = $request -> email;
        $location       = $request -> location;
        $address        = $request -> address;
        $postal         = $request -> postal;
        $phone_number   = $request -> phone_number;
        $order_page_url = $request -> page_url;
        $product        = $request -> sku;
        $price_01       = $request -> price;
        $quantity       = $request -> quantity;
        $postage        = $request -> postage;
        $postage_price  = $request -> postage_price;
        $bonuses        = $request -> comment;
        $package_safety    = '';
        $priority_delivery = '';
        $one_year_warranty = '';
        $surprise_package  = '';
        $variation         = $request -> variation;
        $variation_2       = $request -> variation_2;
        $second_variation    = $request -> second_variation;
        $second_variation_2  = $request -> second_variation_2;
        $third_variation     = $request -> third_variation;
        $third_variation_2   = $request -> third_variation_2;


        $price = $price_01;

        $total_price     = $price;


        if( $bonuses ){
            $bonuses         = json_decode( $bonuses );

            foreach ( $bonuses as $bonus ){


                if( $bonus -> option_name == "package_safety" || $bonus -> option_name == "package-safety" ){
                    $package_safety = $bonus -> option_price;
                    $total_price += $package_safety;
                }
                if( $bonus -> option_name == "priority_delivery" ){
                    $priority_delivery = $bonus->option_price;
                    $total_price += $priority_delivery;
                }
                if( $bonus -> option_name == "one_year_warranty" ){
                    $one_year_warranty = $bonus -> option_price;
                    $total_price += $one_year_warranty;
                }
                if( $bonus -> option_name == "surprise_package" ){
                    $surprise_package = $bonus -> option_price;
                    $total_price += $surprise_package;
                }
            }

        }
        if( $postage == 1 && $postage_price ){

            $total_price += $postage_price;

        }

        Order::create([

            'name'               => $full_name,
            'email'              => $email,
            'location'           => $location,
            'address'            => $address,
            'postal'             => $postal,
            'phone_number'       => $phone_number,
            'order_page_url'     => $order_page_url,
            'product'            => $product,
            'price'              => $price,
            'postage'            => $postage,
            'postage_price'      => $postage_price,
            'quantity'           => $quantity,
            'package_safety'     => $package_safety,
            'priority_delivery'  => $priority_delivery,
            'one_year_warranty'  => $one_year_warranty,
            'surprise_package'   => $surprise_package,
            'total_price'        => $total_price,
            'variation'          => $variation,
            'variation_2'        => $variation_2,
            'second_variation'   => $second_variation,
            'second_variation_2' => $second_variation_2,
            'third_variation'    => $third_variation,
            'third_variation_2'  => $third_variation_2,

        ]);

        //product quantity
        $product = Product::where('sku', $product)->first();
        $product -> stock = $product -> stock - $quantity;
        $product -> save();


        return json_encode( ['success'=> true, 'message' => 'Order created!'] );


    }

    public function storeCrossSale(Request $request)
    {

        $crosselProducts =  json_decode($request -> products);


        $full_name      = $request -> full_name;
        $email          = $request -> email;
        $location       = $request -> location;
        $address        = $request -> address;
        $postal         = $request -> postal;
        $phone_number   = $request -> phone_number;
        $order_page_url = $request -> page_url;
        $product        = $request -> sku;
        $price_01       = $request -> price;
        $quantity       = $request -> quantity;
        $productSku = $product;


        $crosselProducts =  json_decode($request -> products);

        foreach( $crosselProducts as  $crosselProduct ){


            $price = $price_01;


            $productName = Product::where('sku', $productSku)->first();

            $comment = "[SYSTEM MESSAGE]:". $productName->name ."[".$productSku."] UPSELL PRODUCT ORDER]";

            Order::create([

                'name'               => $full_name,
                'email'              => $email,
                'location'           => $location,
                'address'            => $address,
                'postal'             => $postal,
                'phone_number'       => $phone_number,
                'order_page_url'     => $order_page_url,
                'product'            => $crosselProduct -> sku,
                'price'              => $crosselProduct -> price,
                'postage'            => '',
                'postage_price'      => '',
                'quantity'           => 1,
                'package_safety'     => '',
                'priority_delivery'  => '',
                'one_year_warranty'  => '',
                'surprise_package'   => '',
                'total_price'        => $crosselProduct -> price,
                'comment'            => $comment,
                'crossell'           => 1,

            ]);

            //product quantity
            $product = Product::where('sku', $productSku)->first();
            $product -> stock = $product -> stock - $quantity;
            $product -> save();

        }

        return json_encode( ['success'=> true, 'message' => 'Order created!'] );


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        Order::find($id)->update($request->all());

        $order = Order::find($id);
        $total_price    = $order -> price;


        if( $order -> package_safety  ){
            $total_price += $order -> package_safety ;
        }
        if( $order -> priority_delivery ){
            $total_price += $order -> priority_delivery ;
        }
        if( $order -> one_year_warranty ){
            $total_price += $order -> one_year_warranty ;
        }
        if( $order -> surprise_package ){
            $total_price += $order -> surprise_package ;
        }

        if( $order -> postage == 1 ){
            $total_price += $order -> postage_price ;
        }


        $order -> total_price = $total_price;
        $order -> save();

        return back()->with('success', 'Product successfully edited!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $order = Order::find($id);

        $order->deleted = 1;
        $order->save();


        Session::flash('success', 'Order is deleted!');
    }

    public function changeStatus(Request $request)
    {


        $orderId = $request->orderId;
        $status  = $request->status;



        $order = Order::find($orderId);
        $order->status = $status;
        $order->save();

        if($status == "Returned") {
            $product = Product::where('sku', $order->product)->first();
            $product->stock = $product->stock + $order->quantity;
            $product->save();
        }

    }

    public function changeType(Request $request)
    {

        $orderId = $request->orderId;
        $type  = $request->type;

        $order = Order::find($orderId);
        $order->order_type = $type;
        $order->save();

    }

    public function changeStatuses(Request $request)
    {

        $status      = $request->status;
        $ordersArray = $request->ordersArray;


        foreach($ordersArray as $order){

            $order = Order::find($order);
            $order->status = $status;
            $order->save();

        }


    }

    public function restore(Request $request)
    {

        $ordersArray = $request->ordersArray;

        foreach($ordersArray as $order){

            $order = Order::find($order);
            $order->deleted = 0;
            $order->save();

        }

        Session::flash('success', 'Order/s has restored!');



    }

    public function deleteForever(Request $request)
    {

        $ordersArray = $request->ordersArray;

        if($ordersArray) {

            foreach ($ordersArray as $order) {

                $order = Order::find($order);
                $order->delete();

            }

        }

        Session::flash('success', 'Order/s has deleted!');

    }


    public function editModal(Request $request)
    {

        $order = Order::find($request->orderId);

        return View::make( 'orders.edit', compact( 'order', 'order') );


    }

    public function ordersHistory($phoneNumber)
    {

        $orders = Order::where('phone_number', $phoneNumber)->get();

        return view('orders.history', compact('orders', 'orders'));


    }

    public function chart()
    {


        $orders = Order::where('deleted', 0)->orderBy('created_at', 'desc')->take(1)->get();

        return view('orders.chart', compact('orders', 'orders'));


    }

    public function mainChart(Request $request)
    {

        $dateFrom = $request -> date_from;
        $dateTo   = $request -> date_to;

        $from = $dateFrom ?: date('Y-m-d', strtotime('now - 90 days'));
        $to   = $dateTo ?: date('Y-m-d', strtotime('now + 1 day'));

        $orders = Order::where('created_at', '>=', $from)->where('created_at', '<=', $to)->where('deleted', 0);


        if( $request -> product ) {
            $orders -> where( 'product', $request -> product );
        }
        if( $request -> bonus ) {
            if($request -> bonus == 'package_safety') {
                $orders->where('package_safety', '!=', '');
            }
            if($request -> bonus == 'priority_delivery') {
                $orders->where('priority_delivery', '!=', '');
            }
            if($request -> bonus == 'one_year_warranty') {
                $orders->where('one_year_warranty', '!=', '');
            }
            if($request -> bonus == 'surprise_package') {
                $orders->where('surprise_package', '!=', '');
            }
        }

        if( $request -> upsell && $request -> upsell == 1 ) {
            $orders -> where( 'upsell', 1);
        }

        if( $request -> satus ) {
            $orders -> where( 'satus', $request -> satus);
        }
        if( $request -> order_type ) {
            $orders -> where( 'order_type', $request -> order_type);
        }


        $ordersPerDay = $orders -> selectRaw('COUNT(*) quantity' ) -> selectRaw( 'DATE(created_at) day' ) -> groupBy( 'day' ) -> get();


        return json_encode([ 'ordersPerDay'=> $ordersPerDay ]);



    }

    public function table(Request $request)
    {


        $columns = [ '', 'id', 'created_at', 'status', 'name',  'address',  'postal', 'location',  'phone_number', 'history',
            'order_page_url', 'product', 'variation', 'second_variation', 'third_variation', 'quantity',
            'price', 'package_safety', 'priority_delivery', 'one_year_warranty', 'surprise_package', 'postage', 'total_price',
            'comment', '',
        ];

        $limit = $request -> get('length');
        $start = $request -> get( 'start');
        $orderColumn = $columns[$request->input('order.0.column')];


        $direction = $request -> input('order.0.dir');

        $count = Order::whereNotNull('id');

        $orders = Order::offset($start)
            ->limit($limit)
            ->orderBy($orderColumn, $direction);

        $ordersFiltered = Order::whereNotNull('id');


        if( $request -> product ) {
            $orders -> where( 'product', $request -> product );
            $ordersFiltered  -> where( 'product', 'LIKE', $request -> product );
        }

        if( $request -> bonus ) {
            if($request -> bonus == 'package_safety') {
                $orders->where('package_safety', '!=', '');
                $ordersFiltered->where('package_safety', '!=', '');
            }
            if($request -> bonus == 'priority_delivery') {
                $orders->where('priority_delivery', '!=', '');
                $ordersFiltered->where('priority_delivery','!=', '');
            }
            if($request -> bonus == 'one_year_warranty') {
                $orders->where('one_year_warranty', '!=', '');
                $ordersFiltered->where('one_year_warranty', '!=', '');
            }
            if($request -> bonus == 'surprise_package') {
                $orders->where('surprise_package', '!=', '');
                $ordersFiltered->where('surprise_package','!=', '');
            }
        }


        if( $request -> upsell && $request -> upsell == 1 ) {
            $orders -> where( 'upsell', 1);
            $ordersFiltered  -> where( 'upsell', 1);
        }

        if( $request -> status ) {
            $orders -> where( 'status', $request -> status);
            $ordersFiltered  -> where( 'status', $request -> status);
        }
        if( $request -> order_type ) {
            $orders -> where( 'order_type', $request -> order_type);
            $ordersFiltered  -> where( 'order_type', $request -> order_type);

            if( $request -> order_type == "page_order"){
                $orders -> where( 'order_type', $request -> order_type)->orWhereNull('order_type');
                $ordersFiltered  -> where( 'order_type', $request -> order_type)->orWhereNull('order_type');
            }
        }

        if( $request -> date_from ) {
            $orders -> where( 'created_at', '>=', $request -> date_from);
            $ordersFiltered  -> where( 'created_at', '>=', $request -> date_from);
        }
        if( $request -> date_to ) {
            $orders -> where( 'created_at', '<=', $request -> date_to);
            $ordersFiltered  -> where( 'created_at', '<=', $request -> date_to);
        }



        if( $request -> input( 'search.value' ) ) {
            $search = $request -> input( 'search.value' );

            $orders->where(function ($query) use ($search){

                $query -> where( 'name', 'LIKE', "%{$search}%" )
                    ->orWhere( 'created_at', 'LIKE', "%{$search}%" )
                    ->orWhere( 'phone_number', 'LIKE', "%{$search}%" )
                    ->orWhere( 'product', 'LIKE', "%{$search}%" );
            });

            $ordersFiltered->where(function ($query) use ($search){

                $query -> where( 'name', 'LIKE', "%{$search}%" )
                    ->orWhere( 'created_at', 'LIKE', "%{$search}%" )
                    ->orWhere( 'phone_number', 'LIKE', "%{$search}%" )

                    ->orWhere( 'product', 'LIKE', "%{$search}%" );
            });
        }



        $ordersFiltered  -> where( 'crossell', '!=', '1')->where('deleted', '!=', '1');

        $orders -> where('created_at', '>', now()->subDays(15)->endOfDay());
        $ordersFiltered -> where('created_at', '>', now()->subDays(15)->endOfDay());
        $count -> where('created_at', '>', now()->subDays(15)->endOfDay());
        $count = $count ->count();


        $ordersFiltered = $ordersFiltered->where('deleted',0)->where('crossell',0)->get();

        $orders = $orders->where('deleted',0)->where('crossell',0)->get();
        $statuses = Status::all();



        $data = [];

        foreach( $orders as $order ) {

            $crossels = Order::where('crossell',1)->where('order_page_url', $order->order_page_url)
                ->where('phone_number', $order->phone_number)->get(['product', 'total_price']);


            $productName = '';
            $allCrossels = '';
            if($order -> products){
                $productName =  $order -> products -> name;
            }

            if(count($crossels)){

                $allCrossels = [];
                $priceSum = [];

                $allCrossels[] = $productName . ' [' . $order -> product .']';
                $priceSum[] = $order -> total_price;

                foreach ($crossels as $cross){

                    $allCrossels[] =  $cross -> products -> name . ' [' . $cross -> product .']';
                    $priceSum[] = $cross -> total_price;

                }

                $allCrossels = implode(' + ', $allCrossels);
                $total_price = array_sum($priceSum);

            }

            if($allCrossels){
                $pName = $allCrossels;
                $total_price = number_format($total_price, 2);
            }else{
                $pName = $productName . ' [' . $order -> product .']';
                $total_price = $order -> total_price;


            }


            $currentOrder = Order::where('phone_number', $order -> phone_number )->where('deleted', 0)->where('crossell', 0)->get();
            $currentOrder -> contains ('status', 'Returned') ? $hasReturned = 1 : $hasReturned = 0;
            $ordersHistory = $currentOrder -> count();
            $hasReturned ? $historyClass = "badge badge-danger" : $historyClass =  "badge badge-info";

            //postage
            if( $order -> postage == 1 &&  $order -> postage_price != '' && $order -> postage_price != 0){
                $postage = "ON" . ' (' . $order -> postage_price . ')';
            }else{
                $postage = "OFF";
            }
            //end postage

            //status define
            $pStatus =    '<select class="form-control optionsSelect"  data-order-id="'. $order -> id .'">';
            foreach($statuses as $stat){
                $selected = '';
                if($stat->name == $order->status){$selected = 'selected';}
                elseif($stat->name == 'Pending'){ $selected = 'selected'; }
                $pStatus .= '<option '.$selected.'>'. $stat -> name .'</option>';
            }
            $pStatus .=  '</select>';
            //end status define

            //order type
            $orderType = '<select class="form-control orderType"  data-order-id="'.$order -> id .'">';
            $pageOrderSelected = $order->order_type == 'page_order' ? 'selected' : '';
            $supportOrderSelected = $order->order_type == 'support_order' ? 'selected' : '';
            $orderType .= '<option '.$pageOrderSelected.' value="page_order"> Page order </option>';
            $orderType .= '<option '.$supportOrderSelected.' value="support_order"> Support order </option>';
            $orderType .= '</select>';
            //end order type


            $variation_1 = $order -> variation ? $order -> variation . '  ' .  $order -> variation_2 : '';
            $variation_2 = $order -> second_variation ? $order -> second_variation . '  ' .  $order -> second_variation_2 : '';
            $variation_3 = $order -> third_variation ? $order -> third_variation . '  ' .  $order -> third_variation_2 : '';

            if($order -> comment){
                $comment = '<span class="comented">' . $order -> comment . '</span>';
            }else{
                $comment = $order -> comment;

            }

            //menage
            $menage = '<button  style="width:70px" class="btn btn-primary edit-order" data-order-id="'. $order -> id .'"  data-toggle="modal" data-target="#editUser">Edit</button>';
            $menage .= '<button class="btn btn-danger delete-order" data-order-id="'. $order -> id .'"  data-href="'. route('orders.destroy', $order -> id) .'" >Delete</button>';
            //end menage

            $nestedData = [];
            $nestedData[ 'id' ] = $order -> id;
            $nestedData[ 'all' ] = "<input name='orderd' type='checkbox' class='checkbox' value=". $order -> id .">";
            $nestedData[ 'name' ] =  $order -> name;
            $nestedData[ 'address' ] =  $order -> crossell ? '' :$order -> address;
            $nestedData[ 'location' ] = $order -> crossell ? '' : $order -> location;
            $nestedData[ 'history' ] = $order -> crossell ? '' :  '<span>' . '<a class="'.$historyClass.'" style="color:white" href="' . route('orders.history', $order -> phone_number) . '" target="_blank">' .$ordersHistory. '</a></span>';;
            $nestedData[ 'postal' ] = $order -> crossell ? '' : $order -> postal;
            $nestedData[ 'phone_number' ] = $order -> phone_number;
            $nestedData[ 'order_page_url' ] = $order -> crossell ? '' :  '<a target="_blank" href="'.$order -> order_page_url .'">' . Str::limit($order -> order_page_url, 50);
            $nestedData[ 'product' ] =  $pName;
            $nestedData[ 'variation' ] = $variation_1;
            $nestedData[ 'variation_2' ] = $variation_2;
            $nestedData[ 'variation_3' ] = $variation_3;
            $nestedData[ 'quantity' ] = $order -> quantity;
            $nestedData[ 'price' ] = $order -> crossell ? '' : $order -> price;
            $nestedData[ 'package_safety' ] = $order -> crossell ? '' : $order -> package_safety;
            $nestedData[ 'priority_delivery' ] = $order -> crossell ? '' : $order -> priority_delivery;
            $nestedData[ 'one_year_warranty' ] =$order -> crossell ? '' :  $order -> one_year_warranty;
            $nestedData[ 'surprise_package' ] = $order -> crossell ? '' : $order -> surprise_package;
            $nestedData[ 'postage' ] = $order -> crossell ? '' : $postage;
            $nestedData[ 'total_price' ] = $total_price;
            $nestedData[ 'status' ] =  $pStatus;
            $nestedData[ 'order_type' ] =  $orderType;
            $nestedData[ 'comment' ] = $comment;
            $nestedData[ 'created_at' ] = Carbon::parse($order -> created_at)->format('Y-m-d H:i:s');;
            $nestedData[ 'manage' ] = $menage;

            $data[] = $nestedData;

        }
        $ordersFiltered = $ordersFiltered -> count();
        $return = [

            'draw' => intval( $request -> get( 'draw' ) ),
            'recordsTotal' => intval( $count ),
            'recordsFiltered' => intval( $ordersFiltered ),
            'data' => $data

        ];

        return json_encode( $return );




    }



}
