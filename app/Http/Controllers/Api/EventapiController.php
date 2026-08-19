<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EventapiController extends Controller
{
    // public function list()
    // {
    //     try {
    //         $events = Event::all();
    //         return response()->json(['success' => true, 'data' => $events], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    //     }
    // }

    public function list(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Query Builder for Event model
            $events = Event::query()
                ->when($request->filled('type'), function ($query) use ($request) {
                    $query->where('type', $request->input('type'));
                })
                ->where('status', 1) // Only fetch active events
                ->orderBy('start_date', 'desc')
                ->get(); // Fetch results

            // Check if data exists
            if ($events->isNotEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data found',
                    'data' => $events
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
                // 'type' => 'required|string|in:Event,Blog,Alumni,Forum',
                'type' => 'required|string',
                'name' => 'required|string|max:255',
                'details' => 'required|string',
                'image' => 'nullable|string',
                'company' => 'nullable|string|max:255',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'status' => 'sometimes|boolean',
                'is_featured' => 'sometimes|boolean',
                // 'is_upcoming' => 'sometimes|boolean', // Uncomment if needed
                'published_user' => 'nullable|integer|exists:users,id',
            ], [
                'published_user.exists' => 'User does not exist, cannot create info.',
                // 'type.in' => 'Type must be one of the following: Event, Blog, Alumni, Forum.',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $event = new Event();
            $event->fill($request->except('image'));
            $event->type = $request->type ? $request->type : null;
            $event->status = $request->status ?? 0;
            $event->is_featured = $request->is_featured?? 0;
            // $event->is_upcoming = $request->is_upcoming?? 0;
            $event->published_user = $request->published_user ? $request->published_user : null;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/event-images'), $filename);
                $event->image = 'uploads/event-images/' . $filename;
            }

            $event->save();

            return response()->json(['success' => true, 'message' => 'Event created successfully.', 'data' => $event], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function details($id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return response()->json(['success' => false, 'message' => 'Event not found.'], 404);
            }

            return response()->json(['success' => true, 'data' => $event], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return response()->json(['success' => false, 'message' => 'Event not found.'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'details' => 'required|string',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
                'company' => 'nullable|string|max:255',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
                'status' => 'boolean',
                'is_featured' => 'boolean',
                // 'is_upcoming' => 'boolean',
                'published_user' => 'nullable|integer|exists:user,id',
            ],[
                'published_user.exists' => 'User does not exist, Can not create info',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }

            $event->fill($request->except('image'));
            $event->status = $request->status ?? $event->status;
            $event->is_featured = $request->is_featured?? 0;
            // $event->is_upcoming = $request->is_upcoming?? 0;
            $event->published_user = $request->published_user ? $request->published_user : null;

            if ($request->hasFile('image')) {
                if ($event->image && file_exists(public_path($event->image))) {
                    unlink(public_path($event->image));
                }

                $image = $request->file('image');
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/event-images'), $filename);
                $event->image = 'uploads/event-images/' . $filename;
            }

            $event->save();

            return response()->json(['success' => true, 'message' => 'Event updated successfully.', 'data' => $event], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $event = Event::find($id);

            if (!$event) {
                return response()->json(['success' => false, 'message' => 'Event not found.'], 404);
            }

            if ($event->image && file_exists(public_path($event->image))) {
                unlink(public_path($event->image));
            }

            $event->delete();

            return response()->json(['success' => true, 'message' => 'Event deleted successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }


    public function searchFilter(Request $request){
        try {

            
            // Validate input
            $validator = Validator::make($request->all(), [
                'type' => 'required|string',
                'name' => 'nullable|string|max:255',
                // 'location' => 'nullable|string|max:255',
                'company' => 'nullable|string|max:255',
                'start_date' => 'nullable|date',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 

    
            // Start query - fetch only active events
            $events = Event::query();
    
            // Apply filters dynamically
            if ($request->filled('type')) {
                $events->where('type', '=',  $request->input('type'));
            }
            if ($request->filled('name')) {
                // $events->where('type', 'LIKE', '%' . $request->input('type') . '%');
                $events->where('name', '=',  $request->input('name'));
            }
            // location == company in database   
            // if ($request->filled('location')) {
            //     $events->where('company', '=',  $request->input('location'));
            // }

            if ($request->filled('company')) {
                $events->where('company', '=',  $request->input('company'));
            }
            
            if ($request->filled('start_date')) {
                $events->whereDate('start_date', '=', $request->input('start_date'));
            }

            // if ($request->filled('created_at')) {
            //     $events->whereDate('created_at', '=', $request->input('created_at'));
            // }
            
            // Filter only active events (assuming active status is 1)
            $active_events = 1; 
            $events->where('status', $active_events);

    
            // Fetch results and order by start_date (latest first)
            $events = $events->orderBy('start_date', 'desc')->get();

    
            if ($events->isNotEmpty()) {
                return response()->json(['success' => true, 'message' => 'Data found', 'data' => $events]);
            }
            
            return response()->json(['success' => true, 'message' => 'No data found', 'data' => []]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    


    public function upcomingEventsList(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string',
                'is_upcoming' => 'nullable|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 

                        
            // Start building the query
            $events = Event::query();

            // Apply filters dynamically
            // if (!empty($request->input('is_upcoming'))) {
            //     $events->where('is_upcoming', '=',  $request->input('is_upcoming'));
            // }

            if ($request->filled('type')) {
                $events->where('type', '=',  $request->input('type'));
            }

            // Fetch only upcoming news (from today onwards)
            $events->whereDate('start_date', '>=', now());

            
            // Filter only active events (assuming active status is 1)
            $active_events = 1; 
            $events->where('status', $active_events);


            // Get results
            $events = $events->get();

            if(!empty($events)){
                return response()->json(['success' => true, 'message' => 'Data found', 'data' => $events]);
            }
            return response()->json(['success' => true, 'message' => 'No data found', 'data' => []]);    
        } catch (\Exception $e){
            return response()->json(['success' => false,'message' => $e->getMessage()], 500);
        }
    }


    public function featuredEventsList(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string',
                'is_featured' => 'required|integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 
    
            // Start building the query
            $events = Event::query(); // Only fetch active events
    
            // Apply filter
            if ($request->filled('type')) {
                $events->where('type', '=',  $request->input('type'));
            }
            if ($request->filled('is_featured')) {
                $events->where('is_featured', '=', $request->input('is_featured'));
            }
    
            // Filter only active events (assuming active status is 1)
            $active_events = 1; 
            $events->where('status', $active_events);
 
            // Get results
            $events = $events->orderBy('start_date', 'desc')->get();
    
            if ($events->isNotEmpty()) {
                return response()->json(['success' => true, 'message' => 'Data found', 'data' => $events]);
            }
            
            return response()->json(['success' => true, 'message' => 'No data found', 'data' => []]);    
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    

    public function recentEventsList(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required|string',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 
    
            // Get today's date and calculate the past 7 days
            $sevenDaysAgo = now()->subDays(7)->format('Y-m-d');
    
            // Start building the query
            $events = Event::query();
    
            
            // Filter for the last 7 days
            $events->where('start_date', '>=', $sevenDaysAgo);
    
            // Apply additional filters dynamically
            if ($request->filled('type')) {
                $events->where('type', '=',  $request->input('type'));
            }
            if (!empty($request->input('start_date'))) {
                $events->where('start_date', '>=', $request->input('start_date'));
            }
            if (!empty($request->input('end_date'))) {
                $events->where('end_date', '<=', $request->input('end_date'));
            }

            // Filter only active events (assuming active status is 1)
            $active_events = 1; 
            $events->where('status', $active_events);
 
    
    
            // Get results
            $events = $events->orderBy('start_date', 'desc')->get();
    
            if ($events->isNotEmpty()) {
                return response()->json(['success' => true, 'message' => 'Data found', 'data' => $events]);
            }
            return response()->json(['success' => true, 'message' => 'No data found', 'data' => []]);    
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
}
