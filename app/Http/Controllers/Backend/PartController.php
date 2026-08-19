<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\Product;
use App\Models\Brand;
use App\Models\CustomField;
use App\Models\ProductPart;
use App\Models\PartAttribute;
use Illuminate\Contracts\Session\Session as SessionSession;
use Session;

class PartController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('part.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $products = Product::all();
        $brands = Brand::all();
        return view('backend.pages.part.index', compact('products','brands'));
    }

    public function getList(Request $request){

        $data = ProductPart::query();
        if (!empty($request->product)) {
            $data->where('product_id', $request->product);
        }

        if ($request->brand) {
            $data->where('brand_id', $request->brand);
        }

        if (!empty($request->name)) {
            $data->where(function($query) use ($request){
                $query->where('name','like', "%" .$request->name ."%" );
            });
        }
        
        return Datatables::of($data)

        ->editColumn('thumbnail', function ($row) {
            return ($row->thumbnail) ? '<a href="'.asset('uploads/part-images/'.$row->thumbnail).'" target="_blank"><img class="profile-img" src="'.asset('uploads/part-images/'.$row->thumbnail).'" alt="profile image"></a>' : '<img class="profile-img" src="'.asset('assets/img/no-img.jpg').'" alt="profile image">';
        })

        ->editColumn('product_id', function ($row) {
            return ($row->product)->name ?? '-';
        })

       
        ->editColumn('brand_id', function ($row) {
            return ($row->brand)->title ?? '-';
        })

        ->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="badge bg-primary w-80">Active</span>';
            }else{
                return '<span class="badge bg-danger w-80">Inactive</span>';
            }
        })

        ->addColumn('action', function ($row) {
            $btn = '';
            if (Helper::hasRight('part.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('part.custom-option')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="custom_option_btn btn btn-sm btn-info mx-1"><i class="fa-solid fa-screwdriver-wrench"></i></a>';
            }
            if (Helper::hasRight('part.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['thumbnail','product_id','brand_id','status','action'])->make(true);
    }

    public function store(Request $request){
        
        $validator = $request->validate([
			'name' => 'required',
			'code' => 'required',
			'product' => 'required',
			'price' => 'required',
            'thumbnail' => 'required|image:png,jpg,jpeg,gif,webp,',
		]);

        
        $part = new ProductPart();
        $part->name = $request->name;
        $part->code = $request->code;
        $part->parts_type = $request->parts_type;
        $part->brand_id = $request->brand;
        $part->product_id = $request->product;
        $part->price  = $request->price;
        $part->key_features  = $request->key_features;
        $part->further_information  = $request->further_information;
        $part->discount_type  = $request->discount_type;
        $part->discount  = $request->discount;
        $part->status = ($request->status) ? 1 : 0;
        if($request->hasFile('thumbnail')){
            $thumbnail = $request->file('thumbnail');
            $filename = time().uniqid().$thumbnail->getClientOriginalName();
            $thumbnail->move(public_path('uploads/part-images'), $filename);
            $part->thumbnail = $filename;
        }

        if($request->hasFile('gallery')){
            $gallerys = $request->file('gallery');
            $image_name = [];
            foreach ($gallerys as $image) {
                $filename = time().uniqid().$image->getClientOriginalName();
                $image->move(public_path('uploads/part-images'), $filename);
                array_push($image_name, $filename);
            }
            $part->images =  json_encode($image_name);
        }
        
        if ($part->save()) {
            // language 
            Helper::insertLanguage(ProductPart::class, $part->id, 'en', 'name', $part->name);
            Helper::insertLanguage(ProductPart::class, $part->id, 'en', 'key_features', $part->key_features);
            Helper::insertLanguage(ProductPart::class, $part->id, 'en', 'further_information', $part->further_information);

            $part->refresh();
            if(isset($request->attributes_name) && count($request->attributes_name) > 0){
                for ($i=0; $i < count($request->attributes_name); $i++) { 
                    if (!empty($request->attributes_name[$i])) {
                        $filterable_checkbox = explode(',',$request->filterable_checkbox);
                        $attribute = new PartAttribute();
                        $attribute->part_id = $part->id;
                        $attribute->attribute_name = $request->attributes_name[$i];
                        $attribute->is_filter = ($filterable_checkbox[$i] == '1') ? 1 : 0;
                        $attribute->value = $request->attributes_value[$i];
                        $attribute->save();
                    }
                }
            }
            return response()->json([
                'type' => 'success',
                'message' => 'Product Part created successfully.',
            ]);
        }
    }

    public function edit($id){
        $products = Product::all();
        $brands = Brand::all();
        $part = ProductPart::find($id);
        return view('backend.pages.part.edit', compact('products','brands','part'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'name' => 'required',
			'code' => 'required',
			'product' => 'required',
			'price' => 'required',
            'thumbnail' => 'nullable|image:png,jpg,jpeg,gif,webp,',
		]);

        
        $part = ProductPart::find($id);
        if (Session::get('admin_language') == 'en') {
            $part->name = $request->name;
            $part->key_features  = $request->key_features;
            $part->further_information  = $request->further_information;
        }
        $part->code = $request->code;
        $part->parts_type = $request->parts_type;
        $part->brand_id = $request->brand;
        $part->product_id = $request->product;
        $part->price  = $request->price;
        $part->discount_type  = $request->discount_type;
        $part->discount  = $request->discount;
        $part->status = ($request->status) ? 1 : 0;
        if($request->hasFile('thumbnail')){
            $thumbnail = $request->file('thumbnail');
            $filename = time().uniqid().$thumbnail->getClientOriginalName();
            $thumbnail->move(public_path('uploads/part-images'), $filename);
            $part->thumbnail = $filename;
        }

        if($request->hasFile('gallery')){
            $gallerys = $request->file('gallery');
            $image_name = [];
            foreach ($gallerys as $image) {
                $filename = time().uniqid().$image->getClientOriginalName();
                $image->move(public_path('uploads/part-images'), $filename);
                array_push($image_name, $filename);
            }
            $part->images =  json_encode($image_name);
        }
        
        if ($part->save()) {
            // language
            Helper::insertLanguage(ProductPart::class, $part->id, Session::get('admin_language') ?? 'en', 'name', $request->name);
            Helper::insertLanguage(ProductPart::class, $part->id, Session::get('admin_language') ?? 'en', 'key_features', $request->key_features);
            Helper::insertLanguage(ProductPart::class, $part->id, Session::get('admin_language') ?? 'en', 'further_information', $request->further_information);

            PartAttribute::where('part_id', $part->id)->where('type', 'attributes')->delete();
            if(isset($request->attributes_name) && count($request->attributes_name) > 0){
                for ($i=0; $i < count($request->attributes_name); $i++) { 
                    if (!empty($request->attributes_name[$i])) {
                        $filterable_checkbox = explode(',',$request->filterable_checkbox);
                        $attribute = new PartAttribute();
                        $attribute->part_id = $part->id;
                        $attribute->is_filter = ($filterable_checkbox[$i] == '1') ? 1 : 0;
                        $attribute->type = 'attributes';
                        $attribute->attribute_name = $request->attributes_name[$i];
                        $attribute->value = $request->attributes_value[$i];
                        $attribute->save();
                    }
                }
            }
            
            return response()->json([
                'type' => 'success',
                'message' => 'Product Part updated successfully.',
            ]);
        }
    }

    public function delete($id){
        $part = ProductPart::find($id);
        if($part){
            if ($part->thumbnail != null && file_exists(public_path('uploads/part-images/'.$part->thumbnail))) {
                unlink(public_path('uploads/part-images/'.$part->thumbnail));
            }
            $part->delete();
            PartAttribute::where('part_id', $part->id)->delete();
            return json_encode(['success' => 'Product Part deleted successfully.']);
        }else{
            return json_encode(['error' => 'Product Part not found.']);
        }
    }

    public function custom_option($id){
        $part = ProductPart::find($id);
        $custom_fields = CustomField::where('status', 1)->get();
        $custom_options = PartAttribute::where('part_id', $id)->where('type', "custom value")->where('ancestor_id', null)->get();
        return view('backend.pages.part.custom-options.custom-option', compact('part','custom_fields', 'custom_options'));
    }

    public function custom_sub_option($id){
        $custom_sub_options = PartAttribute::where('type', "custom value")->where('custom_field_id', $id)->where('ancestor_id', null)->groupBy('sub_option')->select('sub_option')->get();
        $html = '<option value="">-- Select --</option>';
        foreach($custom_sub_options as $row){
            $html .= '<option value="'.$row->sub_option.'">'.$row->sub_option.'</option>';
        }
        return $html ?? null;
    }

    public function generate_html_for_customoption(Request $request){
        $part = ProductPart::find($request->part_id);
        $custom_fields = CustomField::find($request->custom_field_id);
        $sub_option = $request->sub_option;

        $sub_option_id = PartAttribute::where('part_id', $part->id)->where('type', 'custom value')->where('custom_field_id', $custom_fields->id)->where('sub_option', $sub_option)->where('ancestor_id', null)->first();

        $attributes = PartAttribute::where('part_id', $part->id)->where('type', 'custom value')->where('custom_field_id', $custom_fields->id)->where('sub_option', $sub_option)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $sub_option_id->id ?? '')->get();


        return view('backend.pages.part.custom-options.more-features', compact('part','custom_fields', 'sub_option', 'attributes'));
    }

    public function update_custom_option(Request $request){
        $part = ProductPart::find($request->id);
        if ($part) {
            if(isset($request->custom_option_name) && count($request->custom_option_name) > 0){
                $ancestor_id = '';
                $sub_option = PartAttribute::where('part_id', $request->id)->where('type', 'custom value')->where('custom_field_id', $request->custom_field_id)->where('sub_option', $request->sub_option)->where('ancestor_id', null)->first();
                if ($sub_option) {
                    $ancestor_id = $sub_option->id;
                }else{
                    $new_sub_option = new PartAttribute();
                    $new_sub_option->part_id = $part->id;
                    $new_sub_option->type = 'custom value';
                    $new_sub_option->custom_field_id = $request->custom_field_id;
                    $new_sub_option->sub_option = $request->sub_option;
                    $new_sub_option->language_code = Session::get('admin_language') ?? 'en';
                    $new_sub_option->save();
                    $ancestor_id = $new_sub_option->id;
                }

                PartAttribute::where('part_id', $request->id)->where('type', 'custom value')->where('custom_field_id', $request->custom_field_id)->where('sub_option', $request->sub_option)->where('language_code', Session::get('admin_language') ?? 'en')->whereNotNull('ancestor_id')->delete();
                $images = $request->file('custom_option_image');
                for ($i=0; $i < count($request->custom_option_name); $i++) { 
                    if (!empty($request->custom_option_name[$i]) || !empty($request->custom_option_image[$i]) || !empty($request->custom_option_value[$i]) || !empty($request->custom_option_details[$i])) {
                        $attribute = new PartAttribute();
                        $attribute->part_id = $part->id;
                        $attribute->type = 'custom value';
                        $attribute->language_code = Session::get('admin_language') ?? 'en';
                        $attribute->custom_field_id = $request->custom_field_id;
                        $attribute->sub_option = $request->sub_option;
                        $attribute->title = $request->custom_option_name[$i];
                        $attribute->value = $request->custom_option_value[$i];
                        $attribute->details = $request->custom_option_details[$i];
                        $attribute->ancestor_id = $ancestor_id;
                        //image
                        if ($images) {
                            $filenames = '';
                            foreach ($images as $index => $image) {
                                if ($index == $i) {
                                    $filename = time() . uniqid() . '_' . $image->getClientOriginalName();
                                    $image->move(public_path('uploads/parts-custom-files'), $filename);
                                    $filenames = $filename;
                                }
                            }
                            $attribute->image = ($filenames == '') ? $request->old_image[$i] : $filenames ;
                        }else{
                            $attribute->image = $request->old_image[$i] ?? '';
                        }

                        $attribute->save();
                    }
                }
                return response()->json([
                    'type' => 'success',
                    'message' => 'Product custom options update successfully.',
                    'part_id' => $part->id,
                ]);
            }
        }else{
            return response()->json([
                'type' => 'error',
                'message' => 'Product not found.',
            ]);
        }
        // return $request->all();
    }

    public function custom_option_delete(Request $request){
        $sub_option = PartAttribute::find($request->sub_option);
        if ($sub_option) {
            PartAttribute::where('part_id', $request->part_id)->where('type', 'custom value')->where('custom_field_id', $request->custom_field_id)->where('language_code', Session::get('admin_language') ?? 'en')->where('ancestor_id', $sub_option->id)->delete();
            $sub_option->delete();
            return response()->json([
                'type' => 'success',
                'message' => 'Custom option deleted successfully.',
            ]);
        }
    }
}
