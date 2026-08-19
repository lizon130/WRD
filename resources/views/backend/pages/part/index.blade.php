@extends('backend.layout.app')
@section('title', 'Product | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Product Parts Management</h4>
        
        <div class="card my-2">
            <div class="card-body pb-0">
                <form method="" id="filter_form">
                    <div class="row">
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
                                <select name="brand" class="form-control" id="brand">
                                    <option value="">Select Brand</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id}}">{{ $brand->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" id="name" placeholder="Part Name" > 
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
                        <div class="d-flex align-items-center"><h5 class="m-0">Product Parts List</h5></div>
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
                            <th>Code(SKU)</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Parts Name</th>
                            <th>Brand</th>
                            <th>Price</th>
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
    @include('backend.pages.part.modal')
    @push('footer')
        <script type="text/javascript">
            $('.select2').select2({
                dropdownParent: $('#createModal')
            });

            function getproductpart(product = null, brand = null, name = null){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/part/get/list') }}",
                        type: 'GET',
                        data:{
                            'product': product,
                            'brand': brand,
                            'name': name
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
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'thumbnail',
                            name: 'thumbnail',
                            orderable: false,
                            searchable: false,
                            "className": "text-center"
                        },
                        {
                            data: 'product_id',
                            name: 'product_id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'brand_id',
                            name: 'brand_id'
                        },
                        {
                            data: 'price',
                            name: 'price'
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
            getproductpart();

            $(document).on('click', '#createPartBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#createPartBtn').prop('disabled', true);
                    let key_features = $('#createPartForm #key_features').summernote('code');
                    let further_information = $('#createPartForm #further_information').summernote('code');
                    let form = document.getElementById('createPartForm');
                    var formData = new FormData(form);
                    formData.append('key_features', key_features);
                    formData.append('further_information', further_information);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#createPartForm').attr('action'),
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
                            getproductpart();
                            $('#createModal').modal('hide');
                            $('#createPartBtn').prop('disabled', false);
                        },
                        error: function (xhr) {
                            $('#createPartBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#createPartForm .server_side_error').empty();
                            $('#createPartForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '#filterBtn', function(e) {
                e.preventDefault();  
                let product = $('#filter_form #product').val();
                let brand = $('#filter_form #brand').val();
                let name = $('#filter_form #name').val();
                
                $('#dataTable').DataTable().destroy();
                getproductpart(product, brand, name);
            })


            $(document).on('click', '#editParttBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#editParttBtn').prop('disabled', true);
                    let key_features = $('#editPartForm #key_features').summernote('code');
                    let further_information = $('#editPartForm #further_information').summernote('code');
                    let form = document.getElementById('editPartForm');
                    var formData = new FormData(form);
                    formData.append('key_features', key_features);
                    formData.append('further_information', further_information);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#editPartForm').attr('action'),
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
                            getproductpart();
                            $('#editModal').modal('hide');
                            $('#editParttBtn').prop('disabled', false);
                        },
                        error: function (xhr) {
                            $('#editParttBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#editPartForm .server_side_error').empty();
                            $('#editPartForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/part/edit/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');

                        initSummerNote();

                        $('.select2').select2({
                            dropdownParent: $('#editModal')
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
                            url: "{{  url('/admin/part/delete/') }}/"+id,
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
                                getproductpart();
                            }
                        })
                        
                    }
                })
            })

            var create_attributes_row = 1;

            $(document).on('click', '#createModal #addPartAttributes', function(e) {
                e.preventDefault();  
                create_attributes_row++;
                $('#createModal .attributes_area tbody').append(
                    '<tr class="attributes'+create_attributes_row+'" data-row="'+create_attributes_row+'">'+
                        '<td><input type="checkbox" class="attributes_filterable" name="filterable[]" ></td>'+
                        '<td><input type="text" class="form-control" name="attributes_name[]" placeholder="Attributes name"></td>'+
                        '<td><input type="text" class="form-control" name="attributes_value[]" placeholder="Attributes value"></td>'+
                        '<td><a href="" type="button" class="btn btn-sm btn-danger remove_attributes"  id="attributes'+create_attributes_row+'"><i class="fa fa-trash"></i></a></td>'+
                    '</tr>'
                );
            })

            $(document).on('click', '#createModal .remove_attributes', function(e) {
                e.preventDefault(); 
                let id = $(this).attr('id');
                let row = $(this).attr('data-row');
                // alert(id);
                $("#createModal .attributes_area tbody ."+id).remove();
                create_attributes_row = $('#createModal tbody tr:last').attr('data-row');
            })

            $(document).on('click', '#editModal #addPartAttributes', function(e) {
                e.preventDefault();  
                create_attributes_row++;
                $('#editModal .attributes_area tbody').append(
                    '<tr class="attributes'+create_attributes_row+'" data-row="'+create_attributes_row+'">'+
                        '<td><input type="checkbox" class="attributes_filterable" name="filterable[]" ></td>'+
                        '<td><input type="text" class="form-control" name="attributes_name[]" placeholder="Attributes name"></td>'+
                        '<td><input type="text" class="form-control" name="attributes_value[]" placeholder="Attributes value"></td>'+
                        '<td><a href="" type="button" class="btn btn-sm btn-danger remove_attributes"  id="attributes'+create_attributes_row+'"><i class="fa fa-trash"></i></a></td>'+
                    '</tr>'
                );
            })

            $(document).on('click', '#editModal .remove_attributes', function(e) {
                e.preventDefault(); 
                let id = $(this).attr('id');
                let row = $(this).attr('data-row');
                // alert(id);
                $("#editModal .attributes_area tbody ."+id).remove();
                create_attributes_row = $('#editModal tbody tr:last').attr('data-row');
            })

            $(document).on('click', '#createModal .next_btn', function(e) {
                e.preventDefault(); 
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let step = $(this).attr('data-step-open');
                    let step_btn = $(this).attr('data-step-button');

                    $('#createModal .step').removeClass('active show');
                    $('#createModal .step_btn').removeClass('d-block');
                    $('#createModal .step_btn').addClass('d-none');

                    $('#createModal .'+step).addClass('active show');
                    $('#createModal .'+step_btn).removeClass('d-none');
                    $('#createModal .'+step_btn).addClass('d-block');
                }
            })

            $(document).on('click', '#editModal .next_btn', function(e) {
                e.preventDefault(); 
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let step = $(this).attr('data-step-open');
                    let step_btn = $(this).attr('data-step-button');

                    $('#editModal .step').removeClass('active show');
                    $('#editModal .step_btn').removeClass('d-block');
                    $('#editModal .step_btn').addClass('d-none');

                    $('#editModal .'+step).addClass('active show');
                    $('#editModal .'+step_btn).removeClass('d-none');
                    $('#editModal .'+step_btn).addClass('d-block');
                }
            })

            // custom option 
            $(document).on('click', '.custom_option_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                getCustomOptionModel(id);
                $('.back_btn_area').hide();
                $('#customModal').modal('show');
                setTimeout(function() {
                    $('.select2').select2({
                        tags: true,
                        dropdownParent: $('#customModal')
                    });
                }, 500);
            });

            function getCustomOptionModel(id){
                $.ajax({
                    url: "{{  url('/admin/part/custom-option/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#customModal .modal-content').html(data);
                    }
                })
            }

            $(document).on('change', '.custom_field_id', function(e) {
                e.preventDefault();
                let id = $(this).val();
                if (id != '') {
                    $.ajax({
                        url: "{{  url('/admin/part/custom-option/sub-option/') }}/"+id,
                        type: "GET",
                        dataType: "html",
                        success: function (data) {
                            let empty = $('.custom_field_sub_option').empty();
                            $('.custom_field_sub_option').append(data);
                            $('.select2').select2({
                                tags: true,
                                dropdownParent: $('#customModal')
                            });
                        }
                    })
                }
            });

            $(document).on('click', '.generate_html_btn', function(e) {
                e.preventDefault();
                let custom_field_id = $('.custom_field_id').val();
                let sub_option = $('.custom_field_sub_option').val();
                let part_id = $(this).attr('data-part-id');
                generateHtmlFroCustomOption(custom_field_id, sub_option, part_id);
            });

            function generateHtmlFroCustomOption(custom_field_id, sub_option, part_id){
                $.ajax({
                    url: "{{  url('/admin/part/custom-option-generate-html') }}",
                    type: "GET",
                    data: {
                        'custom_field_id': custom_field_id,
                        'sub_option': sub_option,
                        'part_id': part_id
                    },
                    dataType: "html",
                    success: function (html) {
                        $('#customProductForm .generated_html_form_area').empty();
                        $('#customProductForm .generated_html_form_area').append(html);
                    }
                })
            }

            function editCustomOption(custom_field_id, sub_option, part_id){
                $('.select_custom_field_area').hide();
                $('.back_btn_area').show();
                $('.custom_field_id').val(custom_field_id).trigger('change');
                setTimeout(function() { 
                    $('.custom_field_sub_option').val(sub_option).trigger('change');
                }, 1000);
                generateHtmlFroCustomOption(custom_field_id, sub_option, part_id);
            }

            function deleteCustomOption(custom_field_id, sub_option, part_id){
                $.ajax({
                    url: "{{  url('/admin/part/custom-option-delete') }}",
                    type: "GET",
                    data: {
                        'custom_field_id': custom_field_id,
                        'sub_option': sub_option,
                        'part_id': part_id
                    },
                    dataType: "json",
                    success: function (response) {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-center',
                            icon: 'success'
                        })
                        getCustomOptionModel(part_id);
                        // $('#customModal').modal('hide');
                    }
                })
            }

            $(document).on('click', '#updateCustomOptionBtn', function(e) {
                e.preventDefault();
                let form = document.getElementById('customProductForm');
                var formData = new FormData(form);
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: $('#customProductForm').attr('action'),
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        console.log(response);
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-center',
                            icon: 'success'
                        })
                        $('#dataTable').DataTable().destroy();
                        getproductpart();
                        // $('#customModal').modal('hide');
                        getCustomOptionModel(response.part_id);
                    },
                    error: function (xhr) {
                        let errorMessage = '';
                        $.each(xhr.responseJSON.errors, function(key,value) {
                            errorMessage +=(''+value+'<br>');
                        });
                        $('#customProductForm .server_side_error').empty();
                        $('#customProductForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    },
                })
            })

            $(document).on('click', '#go_back_btn', function(e) {
                e.preventDefault();
                $('.generated_html_form_area').empty();
                $('.custom_field_id').val('').trigger('change');
                $('.custom_field_sub_option').empty();
                $('.custom_field_sub_option').append('<option value="">-- Select --</option>');
                $('.select_custom_field_area').show();
                $('.back_btn_area').hide();
            });

            function incrementRow(first_div, second_div, copy_single = null){
                console.log(copy_single);
                    if (copy_single == null) {
                        var maindiv = $('.' + first_div);
                    }else{
                        var maindiv = $(copy_single).closest('.' + first_div);
                    }
                    var copydiv = maindiv.find('.' + second_div + ':last');
                    var clonedDiv = copydiv.clone(true);
                    var rowNumber = parseInt(copydiv.attr('data-row-no')) + 1;
                    clonedDiv.attr('data-row-no', rowNumber);
                    clonedDiv.insertAfter(copydiv);
            }

            function removeRow(event){
                event.preventDefault();
                var row = event.target.closest('tr');
                row.remove();
            }

        </script>
    @endpush
@endsection