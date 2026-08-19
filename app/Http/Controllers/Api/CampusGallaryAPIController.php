<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Helper;
use App\Models\CampusGallary;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Exception;

class CampusGallaryAPIController extends Controller
{

    public function list(Request $request)
    {
        try {
            
            $validator = Validator::make($request->all(), [
                'static_status' => 'required|integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 


            // Start query - fetch only active events
            $campus_gallary = CampusGallary::query();
    
            // Apply filters dynamically
            if ($request->filled('static_status')) {
                $campus_gallary->where('static_status', '=',  $request->input('static_status'));
            }

            // Filter only active events (assuming active status is 1)
            $active_status = 1; 
            $campus_gallary->where('status', $active_status);

    
            // Fetch results and order by created_at (latest first)
            $campus_gallary = $campus_gallary->orderBy('created_at', 'desc')->get();

    
            if ($campus_gallary->isNotEmpty()) {
                return response()->json(['success' => true, 'message' => 'Data found', 'data' => $campus_gallary]);
            }
            
            return response()->json(['success' => true, 'message' => 'No data found', 'data' => []]);
        } catch (Exception $e) {
            Log::error('Error fetching news list: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to fetch gallary list.');
        }
    }

    public function getList(Request $request)
    {
        try {
            $data = CampusGallary::query();

            if ($request->created_at) {
                $data->whereDate('created_at', $request->created_at);
            }

            if (!empty($request->title)) {
                $data->where('title', 'like', "%" . $request->title . "%");
            }
            if (!empty($request->description)) {
                $data->where('description', 'like', "%" . $request->description . "%");
            }
            if (!empty($request->short_description)) {
                $data->where('short_description', 'like', "%" . $request->short_description . "%");
            }
            if ($request->status) {
                $data->where('status', $request->status == 1 ? 1 : 0);
            }


            return Datatables::of($data)
                ->editColumn('short_description', function ($row) {
                    return Str::limit($row->short_description, 40, '...');
                })
                ->editColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-primary w-100">Visible</span>'
                        : '<span class="badge bg-danger w-100">Hidden</span>';
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    if (Helper::hasRight('news.edit')) {
                        $btn .= '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
                    }
                    if (Helper::hasRight('news.delete')) {
                        $btn .= '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['short_description', 'status', 'action'])
                ->make(true);
                } catch (Exception $e) {
                    Log::error('Error fetching news list: ' . $e->getMessage());
                    return response()->json(['error' => 'Unable to fetch data.'], 500);
                }
    }


    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'required|string',
                'short_description' => 'required|string',
                'status' => 'nullable|integer',
                'image' => 'required|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
                'static_status' => 'required|integer',
            ], [
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'short_description.required' => 'Short description is required.',
                'image.image' => 'The uploaded file must be an image.',
                'image.mimes' => 'Allowed image formats: jpg, jpeg, png, gif, webp.',
                'image.max' => 'Image size should not exceed 2MB.',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create new gallery record
            $campus_gallary = new CampusGallary();
            $campus_gallary->fill($request->only([
                'title',
                'description',
                'short_description',
                'static_status'
            ]));

            $campus_gallary->status = $request->status ?? 1; // Default status to 1 if not provided

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $directory = public_path('uploads/campusgallary-images');

                // Ensure directory exists
                File::ensureDirectoryExists($directory, 0755, true);

                // Generate a unique filename
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($directory, $filename);
                $campus_gallary->image = 'uploads/campusgallary-images/' . $filename;
            }

            // Save to database
            $campus_gallary->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Gallery data created successfully.',
                'data' => $campus_gallary
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'type' => 'error',
                'message' => 'Unable to create gallery data. Please try again later.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function details($id)
    {
        try {
            $campus_gallary = CampusGallary::find($id);

            if (!$campus_gallary) {
                return response()->json(['success' => false, 'message' => 'Gallery info not found.'], 404);
            }

            return response()->json(['success' => true, 'data' => $campus_gallary], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $campus_gallary = CampusGallary::findOrFail($id);
            return view('backend.pages.campus_gallary.edit', compact('campus_gallary'));
        } catch (\Exception $e) {
            Log::error('Error fetching gallery info for editing: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gallery info not found.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
                'description' => 'required|string',
                'short_description' => 'required|string',
                'status' => 'nullable|integer',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
                'static_status' => 'nullable|integer',
            ], [
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'short_description.required' => 'Short description is required.',
                'image.image' => 'The uploaded file must be an image.',
                'image.mimes' => 'Allowed image formats: jpg, jpeg, png, gif, webp.',
                'image.max' => 'Image size should not exceed 2MB.',
            ]);

            // Check if validation fails
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                ], 422);
            }

            $campus_gallary = CampusGallary::findOrFail($id);
            $campus_gallary->fill($request->only([
                'title',
                'description',
                'short_description',
                'status',
                'static_status'
            ]));

            if ($request->hasFile('image')) {
                $directory = public_path('uploads/campusgallary-images');

                // Ensure directory exists
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true, true);
                }

                // Delete old image if exists
                if (!empty($campus_gallary->image) && File::exists(public_path($campus_gallary->image))) {
                    File::delete(public_path($campus_gallary->image));
                }

                $image = $request->file('image');
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move($directory, $filename);
                $campus_gallary->image = 'uploads/campusgallary-images/' . $filename;
            }

            $campus_gallary->save();

            return response()->json([
                'type' => 'success',
                'message' => 'Gallery updated successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating gallery: ' . $e->getMessage());
            return response()->json([
                'type' => 'error',
                'message' => 'Unable to update gallery. Please try again later.',
            ], 500);
        }
    }


    public function delete($id)
    {
        try {
            $campus_gallary = CampusGallary::findOrFail($id);

            if ($campus_gallary->media && file_exists(public_path('uploads/campusgallary-images/' . $campus_gallary->image))) {
                unlink(public_path('uploads/campusgallary-images/' . $campus_gallary->image));
            }

            $campus_gallary->delete();

            return response()->json(['success' => 'Gallary info deleted successfully.']);
        } catch (Exception $e) {
            Log::error('Error deleting news: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to delete gallary info.'], 500);
        }
    }

    public function searchFilter(Request $request){
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
                'short_description' => 'nullable|string|max:255',
                'static_status' => 'required|integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 
    
            // Start query - fetch only active events
            $campus_gallary = CampusGallary::query();
    
            // Apply filters dynamically
            if ($request->filled('static_status')) {
                $campus_gallary->where('static_status', '=',  $request->input('static_status'));
            }
            
            if ($request->filled('title')) {
                $campus_gallary->where('title', 'LIKE', '%' . $request->input('title') . '%');
            }

            if ($request->filled('description')) {
                $campus_gallary->where('description', 'LIKE', '%' . $request->input('description') . '%');
            }
            
            if ($request->filled('short_description')) {
                $campus_gallary->where('short_description', 'LIKE', '%' . $request->input('short_description') . '%');
            }


            
            // Filter only active events (assuming active status is 1)
            $active_events = 1; 
            $campus_gallary->where('status', $active_events);

    
            // Fetch results and order by created_at (latest first)
            $campus_gallary = $campus_gallary->orderBy('created_at', 'desc')->get();

    
            if ($campus_gallary->isNotEmpty()) {
                return response()->json(['success' => true, 'message' => 'Data found', 'data' => $campus_gallary]);
            }
            
            return response()->json(['success' => true, 'message' => 'No data found', 'data' => []]);
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    
}
