<?php
include "connection.php";

// Fetch random questions for a specific exam
$student_id = $_GET['student_id']; // Assume this is dynamically retrieved
$exam_id = $_GET['exam_id']; // Set the exam_id dynamically in real scenarios

$query_exam = "SELECT * FROM exams WHERE id = ?";
$stmt_exam = $conn->prepare($query_exam);
$stmt_exam->bind_param("i", $exam_id);
$stmt_exam->execute();
$result_exam = $stmt_exam->get_result();
$exam = $result_exam->fetch_assoc();

// Fetch questions in random order
$query_questions = "SELECT * FROM questions WHERE exam_id = ? ORDER BY RAND()";
$stmt_questions = $conn->prepare($query_questions);
$stmt_questions->bind_param("i", $exam_id);
$stmt_questions->execute();
$result_questions = $stmt_questions->get_result();
$questions = $result_questions->fetch_all(MYSQLI_ASSOC);

// Fetch student details
$query_student = "SELECT full_name, photo_path FROM exam_application WHERE user_id = ?";
$stmt_student = $conn->prepare($query_student);
$stmt_student->bind_param("i", $student_id);
$stmt_student->execute();
$student_result = $stmt_student->get_result();
$student = $student_result->fetch_assoc();

// Auto submit feature
$exam_end = $exam['exam_end'];
$current_datetime = new DateTime();
$exam_end_datetime = new DateTime($exam_end);

// Check if current datetime equals exam_end
if ($current_datetime >= $exam_end_datetime) {
    echo "<script>
        document.getElementById('submitButton').click();
        window.location.href = 'submit_success.html';
    </script>";
}

//$stmt->close();
$conn->close();




// Close statements
$stmt_exam->close();
$stmt_questions->close();
$stmt_student->close();
//$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Randomized Exam Questions</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .status-panel {
            height: 100vh;
            overflow-y: auto;
            border-right: 1px solid #ddd;
        }

        .status-item {
            cursor: pointer;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            margin: 5px 0;
        }

        .status-item.solved {
            background-color: #d4edda;
        }

        .status-item.doubt {
            background-color: #fff3cd;
        }

        .status-item.skipped {
            background-color: #f8d7da;
        }

        .status-item.unanswered {
            background-color: #e2e3e5;
        }

        .question-panel {
            height: 100vh;
            overflow-y: auto;
        }

        .submit-container {
            display: none;
            text-align: center;
            margin-top: 20px;
        }

        .student-photo {
            height: 90px;
            width: 90px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Status Panel -->
            <div class="col-md-2 status-panel">
                <div id="statusContainer"></div>
            </div>

            <!-- Question Panel -->
            <div class="col-md-10 question-panel p-4">
                <div id="questionContainer"></div>
                <div id="submitContainer" class="submit-container">
                    <button id="submitButton" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const questions = <?php echo json_encode($questions); ?>;
            const statusContainer = document.getElementById('statusContainer');
            const questionContainer = document.getElementById('questionContainer');
            const submitContainer = document.getElementById('submitContainer');
            const userResponses = {};
            let currentQuestionIndex = 0;

            function renderStatusPanel() {
                statusContainer.innerHTML = questions.map((_, index) => `
            <div class="status-item unanswered" id="status-${index}" onclick="navigateToQuestion(${index})">
                Q${index + 1}
            </div>
        `).join('');
            }

            function updateStatus(index, status) {
                const statusItem = document.getElementById(`status-${index}`);
                statusItem.className = `status-item ${status}`;
            }

            function renderQuestion(index) {
                currentQuestionIndex = index;
                const question = questions[index];
                const options = [
                    { label: 'A', value: question.option_a },
                    { label: 'B', value: question.option_b },
                    { label: 'C', value: question.option_c },
                    { label: 'D', value: question.option_d }
                ];

                const previousResponse = userResponses[question.question_id];

                questionContainer.innerHTML = `
            <p><strong>Q${index + 1}: ${question.question_text}</strong></p>
            ${options.map(option => `
                <label>
                    <input type="radio" name="response[${question.question_id}]" value="${option.label}" 
                    ${previousResponse?.selected_option === option.label ? 'checked' : ''}>
                    ${option.label}: ${option.value}
                </label><br>
            `).join('')}
            <button class="btn btn-success mt-3" onclick="saveResponse(${index}, 'solved')">Solve with Confidence</button>
            <button class="btn btn-warning mt-3" onclick="saveResponse(${index}, 'doubt')">Mark with Doubt</button>
            <button class="btn btn-danger mt-3" onclick="skipQuestion(${index})">Skip</button>
        `;
            }

            function saveResponse(index, status) {
                const question = questions[index];
                const selectedOption = document.querySelector(`input[name="response[${question.question_id}]"]:checked`);

                if (!selectedOption && status !== 'skipped') {
                    alert('Please select an option before proceeding.');
                    return;
                }

                userResponses[question.question_id] = {
                    selected_option: selectedOption ? selectedOption.value : null,
                    status: status
                };

                updateStatus(index, status);
                navigateToNextQuestion();
            }

            function skipQuestion(index) {
                const question = questions[index];

                userResponses[question.question_id] = {
                    selected_option: null,
                    status: 'skipped'
                };

                updateStatus(index, 'skipped');
                navigateToNextQuestion();
            }

            function navigateToNextQuestion() {
                if (currentQuestionIndex < questions.length - 1) {
                    currentQuestionIndex++;
                    renderQuestion(currentQuestionIndex);
                } else {
                    alert('You have visited all the questions.');
                    submitContainer.style.display = 'block';
                }
            }

            window.navigateToQuestion = function (index) {
                renderQuestion(index);
            };

            // Expose functions globally
            window.saveResponse = saveResponse;
            window.skipQuestion = skipQuestion;

            submitContainer.querySelector('#submitButton').addEventListener('click', () => {
                const responses = Object.entries(userResponses).map(([question_id, response]) => ({
                    question_id: parseInt(question_id),
                    selected_option: response.selected_option,
                    response_status: response.status
                }));

                // Debugging: Log the data being sent
                console.log({
                    exam_id: <?php echo $exam_id; ?>,
                    student_id: <?php echo $student_id; ?>,
                    responses: responses
                });

                fetch('save_responses.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        exam_id: <?php echo $exam_id; ?>,
                        student_id: <?php echo $student_id; ?>,
                        responses: responses
                    })
                }).then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            alert('Responses saved successfully!');
                            window.location.href = 'submit_success.html'; // Redirects to the HTML file
                        } else {
                            alert('Failed to save responses.');
                        }
                    });
            });

            renderStatusPanel();
            renderQuestion(0);
        });

    </script>
</body>

</html>