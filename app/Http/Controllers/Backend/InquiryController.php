<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Company;
use App\Models\Inquiry;
use App\Models\InquiryProduct;
use App\Models\User;

class InquiryController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {

    //         $this->user = Auth::user();

    //         if (!$this->user || Helper::hasRight('order.view') == false) {
    //             session()->flash('error', 'You can not access! Login first.');
    //             return redirect()->route('admin');
    //         }
    //         return $next($request);
    //     });
    // }

    public function index(){
        $category = Category::all();
        $brands = Brand::all();
        $partners = Company::all();
        $products = Product::all();
        return view('backend.pages.inquiry.index', compact('category','brands','partners', 'products'));
    }

    public function getList(Request $request){

        $data = Inquiry::query();
        if ($this->user->role == 2 || $this->user->role == 4 || $this->user->role == 5) {
            $data->where('user_id', $this->user->id);
        }
        if (!empty($request->date)) {
            $data->where('date', $request->date);
        }

        if ($request->request_by) {
            $data->where('request_by', $request->request_by);
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

        ->addColumn('company', function ($row) {
            $address = json_decode($row->address_information);
            return $address->company ?? '';
        })

        ->addColumn('email', function ($row) {
            $address = json_decode($row->address_information);
            return $address->email ?? '';
        })


        ->editColumn('status', function ($row) {
            if ($row->status == 0) {
                return '<span class="badge bg-warning w-80">New</span>';
            }elseif ($row->status == 1) {
                return '<span class="badge bg-success w-80">Complete</span>';
            }else{
                return '<span class="badge bg-danger w-80">Rejected</span>';
            }
        })

        ->addColumn('action', function ($row) {
            if ($this->user->role === 4 || $this->user->role === 5) {
                return ''; // Return empty string for users with roles 4 and 5
            }
            $btn = '';
            if (Helper::hasRight('order.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="status_change_btn btn btn-sm btn-info text-light"><i class="fa-solid fa-truck"></i></a>';
            }
            if (Helper::hasRight('inquiry.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary mx-1"><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('inquiry.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger " data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['company','email','status','action'])->make(true);
    }

    public function row($number){
        $products = Product::all();
        $number++;
        return view('backend.pages.inquiry.row', compact('products','number'));
    }

    public function getCompany($user_id){
        $company = Company::where('user_id', $user_id)->first();
        return json_encode($company);
    }

    public function getProduct(Request $request){
        $product = Product::find($request->product_id);
        $product->price = ($product->discount) ? Helper::priceFaterOffer($product->id) : $product->price;
        return json_encode($product);
    }

    public function store(Request $request){

        $validator = $request->validate([
			'request_by' => 'required',
			'company' => 'required',
			'name' => 'required',
			'phone' => 'required',
			'email' => 'required',
			'address' => 'required',
			'post_code' => 'required',
			'city' => 'required',
			'state' => 'required',
			'country' => 'required',
			'product' => 'required',
		]);

        $inquiry = new Inquiry();
        $inquiry->user_id = $request->user_id ?? '';
        $inquiry->date = date('Y-m-d');
        $inquiry->total_price = $request->total_price;
        $inquiry->request_by = $request->request_by;
        $inquiry->note = $request->note;

        $address_information = [
            'company' => $request->company,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country
        ];

        $inquiry->address_information = json_encode($address_information);

        if ($inquiry->save()) {
            $inquiry->refresh();

            for ($i=0; $i < count($request->product); $i++) {
                $inquiry_products = new InquiryProduct();
                $inquiry_products->inquiry_id  = $inquiry->id;
                $inquiry_products->product_id = $request->product[$i];
                $inquiry_products->quantity = $request->qty[$i];
                $inquiry_products->unit_price = $request->price[$i];
                $inquiry_products->note = $request->notes[$i];
                $inquiry_products->subtotal = $request->subtotal[$i];
                $inquiry_products->save();
            }

            return response()->json([
                'type' => 'success',
                'message' => 'Inquiry created successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function edit($inquiry_id){
        $inquiry = Inquiry::find($inquiry_id);
        $products = Product::where('status', 1)->get();
        $partners = Company::all();
        $billing = json_decode($inquiry->address_information);
        return view('backend.pages.inquiry.edit', compact('inquiry','products','partners','billing'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'request_by' => 'required',
			'company' => 'required',
			'name' => 'required',
			'phone' => 'required',
			'email' => 'required',
			'address' => 'required',
			'post_code' => 'required',
			'city' => 'required',
			'state' => 'required',
			'country' => 'required',
			'product' => 'required',
		]);

        $inquiry = Inquiry::find($id);
        $inquiry->user_id = $request->user_id ?? '';
        $inquiry->date = date('Y-m-d');
        $inquiry->total_price = $request->total_price;
        $inquiry->request_by = $request->request_by;
        $inquiry->note = $request->note;

        $address_information = [
            'company' => $request->company,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country
        ];

        $inquiry->address_information = json_encode($address_information);

        if ($inquiry->save()) {
            InquiryProduct::where('inquiry_id', $id)->delete();

            for ($i=0; $i < count($request->product); $i++) {
                $inquiry_products = new InquiryProduct();
                $inquiry_products->inquiry_id  = $inquiry->id;
                $inquiry_products->product_id = $request->product[$i];
                $inquiry_products->quantity = $request->qty[$i];
                $inquiry_products->unit_price = $request->price[$i];
                $inquiry_products->note = $request->notes[$i];
                $inquiry_products->subtotal = $request->subtotal[$i];
                $inquiry_products->save();
            }

            return response()->json([
                'type' => 'success',
                'message' => 'Inquiry updated successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function sendEmail(Request $request, $id){
        $validator = $request->validate([
			'request_by' => 'required',
			'company' => 'required',
			'name' => 'required',
			'phone' => 'required',
			'email' => 'required',
			'address' => 'required',
			'post_code' => 'required',
			'city' => 'required',
			'state' => 'required',
			'country' => 'required',
			'product' => 'required',
		]);

        $inquiry = Inquiry::find($id);
        $inquiry->user_id = $request->user_id ?? '';
        $inquiry->date = date('Y-m-d');
        $inquiry->total_price = $request->total_price;
        $inquiry->request_by = $request->request_by;
        $inquiry->note = $request->note;

        $address_information = [
            'company' => $request->company,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country
        ];

        $inquiry->address_information = json_encode($address_information);

        if ($inquiry->save()) {
            InquiryProduct::where('inquiry_id', $id)->delete();

            for ($i=0; $i < count($request->product); $i++) {
                $inquiry_products = new InquiryProduct();
                $inquiry_products->inquiry_id  = $inquiry->id;
                $inquiry_products->product_id = $request->product[$i];
                $inquiry_products->quantity = $request->qty[$i];
                $inquiry_products->unit_price = $request->price[$i];
                $inquiry_products->note = $request->notes[$i];
                $inquiry_products->subtotal = $request->subtotal[$i];
                $inquiry_products->save();
            }

            // send email
            $email = $request->email;
            $subject = 'Product Inquiry - Request for Information';
            $data['user'] = User::where('email', $request->email)->first();
            $data['inquiry'] = $inquiry;
            $data['inquiry_products'] = InquiryProduct::where('inquiry_id', $inquiry->id)->with('product')->get();
            $data['billing'] = json_decode($inquiry->address_information);
            // return $data['inquery_products'];
            // return view('mails.inquiryinvoice', compact($data));
            if ($email) {
                Helper::sendEmail($email, $subject, $data, 'inquiryinvoice');
            }

            return response()->json([
                'type' => 'success',
                'message' => 'Email send successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id){
        $inquiry = Inquiry::find($id);
        if($inquiry->delete()){
            $details = InquiryProduct::where('inquiry_id', $id)->delete();
            return json_encode(['success' => 'Inquiry deleted successfully.']);
        }else{
            return json_encode(['error' => 'Inquiry not found.']);
        }
    }

    public function editStaus($inquiry_id){
        $inquiry = Inquiry::find($inquiry_id);
        $products = Product::where('status', 1)->get();
        $partners = Company::all();
        $billing = json_decode($inquiry->address_information);
        return view('backend.pages.inquiry.status', compact('inquiry','products','partners','billing'));
    }

    public function updateStatus(Request $request, $id){
        $inquiry = Inquiry::find($id);
        $inquiry->status = $request->status;
        if ($inquiry->save()) {
            $billing = json_decode($inquiry->address_information);
            if ($billing->email) {
                // send email
                $user = User::where('id', $inquiry->user_id)->first();
                $email = $billing->email;
                $subject = 'Inquiry Request - Status changes';
                $data['user'] = User::where('email', $email)->first();
                $data['inquiry'] = $inquiry;
                $data['inquiry_products'] = InquiryProduct::where('inquiry_id', $inquiry->id)->with('product')->get();
                $data['billing'] = $billing;
                $data['comments'] = $request->message ?? '';
                // return view('mails.orderinvoice', compact($data));
                if ($request->send_email && $email) {
                    Helper::sendEmail($email, $subject, $data, 'inquiryinvoice');
                }
            }
            return response()->json([
                'type' => 'success',
                'message' => 'Order status changed successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }

    }
}
