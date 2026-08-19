@extends('backend.layout.app')
@section('title', 'Settings | '.Helper::getSettings('application_name') ?? 'Machine Tool Solution' )
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Settings</h4>

        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Static Content</h5></div>
                    </div>
                </div>
            </div>
            <div class="card-body pb-0">
                <form action="{{ route('admin.setting.update') }}" id="settingForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">About Us Title:</label>
                        <div class="col-sm-9">
                            <input name="title" id="" class="form-control" value="{{Helper::getSettings('title')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">About Us Sub Title:</label>
                        <div class="col-sm-9">
                            <input name="subtitle" id="" class="form-control" value="{{Helper::getSettings('subtitle')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">About Us Images:</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #about_banner_image', 'settingForm .about_banner_image_image')" name="about_banner_image" id="about_banner_image">
                            <img src="{{ Helper::getSettings('about_banner_image') ? asset(Helper::getSettings('about_banner_image')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="about_banner_image_image mt-1 border" alt="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Mission & Vision:</label>
                        <div class="col-sm-9">
                            <textarea name="mission_vision" id="" class="tinymceText form-control" cols="30" rows="20">{!! Helper::getSettings('mission_vision') !!}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Mission & Vision Images:</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #about_image_1', 'settingForm .about_image_1_image')" name="about_image_1" id="about_image_1">
                            <img src="{{ Helper::getSettings('about_image_1') ? asset(Helper::getSettings('about_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="about_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #about_image_2', 'settingForm .about_image_2_image')" name="about_image_2" id="about_image_2">
                            <img src="{{ Helper::getSettings('about_image_2') ? asset(Helper::getSettings('about_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="about_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #about_image_3', 'settingForm .about_image_3_image')" name="about_image_3" id="about_image_3">
                            <img src="{{ Helper::getSettings('about_image_3') ? asset(Helper::getSettings('about_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="about_image_3_image mt-1 border" alt="">
                        </div>
                    </div>


                    {{-- <div class="form-goup row">
                        <label for="" class="col-sm-3 col-form-label">Mission & Vision Images:</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #mission_vision_image', 'settingForm .mission_vision_image')" name="mission_vision_image" id="mission_vision_image">
                            <img src="{{ Helper::getSettings('mission_vision_image') ? asset('uploads/settings/'.Helper::getSettings('mission_vision_image')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="mission_vision_image mt-1 border" alt="">
                        </div>
                    </div> --}}

                    <div class="form-goup row">
                        <label for="" class="col-sm-3 col-form-label">Principal's Image:</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #principal_image', 'settingForm .principal_image')" name="principal_image" id="principal_image">
                            <img src="{{ Helper::getSettings('principal_image') ? asset(Helper::getSettings('principal_image')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="principal_image mt-1 border" alt="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Principal's Message</label>
                        <div class="col-sm-9">
                            <textarea name="principal_message" id="" class="tinymceText form-control" cols="30" rows="20">{!! Helper::getSettings('principal_message') !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Years History's</label>
                        <div class="col-sm-9">
                            <textarea name="years_history" id="" class="tinymceText form-control" cols="30" rows="20">{!! Helper::getSettings('years_history') !!}</textarea>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title one:</label>
                        <div class="col-sm-9">
                            <input name="title_one" id="" class="form-control" value="{{Helper::getSettings('title_one')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title One Description :</label>
                        <div class="col-sm-9">
                            <textarea name="title_one_description" id="" class="tinymceText form-control" >{!! Helper::getSettings('title_one_description') !!}</textarea>
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title One Images:</label>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_1', 'settingForm .title_one_image_1_image')" name="title_one_image_1" id="title_one_image_1">
                            <img src="{{ Helper::getSettings('about_image_1') ? asset('uploads/settings/'.Helper::getSettings('title_one_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_2', 'settingForm .title_one_image_2_image')" name="title_one_image_2" id="title_one_image_2">
                            <img src="{{ Helper::getSettings('title_one_image_2') ? asset('uploads/settings/'.Helper::getSettings('title_one_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_3', 'settingForm .title_one_image_3_image')" name="title_one_image_3" id="title_one_image_3">
                            <img src="{{ Helper::getSettings('title_one_image_3') ? asset('uploads/settings/'.Helper::getSettings('title_one_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_3_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_4', 'settingForm .title_one_image_4_image')" name="title_one_image_4" id="title_one_image_4">
                            <img src="{{ Helper::getSettings('title_one_image_4') ? asset('uploads/settings/'.Helper::getSettings('title_one_image_4')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_4_image mt-1 border" alt="">
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title One Images:</label>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_1', 'settingForm .title_one_image_1_image')" name="title_one_image_1" id="title_one_image_1">
                            <img src="{{ Helper::getSettings('about_image_1') ? asset(Helper::getSettings('title_one_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_2', 'settingForm .title_one_image_2_image')" name="title_one_image_2" id="title_one_image_2">
                            <img src="{{ Helper::getSettings('title_one_image_2') ? asset(Helper::getSettings('title_one_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_3', 'settingForm .title_one_image_3_image')" name="title_one_image_3" id="title_one_image_3">
                            <img src="{{ Helper::getSettings('title_one_image_3') ? asset(Helper::getSettings('title_one_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_3_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_4', 'settingForm .title_one_image_4_image')" name="title_one_image_4" id="title_one_image_4">
                            <img src="{{ Helper::getSettings('title_one_image_4') ? asset(Helper::getSettings('title_one_image_4')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_4_image mt-1 border" alt="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title Two:</label>
                        <div class="col-sm-9">
                            <input name="title_two" id="" class="form-control" value="{{Helper::getSettings('title_two')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title Two Description :</label>
                        <div class="col-sm-9">
                            <textarea name="title_two_description" id="" class="tinymceText form-control" >{!! Helper::getSettings('title_two_description') !!}</textarea>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title Two Images:</label>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_1', 'settingForm .title_two_image_1_image')" name="title_two_image_1" id="title_two_image_1">
                            <img src="{{ Helper::getSettings('about_image_1') ? asset('uploads/settings/'.Helper::getSettings('title_two_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_2', 'settingForm .title_two_image_2_image')" name="title_two_image_2" id="title_two_image_2">
                            <img src="{{ Helper::getSettings('title_two_image_2') ? asset('uploads/settings/'.Helper::getSettings('title_two_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_3', 'settingForm .title_two_image_3_image')" name="title_two_image_3" id="title_two_image_3">
                            <img src="{{ Helper::getSettings('title_two_image_3') ? asset('uploads/settings/'.Helper::getSettings('title_two_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_3_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_4', 'settingForm .title_two_image_4_image')" name="title_two_image_4" id="title_two_image_4">
                            <img src="{{ Helper::getSettings('title_two_image_4') ? asset('uploads/settings/'.Helper::getSettings('title_two_image_4')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_4_image mt-1 border" alt="">
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title Two Images:</label>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_1', 'settingForm .title_two_image_1_image')" name="title_two_image_1" id="title_two_image_1">
                            <img src="{{ Helper::getSettings('about_image_1') ? asset(Helper::getSettings('title_two_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_2', 'settingForm .title_two_image_2_image')" name="title_two_image_2" id="title_two_image_2">
                            <img src="{{ Helper::getSettings('title_two_image_2') ? asset(Helper::getSettings('title_two_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_3', 'settingForm .title_two_image_3_image')" name="title_two_image_3" id="title_two_image_3">
                            <img src="{{ Helper::getSettings('title_two_image_3') ? asset(Helper::getSettings('title_two_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_3_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_4', 'settingForm .title_two_image_4_image')" name="title_two_image_4" id="title_two_image_4">
                            <img src="{{ Helper::getSettings('title_two_image_4') ? asset(Helper::getSettings('title_two_image_4')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_4_image mt-1 border" alt="">
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
