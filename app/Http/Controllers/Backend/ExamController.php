<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CustomOption;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamQuestionOption;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\Segmentation;
use App\Models\UserExam;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $this->user = Auth::user();

            if (!$this->user || Helper::hasRight('exam.view') == false) {
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
        return view(
            'backend.pages.exam.index',
            compact(
                'companies',
                'preferances',
                'types',
                'difficulty_level',
                'exam_purpose',
                'media_type',
                'segmentation',
            )
        );
    }

    public function getQuestionContent(Request $request)
    {
        if ($request->generated_type == 'randomGenerated') {
            $numberOfQuestions = $request->no_of_questions;
            $segmentwiseQuestion = $request->segmentwise_question; // Array of questions per segment
            $segments = $request->segmentation; // Array of segment IDs

            if ($request->is_generated) {
                // If fully random, just fetch a total of $numberOfQuestions
                $questions = Question::inRandomOrder()
                    ->limit($numberOfQuestions)
                    ->get();

                $content_type = 'multiple';
                return view('backend.pages.exam.include.question-for-exam', compact('questions', 'content_type'));
            } else {
                $questions = collect(); // Initialize empty collection to store questions

                foreach ($segments as $index => $segment) {
                    // Get the number of questions to retrieve for this segment
                    $questionsForSegment = $segmentwiseQuestion[$index] ?? 0;

                    if ($questionsForSegment > 0) {
                        // Fetch random questions for this segment
                        $segmentQuestions = Question::whereJsonContains('segmentation', $segment)
                            ->inRandomOrder()
                            ->limit($questionsForSegment)
                            ->get();

                        // Add the fetched questions to the collection
                        $questions = $questions->merge($segmentQuestions);
                    }
                }

                // If the total number of questions is less than what is requested, fill the remaining slots with random questions
                if ($questions->count() < $numberOfQuestions) {
                    $remainingQuestions = $numberOfQuestions - $questions->count();
                    $additionalQuestions = Question::inRandomOrder()
                        ->limit($remainingQuestions)
                        ->get();
                    $questions = $questions->merge($additionalQuestions);
                }

                $content_type = 'multiple';
                return view('backend.pages.exam.include.question-for-exam', compact('questions', 'content_type'));
            }
        } else {
            $question = Question::findORFail($request->questions_of_question_bank);
            $last_question_number = $request->last_question_number;
            $content_type = 'single';
            return view('backend.pages.exam.include.question-for-exam', compact('question', 'last_question_number', 'content_type'));
        }
    }

    public function getList(Request $request)
    {

        $data = Exam::query();

        return DataTables::of($data)

            ->editColumn('company_id', function ($row) {
                return $row->company->name ?? '';
            })

            ->editColumn('type', function ($row) {
                return $row->examtype->title ?? '';
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
                if (Helper::hasRight('exam.delete')) {
                    $btn = $btn . '<a class="delete_btn btn btn-sm btn-danger mx-1" data-id="' . $row->id . '" href=""><i class="fa fa-trash" aria-hidden="true"></i></a>';
                }
                if (Helper::hasRight('exam.view')) {
                    $btn = $btn . '<a class="btn btn-sm btn-info mx-1" target="_blank" href="'.route('exam.paper', $row->slug).'"><i class="fa-solid fa-share"></i></a>';
                }
                if (Helper::hasRight('exam.view') && $row->result_published != 1) {
                    $btn = $btn . '<a class="publishResultbtn btn btn-sm btn-success mx-1" data-id="' . $row->id . '" href="'.route('admin.exam.publish.result', $row->id).'"><i class="fa-solid fa-bullhorn"></i></a>';
                }
                return $btn;
            })
            ->rawColumns(['status', 'action'])->make(true);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'company' => 'required',
            'exam_type' => 'required',
            'start_date' => 'required',
            'start_time' => 'required',
            'end_date' => 'required',
            'end_time' => 'required',
            'pass_marks' => 'required',
            'exam_title' => 'required',
            'exam_questions' => ['nullable', 'array'], // Default to nullable
        ], [
            'exam_questions.required' => 'Exam questions are required if the exam is not generated.',
        ]);
        
        if (empty($request->is_generated)) {
            $request->validate([
                'exam_questions' => 'required|array',
            ]);
        }

        DB::beginTransaction(); // Start transaction

        try {
            $exam = new Exam();
            $exam->company_id = $request->company;
            $exam->name = $request->exam_title;
            $exam->description = $request->description;
            $exam->short_description = $request->short_description;

            $exam->referance_id = $request->referance_id;
            $exam->referance_type = $request->referance_type;
            $exam->type = $request->exam_type;
            $exam->segmentation = json_encode($request->segmentation);
            $exam->exam_purpose = $request->exam_purpose;
            $exam->difficulty_level = $request->difficulty_level;
            $exam->segmentwise_question = json_encode($request->segmentwise_question);

            $exam->start_date = $request->start_date;
            $exam->end_date = $request->end_date;
            $exam->start_time = $request->start_time;
            $exam->end_time = $request->end_time;
            $exam->pass_marks = $request->pass_marks;
            $exam->no_of_questions = $request->no_of_questions;
            $exam->status = ($request->status) ? 1 : 0;
            $exam->is_login_required = ($request->is_login_required) ? 1 : 0;
            $exam->is_generated = ($request->is_generated) ? 1 : 0;
            $exam->instant_result = ($request->instant_result) ? 1 : 0;
            $exam->time_limit = $request->time_limit;

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $filename = time() . uniqid() . $thumbnail->getClientOriginalName();
                $thumbnail->move(public_path('uploads/exam-images'), $filename);
                $exam->thumbnail = $filename;
            }

            if ($request->hasFile('gallery')) {
                $gallerys = $request->file('gallery');
                $image_name = [];
                foreach ($gallerys as $image) {
                    $filename = time() . uniqid() . $image->getClientOriginalName();
                    $image->move(public_path('uploads/exam-images'), $filename);
                    $image_name[] = $filename;
                }
                $exam->gallery = json_encode($image_name);
            }

            // Handle custom_data
            if (!empty($validatedData['exam_custom_data'])) {
                $customData = [];
                foreach ($validatedData['exam_custom_data']['module'] as $key => $module) {
                    if (isset($validatedData['exam_custom_data']['category'])) {
                        foreach ($validatedData['exam_custom_data']['category'] as $value) {
                            $customData[] = [
                                'module' => $module,
                                'key' => $key,
                                'value' => $value,
                            ];
                        }
                    }
                }
                $exam->custom_data = json_encode($customData);
            }

            $exam->save();

            // Handle questions and options only if the exam is not generated
            if (!$exam->is_generated && !empty($request->exam_questions)) {
                foreach ($request->exam_questions as $questionId) {
                    $question = new ExamQuestion();
                    $question->exam_id = $exam->id;

                    $selectedQuestion = Question::find($questionId);
                    if ($selectedQuestion) {
                        $question->company_id = $selectedQuestion->company_id;
                        $question->custom_data = $selectedQuestion->custom_data;
                        $question->question_type = $selectedQuestion->question_type;
                        $question->marks = $selectedQuestion->marks;
                        $question->title = $selectedQuestion->title;
                        $question->save();

                        // Insert options from the question bank
                        $options = [];
                        foreach ($selectedQuestion->options as $option) {
                            $options[] = [
                                'question_id' => $question->id,
                                'title' => $option->title,
                                'is_correct' => $option->is_correct,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                        ExamQuestionOption::insert($options); // Bulk insert options
                    }
                }
            }

            DB::commit(); // Commit the transaction
            return response()->json([
                'type' => 'success',
                'message' => 'Exam added successfully.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on failure
            return response()->json([
                'type' => 'error',
                'message' => 'An error occurred while saving the exam. Please try again.',
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ], 500);
        }
    }


    public function delete($id)
    {
        $question = Exam::find($id);
        if ($question) {
            $question->delete();
            return json_encode(['success' => 'Exam deleted successfully.']);
        } else {
            return json_encode(['error' => 'Exam not found.']);
        }
    }

    public function publishResult($id)
    {
        // Find the exam by ID
        $exam = Exam::find($id);
        if (!$exam) {
            return response()->json(['error' => 'Exam not found.'], 404);
        }

        try {
            // Update the exam to mark it as result published
            $exam->result_published = 1;
            $exam->save();

            // Fetch all participants' results for the exam
            $userExams = UserExam::where('exam_id', $exam->id) // Include user details if needed
                ->get();

            // Sort participants by score (desc) and duration (asc)
            $sortedResults = $userExams->sort(function ($a, $b) {
                // Compare scores first (descending)
                if ($b->achieve_mark != $a->achieve_mark) {
                    return $b->achieve_mark - $a->achieve_mark;
                }

                // If scores are equal, compare exam completion time (ascending)
                $durationA = strtotime($a->ended_at) - strtotime($a->started_at);
                $durationB = strtotime($b->ended_at) - strtotime($b->started_at);

                return $durationA - $durationB;
            });

            // Assign positions based on sorted results
            $position = 1;
            foreach ($sortedResults as $result) {
                $result->update([
                    'result_published' => 2,
                    'position' => $position,
                ]);
                $position++;
            }

            return response()->json(['success' => 'Exam results published successfully and positions assigned.'], 200);
        } catch (\Exception $e) {
            // Handle exceptions
            return response()->json(['error' => 'An error occurred while publishing results.'], 500);
        }
    }
}
