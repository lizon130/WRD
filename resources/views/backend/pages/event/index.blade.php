@extends('backend.layout.app')
@section('title', 'Event | '.Helper::getSettings('application_name') ?? 'School Management')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Event Management</h4>
        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Event List</h5></div>
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
                            <th>Type</th>
                            <th>Event Name</th>
                            <th>Company Name</th>
                            <th>Images</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Upcoming</th>
                            <th>Featured</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('backend.pages.event.modal')
    @push('footer')
        <script>
            $(document).ready(function () {

                $(".next_btn").click(function () {
                    let currentStep = $(this).attr("data-check-area");
                    let nextStep = $(this).attr("data-step-open");
                    let nextBtnClass = $(this).attr("data-step-button");
        
                    $(".step").removeClass("show active"); // Hide all steps
                    $("." + nextStep).addClass("show active"); // Show the next step
        
                    $(".step_btn").addClass("d-none"); // Hide all buttons
                    $("." + nextBtnClass).removeClass("d-none"); // Show the correct button section
                });
        
                $("#createModal .modal-footer .next_btn").click(function () {
                    let nextStep = $(this).attr("data-step-open");
                    let nextBtnClass = $(this).attr("data-step-button");
        
                    $(".step").removeClass("show active");
                    $("." + nextStep).addClass("show active");
        
                    $(".step_btn").addClass("d-none");
                    $("." + nextBtnClass).removeClass("d-none");
                });
        
                $("#editModal .modal-footer a.next_btn").click(function () {
                    let prevStep = $(this).attr("data-step-open");
                    let prevBtnClass = $(this).attr("data-step-button");
        
                    $(".step").removeClass("show active");
                    $("." + prevStep).addClass("show active");
        
                    $(".step_btn").addClass("d-none");
                    $("." + prevBtnClass).removeClass("d-none");
                });
            });
        </script>
        

        

        <script type="text/javascript">
            $('.parrent_category').select2({
                dropdownParent: $('#createModal')
            });

            $('.company').select2({
                dropdownParent: $('#createModal')
            });
            
            function getcategory(parent_category = null, title = null, status = null) {
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    destroy: true, // Ensures reinitialization without duplication
                    ajax: {
                        url: "{{ url('admin/event/get/list') }}",
                        type: 'GET',
                        data: {
                            'parent_category': parent_category,
                            'title': title,
                            'status': status
                        },
                    },
                    aLengthMenu: [
                        [25, 50, 100, 500, 5000, -1],
                        [25, 50, 100, 500, 5000, "All"]
                    ],
                    iDisplayLength: 25,
                    order: [
                        [2, 'asc']
                    ],
                    columns: [
                        {
                            data: 'type',
                            name: 'typeof',
                            render: function(data) {
                                return data ? data : 'N/A';
                            }
                        },
                        {
                            data: 'name',
                            name: 'name',
                            render: function(data) {
                                return data ? data : 'N/A';
                            }
                        },
                        {
                            data: 'company',
                            name: 'company',
                            render: function(data) {
                                return data ? data : 'N/A';
                            }
                        },
                        {
                            data: 'image',
                            name: 'image',
                            orderable: false,
                            searchable: false,
                            className: "text-center",
                            // render: function(data) {
                            //     return data ? `<img src="${data}" alt="Event Image" class="img-fluid" width="50">` : 'N/A';
                            // }
                        },
                        {
                            data: 'start_date',
                            name: 'start_date',
                            render: function(data) {
                                return data ? data : 'N/A';
                            }
                        },
                        {
                            data: 'end_date',
                            name: 'end_date',
                            render: function(data) {
                                return data ? data : 'N/A';
                            }
                        },
                        {
                            data: 'status',
                            name: 'status',
                            className: "text-center",
                            render: function(data) {
                                return data ? data : 'N/A';
                            }
                        },
                        {
                            data: 'is_upcoming',
                            name: 'is_upcoming',
                            className: "text-center",
                            render: function(data) {
                                return data == "1" ? 'Yes' : data == "0" ? 'No' : 'N/A';
                            }
                        },
                        {
                            data: 'is_featured',
                            name: 'is_featured',
                            className: "text-center",
                            render: function(data) {
                                return data == "1" ? 'Yes' : data == "0" ? 'No' : 'N/A';
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: "text-center w-15",
                            render: function(data) {
                                return data ? data : 'N/A';
                            }
                        },
                    ],
                    createdRow: function(row, data, dataIndex) {
                        $(row).css('font-size', '13px');
                    }
                });
            }

            getcategory();


            $(document).on('click', '#filterBtn', function(e) {
                e.preventDefault();  
                let parent_category = $('#filter_form #parent_category').val();
                let title = $('#filter_form #title').val();
                let status = $('#filter_form #status').val();
                
                $('#dataTable').DataTable().destroy();
                getcategory(parent_category, title, status);
            })

            $(document).on('click', '#createCategoryBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#createModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let form = document.getElementById('createCategoryForm');
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#createCategoryForm').attr('action'),
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
                            getcategory();
                            $('#createModal').modal('hide');
                            location.reload();
                        },
                        error: function (xhr) {
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#createCategoryForm .server_side_error').empty();
                            $('#createCategoryForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        },
                    })
                }
            })

            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $.ajax({
                    url: "{{  url('/admin/event/edit/') }}/"+id,
                    type: "GET",
                    dataType: "html",
                    success: function (data) {
                        $('#editModal .modal-content').html(data);
                        $('#editModal').modal('show');
                        $('.edit_parent_category').select2({
                            dropdownParent: $('#editModal')
                        });
                    }
                })
            });

            $(document).on('click', '#editCategoryBtn', function(e) {
                e.preventDefault();
                let go_next_step = true;
                if ($(this).attr('data-check-area') && $(this).attr('data-check-area').trim() !== '') {
                    go_next_step = check_validation_Form('#editModal .'+$(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let form = document.getElementById('editCategoryForm');
                    var formData = new FormData(form);
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: $('#editCategoryForm').attr('action'),
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
                            getcategory();
                            $('#editModal').modal('hide');
                        },
                        error: function (xhr) {
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key,value) {
                                errorMessage +=(''+value+'<br>');
                            });
                            $('#editCategoryForm .server_side_error').empty();
                            $('#editCategoryForm .server_side_error').html('<div class="alert alert-danger" role="alert">'+errorMessage+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
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
                            url: "{{  url('/admin/event/delete/') }}/"+id,
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
                                getcategory();
                            }
                        })
                        
                    }
                })
            })


            // for next page script
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
                    go_next_step = check_validation_Form('#editModal .' + $(this).attr('data-check-area'));
                }
                if (go_next_step == true) {
                    let step = $(this).attr('data-step-open');
                    let step_btn = $(this).attr('data-step-button');

                    $('#editModal .step').removeClass('active show');
                    $('#editModal .step_btn').removeClass('d-block');
                    $('#editModal .step_btn').addClass('d-none');

                    $('#editModal .' + step).addClass('active show');
                    $('#editModal .' + step_btn).removeClass('d-none');
                    $('#editModal .' + step_btn).addClass('d-block');
                }
            })


            // preview image function
            function previewFiles(inputId, previewContainerId) {
                const input = document.getElementById(inputId);
                const previewContainer = document.getElementById(previewContainerId);

                // Clear previous previews
                previewContainer.innerHTML = '';

                // Loop through the selected files
                for (let i = 0; i < input.files.length; i++) {
                    const file = input.files[i];

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const imgElement = document.createElement('img');
                            imgElement.src = event.target.result;
                            imgElement.height = 80;
                            imgElement.width = 100;
                            imgElement.classList.add('mt-1', 'border', 'preview_image');
                            previewContainer.appendChild(imgElement);
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }
        </script>
        <script>
            $(document).ready(function () {
                
                // Speaker
                $(document).on('click', '#addSpeakerInputField', function(e) {
                    e.preventDefault();

                    $('#speakersInfoContainer').append(`
                        <div class="input-group mb-2 hobby-row">
                            <input type="text" name="speakers_info[]" class="form-control addmissionrequired" placeholder="Speakers Name">
                            <button type="button" class="btn btn-danger removeSpekaerFieldInfo"><i class="fa fa-trash"></i></button>
                        </div>
                    `);
                });


                
                $(document).on('click', '.removeSpekaerFieldInfo', function(e) {
                    e.preventDefault();
                    $(this).closest('.hobby-row').remove();
                });


                // Workshop
                $(document).on('click', '#addWorkshopInputField', function(e) {
                    e.preventDefault();

                    $('#eventInfoContainer').append(`
                        <div class="input-group mb-2 hobby-row">
                            <input type="text" name="workshops_info[]" class="form-control addmissionrequired" placeholder="Workshop">
                            <button type="button" class="btn btn-danger removeEventFieldInfo"><i class="fa fa-trash"></i></button>
                        </div>
                    `);
                });


                $(document).on('click', '.removeEventFieldInfo', function(e) {
                    e.preventDefault();
                    $(this).closest('.hobby-row').remove();
                });
            

                
                // Networking 
                $(document).on('click', '#addNetworkingInputField', function(e) {
                    e.preventDefault();

                    $('#networkingInfoContainer').append(`
                        <div class="input-group mb-2 hobby-row">
                            <input type="text" name="networks_info[]" class="form-control addmissionrequired" placeholder="Networking">
                            <button type="button" class="btn btn-danger removeNetworkingFieldInfo"><i class="fa fa-trash"></i></button>
                        </div>
                    `);
                });


                $(document).on('click', '.removeNetworkingFieldInfo', function(e) {
                    e.preventDefault();
                    $(this).closest('.hobby-row').remove();
                });
            
            });
        </script>
    @endpush
@endsection