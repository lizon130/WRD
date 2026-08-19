<form id="editPartnerProductForm" action="{{ route('admin.partner.product.update', $partner_product->id)}}" method="post" enctype="multipart/form-data">
    @csrf 
    <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Assign Product</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="server_side_error" role="alert">

                </div>
            </div>
            <div class="col-lg-4 form-group">
                <label>Company<span class="text-danger">*</span></label>
                <select name="company" class="form-control company_select" required>
                    <option value="">Select</option>
                    @foreach ($partners as $partner)
                        <option @if($partner_product->company_id == $partner->company_id) selected @endif value="{{ $partner->company_id}}">{{ $partner->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-4 form-group">
                <label for="">Partner<span class="text-danger">*</span></label>
                <input type="text" name="partner" class="form-control partner_name" value="{{$partner_product->partner}}" placeholder="Partner" {{ old('partner')}}  required>
            </div>

            <div class="col-lg-4 form-group">
                <label for="">Status</label>
                <select name="status" class="form-control partner_name">
                    <option @if($partner_product->status == 1) selected @endif value="1">Approved</option>
                    <option @if($partner_product->status == 0) selected @endif value="0">Pending</option>
                    <option @if($partner_product->status == 2) selected @endif value="2">Rejected</option>
                </select>
                
            </div>

            <div class="col-lg-12 form-group products_area">
                <table class="w-100">
                    <tbody>
                        <tr class="row mb-1">
                            <td class="col-lg-6">Product</td>
                            <td class="col-lg-5">Price</td>
                            <td class="col-lg-1 text-center">
                                <a href="" type="button" id="addProduct" class="btn btn-sm btn-primary"><i class="fa-solid fa-plus"></i></a>
                            </td>
                        </tr>

                        <tr class="product1 row" data-row="1">
                            <td class="col-lg-6 row m-0 p-0">
                                <div class="col-lg-4">
                                    <select name="category[]" class="form-control category_select category1" data-row="1" required>
                                        <option value="">Category</option>
                                        @foreach ($category as $cat)
                                            <option @if($partner_product->category_id == $cat->id) selected @endif value="{{ $cat->id}}">{{ $cat->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <select name="subcategory[]" class="form-control subcategory_select subcategory1" data-row="1" required>
                                        <option value="">Sub-Category</option>
                                        @foreach ($subcategory as $cat)
                                            <option @if($partner_product->subcategory_id == $cat->id) selected @endif value="{{ $cat->id}}">{{ $cat->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <select name="product[]" class="form-control product_select product_select1" data-row="1" required>
                                        <option value="">Product</option>
                                        @foreach ($products as $product)
                                            <option @if($partner_product->product_id == $product->id) selected @endif value="{{ $product->id}}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="col-lg-5 row m-0 p-0">
                                <div class="col-lg-3">
                                    <input type="number" min="0" class="form-control" name="quantity[]" value="{{$partner_product->quantity}}" placeholder="Quantity" required>
                                </div>
                                <div class="col-lg-3">
                                    <input type="text" class="form-control price1" name="price[]"  value="{{$partner_product->price}}" placeholder="Price" required>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <select name="discount_type[]" id="" class="form-control">
                                            <option value="">Discount Type</option>
                                            <option @if($partner_product->discount_type == 'percent') selected @endif value="percent">Percent</option>
                                            <option @if($partner_product->discount_type == 'amount') selected @endif value="amount">Amount</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <input type="text" name="discount[]" value="{{$partner_product->discount_price}}" class="form-control" placeholder="Product discount">
                                    </div>
                                </div>
                            </td>
                            <td class="col-lg-1 m-0 p-0 text-center">
                                <a href="" type="button" data-row="1" class="btn btn-sm btn-danger remove_product" id="product1"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a type="button" class="modal__btn_space" data-bs-dismiss="modal">Close</a>
        <button type="submit" id="editPartnerProductBtn" data-check-area="modal-body" class="btn btn-primary">Update</button>
    </div>
</form>