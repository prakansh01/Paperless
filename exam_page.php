<?php
// Database connection
$host = 'localhost';
$dbname = 'paperless_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}











// Fetch questions for a specific exam
$exam_id = 4; // Set the exam_id here
$student_id = 4;
$query = "SELECT * FROM questions WHERE exam_id = :exam_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
$stmt->execute();
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>








<?php
// Fetch exam details
$query2 = "SELECT exam_start, exam_end, subject FROM exams WHERE id = :exam_id";
$stmt2 = $pdo->prepare($query2);
$stmt2->bindParam(':exam_id', $exam_id, PDO::PARAM_INT);
$stmt2->execute();
$exam = $stmt2->fetch(PDO::FETCH_ASSOC);

// If no record is found, handle it
if (!$exam) {
    $exam_name = "Exam not found";
} else {
    $exam_name = htmlspecialchars($exam['subject']);
}
?>






<?php

// Fetch student details
$query_student = "SELECT full_name, photo_path FROM exam_application WHERE id = :student_id";
$stmt_student = $pdo->prepare($query_student);
$stmt_student->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$stmt_student->execute();
$student_details = $stmt_student->fetch(PDO::FETCH_ASSOC);

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

        .question-panel {
            height: 100vh;
            overflow-y: auto;
        }

        .status-item {
            cursor: pointer;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .status-item.solved {
            background-color: #28a745;
            color: white;
        }

        .status-item.doubt {
            background-color: #ffc107;
            color: black;
        }

        .status-item.skipped {
            background-color: #dc3545;
            color: white;
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

        .timer {
            font-size: 1.2rem;
            font-weight: bold;
        }
    </style>
    <script>
        // Fisher-Yates Shuffle for arrays
        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const questions = <?php echo json_encode($questions); ?>; // Questions from PHP
            const studentId = 1; // Replace with dynamic student ID logic

            // Shuffle questions uniquely per student
            const shuffledQuestions = shuffleArray(questions);

            const questionContainer = document.getElementById('questionContainer');
            const statusList = document.getElementById('statusList');
            const submitContainer = document.getElementById('submitContainer');
            const submitButton = document.getElementById('submitButton');

            let currentQuestionIndex = 0;
            const questionStatus = Array(shuffledQuestions.length).fill('unsolved'); // Track question status
            const visitedQuestions = new Set(); // To track visited questions
            const userResponses = {}; // Store user's responses keyed by question ID

            // Render the first question
            renderQuestion();

            // Create status items for each question
            shuffledQuestions.forEach((_, index) => {
                const statusItem = document.createElement('div');
                statusItem.classList.add('status-item');
                statusItem.id = `status-${index}`;
                statusItem.textContent = `Q${index + 1}`;
                statusList.appendChild(statusItem);

                // Jump to the question on clicking the status
                statusItem.addEventListener('click', () => {
                    currentQuestionIndex = index;
                    renderQuestion();
                });
            });

            // Render a question
            function renderQuestion() {
                const question = shuffledQuestions[currentQuestionIndex];

                // Shuffle options
                const options = [
                    question.option1,
                    question.option2,
                    question.option3,
                    question.correct_answer
                ];
                const shuffledOptions = shuffleArray(options);

                questionContainer.innerHTML = `
                    <p><strong>Q${currentQuestionIndex + 1}: ${question.question}</strong></p>
                    ${shuffledOptions
                        .map(option => `
                            <label>
                                <input type="radio" name="response[${question.question_id}]" value="${option}"
                                ${userResponses[question.question_id] === option ? 'checked' : ''}>
                                ${option}
                            </label><br>
                        `)
                        .join('')}
                    <button class="btn btn-success mt-3" id="btnSolve">Solve with Confidence</button>
                    <button class="btn btn-warning mt-3" id="btnDoubt">Mark with Doubt</button>
                    <button class="btn btn-danger mt-3" id="btnSkip">Skip</button>
                    <hr>
                `;

                // Mark question as visited
                visitedQuestions.add(currentQuestionIndex);
                checkAllVisited();

                // Update button actions
                document.getElementById('btnSolve').addEventListener('click', () => updateStatus('solved'));
                document.getElementById('btnDoubt').addEventListener('click', () => updateStatus('doubt'));
                document.getElementById('btnSkip').addEventListener('click', () => updateStatus('skipped'));

                // Track user selection
                const optionsInputs = document.querySelectorAll(`input[name="response[${question.question_id}]"]`);
                optionsInputs.forEach(input => {
                    input.addEventListener('change', () => {
                        userResponses[question.question_id] = input.value; // Store the selected value
                    });
                });
            }

            // Update question status
            function updateStatus(status) {
                const questionId = shuffledQuestions[currentQuestionIndex].question_id;

                // Store status and response
                questionStatus[currentQuestionIndex] = status;
                userResponses[questionId] = {
                    status: status,
                    selectedOption: userResponses[questionId]?.selectedOption || null, // Retain selected option if any
                };

                document.getElementById(`status-${currentQuestionIndex}`).className = `status-item ${status}`;

                // Move to next question
                if (currentQuestionIndex < shuffledQuestions.length - 1) {
                    currentQuestionIndex++;
                    renderQuestion();
                } else {
                    alert('All questions attempted!');
                }
            }

            // Track user selection
            const optionsInputs = document.querySelectorAll(`input[name="response[${question.question_id}]"]`);
            optionsInputs.forEach(input => {
                input.addEventListener('change', () => {
                    const questionId = shuffledQuestions[currentQuestionIndex].question_id;

                    if (!userResponses[questionId]) {
                        userResponses[questionId] = {};
                    }

                    userResponses[questionId].selectedOption = input.value; // Store the selected value
                });
            });


            // Check if all questions have been visited
            function checkAllVisited() {
                if (visitedQuestions.size === shuffledQuestions.length) {
                    submitContainer.style.display = 'block';
                }
            }

            // Submit button handler
            submitButton.addEventListener('click', () => {
                const responses = [];
                for (const [questionId, selectedOption] of Object.entries(userResponses)) {
                    responses.push({
                        question_id: parseInt(questionId),
                        status: response.status || 'skipped',
                        selectedOption: response.selectedOption || '0', // Selected option value
                    });
                }

                // Send data to backend
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'submit_responses.php';

                const examInput = document.createElement('input');
                examInput.type = 'hidden';
                examInput.name = 'exam_id';
                examInput.value = <?php echo $exam_id; ?>;
                form.appendChild(examInput);

                const responseInput = document.createElement('input');
                responseInput.type = 'hidden';
                responseInput.name = 'responses';
                responseInput.value = JSON.stringify(responses);
                form.appendChild(responseInput);

                document.body.appendChild(form);
                form.submit();
            });
        });
    </script>


    <!-- Security script -->
    <script>
        //disable right click
        document.addEventListener('contextmenu', function (event) {
            event.preventDefault();
        });

        //disable function keys and control button combinations 
        document.addEventListener('keydown', function (event) {
            if (event.key === 'F12' || (event.ctrlKey && event.shiftKey && event.key === 'I') || (event.ctrlKey && event.key === 'U')) {
                event.preventDefault();
            }
        });

        /*  JavaScript function to detect when the Developer Tools are opened by monitoring the window.outerWidth and window.innerWidth properties. If they differ, it could indicate that DevTools is open. */
        (function () {
            let devtoolsOpen = false;
            const threshold = 160;

            setInterval(function () {
                const widthDifference = window.outerWidth - window.innerWidth;
                if (widthDifference >= threshold && !devtoolsOpen) {
                    devtoolsOpen = true;
                    alert('Developer tools are open');
                }
                if (widthDifference < threshold && devtoolsOpen) {
                    devtoolsOpen = false;
                }
            }, 1000);
        })();



    </script>

