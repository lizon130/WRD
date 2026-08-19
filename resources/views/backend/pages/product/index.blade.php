@extends('backend.layout.app')
@section('title', 'Course | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Course Management</h4>
        
        <div class="card my-2">
            <div class="card-body pb-0">
                <form method="" id="filter_form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="category" class="form-control" id="category">
                                    <option value="">Select Category</option>
                                    @foreach ($category as $cat)
                                        <option value="{{ $cat->id}}">{{ $cat->title }}</option>
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
                                <input type="text" class="form-control" name="name" id="name" placeholder="Product Name" > 
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
			<form action="{{ route('admin.course.bulk.action') }}" method="POST" style="width: 100%;" id="bulk_option_form">
				<div class="card-header">
					<div class="row ">
						<div class="col-12 d-flex justify-content-between">
							<div class="w-50 d-flex align-items-center justify-content-between">
								<h5 class="w-50">Course List</h5>
								 <select name="bulk_action" id="bulkOption" class="form-control w-40 ms-1 me-1">
									<option value="">--Select Option First--</option>
									<option value="delete" onClick="confirm('Are you sure to delete ?')">Delete
										Selected Items</option>
								</select>
								<button type="button" class="btn btn-primary w-40" id="bulk_action_button"> Submit</button>
							</div>
							<div>
								@if (Helper::hasRight('course.create'))
									<button type="button" class="btn btn-primary btn-create-user create_form_btn" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa-solid fa-plus"></i> Add</button>
								@endif
								@if (Helper::hasRight('custom-field.view'))
									<a href="{{ route('admin.course.custom.field') }}" class="btn btn-info text-light" > Custom Fields</a>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="card-body table-responsive">
					<table class="table table-bordered" id="dataTable">
						<thead>
							<tr>
								<th>
                                    <div class="form-check form-check-flat">
                                        <label class="form-check-label">
                                            <input id="select_all" type="checkbox" class="form-check-input"><i
                                                class="input-helper"></i>
                                        </label>
                                    </div>
                                </th>
								<th>Code(SKU)</th>
								<th>Partner</th>
								<th>Image</th>
								<th>Category</th>
								{{-- <th>Sub-Category</th> --}}
								<th>Title</th>
								{{-- <th>Brand</th> --}}
								<th>Price</th>
								<th>Status</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							
						</tbody>
					</table>
				</div>
			</form>
        </div>
    </div>
    @include('backend.pages.product.modal')
    @push('footer')
        <script type="text/javascript">
            
            $('.create_category_select2').select2({
                dropdownParent: $('#createModal')
            });
            function getproduct(category = null, brand = null, name = null){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/course/get/list') }}",
                        type: 'GET',
                        data:{
                            'category': category,
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
                        [ 5, 'asc' ]
                    ],
                    columns: [
						{
							data: 'checkbox',
							name: 'checkbox',
							"className": "text-center",
							orderable: false,
							searchable: false,
						},
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'company_id',
                            name: 'company_id'
                        },
                        {
                            data: 'thumbnail',
                            name: 'thumbnail',
                            orderable: false,
                            searchable: false,
                            "className": "text-center"
                        },
                        {
                            data: 'category_id',
                            name: 'category_id'
                        },
                        // {
                        //     data: 'sub_category_id',
                        //     name: 'sub_category_id'
                        // },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        // {
                        //     data: 'brand_id',
                        //     name: 'brand_id'
                        // },
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
            getproduct();

            $(document).on('click', '#createProductBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#createProductBtn').prop('disabled', true);
                    let key_features = $('#createProductForm #key_features').summernote('code');
                    let further_information = $('#createProductForm #further_information').summernote('code');
                    let form = document.getElementById('createProductForm');
                    var formData = new FormData(form);
                    formData.append('key_features', key_features);
                    formData.append('further_information', further_information);

                    var filterable_checkbox = [];
                    $("#createProductForm .attributes_filterable").each(function() {
                        if($(this).prop("checked") == true){
                            filterable_checkbox.push(1);
                        }else{
                            filterable_checkbox.push(2);
                        }
                        
                    });
                    formData.append('filterable_checkbox', filterable_checkbox);

                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#createProductForm').attr('action'),
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
                            getproduct();
							$("#createProductForm")[0].reset();
                            $('#createModal').modal('hide');
                            $('#createProductBtn').prop('disabled', false);
                        },
                        error: function (xhr) {
                            $('#createProductBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#createProductForm .server_side_error').empty();
                            $('#createProductForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '#filterBtn', function(e) {
                e.preventDefault();  
                let category = $('#filter_form #category').val();
                let brand = $('#filter_form #brand').val();
                let name = $('#filter_form #name').val();
                
                $('#dataTable').DataTable().destroy();
                getproduct(category, brand, name);
            })


            $(document).on('click', '#editProductBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#editProductBtn').prop('disabled', true);
                    let key_features = $('#editProductForm #key_features').summernote('code');
                    let further_information = $('#editProductForm #further_information').summernote('code');
                    let form = document.getElementById('editProductForm');
                    var formData = new FormData(form);
                    formData.append('key_features', key_features);
                    formData.append('further_information', further_information);

                    var filterable_checkbox = [];
                    $("#editProductForm .attributes_filterable").each(function() {
                        if($(this).prop("checked") == true){
                            filterable_checkbox.push(1);
                        }else{
                            filterable_checkbox.push(2);
                        }
                        
                    });
                    formData.append('filterable_checkbox', filterable_checkbox);
                    
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#editProductForm').attr('action'),
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
                            getproduct();
                            $('#editModal').modal('hide');
                            $('#editProductBtn').prop('disabled', false);
                            location.reload();
                        },
                        error: function (xhr) {
                            $('#editProductBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#editProductForm .server_side_error').empty();
                            $('#editProductForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/course/edit/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');
                        initSummerNote();
                        $('.edit_category_select2').select2({
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
                            url: "{{  url('/admin/course/delete/') }}/"+id,
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
                                getproduct();
                            }
                        })
                        
                    }
                })
            })
			
			$(document).on('click', '#bulk_action_button', function(e) {
                e.preventDefault();
				let bulkOption = $('#bulk_option_form #bulkOption').val();
				if(bulkOption == ''){
					$.toast({
						heading: 'Error',
						text: 'Please select bulk option.',
						position: 'top-center',
						icon: 'error'
					})
				}else if ($('.checkbox_single_select:checked').length <= 0) {
					$.toast({
						heading: 'Error',
						text: 'Please select at least one product.',
						position: 'top-center',
						icon: 'error'
					})
				}else{
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
							let form = document.getElementById('bulk_option_form');
							let formData = new FormData(form);
							$.ajax({
								headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
								url: $('#bulk_option_form').attr('action'),
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
									getproduct();
									//location.reload();
								},
								error: function (xhr) {
									let errorMessage = '';
									$.each(xhr.responseJSON.errors, function(key,value) {
										errorMessage +=(''+value+'<br>');
									});
									$.toast({
										heading: 'Error',
										text: errorMessage,
										position: 'top-center',
										icon: 'error'
									})
								},
							})
						}
					})
				}
			});

            var create_attributes_row = 1;

            $(document).on('click', '#createModal #addAttributes', function(e) {
                e.preventDefault();  
                create_attributes_row++;
                $('#createModal .attributes_area tbody').append(
                    '<tr class="attributes'+create_attributes_row+'" data-row="'+create_attributes_row+'">'+
                        '<td><input type="checkbox" class="attributes_filterable" name="filterable[]" ></td>'+
                        '<td><input type="text" class="form-control" name="attributes_name[]" placeholder="Attributes name"></td>'+
                        '<td><input type="text" class="form-control" name="attributes_value[]" placeholder="Attributes value"></td>'+
                        '<td class="text-center"><a href="" type="button" class="btn btn-sm btn-danger remove_attributes"  id="attributes'+create_attributes_row+'"><i class="fa fa-trash"></i></a></td>'+
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

            $(document).on('click', '#editModal #addAttributes', function(e) {
                e.preventDefault();  
                create_attributes_row++;
                $('#editModal .attributes_area tbody').append(
                    '<tr class="attributes'+create_attributes_row+'" data-row="'+create_attributes_row+'">'+
                        '<td><input type="checkbox" class="attributes_filterable" name="filterable[]" ></td>'+
                        '<td><input type="text" class="form-control" name="attributes_name[]" placeholder="Attributes name"></td>'+
                        '<td><input type="text" class="form-control" name="attributes_value[]" placeholder="Attributes value"></td>'+
                        '<td class="text-center"><a href="" type="button" class="btn btn-sm btn-danger remove_attributes"  id="attributes'+create_attributes_row+'"><i class="fa fa-trash"></i></a></td>'+
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
                let step = $(this).attr('data-step-open');
                let step_btn = $(this).attr('data-step-button');

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
                let step = $(this).attr('data-step-open');
                let step_btn = $(this).attr('data-step-button');

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
                    url: "{{  url('/admin/course/custom-option/') }}/"+id,
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
                        url: "{{  url('/admin/course/custom-option/sub-option/') }}/"+id,
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
                let product_id = $(this).attr('data-product-id');
                generateHtmlFroCustomOption(custom_field_id, sub_option, product_id);
            });

            function generateHtmlFroCustomOption(custom_field_id, sub_option, product_id){
                $.ajax({
                    url: "{{  url('/admin/course/custom-option-generate-html') }}",
                    type: "GET",
                    data: {
                        'custom_field_id': custom_field_id,
                        'sub_option': sub_option,
                        'product_id': product_id
                    },
                    dataType: "html",
                    success: function (html) {
                        $('#customProductForm .generated_html_form_area').empty();
                        $('#customProductForm .generated_html_form_area').append(html);
                        initSummerNote();
                    }
                })
            }

            function editCustomOption(custom_field_id, sub_option, product_id){
                $('.select_custom_field_area').hide();
                $('.back_btn_area').show();
                $('.custom_field_id').val(custom_field_id).trigger('change');
                setTimeout(function() { 
                    $('.custom_field_sub_option').val(sub_option).trigger('change');
                }, 1000);
                generateHtmlFroCustomOption(custom_field_id, sub_option, product_id);
            }

            function deleteCustomOption(custom_field_id, sub_option, product_id){
                $.ajax({
                    url: "{{  url('/admin/course/custom-option-delete') }}",
                    type: "GET",
                    data: {
                        'custom_field_id': custom_field_id,
                        'sub_option': sub_option,
                        'product_id': product_id
                    },
                    dataType: "json",
                    success: function (response) {
                        $.toast({
                            heading: 'Success',
                            text: response.message,
                            position: 'top-center',
                            icon: 'success'
                        })
                        getCustomOptionModel(product_id);
                        // $('#customModal').modal('hide');
                    }
                })
            }

            $(document).on('click', '#updateCustomOptionBtn', function(e) {
                e.preventDefault();
                let form = document.getElementById('customProductForm');
                var formData = new FormData(form);
                $('.tinymceText').each(function() {
                    let details = $(this).summernote('code');
                    formData.append('custom_option_details[]', details);
                })

                $('#updateCustomOptionBtn').prop('disabled', true);
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: $('#customProductForm').attr('action'),
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
                        getproduct();
                        // $('#customModal').modal('hide');
                        getCustomOptionModel(response.product_id);
                    },
                    error: function (xhr) {
                        let errorMessage = '';
                        $.each(xhr.responseJSON.errors, function(key,value) {
                            errorMessage +=(''+value+'<br>');
                        });
                        $('#customProductForm .server_side_error').empty();
                        $('#customProductForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        $('#updateCustomOptionBtn').prop('disabled', false);
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

            function incrementRow(first_div, second_div, copy_single = null) {
                let maindiv;
                if (copy_single == null) {
                    maindiv = $('.' + first_div);
                } else {
                    maindiv = $(copy_single).closest('.' + first_div);
                }

                const copydiv = maindiv.find('.' + second_div + ':last');
                const clonedDiv = copydiv.clone(true);
                const rowNumber = parseInt(copydiv.attr('data-row-no')) + 1;
                clonedDiv.attr('data-row-no', rowNumber);

                // Clear input fields and text areas in the cloned div
                clonedDiv.find('input, textarea').each(function() {
                    $(this).val('');
                });

                clonedDiv.insertAfter(copydiv);
                initSummerNote();
            }

            function removeRow(event) {
                event.preventDefault();
                
                // Check the number of elements with the class "itwillbecoppy"
                if ($('.itwillbecoppy').length > 1) {
                    var row = event.target.closest('.itwillbecoppy');
                    row.remove();
                } 
            }
        </script>
    @endpush
@endsection