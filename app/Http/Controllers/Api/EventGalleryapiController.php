<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\gallery_event;
use App\Models\gallery_image;

class EventGalleryapiController extends Controller
{
    // Fetch list of events
    public function list($event_id)
    {
        try {
            // Fetch the event details by event_id
            $event = Event::select('id', 'name')->where('id', $event_id)->first();
    
            if (!$event) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'Event not found.',
                    'data' => null,
                ]);
            }
    
            // Fetch images associated with the event
            $event_images = gallery_image::where('gallery_events_id', $event_id)->get();
    
            return response()->json([
                'type' => 'success',
                'message' => 'Data retrieved successfully.',
                'data' => [
                    'event' => $event,
                    'images' => $event_images,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Failed to fetch data.',
                'error' => $e->getMessage(),
            ]);
        }
    }
    

    // Store gallery images
    public function store(Request $request)
    {

        // Validate the request
        $request->validate([
            'event_id' => 'required|exists:events,id', // Validate the event ID
            'gallery_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Retrieve the event using the event ID
            $event = Event::findOrFail($request->event_id);

            // Save gallery images
            $images = []; // Store the filenames

            if ($request->hasFile('gallery_image')) {
                foreach ($request->file('gallery_image') as $image) {
                    // Generate a unique filename
                    $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                    
                    // Move the file to the desired location
                    $image->move(public_path('uploads/news-images'), $filename);
                    
                    // Add the filename to the array
                    // $images[] = $filename;
                    $images[] = 'uploads/news-images/' . $filename;
                }
            }

            if (empty($images)) {
                return response()->json([
                    'type' => 'error',
                    'message' => 'No images uploaded.',
                    'data' => null,
                ]);
            }

            // Save the data to the `gallery_image` table
            $eventImage = new gallery_image();
            $eventImage->gallery_events_id = $event->id; // Save the event ID
            $eventImage->name = $event->name; // Save the event name
            $eventImage->image = json_encode($images); // Save filenames as JSON
            $eventImage->save(); 

            return response()->json([
                'type' => 'success',
                'message' => 'Gallery images created successfully.',
                'data' => $eventImage,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