</head>

<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-center align-items-center bg-light border rounded p-3" style="height: 15%;">
            <h1 class="mb-0 text-center text-dark font-weight-bold" style="margin-right:15%">
                <?php echo $exam_name; ?>
            </h1>
            <div class="row" style="margin-right:10%" style="margin-left:10%">
                <!-- Timer -->
                <div>
                    <div class="timer text-primary" id="countdown"></div>
                </div>
            </div>
            <div class="row" style="margin-left:10%">
                <!-- Student Details -->
                <div class="col-12 text-center mb-3">
                    <img src="<?php echo htmlspecialchars($student_details['photo_path']); ?>" alt="Student Photo"
                        class="student-photo rounded">
                    <h5 class="mt-2"><?php echo htmlspecialchars($student_details['full_name']); ?></h5>
                </div>
            </div>

        </div>

        <div class="row">
            <!-- Status Panel -->
            <div class="col-md-2 status-panel">
                <div id="statusList"></div>
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
        // Exam start and end times from PHP
        const examStartTime = new Date("<?php echo $exam['exam_start']; ?>").getTime();
        const examEndTime = new Date("<?php echo $exam['exam_end']; ?>").getTime();

        const countdownElement = document.getElementById('countdown');

        function updateTimer() {
            const now = new Date().getTime();
            let timeRemaining;

            if (now < examStartTime) {
                timeRemaining = examStartTime - now;
                countdownElement.textContent = "Time until exam starts: " + formatTime(timeRemaining);
            } else if (now < examEndTime) {
                timeRemaining = examEndTime - now;
                countdownElement.textContent = "Time remaining:" + formatTime(timeRemaining);
            } else {
                countdownElement.textContent = "The exam has ended.";
            }
        }

        function formatTime(ms) {
            const seconds = Math.floor(ms / 1000);
            const minutes = Math.floor(seconds / 60);
            const hours = Math.floor(minutes / 60);
            const days = Math.floor(hours / 24);

            const displayHours = hours % 24;
            const displayMinutes = minutes % 60;
            const displaySeconds = seconds % 60;

            return `${days}d ${displayHours}h ${displayMinutes}m ${displaySeconds}s`;
        }

        // Update the timer every second
        setInterval(updateTimer, 1000);

        // Initial call to set the timer
        updateTimer();
    </script>


















</body>

</html>