@extends('backend.layout.app')
@section('title', 'Partner Product | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Partner's Product Management</h4>
        
        <div class="card my-2">
            <div class="card-body pb-0">
                <form method="" id="filter_form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="company" class="form-control" id="company">
                                    <option value="">Select Company</option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->company_id}}">{{ $partner->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="product" class="form-control" id="product">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id}}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="status" class="form-control" id="status">
                                    <option value="">Select Status</option>
                                    <option value="3">Pending</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group text-end mt-2">
                                <button type="submit" id="filterBtn" name="submit" class="btn btn-primary"><i class="feather icon-file mr-2"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Partner's Product List</h5></div>
                        @if (Helper::hasRight('user.create'))
                            <button type="button" class="btn btn-primary btn-create-user create_form_btn" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa-solid fa-plus"></i> Add</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Partner Name</th>
                            <th>Category</th>
                            <th>Sub-Category</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('backend.pages.partner-product.modal')
    @push('footer')
        <script type="text/javascript">
            
            
            function getpartnerproduct(company = null, product = null, status = null){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/partner-product/get/list') }}",
                        type: 'GET',
                        data:{
                            'company': company,
                            'product': product,
                            'status': status
                        },
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    "order": [
                        [ 4, 'asc' ]
                    ],
                    columns: [
                        {
                            data: 'company_id',
                            name: 'company_id'
                        },
                        {
                            data: 'partner',
                            name: 'partner'
                        },
                        {
                            data: 'category_id',
                            name: 'category_id'
                        },
                        {
                            data: 'sub_category_id',
                            name: 'subcategory_id'
                        },
                        {
                            data: 'product_id',
                            name: 'product_id'
                        },
                        {
                            data: 'quantity',
                            name: 'quantity'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            "className": "text-center"
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            "className": "text-center w-10"
                        },
                    ]
                });
            }
            getpartnerproduct();

            $(document).on('click', '#filterBtn', function(e) {
                e.preventDefault();  
                let company = $('#filter_form #company').val();
                let product = $('#filter_form #product').val();
                let status = $('#filter_form #status').val();
                
                $('#dataTable').DataTable().destroy();
                getpartnerproduct(company, product, status);
            })

            $(document).on('click', '#createPartnerProductBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let form = document.getElementById('createPartnerProductForm');
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#createPartnerProductForm').attr('action'),
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $.toast({
                                heading: 'Success',
                                text: response.message,
                                position: 'top-center',
                                icon: 'success'
                            })
                            $('#dataTable').DataTable().destroy();
                            getpartnerproduct();
                            $('#createModal').modal('hide');
                        },
                        error: function (xhr) {
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#createPartnerProductForm .server_side_error').empty();
                            $('#createPartnerProductForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'</div>');
                        },
                    })
                }
            })

            $(document).on('click', '#editPartnerProductBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let form = document.getElementById('editPartnerProductForm');
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#editPartnerProductForm').attr('action'),
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $.toast({
                                heading: 'Success',
                                text: response.message,
                                position: 'top-center',
                                icon: 'success'
                            })
                            $('#dataTable').DataTable().destroy();
                            getpartnerproduct();
                            $('#editModal').modal('hide');
                        },
                        error: function (xhr) {
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#createPartnerProductForm .server_side_error').empty();
                            $('#createPartnerProductForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'</div>');
                        },
                    })
                }
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/partner-product/edit/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');

                        tinymce.init({
                            selector: '.tinymceText'
                        });
                    }
                })
            });

            $(document).on('click', '.delete_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{  url('/admin/partner-product/delete/') }}/"+id,
                            type: "GET",
                            dataType: "json",
                            success: function (data) {
                                if (data.success) {
                                    $.toast({
                                        heading: 'Success',
                                        text: data.success,
                                        position: 'top-center',
                                        icon: 'success'
                                    })
                                } else {
                                    $.toast({
                                        heading: 'Error',
                                        text: data.error,
                                        position: 'top-center',
                                        icon: 'error'
                                    })
                                }
                                $('#dataTable').DataTable().destroy();
                                getpartnerproduct();
                            }
                        })
                        
                    }
                })
            })

            function addRow(modalname = null){
                let number = $('#'+modalname+' tbody tr:last').attr('data-row');
                if (number == null) {
                    number = 1;
                }
                $.ajax({
                    url: "{{  url('/admin/partner-product/row/') }}/"+number,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#'+modalname+' .products_area tbody').append(data);
                    }
                })
                
            }
            

            $(document).on('click', '#createModal #addProduct', function(e) {
                e.preventDefault();  
                addRow('createModal');
            })

            $(document).on('click', '#createModal .remove_product', function(e) {
                e.preventDefault(); 
                let id = $(this).attr('id');
                let row = $(this).attr('data-row');
                $("#createModal .products_area tbody ."+id).remove();
            })

            $(document).on('click', '#editModal #addProduct', function(e) {
                e.preventDefault();  
                addRow('editModal');
            })

            $(document).on('click', '#editModal .remove_product', function(e) {
                e.preventDefault(); 
                let id = $(this).attr('id');
                let row = $(this).attr('data-row');
                $("#editModal .products_area tbody ."+id).remove();
            })

            function getCompanyPartner(company_id, main_div){
                $.ajax({
                    url: "{{  url('/admin/partner-product/get/partner/') }}/"+company_id,
                    type: "GET",
                    dataType: "json",
                    success: function (data) {
                        $('#'+main_div+' .partner_name').val(data);
                    }
                })
            }

            function getSubcategory(category_id, main_div, row){
                $.ajax({
                    url: "{{  url('/admin/partner-product/get/subcategory/') }}/"+category_id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#'+main_div+' .subcategory'+row).html(data);
                    }
                })
            }

            function getProduct(category_id,subcategory_id = null, main_div, row){
                $.ajax({
                    url: "{{  url('/admin/partner-product/get/product') }}",
                    type: "GET",
                    data:{
                        'category_id':category_id,
                        'subcategory_id':subcategory_id
                    },
                    dataType: "html",
                    success: function (data) {
                        $('#'+main_div+' .product_select'+row).html(data);
                    }
                })
            }

            $(document).on('change', '#createModal .company_select', function(e) {
                e.preventDefault(); 
                let id = $(this).val();
                if(id != ''){
                    getCompanyPartner(id, 'createModal');
                }
            })


            $(document).on('change', '#createModal .category_select', function(e) {
                e.preventDefault(); 
                let id = $(this).val();
                let row = $(this).attr('data-row');
                if(id != ''){
                    getSubcategory(id, 'createModal', row);
                    getProduct(id, '', 'createModal', row);
                }
            })

            // Edit modal

            $(document).on('change', '#editModal .company_select', function(e) {
                e.preventDefault(); 
                let id = $(this).val();
                if(id != ''){
                    getCompanyPartner(id, 'editModal');
                }
            })

            $(document).on('change', '#editModal .company_select', function(e) {
                e.preventDefault(); 
                let id = $(this).val();
                if(id != ''){
                    getCompanyPartner(id, 'editModal');
                }
            })


            $(document).on('change', '#editModal .category_select', function(e) {
                e.preventDefault(); 
                let id = $(this).val();
                let row = $(this).attr('data-row');
                if(id != ''){
                    getSubcategory(id, 'editModal', row);
                    getProduct(id, '', 'editModal', row);
                }
            })

        </script>
    @endpush
@endsection