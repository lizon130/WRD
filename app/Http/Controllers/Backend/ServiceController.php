<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\Service;
use Illuminate\Support\Str;
use Session;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('service.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $services = Service::all();
        $products = Product::where('status', 1)->get();
        return view('backend.pages.service.index', compact('services', 'products'));
    }

    public function getList(Request $request){

        $data = Service::query();
        
        if ($request->code) {
            $data->where('code', $request->code);
        }

        if (!empty($request->title)) {
            $data->where('title','like', "%" .$request->title ."%" );
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
        
        return Datatables::of($data)

        ->editColumn('short_description', function ($row) {
            return Str::limit($row->short_description, 90, '...');
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
            if (Helper::hasRight('service.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('service.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['short_description','status','action'])->make(true);
    }

    public function store(Request $request){
        $validator = $request->validate([
			'title' => 'required',
			'code' => 'required',
            'image' => 'nullable|image:png,jpg,jpeg,gif,webp,',
		]);

        $service = new Service();
        $service->code = $request->code;
        $service->title = $request->title;
        $service->product_id = $request->product_id;
        $service->short_description = $request->short_description;
        $service->status = ($request->status) ? 1 : 0;
        $service->description = $request->descriptions;
		
		$images = $request->file('aditional_image');
		$aditional_description = [];
		for ($i=0; $i < count($request->aditional_description); $i++) { 
            if (!empty($request->aditional_description[$i])) {
				
				$filenames = '';
				if ($images) {
					
					foreach ($images as $index => $image) {
						if ($index == $i) {
							$filename = time() . uniqid() . '_' . $image->getClientOriginalName();
							$image->move(public_path('uploads/service-images'), $filename);
							$filenames = $filename;
							
						}
					}
				}
				$description = [
					'description' => $request->aditional_description[$i],
					'image' => $filenames
				];
				array_push($aditional_description, $description);
			}
		}
		$service->additional_details = json_encode($aditional_description);
		
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path('uploads/service-images'), $filename);
            $service->media = $filename;
        }
        if ($service->save()) {
            // language 
            Helper::insertLanguage(Service::class, $service->id, 'en', 'title', $service->title);
            Helper::insertLanguage(Service::class, $service->id, 'en', 'short_description', $service->short_description);
            Helper::insertLanguage(Service::class, $service->id, 'en', 'description', $service->description);

            return response()->json([
                'type' => 'success',
                'message' => 'Service created successfully.',
            ]);
        }
    }

    public function edit($id){
        $service = Service::find($id);
        $products = Product::where('status', 1)->get();
        return view('backend.pages.service.edit', compact('service','products'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'title' => 'required',
			'code' => 'required',
            'image' => 'nullable|image:png,jpg,jpeg,gif,webp,',
		]);
		
		

        $service = Service::find($id);
        $service->code = $request->code;
        $service->product_id = $request->product_id;

        if (Session::get('admin_language') == 'en') {
            $service->title = $request->title;
            $service->short_description = $request->short_description;
            $service->description = $request->descriptions;
        }
        
        $service->status = ($request->status) ? 1 : 0;
        if($request->hasFile('image')){
            if (file_exists(public_path('uploads/service-images/'.$service->media))) {
                unlink(public_path('uploads/service-images/'.$service->media));
            }
            $image = $request->file('image');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path('uploads/service-images'), $filename);
            $service->media = $filename;
        }
		
		$images = $request->file('aditional_image');
		$aditional_description = [];
		for ($i=0; $i < count($request->aditional_description); $i++) { 
            if (!empty($request->aditional_description[$i])) {
				
				$filenames = '';
				if ($images) {
					
					foreach ($images as $index => $image) {
						if ($index == $i) {
							$filename = time() . uniqid() . '_' . $image->getClientOriginalName();
							$image->move(public_path('uploads/service-images'), $filename);
							$filenames = $filename;
						}
					}
					$filenames = ($filenames == '') ? $request->old_image[$i] : $filenames ;
				}else{
					$filenames = $request->old_image[$i] ?? '';
				}
				
				$description = [
					'description' => $request->aditional_description[$i],
					'image' => $filenames
				];
				array_push($aditional_description, $description);
			}
		}
		$service->additional_details = json_encode($aditional_description);
		
        if ($service->save()) {

            // language
            Helper::insertLanguage(Service::class, $service->id, Session::get('admin_language') ?? 'en', 'title', $request->title);
            Helper::insertLanguage(Service::class, $service->id, Session::get('admin_language') ?? 'en', 'short_description', $request->short_description);
            Helper::insertLanguage(Service::class, $service->id, Session::get('admin_language') ?? 'en', 'description', $request->descriptions);

            return response()->json([
                'type' => 'success',
                'message' => 'Service updated successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id){
        $service = Service::find($id);
        if($service){
            if ($service->media != null && file_exists(public_path('uploads/service-images/'.$service->media))) {
                unlink(public_path('uploads/service-images/'.$service->media));
            }
            $service->delete();
            return json_encode(['success' => 'Service deleted successfully.']);
        }else{
            return json_encode(['error' => 'Service not found.']);
        }
    }
}
