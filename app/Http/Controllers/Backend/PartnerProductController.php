<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Auth;
use Helper;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Company;
use App\Models\User;
use App\Models\PartnerProduct;

class PartnerProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('partnerproduct.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $category = Category::all();
        $brands = Brand::all();
        $products = Product::where('status', 1)->get();
        $partners = Company::where('status', 1)->get();
        return view('backend.pages.partner-product.index', compact('category','brands', 'products', 'partners'));
    }

    public function getList(Request $request){

        $data = PartnerProduct::query();
        if (!empty($request->company)) {
            $data->where('company_id', $request->company);
        }

        if ($request->product) {
            $data->where('product_id', $request->product);
        }

        if ($request->status) {
            $data->where(function($query) use ($request){
                if ($request->status == 1) {
                    $status = 1;
                }else if($request->status == 2){
                    $status = 2;
                }else{
                    $status = 0;
                }
                $query->where('status', $status);
            });
        }
        
        return Datatables::of($data)

        ->editColumn('company_id', function ($row) {
            return ($row->company)->name ?? '-';
        })

        ->editColumn('category_id', function ($row) {
            return ($row->category)->title ?? '-';
        })

        ->editColumn('sub_category_id', function ($row) {
            return ($row->sub_category)->title ?? '-';
        })

        ->editColumn('product_id', function ($row) {
            return ($row->product)->name ?? '-';
        })

        ->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="badge bg-primary w-100">Approved</span>';
            }else if($row->status == 2){
                return '<span class="badge bg-danger w-100">Rejected</span>';
            }else{
                return '<span class="badge bg-warning w-100">Pending</span>';
            }
        })

        ->addColumn('action', function ($row) {
            $btn = '';
            if (Helper::hasRight('partnerproduct.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('partnerproduct.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['company_id','category_id','sub_category_id','product_id','status','action'])->make(true);
    }

    public function store(Request $request){
        
        $validator = $request->validate([
			'company' => 'required',
			'partner' => 'required',
			'category' => 'required',
			'product' => 'required',
			'quantity' => 'required',
		]);

        for ($i=0; $i < count($request->product); $i++) { 
            $partnerproduct = new PartnerProduct();
            $partnerproduct->company_id = $request->company;
            $partnerproduct->partner = $request->partner;
            
            $partnerproduct->category_id = $request->category[$i];
            $partnerproduct->subcategory_id = $request->subcategory[$i];
            $partnerproduct->product_id = $request->product[$i];
            $partnerproduct->quantity = $request->quantity[$i];
            $partnerproduct->price = $request->price[$i];
            $partnerproduct->discount_type = $request->discount_type[$i];
            $partnerproduct->discount_price = $request->discount[$i];
            $partnerproduct->save();
        }
        return response()->json([
            'type' => 'success',
            'message' => 'Product assign successfully.',
        ]);
    }

    public function edit($id){
        $category = Category::all();
        $brands = Brand::all();
        $products = Product::where('status', 1)->get();
        $partners = Company::where('status', 1)->get();
        $partner_product = PartnerProduct::find($id);
        $subcategory = Category::where('parent_category', $partner_product->category_id)->get();
        return view('backend.pages.partner-product.edit', compact('category','brands','products','partners','partner_product', 'subcategory'));
    }

    public function row($number){
        $category = Category::all();
        $number++;
        return view('backend.pages.partner-product.row', compact('category','number'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'company' => 'required',
			'partner' => 'required',
			'category' => 'required',
			'product' => 'required',
			'quantity' => 'required',
		]);
        for ($i=0; $i < count($request->product); $i++) { 
            $partnerproduct = PartnerProduct::updateOrCreate([
                'id' => $id
            ]);
            $partnerproduct->company_id = $request->company;
            $partnerproduct->partner = $request->partner;
            $partnerproduct->status = $request->status;
            $partnerproduct->category_id = $request->category[$i];
            $partnerproduct->subcategory_id = $request->subcategory[$i] ?? '';
            $partnerproduct->product_id = $request->product[$i];
            $partnerproduct->quantity = $request->quantity[$i];
            $partnerproduct->price = $request->price[$i];
            $partnerproduct->discount_type = $request->discount_type[$i];
            $partnerproduct->discount_price = $request->discount[$i];
            $partnerproduct->save();
        }

        return response()->json([
            'type' => 'success',
            'message' => 'Record updated successfully.',
        ]);
    }

    public function delete($id){
        $partnerproduct = PartnerProduct::find($id);
        if($partnerproduct){
            $partnerproduct->delete();
            return json_encode(['success' => 'Record deleted successfully.']);
        }else{
            return json_encode(['error' => 'Record not found.']);
        }
    }

    public function getPartner($id){
        $company = Company::find($id);
        return json_encode($company->contact_name);
    }

    public function getSubcategory($id){
        $subcategory = Category::where('parent_category', $id)->get();
        $html = '';
        foreach($subcategory as $row){
            $html .= '<option value="'.$row->id.'">'.$row->title.'</option>';
        }
        return $html;
    }

    public function getProduct(Request $request){
        $products = Product::where('status', 1)->where('category_id', $request->category_id);
        if($request->subcategory_id !=''){
            $products->where('sub_category_id', $request->subcategory_id);
        }
        $html = '';
        foreach($products->get() as $row){
            $html .= '<option value="'.$row->id.'">'.$row->code.' # '.$row->name.'</option>';
        }
        return $html;
    }
}
