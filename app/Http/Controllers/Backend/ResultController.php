<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\UserExam;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ResultController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('result.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('backend.pages.result.index');
    }

    public function getList(Request $request)
    {

        $data = UserExam::query();

        return DataTables::of($data->orderBy('position', 'desc'))

            ->editColumn('exam_id', function ($row) {
                return $row->exam->name ?? '';
            })

            ->editColumn('started_at', function ($row) {
                return date('y-m-d h:i a', strtotime($row->started_at));
            })

            ->editColumn('ended_at', function ($row) {
                return date('y-m-d h:i a', strtotime($row->ended_at));
            })

            ->editColumn('position', function ($row) {
                return $row->position ?? 'n/a';
            })

            ->editColumn('result_published', function ($row) {
                if ($row->result_published == 1) {
                    return '<span class="badge bg-primary w-100">Submited</span>';
                } elseif ($row->result_published == 2) {
                    return '<span class="badge bg-success w-100">Published</span>';
                }else{
                    return '<span class="badge bg-danger w-100">Pending</span>';
                }
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Helper::hasRight('result.view')) {
                    $btn = $btn . '<a class="btn btn-sm btn-info mx-1" target="_blank" href="'.route('admin.results.view', $row->id).'"><i class="fa-solid fa-eye"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['exam_id','result_published', 'action'])->make(true);
    }

    public function view($id)
    {
        $result = UserExam::with(['exam', 'answers.question.options'])->findOrFail($id);

        // Count total participants
        $number_of_user_attend = UserExam::where('exam_id', $result->exam->id)->count();

        // Calculate position based on achieved marks (highest first)
        $allResults = UserExam::where('exam_id', $result->exam->id)
                            ->orderByDesc('achieve_mark')
                            ->get();
        $position = $allResults->search(function ($item) use ($result) {
            return $item->id === $result->id;
        }) + 1;

        return view('backend.pages.result.view', compact('result', 'number_of_user_attend', 'position'));
    }
}
