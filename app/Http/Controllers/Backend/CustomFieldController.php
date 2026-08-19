<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomField;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductAttribute;
use Session;

class CustomFieldController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('custom-field.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $custom_fields = CustomField::all();
        return view('backend.pages.product.customs.custom-fields', compact('custom_fields'));
    }

    public function store(Request $request){
        
        $validator = $request->validate([
			'name' => 'required|unique:custom_fields,field_name'
		]);

        $customfields = new CustomField();
        $customfields->field_name = $request->name;
        $customfields->status = ($request->status) ? 1 : 0;
        if ($customfields->save()) {
            // language 
            Helper::insertLanguage(CustomField::class, $customfields->id, 'en', 'field_name', $customfields->field_name);

            return response()->json([
                'type' => 'success',
                'message' => 'Product created successfully.',
            ]);
        }
    }

    public function edit($id){
        $custom_field = CustomField::find($id);
        return view('backend.pages.product.customs.edit', compact('custom_field'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'name' => 'required'
		]);

        $customfields = CustomField::find($id);
        if (Session::get('admin_language') == 'en') {
            $customfields->field_name = $request->name;
        }
        $customfields->status = ($request->status) ? 1 : 0;
        if ($customfields->save()) {
            // language
            Helper::insertLanguage(CustomField::class, $customfields->id, 'en', 'field_name', $customfields->field_name);
            return response()->json([
                'type' => 'success',
                'message' => 'Custom field updated successfully.',
            ]);
        }
    }

    public function delete($id){
        $customfields = CustomField::find($id);
        if($customfields){
            $customfields->delete();
            return json_encode(['success' => 'Custom field deleted successfully.']);
        }else{
            return json_encode(['error' => 'Custom field not found.']);
        }
    }
}
