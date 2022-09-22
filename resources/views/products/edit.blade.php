<form method="POST" action="{{ route('products.update', $product -> id) }}">
    @method('PUT')

    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Product</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @csrf

            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product -> name }}" required>

            <label for="name">SKU</label>
            <input type="text" name="sku" class="form-control" value="{{ $product -> sku }}" required>

            <label for="name">Price 0</label>
            <input type="text" name="price_0" class="form-control" value="{{ $product -> price_0 }}" required>

            <label for="name">Price 1</label>
            <input type="text" name="price_1" class="form-control" value="{{ $product -> price_1 }}" required>

            <label for="name">Stock</label>
            <input type="text" name="stock" class="form-control" value="{{ $product -> stock }}" required>

            {{--<input type="hidden" name="product_id" class="form-control" value="{{ $product -> id }}" required>--}}


        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Edit product</button>
        </div>
    </div>

</form>



