<form method="POST" action="{{ route('orders.update', $order -> id) }}">
    @method('PUT')

    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Order</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @csrf

            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $order -> name }}" required>

            <label for="name">Address</label>
            <input type="text" name="address" class="form-control" value="{{ $order -> address }}">

            <label for="name">Location</label>
            <input type="text" name="location" class="form-control" value="{{ $order -> location }}">

            <label for="name">Postal</label>
            <input type="text" name="postal" class="form-control" value="{{ $order -> postal }}">

            <label for="name">Phone Number</label>
            <input type="text" name="phone_number" class="form-control" value="{{ $order -> phone_number }}">

            <label for="name">Order Page URL</label>
            <input type="text" name="order_page_url" class="form-control" value="{{ $order -> order_page_url }}">

            <label for="name">Product</label>
            <input type="text" name="product" class="form-control" value="{{ $order -> product }}">

            <label for="name">Price</label>
            <input type="text" name="price" class="form-control" value="{{ $order -> price }}">

            <label for="name">Quantity</label>
            <input type="text" name="quantity" class="form-control" value="{{ $order -> quantity }}">

            <label for="name">Postage</label>
            <input type="text" name="postage_price" class="form-control" value="{{ $order -> postage_price }}">

            <label for="name">Bonus #1</label>
            <input type="text" name="package_safety" class="form-control" value="{{ $order -> package_safety }}">

            <label for="name">Bonus #2</label>
            <input type="text" name="priority_delivery" class="form-control" value="{{ $order -> priority_delivery }}">

            <label for="name">Bonus #3</label>
            <input type="text" name="one_year_warranty" class="form-control" value="{{ $order -> one_year_warranty }}">

            <label for="name">Bonus #4</label>
            <input type="text" name="surprise_package" class="form-control" value="{{ $order -> surprise_package }}">

            <label for="name">Comment</label>
            <input type="text" name="comment" class="form-control" value="{{ $order -> comment }}">

            <label for="name">Total Price</label>
            <input type="text" name="total_price" class="form-control" value="{{ $order -> total_price }}">



        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Edit order</button>
        </div>
    </div>

</form>



