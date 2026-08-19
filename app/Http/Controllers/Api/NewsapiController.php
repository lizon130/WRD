<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\News;
use Illuminate\Support\Str;
use Session;
use Illuminate\Support\Facades\Log;
use Exception;

class NewsapiController extends Controller
{

    public function list()
    {
        try {
            $news = News::where('category', 'news')->get();
            return response()->json(['success' => true, 'data' => $news], 200);
        } catch (Exception $e) {
            Log::error('Error fetching news list: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to fetch news list.');
        }
    }

    public function getList(Request $request)
    {
        try {
            $data = News::query();

            if ($request->date) {
                $data->whereDate('publish_date', $request->date);
            }

            if (!empty($request->title)) {
                $data->where('title', 'like', "%" . $request->title . "%");
            }

            if ($request->status) {
                $data->where('status', $request->status == 1 ? 1 : 0);
            }

            if ($request->is_featured) {
                $data->where('is_featured', $request->is_featured == 1 ? 1 : 0);
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
            $validator = $request->validate([
                'title' => 'required',
                'category' => 'required',
                'publish_date' => 'required',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
                'gallery_image.*' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
                'is_featured' => 'nullable',
                'read_time' => 'nullable|string',
            ]);

            $news = new News();
            $news->fill($request->only([
                'publish_date',
                'title',
                'category',
                'short_description',
                'status',
                'description',
                'url',
                'is_featured',
                'read_time'
            ]));

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . uniqid() . $image->getClientOriginalName();
                $image->move(public_path('uploads/news-images'), $filename);
                $news->media = 'uploads/news-images/' . $filename;
            }
            $news->save();

        } catch (Exception $e) {
            Log::error('Error storing news: ' . $e->getMessage());
            return response()->json([
                'type' => 'error',
                'message' => 'Unable to create news. Please try again later.',
            ], 500);
        }
    }

    public function details($id)
    {
        try {
            $news = News::find($id);

            if (!$news) {
                return response()->json(['success' => false, 'message' => 'News not found.'], 404);
            }

            return response()->json(['success' => true, 'data' => $news], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $news = News::findOrFail($id);
            return view('backend.pages.news.edit', compact('news'));
        } catch (Exception $e) {
            Log::error('Error fetching news for editing: ' . $e->getMessage());
            return redirect()->back()->with('error', 'News not found.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = $request->validate([
                'title' => 'required',
                'publish_date' => 'required',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
                'gallery_image.*' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
                'is_featured' => 'nullable',
                'read_time' => 'nullable|string',
            ]);

            $news = News::findOrFail($id);
            $news->fill($request->only([
                'publish_date',
                'title',
                'category',
                'short_description',
                'status',
                'description',
                'url',
                'is_featured',
                'read_time'
            ]));

            if ($request->hasFile('image')) {
                if (file_exists(public_path('uploads/news-images/' . $news->media))) {
                    unlink(public_path('uploads/news-images/' . $news->media));
                }
                $image = $request->file('image');
                $filename = time() . uniqid() . $image->getClientOriginalName();
                $image->move(public_path('uploads/news-images'), $filename);
                $news->media = $filename;
            }

            if ($news->save()) {
                Helper::insertLanguage(News::class, $news->id, 'en', 'title', $request->title);
                Helper::insertLanguage(News::class, $news->id, 'en', 'short_description', $request->short_description);
                Helper::insertLanguage(News::class, $news->id, 'en', 'description', $request->description);

                return response()->json([
                    'type' => 'success',
                    'message' => 'News updated successfully.',
                ]);
            }
        } catch (Exception $e) {
            Log::error('Error updating news: ' . $e->getMessage());
            return response()->json([
                'type' => 'error',
                'message' => 'Unable to update news. Please try again later.',
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $news = News::findOrFail($id);

            if ($news->media && file_exists(public_path('uploads/news-images/' . $news->media))) {
                unlink(public_path('uploads/news-images/' . $news->media));
            }

            $news->delete();

            return response()->json(['success' => 'News deleted successfully.']);
        } catch (Exception $e) {
            Log::error('Error deleting news: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to delete news.'], 500);
        }
    }

    public function searchFilter(Request $request){
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:255',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 
    
            // Start query - fetch only active events
            $events = News::query();
    
            // Apply filters dynamically
            if ($request->filled('title')) {
                $events->where('title', 'LIKE', '%' . $request->input('title') . '%');
            }

            
            // Filter only active events (assuming active status is 1)
            $active_events = 1; 
            $events->where('status', $active_events);

    
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
    


    public function upcomingNewsList(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'is_upcoming' => 'nullable|integer',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 

                        
            // Start building the query
            $events = News::query();

            // Apply filters dynamically
            if (!empty($request->input('is_upcoming'))) {
                $events->where('is_upcoming', '=',  $request->input('is_upcoming'));
            }

            // Fetch only upcoming news (from today onwards)
            $events->whereDate('created_at', '>=', now());

            
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


    public function featuredNewsList(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'is_featured' => 'required|integer',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 
    
            // Start building the query
            $events = News::query(); // Only fetch active events
    
            // Apply filter if `is_featured` is provided
            if ($request->filled('is_featured')) {
                $events->where('is_featured', '=', $request->input('is_featured'));
            }
    
            // Filter only active events (assuming active status is 1)
            $active_events = 1; 
            $events->where('status', $active_events);
 
            // Get results
            $events = $events->orderBy('created_at', 'desc')->get();
    
            if ($events->isNotEmpty()) {
                return response()->json(['success' => true, 'message' => 'Data found', 'data' => $events]);
            }
            
            return response()->json(['success' => true, 'message' => 'No data found', 'data' => []]);    
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    

    public function recentNewsList(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'created_at' => 'nullable|date',
                'updated_at' => 'nullable|date',
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            } 
    
            // Get today's date and calculate the past 7 days
            $sevenDaysAgo = now()->subDays(7)->format('Y-m-d');
    
            // Start building the query
            $events = News::query();
    
            // Filter for the last 7 days
            $events->where('created_at', '>=', $sevenDaysAgo);
    
            // Apply additional filters dynamically
            if (!empty($request->input('created_at'))) {
                $events->where('created_at', '>=', $request->input('created_at'));
            }
            if (!empty($request->input('updated_at'))) {
                $events->where('updated_at', '<=', $request->input('updated_at'));
            }

            // Filter only active events (assuming active status is 1)
            $active_events = 1; 
            $events->where('status', $active_events);
 
    
    
            // Get results
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
