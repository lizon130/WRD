<tr class="product{{$number}} row" data-row="{{$number}}">
    <td class="col-lg-6 row m-0 p-0">
        <div class="col-lg-4">
            <select name="category[]" class="form-control category_select category{{$number}}" data-row="{{$number}}" required>
                <option value="">Category</option>
                @foreach ($category as $cat)
                    <option value="{{ $cat->id}}">{{ $cat->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-4">
            <select name="subcategory[]" class="form-control subcategory_select subcategory{{$number}}" data-row="{{$number}}" required>
                <option value="">Sub-Category</option>
            </select>
        </div>
        <div class="col-lg-4">
            <select name="product[]" class="form-control product_select product_select{{$number}}" data-row="{{$number}}" required>
                <option value="">Product</option>
            </select>
        </div>
    </td>
    <td class="col-lg-5 row m-0 p-0">
        <div class="col-lg-3">
            <input type="number" min="0" class="form-control" name="quantity[]" placeholder="Quantity" required>
        </div>
        <div class="col-lg-3">
            <input type="text" class="form-control price{{$number}}" name="price[]" placeholder="Price" required>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <select name="discount_type[]" id="" class="form-control">
                    <option value="">Discount Type</option>
                    <option value="percent">Percent</option>
                    <option value="amount">Amount</option>
                </select>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <input type="text" name="discount[]" class="form-control" placeholder="Product discount">
            </div>
        </div>
    </td>
    <td class="col-lg-1 m-0 p-0 text-center">
        <a href="" type="button" data-row="{{$number}}" class="btn btn-sm btn-danger remove_product" id="product{{$number}}"><i class="fa fa-trash"></i></a>
    </td>
</tr>