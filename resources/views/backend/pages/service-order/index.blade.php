@extends('backend.layout.app')
@section('title', 'Service Order | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Service Order Management</h4>

        <div class="card my-2">
            <div class="card-body pb-0">
                <form method="" id="filter_form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="order_id" id="order_id" placeholder="Order Id" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="request_by" id="request_by" placeholder="Request By" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="date" class="form-control" name="date" id="order_date" placeholder="Date">
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
                        <div class="d-flex align-items-center"><h5 class="m-0">Service Order List</h5></div>
                        @if (Helper::hasRight('service-order.create'))
                            <button type="button" class="btn btn-primary btn-create-user create_form_btn" data-bs-toggle="modal" data-bs-target="#createModal"><i class="fa-solid fa-plus"></i> Add</button>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Order Id</th>
                            <th>Service Name</th>
                            <th>Company Name</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Order Date</th>
                            <th>Status</th>
                            @auth
                            @if(auth()->user()->role !== 4 && auth()->user()->role !== 5)
                                <th class="w-15">Action</th>
                            @endif
                            @endauth
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('backend.pages.service-order.modal')
    @push('footer')
        <script type="text/javascript">
            $('.select2').select2({
                dropdownParent: $('#createModal')
            });

            function getOrders(order_id = null, service_name = null, date = null){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/service-order/get/list') }}",
                        type: 'GET',
                        data:{
                            'order_id': order_id,
                            'service_name': service_name,
                            'date': date
                        },
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    "order": [
                        [ 4, 'desc' ]
                    ],
                    columns: [
                        {
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'service_name',
                            name: 'service_name'
                        },
						{
                            data: 'company_name',
                            name: 'company_name'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'date',
                            name: 'date'
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
                            "className": "text-center w-10",
                            visible: {{ auth()->user()->role !== 4 && auth()->user()->role !== 5 ? 'true' : 'false' }}
                        },
                    ]
                });
            }
            getOrders();

            $(document).on('click', '#filterBtn', function(e) {
                e.preventDefault();
                let date = $('#filter_form #order_date').val();
                let request_by = $('#filter_form #request_by').val();
                let id = $('#filter_form #order_id').val();

                $('#dataTable').DataTable().destroy();
                getOrders(id, request_by, date);
            })


            // add row
            function addRow(modalname = null){
                let number = $('#'+modalname+' tbody tr:last').attr('data-row');
                if (number == null) {
                    number = 1;
                }
                $.ajax({
                    url: "{{  url('/admin/service-order/row/') }}/"+number,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#'+modalname+' .service_area tbody').append(data);
                    }
                })
            }

            $(document).on('click', '#createModal #addService', function(e) {
                e.preventDefault();
                addRow('createModal');
            })

            $(document).on('click', '#createModal .remove_service', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                let row = $(this).attr('data-row');
                $("#createModal .service_area tbody ."+id).remove();
            })

            $(document).on('click', '#editModal #addService', function(e) {
                e.preventDefault();
                addRow('editModal');
            })

            $(document).on('click', '#editModal .remove_service', function(e) {
                e.preventDefault();
                let id = $(this).attr('id');
                let row = $(this).attr('data-row');
                $("#editModal .service_area tbody ."+id).remove();
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

            $(document).on('click', '#createServiceOrderBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#createServiceOrderBtn').prop('disabled', true);
                    let form = document.getElementById('createServiceOrderForm');
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#createServiceOrderForm').attr('action'),
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
                            getOrders();
                            $('#createModal').modal('hide');
                            $('#createServiceOrderBtn').prop('disabled', false);
                        },
                        error: function (xhr) {
                            $('#createServiceOrderBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#createServiceOrderForm .server_side_error').empty();
                            $('#createServiceOrderForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/service-order/edit/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');
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
                            url: "{{  url('/admin/service-order/delete/') }}/"+id,
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
                                getOrders();
                            }
                        })

                    }
                })
            })

            $(document).on('click', '#editServiceOrderBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    $('#editServiceOrderBtn').prop('disabled', true);
                    let form = document.getElementById('editServiceOrderForm');
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#editServiceOrderForm').attr('action'),
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
                            getOrders();
                            $('#editModal').modal('hide');
                            $('#editServiceOrderBtn').prop('disabled', false);
                        },
                        error: function (xhr) {
                            $('#editServiceOrderBtn').prop('disabled', false);
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#editServiceOrderForm .server_side_error').empty();
                            $('#editServiceOrderForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '.status_change_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/service-order/edit/status/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#statusModal .modal-content').html(data);
                        $('#statusModal').modal('show');
                    }
                })
            });

            $(document).on('click', '#statusOrderBtn', function(e) {
                e.preventDefault();
                $('#statusOrderBtn').prop('disabled', true);
                let form = document.getElementById('statusOrderForm');
                var formData = new FormData(form);
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    url: $('#statusOrderForm').attr('action'),
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
                        getOrders();
                        $('#statusModal').modal('hide');
                        $('#statusOrderBtn').prop('disabled', false);
                    },
                    error: function (xhr) {
                        $('#statusOrderBtn').prop('disabled', false);
                        let errorMessage = '';
                        $.each(xhr.responseJSON.errors, function(key,value) {
                            errorMessage +=(''+value+'<br>');
                        });
                        $('#statusOrderForm .server_side_error').empty();
                        $('#statusOrderForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    },
                })
            })
        </script>
    @endpush
@endsection
