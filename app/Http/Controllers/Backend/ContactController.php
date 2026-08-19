<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('contact.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $contacts = Contact::all();
        return view('backend.pages.contact.index', compact('contacts'));
    }

    public function getList(Request $request){

        $data = Contact::query();
        return Datatables::of($data)

        ->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="badge bg-primary w-100">Visible</span>';
            }else{
                return '<span class="badge bg-danger w-100">Hidden</span>';
            }
        })

        ->editColumn('is_default', function ($row) {
            if ($row->is_default == 1) {
                return '<span class="badge bg-warning w-100">Default</span>';
            }
        })

        ->addColumn('action', function ($row) {
            $btn = '';
            if (Helper::hasRight('contact.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('contact.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['is_default','status','action'])->make(true);
    }

    public function store(Request $request){
        $validator = $request->validate([
			'title' => 'required|string|max:500',
			'email' => 'required|email|max:50',
            'type' => 'required|string',
		], [
            'title.required' => 'Title is required.',
            'title.string' => 'Title must be a string.',
            'title.max' => 'Title should not exceed 500 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email is not valid.',
            'email.max' => 'Email should not exceed 50 characters.',
            'type.required' => 'Type is required.',
        ]);

        $contact = new Contact();
        $contact->title = $request->title ;
        $contact->phone = $request->phone ?? '';
        $contact->email = $request->email ?? '';
        $contact->type = $request->type ?? null;
        $contact->toll_free = $request->toll_free ?? '';
        $contact->fax = $request->fax ?? '';
        $contact->address = $request->address ?? '';
        $contact->google_map = $request->google_map ?? '';
        $contact->status = ($request->status) ? 1 : 0;
        $contact->is_default = ($request->is_default) ? 1 : 0;
        if ($contact->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Contact created successfully.',
            ]);
        }
        return response()->json([
            'type' => 'success',
            'message' => 'Contact store failed.',
        ]);
    }

    public function edit($id){
        $contact = Contact::find($id);
        return view('backend.pages.contact.edit', compact('contact'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'title' => 'required|string|max:500',
			'email' => 'required|email|max:50',
            'type' => 'required|string',
		], [
            'title.required' => 'Title is required.',
            'title.string' => 'Title must be a string.',
            'title.max' => 'Title should not exceed 500 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email is not valid.',
            'email.max' => 'Email should not exceed 50 characters.',
            'type.required' => 'Type is required.',
        ]);

        $contact = Contact::find($id);
        $contact->title = $request->title;
        $contact->type  = $request->type  ?? null;
        $contact->phone = $request->phone ?? '';
        $contact->email = $request->email ?? '';
        $contact->toll_free = $request->toll_free ?? '';
        $contact->fax = $request->fax ?? '';
        $contact->address = $request->address ?? '';
        $contact->google_map = $request->google_map ?? '';
        $contact->status = ($request->status) ? 1 : 0;
        $contact->is_default = ($request->is_default) ? 1 : 0;
       
        if ($contact->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Contact updated successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => 'Contact updation failed.',
            ]);
        }
    }

    public function delete($id){
        $contact = Contact::find($id);
        if($contact){
            $contact->delete();
            return json_encode(['success' => 'Contact deleted successfully.']);
        }else{
            return json_encode(['error' => 'Contact not found.']);
        }
    }
}
