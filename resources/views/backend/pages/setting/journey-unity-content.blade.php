@extends('backend.layout.app')
@section('title', 'Frontend Section | '.Helper::getfrontensection('application_name') ?? 'Machine Tool Solution' )
@section('content')
    <div class="container-fluid px-4">
        <h4 class="mt-2">Front Page</h4>

        <div class="card my-2">
            <div class="card-header">
                <div class="row ">
                    <div class="col-12 d-flex justify-content-between">
                        <div class="d-flex align-items-center"><h5 class="m-0">Front Page Content</h5></div>
                    </div>
                </div>
            </div>
            <div class="card-body pb-0">
                <form action="{{ route('admin.frontend.update') }}" id="settingForm" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title(A Journey to Unity):</label>
                        <div class="col-sm-9">
                            <input name="title" id="" class="form-control" value="{{Helper::getfrontensection('title')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Description:</label>
                        <div class="col-sm-9">
                            <textarea name="description" id="" class="form-control"  cols="2" rows="2">{!! Helper::getfrontensection('description') !!}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Video Url:</label>
                        <div class="col-sm-9">
                            <input name="video_content" id="" class="form-control" value="{{Helper::getfrontensection('video_content')}}">
                        </div>
                    </div>
                    <div class="form-goup row">
                        @php
                            $directory = "uploads/settings/";
                        @endphp
                        <label for="" class="col-sm-3 col-form-label">Background Image:</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #background_image', 'settingForm .background_image')" name="background_image" id="background_image">
                            <img src="{{ Helper::getfrontensection('background_image') ? asset($directory . Helper::getfrontensection('background_image')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="background_image mt-1 border" alt="">
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Page Images:</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #section_image_1', 'settingForm .section_image_1_image')" name="section_image_1" id="section_image_1">
                            <img src="{{ Helper::getfrontensection('section_image_1') ? asset('uploads/settings/'.Helper::getfrontensection('section_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="section_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #section_image_2', 'settingForm .section_image_2_image')" name="section_image_2" id="section_image_2">
                            <img src="{{ Helper::getfrontensection('section_image_2') ? asset('uploads/settings/'.Helper::getfrontensection('section_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="section_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #section_image_3', 'settingForm .section_image_3_image')" name="section_image_3" id="section_image_3">
                            <img src="{{ Helper::getfrontensection('section_image_3') ? asset('uploads/settings/'.Helper::getfrontensection('section_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="section_image_3_image mt-1 border" alt="">
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Page Images:</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #section_image_1', 'settingForm .section_image_1_image')" name="section_image_1" id="section_image_1">
                            <img src="{{ Helper::getfrontensection('section_image_1') ? asset(Helper::getfrontensection('section_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="section_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #section_image_2', 'settingForm .section_image_2_image')" name="section_image_2" id="section_image_2">
                            <img src="{{ Helper::getfrontensection('section_image_2') ? asset(Helper::getfrontensection('section_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="section_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #section_image_3', 'settingForm .section_image_3_image')" name="section_image_3" id="section_image_3">
                            <img src="{{ Helper::getfrontensection('section_image_3') ? asset(Helper::getfrontensection('section_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="section_image_3_image mt-1 border" alt="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title one:</label>
                        <div class="col-sm-9">
                            <input name="title_one" id="" class="form-control" value="{{Helper::getfrontensection('title_one')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title One Description :</label>
                        <div class="col-sm-9">
                            <textarea name="title_one_description" id="" class="tinymceText form-control" >{!! Helper::getfrontensection('title_one_description') !!}</textarea>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title One Images:</label>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_1', 'settingForm .title_one_image_1_image')" name="title_one_image_1" id="title_one_image_1">
                            <img src="{{ Helper::getfrontensection('section_image_1') ? asset('uploads/settings/'.Helper::getfrontensection('title_one_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_2', 'settingForm .title_one_image_2_image')" name="title_one_image_2" id="title_one_image_2">
                            <img src="{{ Helper::getfrontensection('title_one_image_2') ? asset('uploads/settings/'.Helper::getfrontensection('title_one_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_3', 'settingForm .title_one_image_3_image')" name="title_one_image_3" id="title_one_image_3">
                            <img src="{{ Helper::getfrontensection('title_one_image_3') ? asset('uploads/settings/'.Helper::getfrontensection('title_one_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_3_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_4', 'settingForm .title_one_image_4_image')" name="title_one_image_4" id="title_one_image_4">
                            <img src="{{ Helper::getfrontensection('title_one_image_4') ? asset('uploads/settings/'.Helper::getfrontensection('title_one_image_4')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_4_image mt-1 border" alt="">
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title One Images:</label>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_1', 'settingForm .title_one_image_1_image')" name="title_one_image_1" id="title_one_image_1">
                            <img src="{{ Helper::getfrontensection('section_image_1') ? asset(Helper::getfrontensection('title_one_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_2', 'settingForm .title_one_image_2_image')" name="title_one_image_2" id="title_one_image_2">
                            <img src="{{ Helper::getfrontensection('title_one_image_2') ? asset(Helper::getfrontensection('title_one_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_3', 'settingForm .title_one_image_3_image')" name="title_one_image_3" id="title_one_image_3">
                            <img src="{{ Helper::getfrontensection('title_one_image_3') ? asset(Helper::getfrontensection('title_one_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_3_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_one_image_4', 'settingForm .title_one_image_4_image')" name="title_one_image_4" id="title_one_image_4">
                            <img src="{{ Helper::getfrontensection('title_one_image_4') ? asset(Helper::getfrontensection('title_one_image_4')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_one_image_4_image mt-1 border" alt="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title Two:</label>
                        <div class="col-sm-9">
                            <input name="title_two" id="" class="form-control" value="{{Helper::getfrontensection('title_two')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title Two Description :</label>
                        <div class="col-sm-9">
                            <textarea name="title_two_description" id="" class="tinymceText form-control" >{!! Helper::getfrontensection('title_two_description') !!}</textarea>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title Two Images:</label>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_1', 'settingForm .title_two_image_1_image')" name="title_two_image_1" id="title_two_image_1">
                            <img src="{{ Helper::getfrontensection('section_image_1') ? asset('uploads/settings/'.Helper::getfrontensection('title_two_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_2', 'settingForm .title_two_image_2_image')" name="title_two_image_2" id="title_two_image_2">
                            <img src="{{ Helper::getfrontensection('title_two_image_2') ? asset('uploads/settings/'.Helper::getfrontensection('title_two_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_3', 'settingForm .title_two_image_3_image')" name="title_two_image_3" id="title_two_image_3">
                            <img src="{{ Helper::getfrontensection('title_two_image_3') ? asset('uploads/settings/'.Helper::getfrontensection('title_two_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_3_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_4', 'settingForm .title_two_image_4_image')" name="title_two_image_4" id="title_two_image_4">
                            <img src="{{ Helper::getfrontensection('title_two_image_4') ? asset('uploads/settings/'.Helper::getfrontensection('title_two_image_4')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_4_image mt-1 border" alt="">
                        </div>
                    </div> --}}

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Title Two Images:</label>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_1', 'settingForm .title_two_image_1_image')" name="title_two_image_1" id="title_two_image_1">
                            <img src="{{ Helper::getfrontensection('section_image_1') ? asset(Helper::getfrontensection('title_two_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_2', 'settingForm .title_two_image_2_image')" name="title_two_image_2" id="title_two_image_2">
                            <img src="{{ Helper::getfrontensection('title_two_image_2') ? asset(Helper::getfrontensection('title_two_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_3', 'settingForm .title_two_image_3_image')" name="title_two_image_3" id="title_two_image_3">
                            <img src="{{ Helper::getfrontensection('title_two_image_3') ? asset(Helper::getfrontensection('title_two_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_3_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #title_two_image_4', 'settingForm .title_two_image_4_image')" name="title_two_image_4" id="title_two_image_4">
                            <img src="{{ Helper::getfrontensection('title_two_image_4') ? asset(Helper::getfrontensection('title_two_image_4')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="title_two_image_4_image mt-1 border" alt="">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Thumbnail Title(Explore The Beautiful Rajshahi City):</label>
                        <div class="col-sm-9">
                            <input name="footer_thumbnail_title" id="" class="form-control" value="{{Helper::getfrontensection('footer_thumbnail_title')}}">
                        </div>
                    </div>
                    {{-- <div class="form-goup row">
                        <label for="" class="col-sm-3 col-form-label">Footer section Thumbnail:</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #footer_section_thumbnail', 'settingForm .footer_section_thumbnail')" name="footer_section_thumbnail" id="footer_section_thumbnail">
                            <img src="{{ Helper::getfrontensection('footer_section_thumbnail') ? asset('uploads/settings/'.Helper::getfrontensection('footer_section_thumbnail')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="footer_section_thumbnail mt-1 border" alt="">
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Hero Section Title:</label>
                        <div class="col-sm-9">
                            <input name="hero_section" id="" class="form-control" value="{{Helper::getfrontensection('hero_section')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Hero Section Sub Title :</label>
                        <div class="col-sm-9">
                            <input name="hero_section_sub_title" id="" class="form-control" value="{{Helper::getfrontensection('hero_section_sub_title')}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Hero Section Images:</label>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #herosection_image_1', 'settingForm .herosection_image_1_image')" name="herosection_image_1" id="herosection_image_1" accept="image/png, image/jpeg, image/jpg">
                            <img src="{{ Helper::getfrontensection('herosection_image_1') ? asset(Helper::getfrontensection('herosection_image_1')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="herosection_image_1_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #herosection_image_2', 'settingForm .herosection_image_2_image')" name="herosection_image_2" id="herosection_image_2" accept="image/png, image/jpeg, image/jpg">
                            <img src="{{ Helper::getfrontensection('herosection_image_2') ? asset(Helper::getfrontensection('herosection_image_2')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="herosection_image_2_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #herosection_image_3', 'settingForm .herosection_image_3_image')" name="herosection_image_3" id="herosection_image_3" accept="image/png, image/jpeg, image/jpg">
                            <img src="{{ Helper::getfrontensection('herosection_image_3') ? asset(Helper::getfrontensection('herosection_image_3')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="herosection_image_3_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #herosection_image_4', 'settingForm .herosection_image_4_image')" name="herosection_image_4" id="herosection_image_4" accept="image/png, image/jpeg, image/jpg">
                            <img src="{{ Helper::getfrontensection('herosection_image_4') ? asset(Helper::getfrontensection('herosection_image_4')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="herosection_image_4_image mt-1 border" alt="">
                        </div>

                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label"></label>

                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #herosection_image_5', 'settingForm .herosection_image_5_image')" name="herosection_image_5" id="herosection_image_5" accept="image/png, image/jpeg, image/jpg">
                            <img src="{{ Helper::getfrontensection('herosection_image_5') ? asset(Helper::getfrontensection('herosection_image_5')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="herosection_image_5_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #herosection_image_6', 'settingForm .herosection_image_6_image')" name="herosection_image_6" id="herosection_image_6" accept="image/png, image/jpeg, image/jpg">
                            <img src="{{ Helper::getfrontensection('herosection_image_6') ? asset(Helper::getfrontensection('herosection_image_6')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="herosection_image_6_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #herosection_image_7', 'settingForm .herosection_image_7_image')" name="herosection_image_7" id="herosection_image_7" accept="image/png, image/jpeg, image/jpg">
                            <img src="{{ Helper::getfrontensection('herosection_image_7') ? asset(Helper::getfrontensection('herosection_image_7')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="herosection_image_7_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #herosection_image_8', 'settingForm .herosection_image_8_image')" name="herosection_image_8" id="herosection_image_8" accept="image/png, image/jpeg, image/jpg">
                            <img src="{{ Helper::getfrontensection('herosection_image_8') ? asset(Helper::getfrontensection('herosection_image_8')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="herosection_image_8_image mt-1 border" alt="">
                        </div>

                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label"></label>

                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #herosection_image_9', 'settingForm .herosection_image_9_image')" name="herosection_image_9" id="herosection_image_9" accept="image/png, image/jpeg, image/jpg">
                            <img src="{{ Helper::getfrontensection('herosection_image_9') ? asset(Helper::getfrontensection('herosection_image_9')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="herosection_image_9_image mt-1 border" alt="">
                        </div>
                        <div class="col-sm-2">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #herosection_image_10', 'settingForm .herosection_image_10_image')" name="herosection_image_10" id="herosection_image_10" accept="image/png, image/jpeg, image/jpg">
                            <img src="{{ Helper::getfrontensection('herosection_image_10') ? asset(Helper::getfrontensection('herosection_image_10')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="herosection_image_10_image mt-1 border" alt="">
                        </div>
                    </div>

                    <div class="form-goup row">
                        <label for="" class="col-sm-3 col-form-label">Footer section Thumbnail:</label>
                        <div class="col-sm-3">
                            <input type="file" class="form-control" onchange="previewFile('settingForm #footer_section_thumbnail', 'settingForm .footer_section_thumbnail')" name="footer_section_thumbnail" id="footer_section_thumbnail" accept="image/png, image/jpeg, image/jpg">
                            <img src="{{ Helper::getfrontensection('footer_section_thumbnail') ? asset(Helper::getfrontensection('footer_section_thumbnail')) : asset('assets/img/no-img.jpg')}}" height="80px" width="100px" class="footer_section_thumbnail mt-1 border" alt="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="" class="col-sm-3 col-form-label">Footer Video Url:</label>
                        <div class="col-sm-9">
                            <input name="footer_video_content" id="" class="form-control" value="{{Helper::getfrontensection('footer_video_content')}}">
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
