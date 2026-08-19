<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\gallery_event;
use App\Models\gallery_image;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GalleryEventController extends Controller
{
    public function index(){
        $event_title = Event::select('id', 'name')->get();
        return view('backend.pages.gallery_events.index', compact('event_title'));
    }

    public function getList(Request $request){
        
        $data = gallery_image::query();

        if (!empty($request->company)) {
            $data->where(function($query) use ($request){
                $query->where('company', $request->company);
            });
        }

    
        if (!empty($request->name)) {
            $data->where(function($query) use ($request){
                $query->where('name','like', "%" .$request->name ."%" );
            });
        }

        return Datatables::of($data)

        ->editColumn('image', function ($row) {
            if ($row->image) {
                // Decode the JSON-encoded images
                $images = json_decode($row->image, true);
                $html = '';
                if (is_array($images)) {
                    foreach ($images as $image) {
                        $imagePath = asset('uploads/news-images/' . $image);
                        $html .= '<a href="' . $imagePath . '" target="_blank">
                                    <img class="profile-img" src="' . $imagePath . '" alt="Image" style="width: 50px; height: 50px; margin-right: 5px;">
                                  </a>';
                    }
                }
                return $html;
            }
            return '<img class="profile-img" src="' . asset('assets/img/no-img.jpg') . '" alt="No image" style="width: 50px; height: 50px;">';
        })
        


        // ->editColumn('status', function ($row) {
        //     if ($row->status == 1) {
        //         return '<span class="badge bg-primary w-75">Active</span>';
        //     }else{
        //         return '<span class="badge bg-danger w-75">Inactive</span>';
        //     }
        // })

        ->addColumn('action', function ($row) {
            $btn = '';
          
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
          
            
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
        
            return $btn;
        })
        ->rawColumns(['image','is_parent','status','parent_category','action'])->make(true);
    }

    public function store(Request $request)
    {
        // Validate the request
        $validator = $request->validate([
            'event_id' => 'required|exists:events,id', // Validate the event ID
            'gallery_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Retrieve the event using the event ID
        $event = Event::find($request->event_id);

        if (!$event) {
            return response()->json([
                'type' => 'error',
                'message' => 'Event not found.',
            ]);
        }

        // Save gallery images
        $images = [];
        if ($request->hasFile('gallery_image')) {
            foreach ($request->file('gallery_image') as $image) {
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/news-images'), $filename);
                $images[] = $filename;
            }
        }

        // Save the data to the `gallery_image` table
        $eventImage = new gallery_image();
        $eventImage->gallery_events_id = $event->id; // Save the event ID
        $eventImage->name = $event->name; // Save the event name
        $eventImage->image = json_encode($images); // Save filenames as JSON

        if ($eventImage->save()) {
            return response()->json([
                'type' => 'success',
                'message' => 'Gallery images created successfully.',
            ]);
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Something went wrong.',
        ]);
    }

}
