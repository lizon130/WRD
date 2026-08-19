<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Paper</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/d735ae1186.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }

        .exam-card {
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #fff;
            padding: 20px;
        }

        .exam-header {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }

        .exam-body {
            padding: 20px;
        }

        .submit-btn {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container mt-5 mb-5 @if ($examLog && $examLog->result_published != 0) d-flex justify-content-center align-items-center my-5" style="height: 100vh;" @endif>
        <div class="card
        exam-card">
        @if ($examLog && $examLog->result_published == 0)
            <div class="exam-header">
                <h3>{{ $exam->name }}</h3>
                <p>
                    <strong>Exam Date:</strong> {{ $exam->start_date }} to {{ $exam->end_date }}<br>
                    <strong>Time:</strong> {{ $exam->time_limit }} minutes<br>
                    <strong>Pass Marks:</strong> {{ $exam->pass_marks }}
                </p>
            </div>

            <div class="card-body exam-body">
                <div id="timer" class="text-center my-4">
                    <h4>Time Remaining: <span id="countdown"></span></h4>
                </div>
                <hr>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('errorDetails'))
                    <div class="alert alert-danger">
                        <strong>Error Details:</strong> {{ session('errorDetails') }}
                    </div>
                @endif

                <div class="col-sm-12">
                    <div class="server_side_error" role="alert">

                    </div>
                </div>
                <form action="{{ route('exam.paper.submit', ['id' => $exam->id, 'ip' => $examLog->ip_address]) }}"
                    id="examForm" method="POST">
                    @csrf
                    <!-- User Information Section -->
                    <div class="mb-4">
                        <h5>User Information</h5>
                        <div class="row">
                            <div class="col-lg-3 ">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-lg-3 ">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-lg-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="col-lg-3 ">
                                <label for="organization" class="form-label">Organization</label>
                                <input type="text" class="form-control" id="organization" name="organization"
                                    required>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Exam Questions Section -->
                    @foreach ($questions as $question)
                        <div class="mb-4">
                            <h5>{{ $loop->iteration }}. {{ $question->title }}</h5>

                            <!-- Add a hidden input for unanswered radio buttons or checkboxes -->
                            <input type="hidden" name="answers[{{ $question->id }}]" value="">

                            @if ($question->question_type == 2)
                                @foreach ($question->options as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio"
                                            name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                            id="option-{{ $option->id }}">
                                        <label class="form-check-label" for="option-{{ $option->id }}">
                                            {{ $option->title }}
                                        </label>
                                    </div>
                                @endforeach
                            @elseif ($question->question_type == 1)
                                @foreach ($question->options as $option)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="answers[{{ $question->id }}][]" value="{{ $option->id }}"
                                            id="option-{{ $option->id }}">
                                        <label class="form-check-label" for="option-{{ $option->id }}">
                                            {{ $option->title }}
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <textarea class="form-control" name="answers[{{ $question->id }}]" rows="3"></textarea>
                            @endif
                        </div>
                    @endforeach

                    <!-- Submit Button -->
                    <div class="submit-btn">
                        <button type="submit" id="submitExamBtn" class="btn btn-success">Submit</button>
                    </div>
                </form>
            </div>
        @elseif ($examLog && $examLog->result_published == 1)
            <div class="card-body text-center">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="rounded-circle bg-success p-4 mb-4" style="height: 100px; width: 100px;">
                        <i class="fa fa-check text-white" style="font-size: 40px;"></i>
                    </div>
                </div>
                <h5 class="card-title">Exam Submitted!</h5>
                <p class="card-text">You have successfully submitted the exam (<b>{{ $exam->name }}</b>) paper. <br>
                    Your final result will be published soon, along with your position. Please stay with us and
                    <br>continue to check this link for updates.
                </p>

                @if ($exam->instant_result == 1)
                    <h4>Your Score:</h4>
                    <p class="card-text">Your final score is out of {{ $examLog->answers->count() }}.</p>
                    <a href="#" class="btn btn-outline-success btn-block">{{ $examLog->achieve_mark }}</a>
                @endif

            </div>
        @elseif ($examLog && $examLog->result_published == 2)
            <div class="card-body text-center">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="rounded-circle bg-success p-4 mb-4" style="height: 100px; width: 100px;">
                        <i class="fa fa-check text-white" style="font-size: 40px;"></i>
                    </div>
                </div>
                <h5 class="card-title">Result Published!</h5>
                <p class="card-text">
                    The final result of the exam (<b>{{ $exam->name }}</b>) has been published. <br>
                    We have calculated your position based on your answers and submission time.
                </p>

                <h4>Your Position:</h4>
                <p class="card-text">
                    Your final position out of {{ $number_of_user_attend }} participants is:
                </p>
                <a href="#" class="btn btn-outline-success btn-block">
                    @php
                        // Function to add ordinal suffix
                        function addOrdinalSuffix($number)
                        {
                            if (!in_array($number % 100, [11, 12, 13])) {
                                switch ($number % 10) {
                                    case 1:
                                        return $number . 'st';
                                    case 2:
                                        return $number . 'nd';
                                    case 3:
                                        return $number . 'rd';
                                }
                            }
                            return $number . 'th';
                        }
                    @endphp

                    @if ($examLog->position == 1)
                        <i class="fa fa-trophy text-warning" style="margin-right: 5px;"></i>
                    @endif
                    {{ addOrdinalSuffix($examLog->position) }}
                </a>
            </div>
        @elseif (($examLog && $examLog->result_published == 0) || $exam->result_published != 1)
            <div class="exam-header">
                <h3>{{ $exam->name }}</h3>
                <p>
                    <strong>Exam Date:</strong> {{ $exam->start_date }} to {{ $exam->end_date }}<br>
                    <strong>Time:</strong> {{ $exam->time_limit }} minutes<br>
                    <strong>Pass Marks:</strong> {{ $exam->pass_marks }}
                </p>
            </div>

            {!! $exam->description !!}

            <label for="termsaandcondition"><input type="checkbox" id="termsaandcondition"> I have read all the
                instruction and want to start the exam!</label>
            <br>
            <a href="{{ route('exam.paper', $exam->slug) }}?start_exam=1" class="btn btn-success w-25 mt-2"> Start
                Exam</a>
        @else
            <p>Exam already finished!</p>
        @endif
    </div>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Countdown timer
        document.addEventListener('DOMContentLoaded', function() {
            const countdownElement = document.getElementById('countdown');
            const examEndTime = new Date(Date.now() + {{ $remainingTime }} *
                1000); // Convert remaining seconds to milliseconds

            function updateCountdown() {
                const now = new Date();
                const diff = examEndTime - now;

                if (diff <= 0) {
                    countdownElement.textContent = "Time's up!";
                    document.querySelector('form').submit(); // Auto-submit when time runs out
                    return;
                }

                const minutes = Math.floor(diff / 1000 / 60);
                const seconds = Math.floor((diff / 1000) % 60);
                countdownElement.textContent = `${minutes}m ${seconds}s`;
            }

            setInterval(updateCountdown, 1000); // Update every second
        });

        $(document).on('click', '#submitExamBtn', function(e) {
            e.preventDefault();

            // Disable the submit button to prevent multiple clicks
            $(this).prop('disabled', true);

            // SweetAlert for confirmation
            Swal.fire({
                title: 'Do you agree to submit the exam?',
                text: "Once submitted, you cannot change your answers.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit it!',
                cancelButtonText: 'No, cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('examForm');
                    var formData = new FormData(form);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: $('#examForm').attr('action'),
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000, // Auto-close after 2 seconds
                                showConfirmButton: false // Hides the confirm button
                            }).then(() => {
                                // Reload the current URL after success
                                window.location.reload();
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = '';
                            $.each(xhr.responseJSON.errors, function(key, value) {
                                errorMessage += (value + '<br>');
                            });
                            Swal.fire({
                                title: 'Error!',
                                text: errorMessage,
                                icon: 'error',
                            });
                        },
                        // Enable the submit button again in case of error
                        complete: function() {
                            $('#submitExamBtn').prop('disabled', false);
                        }
                    });
                } else {
                    // Enable the submit button if the user cancels the action
                    $('#submitExamBtn').prop('disabled', false);
                }
            });
        });
    </script>
</body>

</html>
