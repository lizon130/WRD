@extends('backend.layout.app')
@section('title', 'Alumni Resource | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Alumni Resource Management</h4>
        
        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Resource List</h5></div>
                        @if (Helper::hasRight('resource.create'))
                            <button type="button" class="btn btn-primary btn-create-user create_form_btn" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa-solid fa-plus"></i> Add</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
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
    @include('backend.pages.alumni.alumni.modal')
    @push('footer')
        <script type="text/javascript">
            function getresource(code = null, title = null, status = null){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/alumni-resource/get/list') }}",
                        type: 'GET',
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    columns: [
                        {
                            data: 'image',
                            orderable: false,
                            searchable: false,
                            "className": "text-center"
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            "className": "text-center w-10"
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
            getresource();

            $(document).on('click', '#createResourceBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#createResourceBtn').prop('disabled', true);
                    let details = $('#createResourceForm #details').summernote('code');
                    let form = document.getElementById('createResourceForm');
                    var formData = new FormData(form);
                    formData.append('details', details);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#createResourceForm').attr('action'),
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
                            getresource();
                            $('#createModal').modal('hide');
                            $('#createResourceBtn').prop('disabled', false);
                        },
                        error: function (xhr) {
                            $('#createResourceBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#createResourceForm .server_side_error').empty();
                            $('#createResourceForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/alumni-resource/edit/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');
                        initSummerNote();
                    }
                })
            });

            $(document).on('click', '#editResourceBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#editResourceBtn').prop('disabled', true);
                    let details = $('#editResourceForm #details').summernote('code');
                    let form = document.getElementById('editResourceForm');
                    var formData = new FormData(form);
                    formData.append('details', details);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#editResourceForm').attr('action'),
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
                            getresource();
                            $('#editModal').modal('hide');
                            $('#editResourceBtn').prop('disabled', false);
                        },
                        error: function (xhr) {
                            $('#editResourceBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#editResourceForm .server_side_error').empty();
                            $('#editResourceForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
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
                            url: "{{  url('/admin/alumni-resource/delete/') }}/"+id,
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
                                getresource();
                            }
                        })
                        
                    }
                })
            })
            
            // next button 
            $(document).on('change', '#createModal #resource_type', function(e) {
                e.preventDefault(); 
                let type = $(this).val();
                if(type == 'banner'){
                    $('#createModal .banner_element_area').removeClass('d-none');
                    $('#createModal .banner_element_area').addClass('d-block');
                }else{
                    $('#createModal .banner_element_area').removeClass('d-block');
                    $('#createModal .banner_element_area').addClass('d-none');
                }
            })

            $(document).on('click', '#editModal #resource_type', function(e) {
                e.preventDefault(); 
                let type = $(this).val();
                if(type == 'banner'){
                    $('#createModal .banner_element_area').removeClass('d-none');
                    $('#createModal .banner_element_area').addClass('d-block');
                }else{
                    $('#createModal .banner_element_area').removeClass('d-block');
                    $('#createModal .banner_element_area').addClass('d-none');
                }
            })
        </script>
    @endpush
@endsection