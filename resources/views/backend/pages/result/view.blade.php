@extends('backend.layout.app')
@section('title', 'Relust | ' . Helper::getSettings('application_name') ?? 'Nex Academy')
@section('content')
<div class="container-fluid px-4">
    <h4 class="mt-2">Result Management</h4>

    <div class="card shadow-lg">
        <div class="card-header bg-success text-white text-center">
            <h4>Exam Result</h4>
        </div>
        <div class="card-body">

            <!-- Participant Details -->
            <div class="mb-4">
                <h4>Participant Details:</h4>
                <p class="card-text">
                    <b>Name:</b> {{ $result->name ?? 'N/A' }} <br>
                    <b>Email:</b> {{ $result->email ?? 'N/A' }} <br>
                    <b>Phone:</b> {{ $result->phone ?? 'N/A' }} <br>
                    <b>Organization:</b> {{ $result->organization ?? 'N/A' }} 
                </p>
            </div>
            <!-- Exam Details -->
            <div class="mb-4">
                <h5 class="card-title">Exam: <b>{{ $result->exam->name }}</b></h5>
                <p class="card-text">This is the final result for the exam.</p>
            </div>

            <!-- Score Section -->
            <div class="mb-4">
                <h4>Participant's Score:</h4>
                <p class="card-text">The participant scored <b>{{ $result->achieve_mark }}</b> out of 
                    <b>{{ $result->total_marks }}</b>.
                </p>
            </div>

            <!-- Position Section -->
            <div class="mb-4">
                <h4>Participant's Position:</h4>
                <p class="card-text">
                    The participant's final position out of <b>{{ $number_of_user_attend }}</b> participants is 
                    <b>{{ $position }}</b>.
                </p>
            </div>

            <!-- Answer Breakdown -->
            <div class="mb-4">
                <h4>Answer Breakdown:</h4>
                @foreach ($result->answers as $answer)
                    <div class="mb-4">
                        <h5 class="@if($answer->status == 1 && $answer->right_wrong == 1) text-success @elseif($answer->status == 1 && $answer->right_wrong == 0) text-danger @endif" >{{ $loop->iteration }}. {{ $answer->question->title }}</h5>
            
                        @php
                            // Ensure $answer->answer is an array for comparison
                            $selectedAnswers = is_array($answer->answear) ? $answer->answear : explode(',', $answer->answear);
                            // var_dump($selectedAnswers);
                        @endphp
            
                        @if ($answer->question->question_type == 2) <!-- Single-choice -->
                            @foreach ($answer->question->options as $option)
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="radio" 
                                        @if(in_array($option->id, $selectedAnswers)) checked @endif 
                                        disabled>
                                    <label class="form-check-label">
                                        {{ $option->title }}
                                    </label>
                                </div>
                            @endforeach
                        @elseif ($answer->question->question_type == 1) <!-- Multiple-choice -->
                            @foreach ($answer->question->options as $option)
                                <div class="form-check">
                                    <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        @if(in_array($option->id, $selectedAnswers)) checked @endif 
                                        disabled>
                                    <label class="form-check-label">
                                        {{ $option->title }}
                                    </label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
