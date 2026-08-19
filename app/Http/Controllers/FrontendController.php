<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Helper;
use App\Models\User;
use App\Models\Otp;
use App\Models\Service;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Inquiry;
use App\Models\InquiryProduct;
use App\Models\ServiceOrder;
use App\Models\News;
use App\Models\Catalogue;
use App\Models\Company;
use App\Models\ProductPart;
use App\Models\PartAttribute;
use App\Models\Resource;
use App\Models\Contact;
use App\Models\CustomField;
use App\Models\Exam;
use App\Models\ProductAttribute;
use App\Models\Question;
use App\Models\Transaction;
use App\Models\UserExam;
use App\Models\UserExamAnswer;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\DB;

use DateTime;
use DateTimeZone;
use DOMDocument;
use GlobalPayments\Api\Entities\Address;
use GlobalPayments\Api\Entities\Enums\AddressType;
use GlobalPayments\Api\Entities\Enums\HppVersion;
use Illuminate\Support\Facades\App;

use GlobalPayments\Api\ServiceConfigs\Gateways\GpEcomConfig;
use GlobalPayments\Api\Services\HostedService;
use GlobalPayments\Api\Entities\Exceptions\ApiException;
use GlobalPayments\Api\Entities\HostedPaymentData;
use GlobalPayments\Api\HostedPaymentConfig;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class FrontendController extends Controller
{
    public function examPaper(Request $request, $slug)
{
    // Set the default timezone to Asia/Dhaka
    date_default_timezone_set('Asia/Dhaka');

    // Fetch the exam details
    $exam = Exam::where('slug', $slug)->first();
    if (!$exam) {
        abort(404, 'Exam not found');
    }

    // Combine the start and end date-time strings
    $startDateTime = $exam->start_date . ' ' . $exam->start_time; // Format: Y-m-d H:i
    $endDateTime = $exam->end_date . ' ' . $exam->end_time;       // Format: Y-m-d H:i

    try {
        // Convert start and end times to timestamps
        $examStartTimestamp = strtotime($startDateTime);
        $examEndTimestamp = strtotime($endDateTime);

        // Get the current timestamp in Asia/Dhaka timezone
        $currentTimestamp = time();
    } catch (\Exception $e) {
        abort(500, 'Error in processing dates: ' . $e->getMessage());
    }

    // Check if the exam hasn't started yet
    if ($currentTimestamp < $examStartTimestamp) {
        abort(400, 'The exam has not started yet.');
    }

    // Check if the exam has already ended
    if ($currentTimestamp > $examEndTimestamp) {
        abort(400, 'The exam has already ended.');
    }

    $questions = collect();

    // Handle question fetching based on generation method
    if ($exam->is_generated == 1) {
        $numberOfQuestions = $exam->no_of_questions;
        $segmentwiseQuestion = json_decode($exam->segmentwise_question, true);
        $segments = json_decode($exam->segmentation, true);

        if (!is_array($segmentwiseQuestion) || !is_array($segments)) {
            abort(400, 'Invalid segmentation or segmentwise question data.');
        }

        foreach ($segments as $index => $segment) {
            $questionsForSegment = $segmentwiseQuestion[$index] ?? 0;

            if ($questionsForSegment > 0) {
                $segmentQuestions = Question::whereJsonContains('segmentation', $segment)
                    ->inRandomOrder()
                    ->limit($questionsForSegment)
                    ->get();

                $questions = $questions->merge($segmentQuestions);
            }
        }

        if ($questions->count() < $numberOfQuestions) {
            $remainingQuestions = $numberOfQuestions - $questions->count();
            $additionalQuestions = Question::inRandomOrder()
                ->limit($remainingQuestions)
                ->get();
            $questions = $questions->merge($additionalQuestions);
        }
    } else {
        // Fetch pre-defined questions if `is_generated` is false
        $questions = $exam->questions;
    }

    // Check for an existing device token in the session
    $deviceToken = $request->session()->get('device_token');

    if (!$deviceToken) {
        // Generate a new device token if not found
        $deviceToken = Str::uuid()->toString();

        // Store the device token in the session
        $request->session()->put('device_token', $deviceToken);
    }

    if ($request->start_exam == 1) {
        // Get the current timestamp
        $currentTimestamp = time();

        // Find or create an exam log for the user based on the device token and exam ID
        $examLog = UserExam::firstOrCreate(
            ['ip_address' => $deviceToken, 'exam_id' => $exam->id],
            ['started_at' => date('Y-m-d H:i:s', $currentTimestamp)]
        );

        // Parse the started_at timestamp
        $startedAtTimestamp = strtotime($examLog->started_at);

        // Calculate the exam end time
        $examEndTimestamp = $startedAtTimestamp + ($exam->time_limit * 60);

        // Calculate remaining time
        $remainingTime = $currentTimestamp < $examEndTimestamp
            ? $examEndTimestamp - $currentTimestamp
            : 0;
    } else {
        $examLog = UserExam::where(['ip_address' => $deviceToken, 'exam_id' => $exam->id])->first();
        $remainingTime = null; // No remaining time
    }

    $number_of_user_attend = UserExam::where('exam_id', $exam->id)->count();

    // Return the view without setting a cookie since we're using session now
    return view('frontend.exam.exam-paper', compact('exam', 'questions', 'remainingTime', 'examLog', 'number_of_user_attend'));
}


public function examPaperSubmit(Request $request, $id, $deviceToken)
{
    // Retrieve the device token from cookies
    $storedDeviceToken = $request->session()->get('device_token');

    // Validate the device token
    if ($deviceToken != $storedDeviceToken) {
        abort(403, 'Unauthorized device or tampered data.');
    }


    // Validate the request inputs
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:15',
        'organization' => 'required|string|max:255',
        'answers' => 'required|array',
        'answers.*' => 'nullable', // Allow answers to be null for unattempted questions
    ]);

    // Fetch the exam and validate its existence
    $exam = Exam::findOrFail($id);

    DB::beginTransaction();

    try {
        // Find or update the UserExam log
        $userExam = UserExam::updateOrCreate(
            ['exam_id' => $exam->id, 'ip_address' => $deviceToken],
            [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'organization' => $request->organization,
                'started_at' => $userExam->started_at ?? now(), // Preserve `started_at` if it already exists
                'status' => 1,
                'result_published' => 1,
                'ended_at' => now(),
            ]
        );

        // Calculate time difference in minutes
        $startedAt = new DateTime($userExam->started_at);
        $submittedAt = new DateTime(); // Current time
        $timeDifference = $startedAt->diff($submittedAt)->i + ($startedAt->diff($submittedAt)->h * 60); // Convert to total minutes

        // Check if the submission is past the allowed time limit
        $isLateSubmission = $timeDifference > $exam->time_limit;

        // Process and store answers
        $answers = $request->answers ?? [];
        $totalMarks = 0; // Initialize total marks
        $correctAnswers = 0; // Initialize correct answers count
        $wrongAnswers = 0; // Initialize wrong answers count
        $totalMarksForExam = 0; // Total marks for all questions

        foreach ($answers as $questionId => $answer) {
            $question = Question::find($questionId);

            if (!$question) {
                continue; // Skip invalid questions
            }

            $totalMarksForExam += $question->marks ?? 1;

            // Get the correct option for the question
            $correctOption = $question->options()->where('is_correct', 1)->first();

            // Check if the answer is not null or empty
            $status = (!is_null($answer) && $answer !== '') ? 1 : 0;

            // Determine if the answer is correct
            $isCorrect = false;
            $marks = 0;

            if ($status === 1 && is_array($answer)) {
                // Multiple answers selected (assuming array answers)
                $correctAnswersCount = 0;
                foreach ($answer as $selectedOptionId) {
                    $selectedOption = $question->options()->find($selectedOptionId);
                    if ($selectedOption && $selectedOption->is_correct) {
                        $correctAnswersCount++;
                    }
                }
                $isCorrect = $correctAnswersCount == count($answer); // All selected options must be correct
            } else {
                // Single answer
                $selectedOption = $question->options()->find($answer);
                if ($selectedOption && $selectedOption->is_correct) {
                    $isCorrect = true;
                }
            }

            // Award marks for correct answers
            if ($isCorrect) {
                $marks = $question->marks ?? 1; // Example: 1 mark per correct answer
                $correctAnswers++;
            } else {
                if ($status === 1) { // Count wrong answers only if an answer is given
                    $wrongAnswers++;
                }
            }

            // Save individual answers
            UserExamAnswer::create([
                'result_id' => $userExam->id,
                'question_id' => $question->id,
                'answear' => is_array($answer) ? implode(',', $answer) : $answer, // Save as a comma-separated string
                'right_wrong' => $isCorrect ? 1 : 0,
                'marks' => $marks,
                'status' => $status,
            ]);

            // Accumulate total marks
            $totalMarks += $marks;
        }

        // Update the UserExam record
        $userExam->achieve_mark = $totalMarks;
        $userExam->total_mark = $totalMarksForExam;
        $userExam->negative_mark = 0; // Optional: Calculate negative marks if applicable
        $userExam->total_duration = $timeDifference;
        $userExam->correct_answers = $correctAnswers;
        $userExam->wrong_answers = $wrongAnswers;
        $userExam->save();

        DB::commit();

        // Prepare response message
        $successMessage = $isLateSubmission
            ? 'Exam submitted successfully, but your submission was off time.'
            : 'Exam submitted successfully. Your result has been recorded.';

        // Instant result (if enabled)
        if ($exam->instant_result == 1) {
            $resultMessage = "You scored {$totalMarks} out of {$exam->no_of_questions}.";
        } else {
            $resultMessage = null;
        }

        return response()->json([
            'type' => 'success',
            'message' => $successMessage,
            'result' => $resultMessage,
        ], 200);

    } catch (\Exception $e) {
        // Rollback transaction on error
        DB::rollBack();

        return response()->json([
            'type' => 'error',
            'message' => 'An error occurred while saving the exam. Please try again.',
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ], 500);
    }
}



    public function home()
    {

        App::setLocale(Session::get('language'));

        $categories = Category::where('show_home', 1)
            ->where('status', 1)
            ->orderBy('short_number', 'asc')
            ->get();

        $services = Service::where('status', 1)->take(4)->get();
        $newses = News::where('status', 1)->take(4)->get();

        $products = Product::with('category')
            ->where('status', 1)
            ->where('features', 1)
            ->get();

        $banners = Resource::where('status', 1)->where('type', 'banner')->get();
        $partners = Brand::where('status', 1)->where('show_home', 1)->get();

        return view('frontend.pages.home', compact('categories', 'services', 'newses', 'products', 'banners', 'partners'));
    }
    // public function data() {
    //     $subCategories = Category::where('parent_category','=',$id)->get();
    //     return $subCategories;
    // }

    public function registration()
    {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.registration');
    }

    public function login()
    {
        App::setLocale(Session::get('language'));
        return view('backend.auth.login');
    }

    public function about()
    {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.about');
    }
    public function contact()
    {
        App::setLocale(Session::get('language'));
        $addresses = Contact::where('status', 1)->orderBy('is_default', 'desc')->get();
        return view('frontend.pages.contact', compact('addresses'));
    }

    public function contactPost(Request $request)
    {

        $subject = $request->subject ?? 'Contact mail';
        $data = 'Someone trying to contact with you. Here is the details, </br> Name: ' . $request->name . ', </br> Email: ' . $request->email . ', Phone: ' . $request->phone . ', </br> Message: ' . $request->message . ' .';
        $admin_email = $request->sender_email;
        Helper::sendEmail($admin_email, $subject, $data);

        session()->flash('message', 'Email send successfully! We will contact with you soon.');
        return redirect()->back();
    }

    public function categories()
    {
        App::setLocale(Session::get('language'));
        $categories = Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();
        return view('frontend.pages.categories', compact('categories'));
    }
    public function subcategory($id)
    {
        App::setLocale(Session::get('language'));
        $currentCategory = Category::find($id);
        $categories = Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();
        $subCategories = $currentCategory->subcategories->where('status', 1);
        $products = Product::where('category_id', $id)->orderby('code', 'asc')->get();

        if (count($subCategories) > 0) {
            return view('frontend.pages.subcategory', compact('currentCategory', 'categories', 'subCategories', 'products'));
        } else {
            $brands = Brand::where('status', 1)->get();
            $filter_attributes = ProductAttribute::select('attribute_name', \DB::raw('MAX(id) as max_id'))
                ->where('type', 'attributes')
                ->where('is_filter', 1)
                ->groupBy('attribute_name')
                ->orderBy('max_id')
                ->get();

            foreach ($filter_attributes as $row) {
                $row->attributes_values = ProductAttribute::select('value')->where('type', 'attributes')->where('is_filter', 1)->where('attribute_name', $row->attribute_name)->whereNotNull('value')->groupBy('value')->get();
            }

            if ($currentCategory) {
                $current_category =  $currentCategory;
                $root_category = $current_category->rootParent();
                // return $sub_categories;
            } else {
                $current_category = [];
                $root_category = [];
            }

            return view('frontend.pages.products', compact('brands', 'categories', 'filter_attributes', 'current_category', 'root_category', 'currentCategory'));
            // return redirect()->route('products')->with('currentCategory', $currentCategory);
        }
    }

    public function brandWiseProduct($id)
    {
        App::setLocale(Session::get('language'));
        $current_brand = Brand::find($id);
        $products = Product::where('category_id', $id)->orderby('code', 'asc')->get();
        $categories = Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();

        $brands = Brand::where('status', 1)->get();
        $filter_attributes = ProductAttribute::select('attribute_name', \DB::raw('MAX(id) as max_id'))
            ->where('type', 'attributes')
            ->where('is_filter', 1)
            ->groupBy('attribute_name')
            ->orderBy('max_id')
            ->get();

        foreach ($filter_attributes as $row) {
            $row->attributes_values = ProductAttribute::select('value')->where('type', 'attributes')->where('is_filter', 1)->where('attribute_name', $row->attribute_name)->whereNotNull('value')->groupBy('value')->get();
        }

        $current_category = [];
        $root_category = [];
        $currentCategory = [];

        return view('frontend.pages.products', compact('brands', 'categories', 'filter_attributes', 'current_category', 'root_category', 'currentCategory', 'current_brand'));
    }

    public function searchProductBycategory(Request $request)
    {
        App::setLocale(Session::get('language'));
        $products = Product::where('status', 1);

        if ($request->category_id) {
            $products->where('category_id', $request->category_id);
        }

        if ($request->name) {
            $products->where('name', 'like', "%" . $request->name . "%");
        }

        if ($request->model) {
            $products->where('code', 'like', "%" . $request->model . "%");
        }

        $products = $products->orderby('code', 'asc')->paginate(20);
        $productsHtml = view('frontend.pages.search.category-products', compact('products'))->render();
        $paginationHtml = json_decode(json_encode($products));

        $pagination = '';
        $visiblePages = 3;

        for ($i = 1; $i <= $paginationHtml->last_page; $i++) {
            if ($i == $paginationHtml->current_page) {
                $pagination .= '<li class="page-item active"><a class="page-link pagination_btn" href="#">' . $i . '</a></li>';
            } else {
                if ($i <= $visiblePages || $i > $paginationHtml->last_page - $visiblePages || abs($i - $paginationHtml->current_page) < floor($visiblePages / 2)) {
                    $pagination .= '<li class="page-item"><a class="page-link pagination_btn" href="' . $paginationHtml->path . '?page=' . $i . '">' . $i . '</a></li>';
                } elseif ($i == $visiblePages + 1 || $i == $paginationHtml->last_page - $visiblePages) {
                    $pagination .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                }
            }
        }

        // Add Previous Page link
        if ($paginationHtml->current_page > 1) {
            $pagination = '<li class="page-item"><a class="page-link pagination_btn" href="' . $paginationHtml->path . '?page=' . ($paginationHtml->current_page - 1) . '">Previous</a></li>' . $pagination;
        }

        // Add Next Page link
        if ($paginationHtml->current_page < $paginationHtml->last_page) {
            $pagination .= '<li class="page-item"><a class="page-link pagination_btn" href="' . $paginationHtml->path . '?page=' . ($paginationHtml->current_page + 1) . '">Next</a></li>';
        }

        return response()->json([
            'products_html' => $productsHtml,
            'pagination_html' => ($productsHtml) ? $pagination : ''
        ]);
    }

    public function products($id)
    {
        App::setLocale(Session::get('language'));
        $products = Product::where('category_id', '=', $id)->orderby('code', 'asc')->get();
        $brands = Brand::where('status', 1)->get();
        $categories = Category::whereNull('parent_category')->where('status', 1)->get();

        $filter_attributes = ProductAttribute::select('attribute_name', \DB::raw('MAX(id) as max_id'))
            ->where('type', 'attributes')
            ->where('is_filter', 1)
            ->groupBy('attribute_name')
            ->orderBy('max_id')
            ->get();
        foreach ($filter_attributes as $row) {
            $row->attributes_values = ProductAttribute::select('value')
                ->where('type', 'attributes')
                ->where('is_filter', 1)
                ->where('attribute_name', $row->attribute_name)
                ->whereNotNull('value')
                ->groupBy('value')
                ->orderBy('value', 'asc')
                ->get();
        }
        return view('frontend.pages.products', compact('products', 'brands', 'categories', 'filter_attributes'));
    }

    public function allProducts(Request $request)
    {
        App::setLocale(Session::get('language'));
        $brands = Brand::where('status', 1)->get();
        $categories = Category::whereNull('parent_category')->where('status', 1)->get();

        $filter_attributes = ProductAttribute::select('attribute_name', \DB::raw('MAX(id) as max_id'))
            ->where('type', 'attributes')
            ->where('is_filter', 1)
            ->groupBy('attribute_name')
            ->orderBy('max_id')
            ->get();

        foreach ($filter_attributes as $row) {
            $row->attributes_values = ProductAttribute::select('value')
                ->where('type', 'attributes')
                ->where('is_filter', 1)
                ->where('attribute_name', $row->attribute_name)
                ->whereNotNull('value')
                ->groupBy('value')
                ->get();
        }

        if (session()->has('currentCategory')) {
            $current_category =  session('currentCategory');
            $root_category = $current_category->rootParent();
            $sub_categories = Category::find($root_category->id)->subcategoriesRecursive;
            // return $sub_categories;
        } else {
            $current_category = [];
            $root_category = [];
            $sub_categories = [];
        }

        return view('frontend.pages.products', compact('brands', 'categories', 'filter_attributes', 'current_category', 'root_category'));
    }

    public function searchProducts(Request $request)
    {
        App::setLocale(Session::get('language'));
        $subcategory = null;
        $products = Product::where('status', 1);
        if ($request->name) {
            $products->where('name', 'like', "%" . $request->name . "%")
                ->orWhereHas('brand', function ($products) use ($request) {
                    $products->where('title', 'like', "%" . $request->name . "%");
                });
        }
        if ($request->model) {
            $products->where('code', 'like', "%" . $request->model . "%");
        }
        if ($request->brands_for_filter) {
            $products->where(function ($products) use ($request) {
                $products->whereIn('brand_id', $request->brands_for_filter);
            });
        }

        if ($request->category_for_filter) {
            $products->where(function ($products) use ($request) {
                $products->whereIn('category_id', $request->category_for_filter);
            });
        }

        if ($request->current_category) {
            $current_category =  Category::find($request->current_category);
            if ($current_category) {
                $subcategory = $current_category;
                $products->where('sub_category_id', $current_category->id);
            }
        }

        if ($request->attributes_for_filter) {
            $products->whereHas('attributes', function ($query) use ($request) {
                $query->whereIn('value', $request->attributes_for_filter);
            });
        }

        $products = $products->orderby('code', 'asc')->paginate(30);
        $productsHtml = view('frontend.pages.search.products', compact('products', 'subcategory'))->render();
        $paginationHtml = json_decode(json_encode($products));

        if ($products->isEmpty()) {
            return response()->json([
                'products_html' => $productsHtml,
                'pagination_html' => '',
            ]);
        }

        $pagination = '';
        $visiblePages = 2;

        for ($i = 1; $i <= $paginationHtml->last_page; $i++) {
            if ($i == $paginationHtml->current_page) {
                $pagination .= '<li class="page-item active"><a class="page-link pagination_btn" href="#">' . $i . '</a></li>';
            } else {
                if ($i <= $visiblePages || $i > $paginationHtml->last_page - $visiblePages || abs($i - $paginationHtml->current_page) < floor($visiblePages / 2)) {
                    $pagination .= '<li class="page-item"><a class="page-link pagination_btn" href="' . $paginationHtml->path . '?page=' . $i . '">' . $i . '</a></li>';
                } elseif ($i == $visiblePages + 1 || $i == $paginationHtml->last_page - $visiblePages) {
                    $pagination .= '<li class="page-item disabled"><a class="page-link" href="#">...</a></li>';
                }
            }
        }

        // Add Previous Page link
        if ($paginationHtml->current_page > 1) {
            $pagination = '<li class="page-item"><a class="page-link pagination_btn" href="' . $paginationHtml->path . '?page=' . ($paginationHtml->current_page - 1) . '">Previous</a></li>' . $pagination;
        }

        // Add Next Page link
        if ($paginationHtml->current_page < $paginationHtml->last_page) {
            $pagination .= '<li class="page-item"><a class="page-link pagination_btn" href="' . $paginationHtml->path . '?page=' . ($paginationHtml->current_page + 1) . '">Next</a></li>';
        }

        return response()->json([
            'products_html' => $productsHtml,
            'pagination_html' => ($productsHtml) ? $pagination : ''
        ]);
    }

    public function product_details($id)
    {
        App::setLocale(Session::get('language'));
        $product = Product::find($id);
        $imagesArray = json_decode($product->images, true);
        $product->images = $imagesArray;
        $releted_products = Product::where('sub_category_id', $product->sub_category_id)->where('id', '!=', $product->id)->where('status', 1)->get();

        $catalogue = Catalogue::where('product_id', $product->id)->first();
        $custom_fields = CustomField::where('status', 1)->get();
        return view('frontend.pages.new-product-details', compact('product', 'releted_products', 'catalogue', 'custom_fields'));
        // return view('frontend.pages.product_details',compact('product', 'releted_parts','catalogue'));
    }

    public function allParts()
    {
        App::setLocale(Session::get('language'));
        $brands = Brand::where('status', 1)->get();
        $parts = ProductPart::where('status', 1)->get();
        $categories = Category::whereNull('parent_category')->where('status', 1)->orderBy('short_number', 'asc')->get();
        $filter_attributes = PartAttribute::select('attribute_name', \DB::raw('MAX(id) as max_id'))
            ->where('type', 'attributes')
            ->where('is_filter', 1)
            ->groupBy('attribute_name')
            ->orderBy('max_id')
            ->get();

        foreach ($filter_attributes as $row) {
            $row->attributes_values = PartAttribute::select('value')->where('type', 'attributes')->where('is_filter', 1)->where('attribute_name', $row->attribute_name)->whereNotNull('value')->groupBy('value')->get();
        }

        return view('frontend.pages.parts', compact('parts', 'brands', 'categories', 'filter_attributes'));
    }

    public function searchParts(Request $request)
    {
        App::setLocale(Session::get('language'));

        $parts = ProductPart::where('status', 1);

        if ($request->name) {
            $parts->where('name', 'like', "%" . $request->name . "%");
        }

        if ($request->model) {
            $parts->where('code', 'like', "%" . $request->model . "%");
        }

        if ($request->brands_for_filter) {
            $parts->where(function ($parts) use ($request) {
                $parts->whereIn('brand_id', $request->brands_for_filter);
            });
        }

        if ($request->attributes_for_filter) {
            $parts->whereHas('attributes', function ($query) use ($request) {
                $query->whereIn('value', $request->attributes_for_filter);
            });
        }

        $parts = $parts->get();
        return view('frontend.pages.search.parts', compact('parts'));
    }

    public function partsDetails($id)
    {
        App::setLocale(Session::get('language'));
        $part = ProductPart::find($id);
        $imagesArray = json_decode($part->images, true);
        $part->images = $imagesArray;
        $custom_fields = CustomField::where('status', 1)->get();
        return view('frontend.pages.part-details', compact('part', 'custom_fields'));
    }

    public function AddToCart($type, $id, Request $request)
    {
        $productId = $id;
        $quantity = 1;

        if ($request->session()->has('cartlist')) {
            $cartlist = $request->session()->get('cartlist');

            if (isset($cartlist[$productId])) {
                return response()->json(['status' => 'error', 'message' => 'Product is already in your cart!']);
            }

            $cartlist[$productId] = [
                'quantity' => $quantity,
                'type' => $type
            ];
        } else {
            $cartlist = [
                $productId => [
                    'quantity' => $quantity,
                    'type' => $type
                ]
            ];
        }

        $request->session()->put('cartlist', $cartlist);

        // Calculate the updated cart count
        $cartCount = count($cartlist);

        return response()->json(['status' => 'success', 'message' => 'Product added to cart!', 'cartCount' => $cartCount]);
    }


    public function getCartCount(Request $request)
    {
        $cartCount = count($request->session()->get('cartlist', []));
        return response()->json($cartCount);
    }






    public function removeFromCart($product_id, Request $request)
    {
        $productId = $product_id;

        if ($request->session()->has('cartlist')) {
            $cartlist = $request->session()->get('cartlist');

            if (isset($cartlist[$productId])) {
                unset($cartlist[$productId]);
                $request->session()->put('cartlist', $cartlist);
            }
        }

        return redirect()->route('cart')->with('message', 'Product remove from cart!');
    }

    public function incrementCart($product_id, Request $request)
    {
        $productId = $product_id;
        if ($request->session()->has('cartlist')) {
            $cartlist = $request->session()->get('cartlist');

            if (isset($cartlist[$productId])) {
                $cartlist[$productId]['quantity']++;
                $request->session()->put('cartlist', $cartlist);
                return redirect()->route('cart')->with('message', 'Cart quantity increment!');
            }
        }
        return redirect()->route('cart')->with('message', 'Cart updated!');
    }

    public function decrementCart($product_id, Request $request)
    {
        $productId = $product_id;
        if ($request->session()->has('cartlist')) {
            $cartlist = $request->session()->get('cartlist');

            if (isset($cartlist[$productId])) {
                if ($cartlist[$productId]['quantity'] > 1) {
                    $cartlist[$productId]['quantity']--;
                    $request->session()->put('cartlist', $cartlist);
                    return redirect()->route('cart')->with('message', 'Cart quantity decrement!');
                } else {
                    // If the quantity is already 1, you can consider removing the item from the cart
                    unset($cartlist[$productId]);
                    $request->session()->put('cartlist', $cartlist);
                    return redirect()->route('cart')->with('message', 'Product removed from cart!');
                }
            }
        }
        return redirect()->route('cart')->with('message', 'Cart updated!');
    }

    public function cart(Request $request)
    {
        App::setLocale(Session::get('language'));

        $cartlist = $request->session()->get('cartlist', []);

        $productIds = array_keys($cartlist);

        // Retrieve the product models based on the cartlist product IDs
        $products = Product::whereIn('id', $productIds)->get();
        $parts = ProductPart::whereIn('id', $productIds)->get();
        $mergedArray = $products->concat($parts);
        // Combine the product models with their respective quantities and types
        $cartlistItems = [];
        foreach ($mergedArray as $product) {
            $productId = $product->id;
            if (isset($cartlist[$productId]) && is_array($cartlist[$productId])) {
                $quantity = $cartlist[$productId]['quantity'];
                $type = $cartlist[$productId]['type'];
                $cartlistItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => $type,
                ];
            }
        }

        $carts = $cartlistItems;
        // return $carts;
        return view('frontend.pages.cart', compact('carts'));
    }

    public function cashonOrder(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'post_code' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        if (!Auth::user()) {
            return redirect()->route('login')->withErrors(['msg' => 'You need to login first']);
        }

        $cartlist = $request->session()->get('cartlist', []);
        $productIds = array_keys($cartlist);
        // Retrieve the product models based on the cartlist product IDs
        $products = Product::whereIn('id', $productIds)->get();
        $parts = ProductPart::whereIn('id', $productIds)->get();
        $mergedArray = $products->concat($parts);
        // Combine the product models with their respective quantities and types
        $cartlistItems = [];
        foreach ($mergedArray as $product) {
            $productId = $product->id;
            if (isset($cartlist[$productId]) && is_array($cartlist[$productId])) {
                $quantity = $cartlist[$productId]['quantity'];
                $type = $cartlist[$productId]['type'];
                $cartlistItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => $type,
                ];
            }
        }

        $total_price = 0;
        foreach ($cartlistItems as $item) {
            if ($item['type'] == 'product') {
                $total_price = $total_price + $item['quantity'] * (Helper::priceFaterOffer($item['product']['id']));
            } else {
                $total_price = $total_price + ($item['quantity'] * (Helper::partPriceFaterOffer($item['product']['id'])));
            }
        }

        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->date = date('Y-m-d');
        $order->total_price = $total_price;
        $order->payment_status = 0;
        $order->payment_method = '';
        $order->note = $responseValues['COMMENT1'] ?? '';
        $company = Company::where('user_id', Auth::user()->id)->first();
        $billing_information = [
            'company' => $company->name ?? '',
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'city' => $request->city,
            'state' => $request->state,
            'country' => Helper::getCountrynameByCode($request->country)
        ];

        $order->billing_information = json_encode($billing_information);

        if ($order->save()) {
            $order->refresh();
            foreach ($cartlistItems as $item) {
                $order_detail = new OrderDetail();
                $order_detail->order_id  = $order->id;
                $order_detail->product_id = $item['product']['id'];
                $order_detail->reference_id = $item['product']['id'];
                $order_detail->type = $item['type'];
                $order_detail->quantity = $item['quantity'];

                if ($item['type'] == 'product') {
                    $order_detail->unit_price = Helper::priceFaterOffer($item['product']['id']);
                } else {
                    $order_detail->unit_price = Helper::partPriceFaterOffer($item['product']['id']);
                }

                $order_detail->discount_type = $item['product']['discount_type'] ?? '';
                $order_detail->discount = $item['product']['discount'];
                if ($item['type'] == 'product') {
                    $order_detail->subtotal = $item['quantity'] * (Helper::priceFaterOffer($item['product']['id']));
                } else {
                    $order_detail->subtotal = $item['quantity'] * (Helper::partPriceFaterOffer($item['product']['id']));
                }
                $order_detail->save();
            }
            $request->session()->forget('cartlist');

            return redirect()->back()->with('message', 'Order place successfully!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function PlaceOrder(Request $request)
    {

        // get cart
        $cartlist = $request->session()->get('cartlist', []);
        $productIds = array_keys($cartlist);
        // Retrieve the product models based on the cartlist product IDs
        $products = Product::whereIn('id', $productIds)->get();
        $parts = ProductPart::whereIn('id', $productIds)->get();
        $mergedArray = $products->concat($parts);
        // Combine the product models with their respective quantities and types
        $cartlistItems = [];
        foreach ($mergedArray as $product) {
            $productId = $product->id;
            if (isset($cartlist[$productId]) && is_array($cartlist[$productId])) {
                $quantity = $cartlist[$productId]['quantity'];
                $type = $cartlist[$productId]['type'];
                $cartlistItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => $type,
                ];
            }
        }

        $total_price = 0;
        foreach ($cartlistItems as $item) {
            if ($item['type'] == 'product') {
                $total_price = $total_price + $item['quantity'] * (Helper::priceFaterOffer($item['product']['id']));
            } else {
                $total_price = $total_price + ($item['quantity'] * (Helper::partPriceFaterOffer($item['product']['id'])));
            }
        }

        $actual_price = ($total_price / 100);


        $user = Company::where('user_id', Auth::user()->id)->first();

        $config = new GpEcomConfig();
        $config->merchantId = "dev288251102910164081";
        $config->accountId = "";
        $config->sharedSecret = "BZwPfAhuBs";
        $config->serviceUrl = "https://pay.sandbox.realexpayments.com/pay";

        $config->hostedPaymentConfig = new HostedPaymentConfig();
        $config->hostedPaymentConfig->version = HppVersion::VERSION_2;
        $service = new HostedService($config);

        // Add 3D Secure 2 Mandatory and Recommended Fields
        $hostedPaymentData = new HostedPaymentData();
        $hostedPaymentData->customerFirstName = $request->name;
        $hostedPaymentData->customerEmail = $request->email;



        $hostedPaymentData->customerPhoneMobile = $request->phone;
        $hostedPaymentData->addressesMatch = false;

        $billingAddress = new Address();
        $billingAddress->streetAddress1 = $request->address;
        $billingAddress->streetAddress2 = "";
        $billingAddress->streetAddress3 = "";
        $billingAddress->city = $user->city;
        $billingAddress->postalCode = $user->post_code;
        $billingAddress->country = $request->country;

        $shippingAddress = new Address();
        $shippingAddress->streetAddress1 = $request->address;
        $shippingAddress->streetAddress2 = "";
        $shippingAddress->streetAddress3 = "";
        $shippingAddress->city = $request->city;
        $shippingAddress->state = $request->state;
        $shippingAddress->postalCode = $request->post_code;
        $shippingAddress->country = $request->country;

        try {
            $hppJson = $service->charge(0.0)
                ->withCurrency("USD")
                ->withAmount($actual_price)
                ->withDescription($request->note)
                ->withOrderId(substr(uniqid(), 0, 13) . '-ordr-' . random_int(10000000000000000, 99999999999999999))
                ->withHostedPaymentData($hostedPaymentData)
                ->withAddress($billingAddress, AddressType::BILLING)
                ->withAddress($shippingAddress, AddressType::SHIPPING)
                ->serialize();

            return $hppJson;
            // TODO: pass the HPP JSON to the client-side
        } catch (ApiException $e) {
            // TODO: Add your error handling here
        }
    }

    public function AfterOrder(Request $request)
    {
        // configure client settings
        $config = new GpEcomConfig();
        $config->merchantId = "dev288251102910164081";
        $config->accountId = "";
        $config->sharedSecret = "BZwPfAhuBs";
        $config->serviceUrl = "https://pay.sandbox.realexpayments.com/pay";

        $service = new HostedService($config);

        /*
        * TODO: grab the response JSON from the client-side.
        * sample response JSON (values will be Base64 encoded):
        * $responseJson ='{"MERCHANT_ID":"MerchantId","ACCOUNT":"internet","ORDER_ID":"GTI5Yxb0SumL_TkDMCAxQA","AMOUNT":"1999",' .
        * '"TIMESTAMP":"20170725154824","SHA1HASH":"843680654f377bfa845387fdbace35acc9d95778","RESULT":"00","AUTHCODE":"12345",' .
        * '"CARD_PAYMENT_BUTTON":"Place Order","AVSADDRESSRESULT":"M","AVSPOSTCODERESULT":"M","BATCHID":"445196",' .
        * '"MESSAGE":"[ test system ] Authorised","PASREF":"15011597872195765","CVNRESULT":"M","HPP_FRAUDFILTER_RESULT":"PASS"}";
        */
        $responseJson = isset($_POST['hppResponse']) ? $_POST['hppResponse'] : "";
        try {
            $parsedResponse = $service->parseResponse($responseJson, true);


            $orderId = $parsedResponse->orderId; // GTI5Yxb0SumL_TkDMCAxQA
            $responseCode = $parsedResponse->responseCode; // 00
            $responseMessage = $parsedResponse->responseMessage; // [ test system ] Authorised
            $responseValues = $parsedResponse->responseValues; // get values accessible by key
            $transactionReference = $parsedResponse->transactionReference; // get values accessible by key
            $authorizedAmount = $parsedResponse->authorizedAmount; // get values of amount


            $variable = json_encode($transactionReference);
            $variable = json_decode($variable);



            // order insert
            // get cart
            $cartlist = $request->session()->get('cartlist', []);
            $productIds = array_keys($cartlist);
            // Retrieve the product models based on the cartlist product IDs
            $products = Product::whereIn('id', $productIds)->get();
            $parts = ProductPart::whereIn('id', $productIds)->get();
            $mergedArray = $products->concat($parts);
            // Combine the product models with their respective quantities and types
            $cartlistItems = [];
            foreach ($mergedArray as $product) {
                $productId = $product->id;
                if (isset($cartlist[$productId]) && is_array($cartlist[$productId])) {
                    $quantity = $cartlist[$productId]['quantity'];
                    $type = $cartlist[$productId]['type'];
                    $cartlistItems[] = [
                        'product' => $product,
                        'quantity' => $quantity,
                        'type' => $type,
                    ];
                }
            }

            $total_price = 0;
            foreach ($cartlistItems as $item) {
                if ($item['type'] == 'product') {
                    $total_price = $total_price + $item['quantity'] * (Helper::priceFaterOffer($item['product']['id']));
                } else {
                    $total_price = $total_price + ($item['quantity'] * (Helper::partPriceFaterOffer($item['product']['id'])));
                }
            }

            $order = new Order();
            $order->id = $responseValues['ORDER_ID'];
            $order->user_id = Auth::user()->id;
            $order->date = date('Y-m-d');
            $order->total_price = $total_price;
            $order->payment_status = 0;
            $order->payment_method = '';
            $order->note = $responseValues['COMMENT1'] ?? '';
            $company = Company::where('user_id', Auth::user()->id)->first();
            $billing_information = [
                'company' => $company->name ?? '',
                'name' => $responseValues['HPP_CUSTOMER_FIRSTNAME'],
                'phone' => $responseValues['HPP_CUSTOMER_PHONENUMBER_MOBILE'],
                'email' => $responseValues['HPP_CUSTOMER_EMAIL'],
                'address' => $responseValues['HPP_BILLING_STREET1'],
                'post_code' => $responseValues['HPP_SHIPPING_POSTALCODE'],
                'city' => $responseValues['HPP_SHIPPING_CITY'],
                'state' => $responseValues['HPP_SHIPPING_STATE'],
                'country' => Helper::getCountrynameByCode($responseValues['HPP_SHIPPING_COUNTRY'])
            ];

            $order->billing_information = json_encode($billing_information);

            if ($order->save()) {
                $order->refresh();
                foreach ($cartlistItems as $item) {
                    $order_detail = new OrderDetail();
                    $order_detail->order_id  = $order->id;
                    $order_detail->product_id = $item['product']['id'];
                    $order_detail->reference_id = $item['product']['id'];
                    $order_detail->type = $item['type'];
                    $order_detail->quantity = $item['quantity'];

                    if ($item['type'] == 'product') {
                        $order_detail->unit_price = Helper::priceFaterOffer($item['product']['id']);
                    } else {
                        $order_detail->unit_price = Helper::partPriceFaterOffer($item['product']['id']);
                    }

                    $order_detail->discount_type = $item['product']['discount_type'] ?? '';
                    $order_detail->discount = $item['product']['discount'];
                    if ($item['type'] == 'product') {
                        $order_detail->subtotal = $item['quantity'] * (Helper::priceFaterOffer($item['product']['id']));
                    } else {
                        $order_detail->subtotal = $item['quantity'] * (Helper::partPriceFaterOffer($item['product']['id']));
                    }
                    $order_detail->save();
                }
                $request->session()->forget('cartlist');

                if ($responseCode == '00') {
                    $update_order = Order::find($order->id);
                    $update_order->payment_method = 'Online payment';
                    $update_order->transaction_id = $variable->transactionId;
                    $update_order->payment_status = 1;
                    $update_order->save();

                    $transaction = new Transaction();
                    $transaction->order_id = $order->id;
                    $transaction->transaction_id = $variable->transactionId;
                    $transaction->amount = $authorizedAmount;
                    $transaction->response = $responseJson;
                    $transaction->save();
                } else {
                    $update_order = Order::find($order->id);
                    $update_order->payment_method = 'Online payment';
                    $update_order->transaction_id = $variable->transactionId;
                    $update_order->save();

                    $transaction = new Transaction();
                    $transaction->order_id = $order->id;
                    $transaction->transaction_id = $variable->transactionId;
                    $transaction->amount = $authorizedAmount;
                    $transaction->status = 0;
                    $transaction->response = $responseJson;
                    $transaction->save();
                }

                return redirect()->back()->with('message', 'Order place successfully!');
            }
        } catch (ApiException $e) {
            return $e;
            // For example if the SHA1HASH doesn't match what is expected
            // TODO: add your error handling here
        }
    }

    private function generateHash($data, $secret)
    {
        $toHash = [];
        $timeStamp           = !isset($data['TIMESTAMP']) ? "" : $data['TIMESTAMP'];
        $merchantId          = !isset($data['MERCHANT_ID']) ? "" : $data['MERCHANT_ID'];
        $orderId             = !isset($data['ORDER_ID']) ? "" : $data['ORDER_ID'];
        $amount              = !isset($data['AMOUNT']) ? "" : $data['AMOUNT'];
        $currency            = !isset($data['CURRENCY']) ? "" : $data['CURRENCY'];
        $payerReference      = !isset($data['PAYER_REF']) ? "" : $data['PAYER_REF'];
        $paymentReference    = !isset($data['PMT_REF']) ? "" : $data['PMT_REF'];
        $hppSelectStoredCard = !isset($data['HPP_SELECT_STORED_CARD']) ? "" : $data['HPP_SELECT_STORED_CARD'];
        $payRefORStoredCard  = empty($hppSelectStoredCard) ?  $payerReference : $hppSelectStoredCard;

        if (isset($data['CARD_STORAGE_ENABLE']) && $data['CARD_STORAGE_ENABLE'] === '1') {
            $toHash = [
                $timeStamp,
                $merchantId,
                $orderId,
                $amount,
                $currency,
                $payerReference,
                $paymentReference,
            ];
        } elseif ($payRefORStoredCard && empty($paymentReference)) {
            $toHash = [
                $timeStamp,
                $merchantId,
                $orderId,
                $amount,
                $currency,
                $payRefORStoredCard,
                ""
            ];
        } elseif ($payRefORStoredCard && !empty($paymentReference)) {
            $toHash = [
                $timeStamp,
                $merchantId,
                $orderId,
                $amount,
                $currency,
                $payRefORStoredCard,
                $paymentReference,
            ];
        } else {
            $toHash = [
                $timeStamp,
                $merchantId,
                $orderId,
                $amount,
                $currency,
            ];
        }

        return sha1(sha1(implode('.', $toHash)) . '.' . $secret);
    }


    public function wishlist(Request $request)
    {
        App::setLocale(Session::get('language'));
        $wishlist = $request->session()->get('wishlist', []);
        $productIds = array_keys($wishlist);
        // Retrieve the product models based on the wishlist product IDs
        $products = Product::whereIn('id', $productIds)->get();
        $parts = ProductPart::whereIn('id', $productIds)->get();
        $mergedArray = $products->concat($parts);
        // Combine the product models with their respective quantities and types
        $wishlistItems = [];
        foreach ($mergedArray as $product) {
            $productId = $product->id;
            if (isset($wishlist[$productId]) && is_array($wishlist[$productId])) {
                $quantity = $wishlist[$productId]['quantity'];
                $type = $wishlist[$productId]['type'];
                $wishlistItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'type' => $type,
                ];
            }
        }
        $carts = $wishlistItems;
        return view('frontend.pages.wishlist', compact('wishlistItems'));
    }

    public function AddTowishlist($type, $id, Request $request)
    {
        $productId = $id;
        $quantity = 1;

        if ($request->session()->has('cartlist')) {
            $cartlist = $request->session()->get('cartlist');

            if (isset($cartlist[$productId])) {
                return response()->json(['status' => 'error', 'message' => 'Product is already in your cart!']);
            }
        }

        if ($request->session()->has('wishlist')) {
            $wishlist = $request->session()->get('wishlist');

            if (isset($wishlist[$productId])) {
                unset($wishlist[$productId]);
                $request->session()->put('wishlist', $wishlist);
                return response()->json(['status' => 'error', 'message' => 'Product remove from wishlist!']);
            }

            // Add the product to the wishlist
            $wishlist[$productId] = [
                'quantity' => $quantity,
                'type' => $type
            ];
        } else {
            $wishlist = [
                $productId => [
                    'quantity' => $quantity,
                    'type' => $type
                ]
            ];
        }

        $request->session()->put('wishlist', $wishlist);

        return response()->json(['status' => 'success', 'message' => 'Product added to wishlist!']);
    }

    // wishlist-badge

    public function getWishlistCount(Request $request)
    {
        $wishlistCount = count($request->session()->get('wishlist', []));
        return response()->json($wishlistCount);
    }


    public function removeFromWishlist($product_id, Request $request)
    {
        $productId = $product_id;

        if ($request->session()->has('wishlist')) {
            $wishlist = $request->session()->get('wishlist');

            if (isset($wishlist[$productId])) {
                unset($wishlist[$productId]);
                $request->session()->put('wishlist', $wishlist);
            }
        }

        return redirect()->route('wishlist')->with('message', 'Product remove from wishlist!');
    }

    public function incrementWishlist($product_id, Request $request)
    {
        $productId = $product_id;
        if ($request->session()->has('wishlist')) {
            $wishlist = $request->session()->get('wishlist');

            if (isset($wishlist[$productId])) {
                $wishlist[$productId]['quantity']++;
                $request->session()->put('wishlist', $wishlist);
            }
        }
        return redirect()->route('wishlist')->with('message', 'Quantity updated!');
    }

    public function decrementWishlist($product_id, Request $request)
    {
        $productId = $product_id;
        if ($request->session()->has('wishlist')) {
            $wishlist = $request->session()->get('wishlist');

            if (isset($wishlist[$productId])) {
                if ($wishlist[$productId]['quantity'] > 1) {
                    $wishlist[$productId]['quantity']--;
                    $request->session()->put('wishlist', $wishlist);
                    return redirect()->route('wishlist')->with('message', 'Wishlist quantity decrement!');
                } else {
                    // If the quantity is already 1, you can consider removing the item from the wishlist
                    unset($wishlist[$productId]);
                    $request->session()->put('wishlist', $wishlist);
                    return redirect()->route('wishlist')->with('message', 'Product removed from wishlist!');
                }
            }
        }
        return redirect()->route('wishlist')->with('message', 'Wishlist updated!');
    }

    public function AddToInquiry($product_id, Request $request)
    {
        $productId = $product_id;
        $quantity = 1;

        if ($request->session()->has('inquirylist')) {
            $inquirylist = $request->session()->get('inquirylist');

            if (isset($inquirylist[$productId])) {
                return redirect()->route('inquiry')->with('message', 'Product is already in your inquiry list!');
            }

            $inquirylist[$productId] = $quantity;
        } else {
            $inquirylist = [$productId => $quantity];
        }

        $request->session()->put('inquirylist', $inquirylist);

        return redirect()->route('inquiry')->with('message', 'Product added to inquiry list!');
    }

    public function inquiry(Request $request)
    {
        App::setLocale(Session::get('language'));
        $inquirylist = $request->session()->get('inquirylist', []);

        $productIds = array_keys($inquirylist);

        $products = Product::whereIn('id', $productIds)->get();

        // Combine the product models with their respective quantities
        $inquirylistItems = [];
        foreach ($products as $product) {
            $productId = $product->id;
            $quantity = $inquirylist[$productId];
            $inquirylistItems[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }

        return view('frontend.pages.inquiry', compact('inquirylistItems'));
    }

    public function removeFromInquirylist($product_id, Request $request)
    {
        $productId = $product_id;

        if ($request->session()->has('inquirylist')) {
            $inquirylist = $request->session()->get('inquirylist');

            if (isset($inquirylist[$productId])) {
                unset($inquirylist[$productId]);
                $request->session()->put('inquirylist', $inquirylist);
            }
        }

        return redirect()->route('inquiry')->with('message', 'Product remove from inquiry list!');
    }

    public function incrementInquirylist($product_id, Request $request)
    {
        $productId = $product_id;
        if ($request->session()->has('inquirylist')) {
            $inquirylist = $request->session()->get('inquirylist');

            if (isset($inquirylist[$productId])) {
                $inquirylist[$productId]++;
                $request->session()->put('inquirylist', $inquirylist);
            }
        }
        return redirect()->route('inquiry')->with('message', 'Quantity updated!');
    }

    public function decrementInquirylist($product_id, Request $request)
    {
        $productId = $product_id;
        if ($request->session()->has('inquirylist')) {
            $inquirylist = $request->session()->get('inquirylist');

            if (isset($inquirylist[$productId])) {
                $inquirylist[$productId]--;

                if ($inquirylist[$productId] <= 0) {
                    unset($inquirylist[$productId]);
                }

                $request->session()->put('inquirylist', $inquirylist);
            }
        }
        return redirect()->route('inquiry')->with('message', 'Quantity updated!');
    }

    public function inquiryRequest()
    {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.inquiryRequest');
    }

    public function inquiryRequestSend(Request $request)
    {

        $validator = $request->validate([
            'company' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'address' => 'required',
            'post_code' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        $inquiry = new Inquiry();
        $inquiry->user_id = Auth::user()->id ?? '';
        $inquiry->date = date('Y-m-d');
        $inquiry->request_by = $request->name;
        $inquiry->note = $request->note ?? '';

        $address_information = [
            'company' => $request->company,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country
        ];

        $inquiry->address_information = json_encode($address_information);

        if ($inquiry->save()) {
            $inquiry->refresh();

            $inquirylist = $request->session()->get('inquirylist', []);
            $productIds = array_keys($inquirylist);
            $products = Product::whereIn('id', $productIds)->get();
            $inquirylistItems = [];
            foreach ($products as $product) {
                $productId = $product->id;
                $quantity = $inquirylist[$productId];
                $inquirylistItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }

            foreach ($inquirylistItems as $item) {
                $inquiry_products = new InquiryProduct();
                $inquiry_products->inquiry_id  = $inquiry->id;
                $inquiry_products->product_id = $item['product']['id'];
                $inquiry_products->quantity = $item['quantity'];
                $inquiry_products->unit_price = $item['product']['price'];
                $inquiry_products->note = '';
                $inquiry_products->subtotal = $item['product']['price'] * $item['quantity'];
                $inquiry_products->save();
            }

            $request->session()->forget('inquirylist');

            return redirect()->back()->with('message', 'Inquiry request submited!');
        }
    }

    public function catalogues()
    {
        App::setLocale(Session::get('language'));
        $brands = Brand::where('status', 1)->get();
        $categories = Category::where('status', 1)->where('is_parent', 1)->get();
        return view('frontend.pages.catalogues', compact('brands', 'categories'));
    }

    public function searchCatalogue(Request $request)
    {
        App::setLocale(Session::get('language'));
        $catalogues = Catalogue::where('status', 1);

        if ($request->brand) {
            $catalogues->where(function ($catalogues) use ($request) {
                $catalogues->whereIn('brand_id', $request->brand);
            });
        }

        if ($request->title) {
            $catalogues->where('title', 'like', "%" . $request->title . "%");
        }

        $catalogues = $catalogues->orderBy('brand_id', 'asc')->get();
        return view('frontend.pages.search.catalogues', compact('catalogues'));
    }

    public function downloadCatalogue($catalogue_id)
    {
        $catalogue = Catalogue::find($catalogue_id);
        if (file_exists(public_path('uploads/catalogue-files/' . $catalogue->file))) {
            return response()->download(public_path('uploads/catalogue-files/' . $catalogue->file));
        } else {
            return redirect()->back();
        }
    }

    public function viewCatalogue($catalogue_id)
    {
        App::setLocale(Session::get('language'));
        $catalogue = Catalogue::find($catalogue_id);
        return view('frontend.pages.catalogue-view', compact('catalogue'));
    }

    public function news()
    {
        App::setLocale(Session::get('language'));
        $months = DB::select(DB::raw("SELECT 
                                CONCAT(month, '-', year) AS month_year,
                                news_count
                            FROM (
                                SELECT 
                                    MONTH(publish_date) AS month,
                                    YEAR(publish_date) AS year,
                                    COUNT(*) AS news_count
                                FROM 
                                    news
                                GROUP BY 
                                    MONTH(publish_date), YEAR(publish_date)
                            ) AS subquery
                            ORDER BY 
                                year, month"));
        return view('frontend.pages.news', compact('months'));
    }
    public function newsDetails($news_id)
    {
        App::setLocale(Session::get('language'));

        $news = News::findOrFail($news_id);
        $imagesArray = json_decode($news->gallery_images, true);
        $news->gallery_images = $imagesArray;

        return view('frontend.pages.newsDetails', compact('news'));
    }
    public function searchNews(Request $request)
    {
        App::setLocale(Session::get('language'));
        $news = News::where('status', 1);

        if ($request->year) {
            $news->where(function ($news) use ($request) {
                foreach ($request->year as $date) {
                    $year = date('Y', strtotime($date));
                    $month = date('m', strtotime($date));

                    $news->orWhere(function ($news) use ($year, $month) {
                        $news->whereYear('publish_date', $year)->whereMonth('publish_date', $month);
                    });
                }
            });
        }

        if ($request->category) {
            $news->where(function ($news) use ($request) {
                $news->whereIn('category', $request->category);
            });
        }

        if ($request->title) {
            $news->where('title', 'like', "%" . $request->title . "%");
        }


        $news = $news->get();
        return view('frontend.pages.search.news', compact('news'));
    }



    public function services()
    {
        App::setLocale(Session::get('language'));
        $services = Service::where('status', 1)->get();
        return view('frontend.pages.services', compact('services'));
    }
    public function serviceDetails($id)
    {
        App::setLocale(Session::get('language'));
        $service = Service::find($id);
        return view('frontend.pages.serviceDetails', compact('service'));
    }

    public function AddToService($service_id, Request $request)
    {
        if ($request->session()->has('servicelist')) {
            $servicelist = $request->session()->get('servicelist');

            if (in_array($service_id, $servicelist)) {
                return redirect()->route('service.order');
            }

            $servicelist[] = $service_id;
        } else {
            $servicelist = [$service_id];
        }
        $request->session()->put('servicelist', $servicelist);

        return redirect()->route('service.order');
    }

    public function serviceOrder()
    {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.service-order');
    }

    public function serviceOrderSend(Request $request)
    {
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'address' => 'required',
        ]);

        $order = new ServiceOrder();
        $order->user_id = Auth::user()->id ?? null;
        $order->date = date('Y-m-d');
        $order->name = $request->name;
        $order->company_name = $request->company_name ?? '';
        $order->email = $request->email;
        $order->address = $request->address;
        $order->message = $request->note;

        $service_information = [];


        $servicelist = $request->session()->get('servicelist', []);
        $service = Service::whereIn('id', $servicelist)->get();

        foreach ($service as $row) {
            $service = Service::find($row->id);

            if ($request->hasFile('thumbnail')) {
                $thumbnail = $request->file('thumbnail');
                $filename = time() . uniqid() . $thumbnail->getClientOriginalName();
                $thumbnail->move(public_path('uploads/service-order'), $filename);
                $file = $filename;
            } else {
                $file = '';
            }

            $services = [
                'service_id' => $service->id,
                'name' => $service->title,
                'code' => $service->code,
                'file' => $file
            ];

            array_push($service_information, $services);
        }
        $order->service_information = json_encode($service_information);

        if ($order->save()) {
            $request->session()->forget('servicelist');

            return redirect()->back()->with('message', 'Order place successfully!');
        } else {
            return redirect()->back()->with('error', 'Something went wrong!');
        }
    }

    public function order()
    {
        App::setLocale(Session::get('language'));
        $countrycodes = Helper::getCountryCodes();
        return view('frontend.pages.order', compact('countrycodes'));
    }
    public function pdf()
    {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.pdf');
    }
    public function calculator()
    {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.calculator');
    }
    public function forgotPassword()
    {
        App::setLocale(Session::get('language'));
        return view('frontend.pages.forgotPassword');
    }

    public function resetOtpSend(Request $request)
    {

        if (User::where('email', $request->email)->exists()) {
            $email = $request->email;
            $otps = random_int(100000, 999999);
            $subject = 'Password Reset';
            $data['user'] = User::where('email', $request->email)->first();
            $data['otp'] = $otps;
            $data['message'] = 'Your confirmation code is below — enter it in your open browser window and we will help you get changed password. Please do not share the code others';
            Helper::sendEmail($email, $subject, $data, 'resetpassword');

            $otp = new Otp();
            $otp->email = $request->email;
            $otp->otp = $otps;
            $otp->save();

            return view('frontend.pages.otp', compact('email'));
        } else {
            return redirect()->back()->withErrors(['message' => 'There is no account with this email!']);
        }
    }

    public function otp(Request $request)
    {
        App::setLocale(Session::get('language'));
        if ($request->email && $request->otp) {
            Validator::make($request->all(), [
                'email' => 'required',
                'otp' => 'required',
                'password' => 'required',
                'password_confirmation' => 'required_with:password|same:password|min:6',
            ]);

            if (Helper::checkotp($request->email, $request->otp)) {
                $email = $request->email;
                $user = User::where('email', $request->email)->first();
                $user->password = Hash::make($request->password);
                if ($user->save()) {
                    $otp = Otp::where('email', $request->email)->where('otp', $request->otp)->first();
                    $otp->status = 1;
                    $otp->save();
                    return redirect()->route('admin')->with(['message' => 'Password changed successfully!']);
                } else {
                    return view('frontend.pages.otp', compact('email'))->with(['message' => 'OTP invalid or expaired!']);
                }
            } else {
                return view('frontend.pages.otp', compact('email'))->with(['message' => 'OTP invalid or expaired!']);
            }
        } else {
            return view('frontend.pages.otp');
        }
    }

    public function pages($slug)
    {
        App::setLocale(Session::get('language'));
        $content = '';
        if ($slug == 'terms-and-conditions') {
            $content = Helper::getSettings('terms_and_conditions');
        } else if ($slug == 'privacy-policy') {
            $content = Helper::getSettings('privacy_policy');
        } else if ($slug == 'return-policy') {
            $content = Helper::getSettings('return_policy');
        }
        return view('frontend.pages.page', compact('slug', 'content'));
    }

    public function changeLanguage(Request $request)
    {

        $language = $request->input('language');

        Session::put('language', $language);

        return true;
    }

    public function Search(Request $request)
    {
        App::setLocale(Session::get('language'));
        $search_text = $request->search_text;
        $products = Product::where('status', 1);
        if (!empty($search_text)) {
            $products->where(function ($query) use ($search_text) {
                $query->where('name', 'like', "%" . $search_text . "%")
                    ->orWhere('code', 'like', "%" . $search_text . "%")
                    ->orWhereHas('category', function ($q) use ($search_text) {
                        $q->where('title', 'like', "%" . $search_text . "%");
                    });
            });
        }
        //$products = $products->get();
        $products = $products->orderby('code', 'asc')->get();
        return view('frontend.pages.search.search', compact('search_text', 'products'));
    }

    public function Brands($brand_id)
    {
        App::setLocale(Session::get('language'));
        $brand = Brand::find($brand_id);
        $products = Product::where('brand_id', $brand_id)->where('status', 1)->get();
        return view('frontend.pages.brand-product', compact('brand', 'products'));
    }

    public function catchAll()
    {

        return view('frontend.pages.error');
    }
}
