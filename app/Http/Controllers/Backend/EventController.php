<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\Event;
use Illuminate\Http\Request;
use Yajra\DataTables\Utilities\Helper;
use Yajra\DataTables\DataTables;

class EventController extends Controller
{
    public function index(){
        // $is_upcoming_events = [
        //     '1' => 'Yes',
        //     '0' => 'No',
        // ];
        // return view('backend.pages.event.index', compact('is_upcoming_events'));
        return view('backend.pages.event.index');
    }

    public function getList(Request $request){
        
        $data = Event::query();

        if (!empty($request->company)) {
            $data->where(function($query) use ($request){
                $query->where('company', $request->company);
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

        if (!empty($request->name)) {
            $data->where(function($query) use ($request){
                $query->where('name','like', "%" .$request->name ."%" );
            });
        }

        return Datatables::of($data)

        ->editColumn('image', function ($row) {
            return ($row->image) 
                ? '<a href="'.asset($row->image).'" target="_blank"><img class="profile-img" src="'.asset($row->image).'" alt="profile image"></a>' 
                : '<img class="profile-img" src="'.asset('assets/img/no-img.jpg').'" alt="profile image">';
        })


        // ->editColumn('id', function ($row) {
        //     return $row->company->name ?? 'N/A';
        // })

        // ->editColumn('is_parent', function ($row) {
        //     return ($row->is_parent == 1) ? '<span class="badge bg-gray text-dark w-70">Yes</span>' : '<span class="badge bg-gray text-dark w-70">No</span>';
        // })

        // ->editColumn('parent_category', function ($row) {
        //     return ($row->parent)->title ?? '-';
        // })

        ->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="badge bg-primary w-75">Active</span>';
            }else{
                return '<span class="badge bg-danger w-75">Inactive</span>';
            }
        })

        ->addColumn('action', function ($row) {
            $btn = '';
          
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
          
            
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
        
            return $btn;
        })
        ->rawColumns(['image','is_parent','status','parent_category','action'])->make(true);
    }

