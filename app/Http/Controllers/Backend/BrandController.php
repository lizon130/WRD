<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\Brand;
use App\Models\Translation;
use Session;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('brand.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        return view('backend.pages.brand.index');
    }

    public function getList(Request $request){
        
        $data = Brand::query();

        return Datatables::of($data)

        ->editColumn('image', function ($row) {
            return ($row->image) ? '<a href="'.asset('uploads/brand-images/'.$row->image).'" target="_blank"><img class="profile-img" src="'.asset('uploads/brand-images/'.$row->image).'" alt="profile image"></a>' : '<img class="profile-img" src="'.asset('assets/img/no-img.jpg').'" alt="profile image">';
        })


        ->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="badge bg-primary w-50">Active</span>';
            }else{
                return '<span class="badge bg-danger w-50">Inactive</span>';
            }
        })

        ->addColumn('action', function ($row) {
            $btn = '';
            if (Helper::hasRight('brand.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('brand.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['image','status','action'])->make(true);
    }

    public function store(Request $request){
        $validator = $request->validate([
			'title' => 'required',
            'image' => 'nullable|image:png,jpg,jpeg,gif,webp,',
		]);

        $brand = new Brand();
        $brand->title = ucfirst($request->title);
        $brand->status  = ($request->status) ? 1 : 0;
        $brand->show_home  = ($request->show_home) ? 1 : 0;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path('uploads/brand-images'), $filename);
            $brand->image = $filename;
        }
        if ($brand->save()) {
            
            // language 
            Helper::insertLanguage(Brand::class, $brand->id, 'en', 'title', $brand->title);

            return response()->json([
                'type' => 'success',
                'message' => 'Brand created successfully.',
            ]);
        }
    }

    public function edit($id){
        $single_category = Brand::find($id);
        return view('backend.pages.brand.edit', compact('single_category'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'title' => 'required',
            'image' => 'nullable|image:png,jpg,jpeg,gif,webp,',
		]);

        $brand = Brand::find($id);
        if (Session::get('admin_language') == 'en') {
            $brand->title = ucfirst($request->title);
        }
        $brand->status  = ($request->status) ? 1 : 0;
        $brand->show_home  = ($request->show_home) ? 1 : 0;
        if($request->hasFile('image')){

            if ($brand->image != Null && file_exists(public_path('uploads/brand-images/'.$brand->image))) {
                unlink(public_path('uploads/brand-images/'.$brand->image));
            }
            $image = $request->file('image');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path('uploads/brand-images'), $filename);
            $brand->image = $filename;
        }
        
        if ($brand->save()) {

            // language
            Helper::insertLanguage(Brand::class, $brand->id, Session::get('admin_language') ?? 'en', 'title', $request->title);

            return response()->json([
                'type' => 'success',
                'message' => 'Brand updated successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id){
        $brand = Brand::find($id);
        if($brand){
            if ($brand->image != null && file_exists(public_path('uploads/brand-images/'.$brand->image))) {
                unlink(public_path('uploads/brand-images/'.$brand->image));
            }
            $brand->delete();
            return json_encode(['success' => 'Brand deleted successfully.']);
        }else{
            return json_encode(['error' => 'Brand not found.']);
        }
    }
}
