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
use App\Models\ProductAttribute;
use App\Models\Company;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Transaction;
use App\Models\User;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('transaction.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        return view('backend.pages.transaction.index');
    }


    public function getList(Request $request){

        $data = Transaction::query();

        if (!empty($request->date)) {
            $data->whereDate('created_at', $request->date);
        }

        return Datatables::of($data)

        ->editColumn('created_at', function ($row) {
            return date('Y-m-d', strtotime($row->created_at));
        })

        ->addColumn('order_details', function ($row) {
            $order = Order::find($row->order_id);
            $billing = json_decode($order->billing_information);
            $html ='<small>'.$billing->name.'</samll>,</br> <small>'.$billing->email.'</samll>,</br> <small>'.$billing->phone.'</samll>';

            return $html;
        })

        ->editColumn('status', function ($row) {
            if ($row->status == 0) {
                return '<span class="badge bg-warning w-80">Pending</span>';
            }elseif ($row->status == 1) {
                return '<span class="badge bg-success w-80">Success</span>';
            }
        })
        
        ->rawColumns(['created_at','order_details','status'])->make(true);
    }
}
