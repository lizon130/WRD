<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Catalogue;
use Illuminate\Support\Str;

class CatalogueController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('catalogue.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $categorys = Category::all();
        $brands = Brand::all();
        $products = Product::all();
        return view('backend.pages.catalogue.index', compact('categorys', 'brands', 'products'));
    }

    public function getList(Request $request){

        $data = Catalogue::query();
        if (!empty($request->title)) {
            $data->where('title','like', "%" .$request->title ."%" );
        }

        if ($request->date) {
            $data->where('category_id', $request->category);
        }

        if ($request->brand) {
            $data->where('brand_id', $request->brand);
        }
        
        $data->with('category', 'brand');

        return Datatables::of($data)

        ->editColumn('short_description', function ($row) {
            return Str::limit($row->short_description, 90, '...');
        })

        ->editColumn('category_id', function ($row) {
            return $row->category->title ?? '-';
        })

        ->editColumn('brand_id', function ($row) {
            return $row->brand->title ?? '-';
        })

        ->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="badge bg-primary w-80">Visible</span>';
            }else{
                return '<span class="badge bg-danger w-80">Hidden</span>';
            }
        })

        ->addColumn('action', function ($row) {
            $btn = '';
            if (Helper::hasRight('catalogue.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('catalogue.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['short_description','category_id','brand_id','status','action'])->make(true);
    }

    public function store(Request $request){
        $validator = $request->validate([
			'title' => 'required',
            'image' => 'required|image:png,jpg,jpeg,gif,webp,',
            'file' => 'required|mimes:pdf',
		]);

        $catalogue = new Catalogue();
        $catalogue->title = $request->title;
        $catalogue->short_description = $request->short_description;
        $catalogue->status = ($request->status) ? 1 : 0;
        $catalogue->category_id  = $request->category_id;
        $catalogue->brand_id  = $request->brand_id;
        $catalogue->product_id  = $request->product_id;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $imagename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path('uploads/catalogue-images'), $imagename);
            $catalogue->thumbnail = $imagename;
        }
        if($request->hasFile('file')){
            $file = $request->file('file');
            $filename = time().uniqid().$file->getClientOriginalName();
            $file->move(public_path('uploads/catalogue-files'), $filename);
            $catalogue->file = $filename;
        }
        if ($catalogue->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Catalogue created successfully.',
            ]);
        }
    }

    public function edit($id){
        $categorys = Category::all();
        $brands = Brand::all();
        $products = Product::all();
        $catalogue = Catalogue::find($id);
        return view('backend.pages.catalogue.edit', compact('catalogue','categorys', 'brands', 'products'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'title' => 'required',
            'image' => 'nullable|image:png,jpg,jpeg,gif,webp,',
            'file' => 'nullable|mimes:pdf',
		]);

        $catalogue = Catalogue::find($id);
        $catalogue->title = $request->title;
        $catalogue->short_description = $request->short_description;
        $catalogue->status = ($request->status) ? 1 : 0;
        $catalogue->category_id  = $request->category_id;
        $catalogue->brand_id  = $request->brand_id;
        $catalogue->product_id  = $request->product_id;

        if($request->hasFile('image')){
            if (file_exists(public_path('uploads/catalogue-images/'.$catalogue->thumbnail))) {
                unlink(public_path('uploads/catalogue-images/'.$catalogue->thumbnail));
            }
            $image = $request->file('image');
            $imagename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path('uploads/catalogue-images'), $imagename);
            $catalogue->thumbnail = $imagename;
        }

        if($request->hasFile('file')){
            if (file_exists(public_path('uploads/catalogue-files/'.$catalogue->file))) {
                unlink(public_path('uploads/catalogue-files/'.$catalogue->file));
            }
            $file = $request->file('file');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path('uploads/catalogue-files'), $filename);
            $catalogue->file = $filename;
        }
        

        if ($catalogue->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Catalogue updated successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id){
        $catalogue = Catalogue::find($id);
        if($catalogue){
            if ($catalogue->media != null && file_exists(public_path('uploads/catalogue-images/'.$catalogue->media))) {
                unlink(public_path('uploads/catalogue-images/'.$catalogue->media));
            }
            $catalogue->delete();
            return json_encode(['success' => 'Catalogue deleted successfully.']);
        }else{
            return json_encode(['error' => 'Catalogue not found.']);
        }
    }
}