    public function store(Request $request){
        $validator = $request->validate([
			'name' => 'required',
            'typeof' =>'required',
			'details' => 'required',
            'image' => 'nullable|image:png,jpg,jpeg,gif,webp,',
            'published_user' => 'nullable|integer|exists:user,id',
            'speakers_info' => 'nullable|array',
            'workshops_info' => 'nullable|array',
            'networks_info' => 'nullable|array',
		], [
            'published_user.exists' => 'User does not exist, Can not create info',
        ]);

        $event = new Event();
        $event->name = ucfirst($request->name);
        $event->sub_nameor_title = ucfirst($request->sub_nameor_title);
        $event->type = $request->typeof;
        $event->details = $request->details;
        $event->company = $request->company;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->speakers_info = json_encode($request->speakers_info ?? []);
        $event->workshops_info = json_encode($request->workshops_info ?? []);
        $event->networks_info = json_encode($request->networks_info ?? []);
        $event->status  = ($request->status) ? 1 : 0;
        // $event->is_upcoming  = ($request->is_upcoming) ? 1 : 0;
        $event->is_featured  = ($request->is_featured) ? 1 : 0;
        $event->published_user = $request->published_user ? $request->published_user : null;


        $directory = "uploads/event-images/";
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path($directory), $filename);
            $event->image = $directory . $filename; // Store the full relative path
        }

        // Handling multiple event images
        if ($request->hasFile('event_images')) {
            $eventImages = $request->file('event_images');
            $imageNames = [];

            // Ensure eventImages is always an array
            $eventImages = is_array($eventImages) ? $eventImages : [$eventImages];

            foreach ($eventImages as $image) {
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($directory), $filename);
                $imageNames[] = $directory . $filename; // Store path in an array
            }

            // Merge with existing images if needed
            if (!empty($event->event_images)) {
                $existingImages = json_decode($event->event_images, true) ?? [];
                $imageNames = array_merge($existingImages, $imageNames);
            }

            // Always store as JSON array in longtext column
            $event->event_images = json_encode($imageNames);
        }


        if ($event->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Event created successfully.',
            ]);
        }
    }
    
    public function edit($id)
    {
        // Fetch the specific event by its ID
        $event = Event::findOrFail($id);

        // Pass the event data to the view
        return view('backend.pages.event.edit', compact('event'));
    }


    public function update(Request $request, $id)
    {
        // Validate the input data
        $validator = $request->validate([
            'name' => 'required',
            'typeof' => 'required',
            'details' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048', // Max 2MB
            'published_user' => 'nullable|string|exists:user,id',
            'speakers_info' => 'nullable|array',
            'workshops_info' => 'nullable|array',
            'networks_info' => 'nullable|array',
            'event_images.*' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048' 
        ], [
            'published_user.exists' => 'User does not exist, cannot create info',
            'event_images.*.image' => 'Each file must be an image',
            'event_images.*.mimes' => 'Each image must be in PNG, JPG, JPEG, GIF, or WEBP format',
            'event_images.*.max' => 'Each image must not exceed 2MB',
        ]);
        

        // Find the existing event by ID
        $event = Event::findOrFail($id);


        // Update the event details
        $event->name = ucfirst($request->name);
        $event->sub_nameor_title = ucfirst($request->sub_nameor_title);
        $event->type = $request->typeof;
        $event->details = $request->details;
        $event->company = $request->company;
        $event->start_date = $request->start_date;
        $event->end_date = $request->end_date;
        $event->start_time = $request->start_time;
        $event->end_time = $request->end_time;
        $event->speakers_info = json_encode($request->speakers_info ?? []);
        $event->workshops_info = json_encode($request->workshops_info ?? []);
        $event->networks_info = json_encode($request->networks_info ?? []);
    
        $event->status = ($request->status) ? 1 : 0;
        // $event->is_upcoming  = ($request->is_upcoming) ? 1 : 0;
        $event->is_featured  = ($request->is_featured) ? 1 : 0;
        // $event->published_user = $request->published_user ? $request->published_user : null;


        

        $directory = "uploads/event-images/";

        // Handling the main event image
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($event->image && file_exists(public_path($event->image))) {
                unlink(public_path($event->image));
            }

            // Store the new image
            $image = $request->file('image');
            $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path($directory), $filename);
            $event->image = $directory . $filename; // Store the full relative path
        }

        // Handling multiple event images
        if ($request->hasFile('event_images')) {
            $eventImages = $request->file('event_images');
            $imageNames = [];

            // Ensure eventImages is always an array
            $eventImages = is_array($eventImages) ? $eventImages : [$eventImages];

            foreach ($eventImages as $image) {
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path($directory), $filename);
                $imageNames[] = $directory . $filename; // Store path in an array
            }

            // Merge with existing images if needed
            if (!empty($event->event_images)) {
                $existingImages = json_decode($event->event_images, true) ?? [];
                $imageNames = array_merge($existingImages, $imageNames);
            }

            // Always store as JSON array in longtext column
            $event->event_images = json_encode($imageNames);
        }

        // Save the updated event data
        if ($event->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Event updated successfully.',
            ]);
        } else {
            return response()->json([
                'type' => 'error',
                'message' => 'Failed to update the event.',
            ]);
        }
    }


    public function delete($id){
        $event = Event::find($id);
        $directory = "uploads/event-images/";
        if($event){
            if ($event->image != null && file_exists(public_path($directory . $event->image))) {
                unlink(public_path('uploads/event-images/'.$event->image));
            }
             // Delete multiple images from dept_gallary_images
             if (!empty($event->event_images)) {
                $galleryImages = json_decode($event->event_images, true);
                if (is_array($galleryImages)) {
                    foreach ($galleryImages as $image) {
                        if (file_exists(public_path($image))) {
                            unlink(public_path($image));
                        }
                    }
                }
            }
            $status  = $event->delete();
            if($status){
                return json_encode(['success' => 'Event deleted successfully.']);
            } else{
                return json_encode(['error' => 'Failed to delete the event.']);
            }
            
        }else{
            return json_encode(['error' => 'event not found.']);
        }
    }
}
