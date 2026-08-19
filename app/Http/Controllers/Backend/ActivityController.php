<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Company;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ActivityController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('activity.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index(){
        $users = User::whereIn('role', [2])->where('status', 1)->get();
        $products = Product::where('status', 1)->get();
        $companies = Company::where('status', 1)->orderBy('name', 'asc')->get();
        return view('backend.pages.activity.index', compact('users', 'products', 'companies'));
    }

    public function getList(Request $request){
        
        $data = Activity::query();

        return DataTables::of($data)
        ->editColumn('course_id', function($row){
            return $row->course->name ?? '';
        })

        ->editColumn('company_id', function($row){
            return $row->company->name ?? '';
        })

        ->editColumn('user_id', function($row){
            return $row->user->first_name ?? '' . $row->user->last_name ?? '';
        })

        ->editColumn('status', function ($row) {
            
            return '<span class="badge bg-primary w-50">'.$row->status.'</span>';
        })

        ->addColumn('action', function ($row) {
            $btn = '';
            if (Helper::hasRight('activity.delete')) {
                $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="'.$row->id.'" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
            }
            return $btn;
        })
        ->rawColumns(['status','action'])->make(true);
    }

    public function getOtherFields(Request $request)
    {
        $type = $request->type;
        $partner = $request->partner;
        $course = $request->course;
        $date = $request->date;

        if ($type === 'Attendance' && $partner && $course) {
            // Fetch participants based on partner and course filters
            $participants = Order::with('user', 'details')
                ->where('status', 0)
                ->whereHas('details', function ($query) use ($partner, $course) {
                    $query->where('company_id', $partner)
                        ->where('product_id', $course);
                })
                ->get();

            // Attach activities to each participant if they have attendance on the same date
            $participants->each(function ($participant) use ($date) {
                $activity = Activity::where('date', $date)
                    ->where('user_id', $participant->user->id)
                    ->where('course_id', $participant->details[0]->product_id ?? null)
                    ->first();

                $participant->activities = $activity;
            });

            return view('backend.pages.activity.include.attendance-form', compact('participants'));
        }

        return response()->json([
            'type' => 'error',
            'message' => 'Invalid request parameters.',
        ], 400);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'type' => 'required',
            'partner_id' => 'required',
            'course' => 'required',
            'date' => 'required|date',
            'participients' => 'required|array',
            'attendance_status' => 'required|array',
        ]);

        $addedParticipants = [];

        DB::beginTransaction();

        try {
            for ($i = 0; $i < count($request->participients); $i++) {
                $participantId = $request->participients[$i];
                
                // Check if the participant already has an entry for the same course and date
                $exists = DB::table('activities')
                    ->where('user_id', $participantId)
                    ->where('course_id', $request->course)
                    ->where('date', $request->date)
                    ->exists();

                if (!$exists) {
                    // Insert a new record using raw query
                    DB::table('activities')->insert([
                        'course_id' => $request->course,
                        'company_id' => $request->partner_id,
                        'user_id' => $participantId,
                        'type' => $request->type,
                        'date' => $request->date,
                        'status' => $request->attendance_status[$i],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $addedParticipants[] = $participantId;
                }
            }

            DB::commit();

            if (count($addedParticipants) > 0) {
                return response()->json([
                    'type' => 'success',
                    'message' => $request->type . ' created successfully for participants',
                ]);
            } else {
                return response()->json([
                    'type' => 'info',
                    'message' => 'No new participants were added for the selected date.',
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'type' => 'error',
                'message' => 'An error occurred while saving participants. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
