<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\Category;
use App\Models\Company;
use App\Models\Translation;
use Session;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('category.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $companies = Company::where('status', 1)->orderBy('name', 'asc')->get();
        $parent_category = Category::all();
        return view('backend.pages.category.index', compact('parent_category', 'companies'));
    }

    public function getList(Request $request){
        
        $data = Category::query();

        if (!empty($request->parent_category)) {
            $data->where(function($query) use ($request){
                $query->where('parent_category', $request->parent_category);
            });
        }

        if ($request->status) {
            $data->where(function($query) use ($request){
                if ($request->status == 1) {
                    $status = 1;
                }else{
                    $status = 0;
                }
                $query->where('status', $status);
            });
        }

        if (!empty($request->title)) {
            $data->where(function($query) use ($request){
                $query->where('title','like', "%" .$request->title ."%" );
            });
        }

        return Datatables::of($data)

        ->editColumn('image', function ($row) {
            return ($row->image) ? '<a href="'.asset('uploads/category-images/'.$row->image).'" target="_blank"><img class="profile-img" src="'.asset('uploads/category-images/'.$row->image).'" alt="profile image"></a>' : '<img class="profile-img" src="'.asset('assets/img/no-img.jpg').'" alt="profile image">';
        })

        ->editColumn('company_id', function ($row) {
            return $row->company->name ?? 'N/A';
        })

        ->editColumn('is_parent', function ($row) {
            return ($row->is_parent == 1) ? '<span class="badge bg-gray text-dark w-70">Yes</span>' : '<span class="badge bg-gray text-dark w-70">No</span>';
        })

        ->editColumn('parent_category', function ($row) {
            return ($row->parent)->title ?? '-';
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
            if (Helper::hasRight('category.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('category.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['image','is_parent','status','parent_category','action'])->make(true);
    }

    public function store(Request $request){
        $validator = $request->validate([
			'title' => 'required',
			'company' => 'required',
            'image' => 'nullable|image:png,jpg,jpeg,gif,webp,',
		]);

        $category = new Category();
        $category->title = ucfirst($request->title);
        $category->parent_category = $request->parent_category;
        $category->company_id = $request->company;
        $category->is_parent = ($request->is_parent) ? 1 : 0;
        $category->alternate_name = $request->alternate_name;
        $category->value  = $request->value;
        $category->status  = ($request->status) ? 1 : 0;
        $category->show_home  = ($request->show_home) ? 1 : 0;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path('uploads/category-images'), $filename);
            $category->image = $filename;
        }
        if ($category->save()) {
            // language 
            Helper::insertLanguage(Category::class, $category->id, 'en', 'title', $category->title);

            return response()->json([
                'type' => 'success',
                'message' => 'Category created successfully.',
            ]);
        }
    }

    public function edit($id){
        $single_category = Category::find($id);
        $parent_category = Category::all();
        $companies = Company::where('status', 1)->orderBy('name', 'asc')->get();
        return view('backend.pages.category.edit', compact('single_category','parent_category', 'companies'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'title' => 'required',
            'company' => 'required',
            'image' => 'nullable|image:png,jpg,jpeg,gif,webp,',
		]);

        $category = Category::find($id);
        if (Session::get('admin_language') == 'en') {
            $category->title = ucfirst($request->title);
        }
        $category->company_id = $request->company;
        $category->parent_category = $request->parent_category;
        $category->is_parent = ($request->is_parent) ? 1 : 0;
        $category->alternate_name = $request->alternate_name;
        $category->value  = $request->value;
        $category->status  = ($request->status) ? 1 : 0;
        $category->show_home  = ($request->show_home) ? 1 : 0;
        if($request->hasFile('image')){

            if ($category->image != Null && file_exists(public_path('uploads/category-images/'.$category->image))) {
                unlink(public_path('uploads/category-images/'.$category->image));
            }
            $image = $request->file('image');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path('uploads/category-images'), $filename);
            $category->image = $filename;
        }
        
        if ($category->save()) {

            // language
            Helper::insertLanguage(Category::class, $category->id, Session::get('admin_language') ?? 'en', 'title', $request->title);

            return response()->json([
                'type' => 'success',
                'message' => 'Category updated successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id){
        $category = Category::find($id);
        if($category){
            if ($category->image != null && file_exists(public_path('uploads/category-images/'.$category->image))) {
                unlink(public_path('uploads/category-images/'.$category->image));
            }
            $category->delete();
            return json_encode(['success' => 'Category deleted successfully.']);
        }else{
            return json_encode(['error' => 'Category not found.']);
        }
    }
}
