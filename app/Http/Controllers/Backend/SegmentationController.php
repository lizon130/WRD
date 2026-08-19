<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Segmentation;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class SegmentationController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('segmentation.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $parent_segmentation = Segmentation::all();
        return view('backend.pages.segmentation.index', compact('parent_segmentation'));
    }

    public function getList(Request $request){
        
        $data = Segmentation::query();

        if (!empty($request->parent_segmentation)) {
            $data->where(function($query) use ($request){
                $query->where('ancestor_id', $request->parent_segmentation);
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
                $query->where('name','like', "%" .$request->title ."%" );
            });
        }

        return DataTables::of($data)

        ->editColumn('ancestor_id', function ($row) {
            return ($row->parent)->name ?? '-';
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
            if (Helper::hasRight('segmentation.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('segmentation.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['status','ancestor_id','action'])->make(true);
    }

    public function store(Request $request){
        $validator = $request->validate([
			'name' => 'required',
		]);

        $segmentation = new Segmentation();
        $segmentation->name = ucfirst($request->name);
        $segmentation->ancestor_id = $request->parent_segmentation;
        $segmentation->status  = ($request->status) ? 1 : 0;
        
        if ($segmentation->save()) {
            // language 
            Helper::insertLanguage(Segmentation::class, $segmentation->id, 'en', 'name', $segmentation->name);

            return response()->json([
                'type' => 'success',
                'message' => 'Segment created successfully.',
            ]);
        }
    }

    public function edit($id){
        $single_segmentation = Segmentation::find($id);
        $parent_segmentation = Segmentation::all();
        return view('backend.pages.segmentation.edit', compact('single_segmentation','parent_segmentation'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'name' => 'required',
		]);

        $segmentation = Segmentation::find($id);
        if (Session::get('admin_language') == 'en') {
            $segmentation->name = ucfirst($request->name);
        }
        $segmentation->ancestor_id = $request->parent_segmentation;
        $segmentation->status  = ($request->status) ? 1 : 0;
        if ($segmentation->save()) {
            // language
            Helper::insertLanguage(Segmentation::class, $segmentation->id, Session::get('admin_language') ?? 'en', 'name', $request->name);

            return response()->json([
                'type' => 'success',
                'message' => 'Segmentation updated successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id){
        $segmentation = Segmentation::find($id);
        if($segmentation){
            $segmentation->delete();
            return json_encode(['success' => 'Segmentation deleted successfully.']);
        }else{
            return json_encode(['error' => 'Segmentation not found.']);
        }
    }
}
