<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class ContactUsAPIController extends Controller
{
    public function list(Request $request)
    {
        try {
        
            // query 
            $contact_info = Contact::orderBy('created_at', 'DESC')->get();

            // Check if data exists
            if ($contact_info->isNotEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data found',
                    'data' => $contact_info
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'No data found',
                'data' => []
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:100',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:500',
            ], [
                'name.required' => 'Name is required.',
                'name.max' => 'Name should not exceed 255 characters.',
                
                'email.required' => 'Email is required.',
                'email.email' => 'Please enter a valid email address.', 
                'email.max' => 'Email should not exceed 255 characters.',
                
                'subject.required' => 'Subject is required.',
                'subject.max' => 'Subject should not exceed 255 characters.',
                
                'message.required' => 'Message is required.',
                'message.max' => 'Message should not exceed 500 characters.',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            
            
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            

            // check if the passed email exist in  user database
            $user_email = $request->email;
            $user = User::where('email', $user_email)->first();
            if ($user) {    
                $contact_info = new Contact();
                $contact_info->title = $request->title ?? null;
                $contact_info->name = $request->name ? $request->name : null;
                $contact_info->email = $request->email ? $request->email : null;
                $contact_info->subject = $request->subject ? $request->subject : null;
                $contact_info->message = $request->message ? $request->message : null;
                $contact_info->toll_free = $request->toll_free ?? null;
                $contact_info->fax = $request->fax ?? null;
                $contact_info->address = $request->address ?? null;
                $contact_info->google_map = $request->google_map ?? null;
                $contact_info->status = ($request->status) ? 1 : 0;
                $contact_info->is_default = ($request->is_default) ? 1 : 0;
                $contact_info->user_id = $user->id ?? null; 
            } else {
                $contact_info = new Contact();
                $contact_info->name = $request->name ? $request->name : null;
                $contact_info->title = $request->title ?? null;
                $contact_info->email = $request->email ? $request->email : null;
                $contact_info->subject = $request->subject ? $request->subject : null;
                $contact_info->message = $request->message ? $request->message : null;
                $contact_info->toll_free = $request->toll_free ?? null;
                $contact_info->fax = $request->fax ?? null;
                $contact_info->address = $request->address ?? null;
                $contact_info->google_map = $request->google_map ?? null;
                $contact_info->status = ($request->status) ? 1 : 0;
                $contact_info->is_default = ($request->is_default) ? 1 : 0;
                $contact_info->user_id = $user->id ?? null; 
            }

            $status = $contact_info->save();
            if($status){
                return response()->json(['success' => true, 'message' => 'Contact info sent successfully.', 'data' => $contact_info], 201);
            } else{
                return response()->json(['success' => false, 'message' => 'Failed to send contact info.'], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function searchFilter(Request $request){
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:255',
                'type' => 'required|string|max:30',
            ], [
               
                'title.max' => 'Title should not exceed 255 characters.',
                'type.required' => 'Type is required.',
                'type.max' => 'Type should not exceed 30 characters.',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 
    
            // Start query - fetch only active events
            $events = Contact::query();
    
            // Apply filters dynamically
            if ($request->filled('title')) {
                $events->where('title', 'LIKE', '%' . $request->input('title') . '%');
            }

            if ($request->filled('type')) {
                $events->where('type', '=',  $request->input('type') );
            }

            
            // Filter only active events (assuming active status is 1)
            // $active_events = 1; 
            // $events->where('status', $active_events);

    
            // Fetch results and order by created_at (latest first)
            $events = $events->orderBy('created_at', 'desc')->get();

    
            if ($events->isNotEmpty()) {
                return response()->json(['success' => true, 'message' => 'Data found', 'data' => $events]);
            }
            
            return response()->json(['success' => true, 'message' => 'No data found', 'data' => []]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
