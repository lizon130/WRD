<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\Service;
use App\Models\ServiceOrder;
use App\Models\User;

class ServiceOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('service-order.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $services = Service::all();
        return view('backend.pages.service-order.index', compact('services'));
    }

    public function getList(Request $request){

        $data = ServiceOrder::query();

        if ($this->user->role == 2 || $this->user->role == 4 || $this->user->role == 5) {
            $data->where('user_id', $this->user->id);
        }

        if (!empty($request->date)) {
            $data->whereDate('date', $request->date);
        }

        if ($request->order_id) {
            $data->where('id', $request->order_id);
        }

        if (!empty($request->service_name)) {
            $data->where('name','like', "%" .$request->service_name ."%" );
        }

        return Datatables::of($data)

        ->editColumn('service_information', function ($row) {
            $service_information = json_decode($row->service_information);
            $code = '';
            foreach($service_information as $item){
                $code .= $item->code. ',';
            }
            return $code;
        })

        ->addColumn('service_name', function ($row) {
            $service_information = json_decode($row->service_information);
            $name = '';
            foreach($service_information as $item){
                $name .= $item->name. ',';
            }
            return $name;
        })

        ->editColumn('status', function ($row) {
            if ($row->status == 0) {
                return '<span class="badge bg-warning w-80">New</span>';
            }elseif ($row->status == 1) {
                return '<span class="badge bg-success w-80">Complete</span>';
            }else if ($row->status == 2) {
                return '<span class="badge bg-info w-80">On Progress</span>';
            }else{
                return '<span class="badge bg-danger w-80">Rejected</span>';
            }
        })

        ->addColumn('action', function ($row) {
            if ($this->user->role === 4 || $this->user->role === 5) {
                return ''; // Return empty string for users with roles 4 and 5
            }
            $btn = '';
            if (Helper::hasRight('service-order.status')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="status_change_btn btn btn-sm btn-info text-light"><i class="fa-solid fa-truck"></i></a>';
            }
            if (Helper::hasRight('service-order.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary mx-1"><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('service-order.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger " data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['service_name','status','action'])->make(true);
    }

    public function row($number){
        $services = Service::all();
        $number++;
        return view('backend.pages.service-order.row', compact('services','number'));
    }

    public function store(Request $request){

        $validator = $request->validate([
			'company_name' => 'required',
			'name' => 'required',
			'email' => 'required',
			'address' => 'required',
			'service' => 'required',
		]);

        $order = new ServiceOrder();
        $order->date = date('Y-m-d');
        $order->company_name = $request->company_name;
        $order->name = $request->name;
        $order->email = $request->email;
        $order->address = $request->address;
        $order->message = $request->message;

        $service_information =[];

        for ($i=0; $i < count($request->service); $i++) {
            $service = Service::find($request->service[$i]);
            $files = $request->file('files');

            // Check if files were uploaded for the current service
            if ($files && isset($files[$i])) {
                $thumbnail = $files[$i];
                $filename = time() . uniqid() . $thumbnail->getClientOriginalName();
                $thumbnail->move(public_path('uploads/service-order'), $filename);
                $file = $filename;
            } else {
                $file = '';
            }

            $services = [
                'service_id' => $service->id,
                'name' => $service->title,
                'code' => $service->code,
                'file' => $file
            ];
            array_push($service_information, $services);
        }
        $order->service_information = json_encode($service_information);

        if ($order->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Service Order created successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function edit($id){
        $order = ServiceOrder::find($id);
        $services = Service::all();
        $service_item = json_decode($order->service_information);
        return view('backend.pages.service-order.edit', compact('order','services','service_item'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'company_name' => 'required',
			'name' => 'required',
			'email' => 'required',
			'address' => 'required',
			'service' => 'required',
		]);

        $order = ServiceOrder::find($id);
        $order->date = date('Y-m-d');
		$order->company_name = $request->company_name;
        $order->name = $request->name;
        $order->email = $request->email;
        $order->address = $request->address;
        $order->message = $request->message;
        $order->status = $request->status;

        $service_information =[];
        for ($i=0; $i < count($request->service); $i++) {
            $service = Service::find($request->service[$i]);
            $files = $request->file('files');
            // Check if files were uploaded for the current service
            if ($files && isset($files[$i])) {
                $thumbnail = $files[$i];
                $filename = time() . uniqid() . $thumbnail->getClientOriginalName();
                $thumbnail->move(public_path('uploads/service-order'), $filename);
                $file = $filename;
            } else {
                $file = $request->hidden_file[$i] ?? '';
            }

            $services = [
                'service_id' => $service->id,
                'name' => $service->title,
                'code' => $service->code,
                'file' => $file
            ];
            array_push($service_information, $services);
        }
        $order->service_information = json_encode($service_information);

        if ($order->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Service Order created successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id){
        $order = ServiceOrder::find($id);
        if($order->delete()){
            return json_encode(['success' => 'Service Order deleted successfully.']);
        }else{
            return json_encode(['error' => 'Service Order not found.']);
        }
    }

    public function editStaus($order_id){
        $order = ServiceOrder::find($order_id);
        $service_item = json_decode($order->service_information);
        return view('backend.pages.service-order.status', compact('order', 'service_item'));
    }

    public function updateStatus(Request $request, $id){
        $order = ServiceOrder::find($id);
        $order->status = $request->status;
        if ($order->save()) {
            if ($order->email) {
                // send email
                $user = User::where('id', $order->user_id)->first();
                $email = $order->email;
                $subject = 'Service Order - Status changes';
                $data['user'] = $user;
                $data['order'] = $order;
                $data['services'] = json_decode($order->service_information);
                $data['comments'] = $request->message ?? '';

                // return $data;
                // return view('mails.orderinvoice', compact($data));
                if ($request->send_email && $email) {
                    Helper::sendEmail($email, $subject, $data, 'serviceorderinvoice');
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
