<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <form id="createPartnerProductForm" action="{{ route('admin.partner.product.store') }}" method="post" enctype="multipart/form-data">
                @csrf 
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Assign Product</h5>
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
                                    <option value="{{ $partner->company_id}}">{{ $partner->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-4 form-group">
                            <label for="">Partner<span class="text-danger">*</span></label>
                            <input type="text" name="partner" class="form-control partner_name" placeholder="Partner" {{ old('partner')}}  required>
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
                                                        <option value="{{ $cat->id}}">{{ $cat->title }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <select name="subcategory[]" class="form-control subcategory_select subcategory1" data-row="1" required>
                                                    <option value="">Sub-Category</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <select name="product[]" class="form-control product_select product_select1" style="width:100%;" data-row="1" required>
                                                    <option value="">Product</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="col-lg-5 row m-0 p-0">
                                            <div class="col-lg-3">
                                                <input type="number" min="0" class="form-control" name="quantity[]" placeholder="Quantity" required>
                                            </div>
                                            <div class="col-lg-3">
                                                <input type="text" class="form-control price1" name="price[]" placeholder="Price" required>
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
                    <button type="submit" id="createPartnerProductBtn" data-check-area="modal-body" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- edit modal  --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl " role="document">
        <div class="modal-content">
            
        </div>
    </div>
</div>
