@extends('backend.layout.app')
@section('title', 'Alumni Banner Section | '.Helper::getalumnisection('application_name') ?? 'Machine Tool Solution' )
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Alumni Banner Page</h4>

        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Alumni Page Content</h5></div>
                    </div>
                </div>
            </div>
            <div class="card-body pb-0">
                <form action="{{ route('admin.alumniBannerSection.update') }}" id="settingForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alumni Banner Title:</label>
                        <div class="col-sm-9">
                            <input name="title" id="" class="form-control" value="{{Helper::getalumnisection('title')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alumni Banner Sub Title:</label>
                        <div class="col-sm-9">
                            <input name="subtitle" id="" class="form-control" value="{{Helper::getalumnisection('subtitle')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alumni Banner Images:</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #section_image_1', 'settingForm .section_image_1_image')" name="section_image_1" id="section_image_1">
                            <img src="{{ Helper::getalumnisection('section_image_1') ? asset(Helper::getalumnisection('section_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="section_image_1_image mt-1 border" alt="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alumni Welcome Title:</label>
                        <div class="col-sm-9">
                            <input name="welcome_title" id="" class="form-control" value="{{Helper::getalumnisection('welcome_title')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alumni Welcome Description :</label>
                        <div class="col-sm-9">
                            <textarea name="welcome_description" id="" class="tinymceText form-control" >{!! Helper::getalumnisection('welcome_description') !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alumni Connect Title:</label>
                        <div class="col-sm-9">
                            <input name="connect_title" id="" class="form-control" value="{{Helper::getalumnisection('connect_title')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alumni Connect Description :</label>
                        <div class="col-sm-9">
                            <textarea name="connect_description" id="" class="tinymceText form-control" >{!! Helper::getalumnisection('connect_description') !!}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alumni Camping Title:</label>
                        <div class="col-sm-9">
                            <input name="Camping_title" id="" class="form-control" value="{{Helper::getalumnisection('Camping_title')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alumni Camping Description :</label>
                        <div class="col-sm-9">
                            <textarea name="Camping_description" id="" class="tinymceText form-control" >{!! Helper::getalumnisection('Camping_description') !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Alumni Camping Images:</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #section_image_2', 'settingForm .section_image_2_image')" name="section_image_2" id="section_image_2">
                            <img src="{{ Helper::getalumnisection('section_image_2') ? asset(Helper::getalumnisection('section_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="section_image_2_image mt-1 border" alt="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Facebook Link:</label>
                        <div class="col-sm-9">
                            <input name="facebook_link" id="" class="form-control" value="{{Helper::getalumnisection('facebook_link')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Youtube Link:</label>
                        <div class="col-sm-9">
                            <input name="youtube_link" id="" class="form-control" value="{{Helper::getalumnisection('youtube_link')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Linkdin Link:</label>
                        <div class="col-sm-9">
                            <input name="linkdin_link" id="" class="form-control" value="{{Helper::getalumnisection('linkdin_link')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Twitter Link:</label>
                        <div class="col-sm-9">
                            <input name="twitter_link" id="" class="form-control" value="{{Helper::getalumnisection('twitter_link')}}">
                        </div>
                    </div>
                    <div class="form-group text-end">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('footer')
        <script type="text/javascript">

        </script>
    @endpush
@endsection
