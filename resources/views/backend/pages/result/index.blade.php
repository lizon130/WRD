@extends('backend.layout.app')
@section('title', 'Relust | '.Helper::getSettings('application_name') ?? 'Nex Academy')
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Result Management</h4>
        
        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Result List</h5></div>
                    </div>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered" id="dataTable">
                    <thead>
                        <tr>
                            <th>Exam</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Organization</th>
                            <th>Started</th>
                            <th>Ended</th>
                            <th>Total marks</th>
                            <th>Achive marks</th>
                            <th>Position</th>
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
    @push('footer')
        <script type="text/javascript">
            $('.segmentation_select').select2({
                dropdownParent: $('#createModal'),
                placeholder: "Select segmentation options", // Placeholder text
                width: '100%' 
            });
            
            function getQuestions(parent_category = null, title = null, status = null){
                var table = jQuery('#dataTable').DataTable({
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ url('admin/results/get/list') }}",
                        type: 'GET',
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
                            data: 'exam_id',
                            name: 'exam_id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'organization',
                            name: 'organization'
                        },
                        {
                            data: 'started_at',
                            name: 'started_at'
                        },
                        {
                            data: 'ended_at',
                            name: 'ended_at'
                        },
                        {
                            data: 'total_mark',
                            name: 'total_mark',
                            "className": "text-center"
                        },
                        {
                            data: 'achieve_mark',
                            name: 'achieve_mark',
                            "className": "text-center"
                        },
                        {
                            data: 'position',
                            name: 'position',
                            "className": "text-center"
                        },
                        {
                            data: 'result_published',
                            name: 'result_published',
                            "className": "text-center"
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            "className": "text-center w-15"
                        },
                    ]
                });
            }
            getQuestions();
        </script>
    @endpush
@endsection