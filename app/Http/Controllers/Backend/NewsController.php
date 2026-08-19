<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Auth;
use Helper;
use App\Models\News;
use Illuminate\Support\Str;
use Session;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('news.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $newss = News::all();
        return view('backend.pages.news.index', compact('newss'));
    }

    public function getList(Request $request){

        $data = News::query();
        
        if ($request->date) {
            $data->whereDate('publish_date', $request->date);
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
            return Str::limit($row->short_description, 40, '...');
        })

        ->editColumn('status', function ($row) {
            if ($row->status == 1) {
                return '<span class="badge bg-primary w-100">Visible</span>';
            }else{
                return '<span class="badge bg-danger w-100">Hidden</span>';
            }
        })

        ->addColumn('action', function ($row) {
            $btn = '';
            if (Helper::hasRight('news.edit')) {
                $btn = $btn . '<a href="" data-id="'.$row->id.'" class="edit_btn btn btn-sm btn-primary "><i class="fa-solid fa-pencil"></i></a>';
            }
            if (Helper::hasRight('news.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['short_description','status','action'])->make(true);
    }

    public function store(Request $request){
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
        $news->publish_date = $request->publish_date;
        $news->title = $request->title;
        $news->category = $request->category;
        $news->short_description = $request->short_description;
        $news->status = ($request->status) ? 1 : 0;
        $news->is_featured = ($request->is_featured) ? 1 : 0;
        $news->description = $request->descriptions;
        $news->url = $request->url;
        $news->read_time = $request->read_time;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path('uploads/news-images'), $filename);
            $news->media = 'uploads/news-images/' . $filename;
    
        }
		
		// if($request->hasFile('gallery_image')){
        //     $gallerys = $request->file('gallery_image');
        //     $image_name = [];
        //     foreach ($gallerys as $image) {
        //         $filename = time().uniqid().$image->getClientOriginalName();
        //         $image->move(public_path('uploads/news-images'), $filename);
        //         array_push($image_name, $filename);
        //     }
        //     $news->gallery_images =  json_encode($image_name);
        // }

        if ($request->hasFile('gallery_image')) {
            $galleryImages = $request->file('gallery_image');
            $imageNames = [];
        
            // Ensure $galleryImages is always an array
            if (!is_array($galleryImages)) {
                $galleryImages = [$galleryImages];
            }
        
            foreach ($galleryImages as $image) {
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/news-images'), $filename);
                $imageNames[] = "uploads/news-images/" . $filename; // Ensure proper path formatting
            }
        
            // Always store as JSON array
            $news->gallery_images = json_encode($imageNames);
        }
        
        
		
		if($request->hasFile('file')){
            $file = $request->file('file');
            $filename = time().uniqid().$file->getClientOriginalName();
            $file->move(public_path('uploads/news-files'), $filename);
            $news->file = $filename;
        }
        // return $news;

        if ($news->save()) {
            // language 
            Helper::insertLanguage(News::class, $news->id, 'en', 'title', $news->title);
            Helper::insertLanguage(News::class, $news->id, 'en', 'short_description', $news->short_description);
            Helper::insertLanguage(News::class, $news->id, 'en', 'description', $news->description);

            return response()->json([
                'type' => 'success',
                'message' => 'News created successfully.',
            ]);
        }
    }

    public function edit($id){
        $news = News::find($id);
        return view('backend.pages.news.edit', compact('news'));
    }

    public function update(Request $request, $id){
        $validator = $request->validate([
			'title' => 'required',
			'publish_date' => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
            'gallery_image.*' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
            'is_featured' => 'nullable',
            'read_time' => 'nullable|string',
		]);

        $news = News::find($id);
        $news->publish_date = $request->publish_date;
        $news->read_time = $request->read_time;
        if (Session::get('admin_language') == 'en') {
            $news->title = $request->title;
            $news->short_description = $request->short_description;
            $news->description = $request->descriptions;
        }
		$news->category = $request->category;
        $news->status = ($request->status) ? 1 : 0;
        $news->is_featured = ($request->is_featured) ? 1 : 0;
        $news->url = $request->url;
        if($request->hasFile('image')){
            if (file_exists(public_path('uploads/news-images/'.$news->media))) {
                unlink(public_path('uploads/news-images/'.$news->media));
            }
            $image = $request->file('image');
            $filename = time().uniqid().$image->getClientOriginalName();
            $image->move(public_path('uploads/news-images'), $filename);
            $news->media = "uploads/news-files/". $filename;
        }
		
		// if($request->hasFile('gallery_image')){
        //     $gallerys = $request->file('gallery_image');
        //     $image_name = [];
        //     foreach ($gallerys as $image) {
        //         $filename = time().uniqid().$image->getClientOriginalName();
        //         $image->move(public_path('uploads/news-images'), $filename);
        //         array_push($image_name, $filename);
        //     }
        //     $news->gallery_images =  json_encode($image_name);
        // }

        if ($request->hasFile('gallery_image')) {
            $galleryImages = $request->file('gallery_image');
            $imageNames = [];
        
            // Ensure $galleryImages is always an array
            if (!is_array($galleryImages)) {
                $galleryImages = [$galleryImages];
            }
        
            foreach ($galleryImages as $image) {
                $filename = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/news-images'), $filename);
                $imageNames[] = "uploads/news-images/" . $filename; // Ensure proper path formatting
            }
        
            // Always store as JSON array
            $news->gallery_images = json_encode($imageNames);
            
        }
        
        
		
		if($request->hasFile('file')){
            if (file_exists(public_path('uploads/news-files/'.$news->file))) {
                unlink(public_path('uploads/news-files/'.$news->file));
            }
            $file = $request->file('file');
            $filename = time().uniqid().$file->getClientOriginalName();
            $file->move(public_path('uploads/news-files'), $filename);
            $news->file = 'uploads/news-files'. $filename;
        }
        if ($news->save()) {

            // language
            Helper::insertLanguage(News::class, $news->id, Session::get('admin_language') ?? 'en', 'title', $request->title);
            Helper::insertLanguage(News::class, $news->id, Session::get('admin_language') ?? 'en', 'short_description', $request->short_description);
            Helper::insertLanguage(News::class, $news->id, Session::get('admin_language') ?? 'en', 'description', $request->descriptions);

            return response()->json([
                'type' => 'success',
                'message' => 'News updated successfully.',
            ]);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => 'Something went wrong.',
            ]);
        }
    }

    public function delete($id){
        $news = News::find($id);
        if($news){
            if ($news->media != null && file_exists(public_path('uploads/news-images/'.$news->media))) {
                unlink(public_path('uploads/news-images/'.$news->media));
            }
			if ($news->file != null && file_exists(public_path('uploads/news-files/'.$news->file))) {
                unlink(public_path('uploads/news-files/'.$news->file));
            }
            $news->delete();
            return json_encode(['success' => 'News deleted successfully.']);
        }else{
            return json_encode(['error' => 'News not found.']);
        }
    }
}
