<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomOption;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Segmentation;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('question.view') == false) {
                session()->flash('error', 'You can not access! Login first.');
                return redirect()->route('admin');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $preferances = json_decode(Helper::getSettings('question_module_preferances'), true);
        $companies = Company::where('status', 1)->orderBy('name', 'asc')->get();
        $types = CustomOption::where('option_for', 'exam')->where('type', 'question type')->get();
        $difficulty_level = CustomOption::where('option_for', 'exam')->where('type', 'difficulty level')->get();
        $exam_purpose = CustomOption::where('option_for', 'exam')->where('type', 'exam purpose')->get();
        $media_type = CustomOption::where('option_for', 'exam')->where('type', 'media type')->get();
        $segmentation = Segmentation::all();

        return view('backend.pages.question.index', compact('companies', 'preferances', 'types', 'difficulty_level', 'exam_purpose', 'media_type', 'segmentation'));
    }

    public function getList(Request $request)
    {

        $data = Question::query();

        return DataTables::of($data)

            ->editColumn('company_id', function ($row) {
                return $row->company->name ?? '';
            })

            ->editColumn('segmentation', function ($row) {
                // Decode the JSON field to an array
                $segmentationIds = json_decode($row->segmentation, true);

                if (is_array($segmentationIds)) {
                    // Fetch the segmentation names and join them into a string
                    $segmentation = Segmentation::whereIn('id', $segmentationIds)->pluck('name')->join(', ');
                    return $segmentation;
                }

                return ''; // Return empty string if segmentation IDs are invalid
            })

            ->editColumn('custom_data', function ($row) {
                $html = '';
                if ($row->custom_data) {
                    $data = json_decode($row->custom_data, true);
                    foreach ($data as $module) {
                        $module_object = app($module['module'])->find($module['value']);
                        $moduleName = str_replace('App\Models\\', '', $module['module']);

                        // Concatenate module name and title safely
                        $html .= $moduleName . ': ' . ($module_object->title ?? '');
                    }
                }

                return $html;
            })

            ->editColumn('status', function ($row) {
                if ($row->status == 1) {
                    return '<span class="badge bg-primary w-80">Active</span>';
                } else {
                    return '<span class="badge bg-danger w-80">Inactive</span>';
                }
            })

            ->addColumn('action', function ($row) {
                $btn = '';
                if (Helper::hasRight('question.edit')) {
                    $btn = $btn . '<a href="" data-id="' . $row->id . '" class="edit_btn btn btn-sm btn-primary mx-1"><i class="fa-solid fa-pencil"></i></a>';
                }
                if (Helper::hasRight('question.delete')) {
                    $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['status', 'segmentation', 'custom_data', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'partner_id' => 'required',
            'title' => 'required',
            'type' => ['required',],
            'marks' => ['required', 'integer', 'min:1'],
            'options' => ['required_if:type,single choice,multiple choice', 'array', 'min:2'],
            'options.*' => ['required', 'string'],
            'is_correct' => ['array'], // Allow is_correct to be an array
            'custom_data' => ['array'],
        ]);

        DB::beginTransaction(); // Start transaction

        try {
            // 1. Create the Question
            $question = new Question();
            $question->company_id = $validatedData['partner_id'];
            $question->question_type = $validatedData['type'];

            $question->segmentation = json_encode($request->segmentation);
            $question->difficulty_level = $request->difficulty_level ?? null;
            $question->exam_purpose = $request->exam_purpose ?? null;
            $question->media_type = $request->media_type ?? null;
            $question->status = $request->status ? 1 : 0;

            $question->marks = $validatedData['marks'];
            $question->title = $validatedData['title'];

            // Handle custom_data
            if (!empty($validatedData['custom_data'])) {
                $customData = [];
                // Iterate over module
                foreach ($validatedData['custom_data']['module'] as $key => $module) {
                    if (isset($validatedData['custom_data']['category'])) {
                        foreach ($validatedData['custom_data']['category'] as $value) {
                            $customData[] = [
                                'module' => $module, // Module name
                                'key' => $key,
                                'value' => $value // Selected category value
                            ];
                        }
                    }
                }
                // Encode and save custom data
                $question->custom_data = json_encode($customData);
            }

            $question->save(); // Save the question

            // Store Options
            if (!empty($validatedData['options'])) {
                $options = [];
                foreach ($validatedData['options'] as $key => $option) {
                    $isCorrect = isset($validatedData['is_correct'][$key]) && $validatedData['is_correct'][$key] == 1;
                    $options[] = [
                        'question_id' => $question->id,
                        'title' => $option,
                        'is_correct' => $isCorrect ? 1 : 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                QuestionOption::insert($options); // Bulk insert options
            }
            DB::commit(); // Commit the transaction
            return response()->json([
                'type' => 'success',
                'message' => 'Question added successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on failure
            return response()->json([
                'type' => 'error',
                'message' => 'An error occurred while saving question. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);
        $preferances = json_decode(Helper::getSettings('question_module_preferances'), true);
        $companies = Company::where('status', 1)->orderBy('name', 'asc')->get();
        $types = CustomOption::where('option_for', 'exam')->where('type', 'question type')->get();
        $difficulty_level = CustomOption::where('option_for', 'exam')->where('type', 'difficulty level')->get();
        $exam_purpose = CustomOption::where('option_for', 'exam')->where('type', 'exam purpose')->get();
        $media_type = CustomOption::where('option_for', 'exam')->where('type', 'media type')->get();
        $segmentation = Segmentation::all();
        return view('backend.pages.question.edit', compact('question', 'companies', 'preferances', 'types', 'difficulty_level', 'exam_purpose', 'media_type', 'segmentation'));
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the incoming request
            $validatedData = $request->validate([
                'partner_id' => 'required',
                'title' => 'required',
                'type' => ['required'],
                'marks' => ['required', 'integer', 'min:1'],
                'options' => ['required_if:type,single choice,multiple choice', 'array', 'min:2'],
                'options.*' => ['required', 'string'],
                'is_correct' => ['array'], // Allow is_correct to be an array
                'custom_data' => ['array'],
            ]);

            // Start the transaction
            DB::beginTransaction();

            // Find the question to update
            $question = Question::findOrFail($id);

            // Update the question fields
            $question->company_id = $request->partner_id;
            $question->segmentation = json_encode($request->segmentation); // Store as JSON
            $question->marks = $request->marks;
            $question->question_type = $request->type;
            $question->difficulty_level = $request->difficulty_level;
            $question->exam_purpose = $request->exam_purpose;
            $question->media_type = $request->media_type;
            $question->title = $request->title;
            $question->status = $request->status ? 1 : 0;
            $question->save();

            // Update options
            $options = $request->options;
            $isCorrectArray = $request->is_correct;

            // Delete old options
            $question->options()->delete();

            // Add new options
            foreach ($options as $key => $optionTitle) {
                $question->options()->create([
                    'title' => $optionTitle,
                    'is_correct' => $isCorrectArray[$key] ?? 0,
                ]);
            }

            // Commit the transaction
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Question updated successfully!',
            ], 200);

        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function delete($id)
    {
        $question = Question::find($id);
        if ($question) {
            $question->delete();
            return json_encode(['success' => 'Question deleted successfully.']);
        } else {
            return json_encode(['error' => 'Question not found.']);
        }
    }

    public function ajaxList(){
        $questions = Question::where('status', 1)->get();
        return response()->json([
            'status' => 200,
            'data' => $questions
        ]);
    }
}
