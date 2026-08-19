<form id="customProductForm" action="{{ route('admin.product.update', $product->id) }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <div class="modal-header justify-content-between">
        <ul class="nav nav-pills p-2 w-100 justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-informations-tab" data-bs-toggle="pill" data-bs-target="#pills-informations"
                    type="button" role="tab" aria-controls="pills-informations" aria-selected="true">Informations</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-benefits-tab" data-bs-toggle="pill" data-bs-target="#pills-benefits"
                    type="button" role="tab" aria-controls="pills-benefits" aria-selected="false">Benefits </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-application-tab" data-bs-toggle="pill" data-bs-target="#pills-application"
                    type="button" role="tab" aria-controls="pills-application" aria-selected="false">Application</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-downloads-tab" data-bs-toggle="pill" data-bs-target="#pills-downloads"
                    type="button" role="tab" aria-controls="pills-downloads" aria-selected="false">Downloads</button>
            </li>
        </ul>
        <button type="button" class="close p-2 flex-shrink-1" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fa-solid fa-xmark"></i></span>
        </button>
    </div>
    <div class="modal-body row">
        <div class="col-lg-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-informations" role="tabpanel" aria-labelledby="pills-informations-tab">
                    <div class="w-100 text-left">
                        <label for="">More features:</label>
                        <div class="more-features-area">
                            <div class="">
                                <table class="w-100">
                                    <thead>
                                        <tr>
                                            <th class="w-25">Feature</th>
                                            <th class="w-20">Icon</th>
                                            <th class="w-20">Value</th>
                                            <th class="w-30">Details</th>
                                            <th class="w-5 text-center">
                                                <a href="#" type="button" onclick="incrementRow('more-features-area', 'itwillbecoppy'); return false;" class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-plus"></i>
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="itwillbecoppy" data-row-no="1">
                                            <td>
                                                <input type="text" class="form-control" name="features['name'][]" placeholder="Features">
                                            </td>
                                            <td>
                                                <input type="file" class="form-control" name="features['icon'][]" >
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="features['value'][]" placeholder="Value">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="features['details'][]" placeholder="Details">
                                            </td>
                                            <td class="text-center">
                                                <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="w-100 text-left">
                        <label for="">Single components features:</label>
                        <div class="single-component-features-area">
                            <div class="">
                                <table class="w-100">
                                    <thead>
                                        <tr>
                                            <th class="w-30">Details</th>
                                            <th class="w-5 text-center">
                                                <a href="#" type="button" onclick="incrementRow('single-component-features-area', 'itwillbecoppy'); return false;" class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-plus"></i>
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="itwillbecoppy" data-row-no="1">
                                            <td>
                                                <input type="text" class="form-control" name="single_components['name'][]" placeholder="Component Features">

                                                <div class="single-component-area">
                                                    <table class="w-80">
                                                        <thead>
                                                            <tr>
                                                                <th class="">Feature</th>
                                                                <th class="">Value</th>
                                                                <th class="w-5 text-center">
                                                                    <a href="#" type="button" onclick="incrementRow('single-component-area', 'single_components_copy', this); return false;" class="btn btn-sm btn-primary">
                                                                        <i class="fa-solid fa-plus"></i>
                                                                    </a>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="single_components_copy" data-row-no="1">
                                                                <td>
                                                                    <input type="text" class="form-control" name="single_components['components']['name'][]" placeholder="Features">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="single_components['components']['value'][]" placeholder="Value">
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="w-100">
                        <div class="form-group">
                            <label for="">Notice:</label>
                            <textarea name="notice" class="form-control" cols="30" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="">Scope of delivery::</label>
                            <textarea name="scope_of_delivery" class="form-control" cols="30" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-benefits" role="tabpanel" aria-labelledby="pills-benefits-tab">
                    <div class="w-100 text-left">
                        <label for="">Benefits:</label>
                        <div class="benefits-area">
                            <div class="">
                                <table class="w-100">
                                    <thead>
                                        <tr>
                                            <th class="w-30">Description</th>
                                            <th class="w-5 text-center">
                                                <a href="#" type="button" onclick="incrementRow('benefits-area', 'itwillbecoppy'); return false;" class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-plus"></i>
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="itwillbecoppy" data-row-no="1">
                                            <td>
                                                <input type="text" class="form-control" name="benefits['name'][]" placeholder="Benefits Heading">
                                                <textarea name="benefits['details']" class="form-control mt-1" cols="30" rows="2" placeholder="Benefits Setails"></textarea>
                                                <div class="benefits-component-area">
                                                    <table class="w-80">
                                                        <thead>
                                                            <tr>
                                                                <th class="">Title</th>
                                                                <th class="">Details</th>
                                                                <th class="w-5 text-center">
                                                                    <a href="#" type="button" onclick="incrementRow('benefits-component-area', 'single_benefits_copy', this); return false;" class="btn btn-sm btn-primary">
                                                                        <i class="fa-solid fa-plus"></i>
                                                                    </a>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr class="single_benefits_copy" data-added-number="false" data-row-no="1">
                                                                <td>
                                                                    <input type="text" class="form-control" name="benefits_components['components']['title'][]" placeholder="Title">
                                                                </td>
                                                                <td>
                                                                    <input type="text" class="form-control" name="benefits_components['components']['details'][]" placeholder="Details">
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-application" role="tabpanel" aria-labelledby="pills-application-tab">
                    <div class="w-100 text-left">
                        <label for="">Application pictures:</label>
                        <div class="application-image-area">
                            <div class="">
                                <table class="w-100">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th class="w-5 text-center">
                                                <a href="#" type="button" onclick="incrementRow('application-image-area', 'itwillbecoppy'); return false;" class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-plus"></i>
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="itwillbecoppy" data-row-no="1">
                                            <td>
                                                <input type="file" class="form-control" name="applications['image'][]" >
                                            </td>
                                            <td class="text-center">
                                                <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="w-100 text-left">
                        <label for="">Application videos:</label>
                        <div class="application-video-area">
                            <div class="">
                                <table class="w-100">
                                    <thead>
                                        <tr>
                                            <th>Video</th>
                                            <th class="w-5 text-center">
                                                <a href="#" type="button" onclick="incrementRow('application-video-area', 'itwillbecoppy'); return false;" class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-plus"></i>
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="itwillbecoppy" data-row-no="1">
                                            <td>
                                                <input type="file" class="form-control" name="applications['video'][]" >
                                            </td>
                                            <td class="text-center">
                                                <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-downloads" role="tabpanel" aria-labelledby="pills-downloads-tab">
                    <div class="w-100 text-left">
                        <label for="">Downloads:</label>
                        <div class="downloads-area">
                            <div class="">
                                <table class="w-100">
                                    <thead>
                                        <tr>
                                            <th class="w-50">Tilte</th>
                                            <th >File</th>
                                            <th class="w-5 text-center">
                                                <a href="#" type="button" onclick="incrementRow('downloads-area', 'itwillbecoppy'); return false;" class="btn btn-sm btn-primary">
                                                    <i class="fa-solid fa-plus"></i>
                                                </a>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="itwillbecoppy" data-row-no="1">
                                            <td>
                                                <input type="text" class="form-control" name="downloads['title'][]" placeholder="Title">
                                            </td>
                                            <td>
                                                <input type="file" class="form-control" name="downloads['file'][]" >
                                            </td>
                                            <td class="text-center">
                                                <a href="#" type="button" class="btn btn-sm btn-danger" onclick="removeRow(event); return false;"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="d-block step_btn step_btn_1">
            <button type="button" data-step-open="step_2" data-step-button="step_btn_2" data-check-area="step_1"
                class="btn btn-primary next_btn">Submit</button>
        </div>
</form>



