@extends('backend.layout.app')
@section('title', 'Service | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Service Management</h4>
        
        <div class="card my-2">
            <div class="card-body pb-0">
                <form method="" id="filter_form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="code" id="code" placeholder="Service Code" > 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="title" id="title" placeholder="Service Name" > 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="status" class="form-control" id="status">
                                    <option value="">Select Status</option>
                                    <option value="1">Visible</option>
                                    <option value="2">Hidden</option>
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
                        <div class="d-flex align-items-center"><h5 class="m-0">Service List</h5></div>
                        @if (Helper::hasRight('service.create'))
                            <button type="button" class="btn btn-primary btn-create-user create_form_btn" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa-solid fa-plus"></i> Add</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Title</th>
                            <th>Short Description</th>
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
    @include('backend.pages.service.modal')
    @push('footer')
        <script type="text/javascript">
            function getservice(code = null, title = null, status = null){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/service/get/list') }}",
                        type: 'GET',
                        data:{
                            'code': code,
                            'title': title,
                            'status': status
                        },
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    "order": [
                        [ 2, 'asc' ]
                    ],
                    columns: [
                        {
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'short_description',
                            name: 'short_description'
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
            getservice();


            $(document).on('click', '#filterBtn', function(e) {
                e.preventDefault();  
                let code = $('#filter_form #code').val();
                let title = $('#filter_form #title').val();
                let status = $('#filter_form #status').val();
                
                $('#dataTable').DataTable().destroy();
                getservice(code, title, status);
            })

            $(document).on('click', '#createServiceBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let descriptions = $('#descriptions').summernote('code');
                    let form = document.getElementById('createServiceForm');
                    var formData = new FormData(form);
                    formData.append('descriptions', descriptions);
					
					$("#createServiceForm .aditional_descriptions").each(function() {
						let id = $(this).attr('id');
						formData.append('aditional_description[]', $('#createServiceForm #'+id).summernote('code'));
                    });
					
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#createServiceForm').attr('action'),
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
                            getservice();
                            $('#createModal').modal('hide');
                        },
                        error: function (xhr) {
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#createServiceForm .server_side_error').empty();
                            $('#createServiceForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/service/edit/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');
                        initSummerNote();
                    }
                })
            });

            $(document).on('click', '#editServiceBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let descriptions = $('#edit_descriptions').summernote('code');
                    let form = document.getElementById('editServiceForm');
                    var formData = new FormData(form);
                    formData.append('descriptions', descriptions);
					
                    $("#editServiceForm .aditional_descriptions").each(function() {
						let id = $(this).attr('id');
						console.log(id);
						formData.append('aditional_description[]', $('#editServiceForm #'+id).summernote('code'));
                    });
					
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#editServiceForm').attr('action'),
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
                            getservice();
                            $('#editModal').modal('hide');
                        },
                        error: function (xhr) {
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#editServiceForm .server_side_error').empty();
                            $('#editServiceForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

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
                            url: "{{  url('/admin/service/delete/') }}/"+id,
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
                                getservice();
                            }
                        })
                        
                    }
                })
            })
            
            // next button 
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
				clonedDiv.find('.aditional_descriptions').attr('id', 'aditional_description'+rowNumber);
				clonedDiv.insertAfter(copydiv);
            }

            function removeRow(event){
                event.preventDefault();
                var row = event.target.closest('.itwillbecoppy');
                row.remove();
            }
            
        </script>
    @endpush
@endsection