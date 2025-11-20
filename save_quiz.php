<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// ✅ Connect to database
include 'dbconnection.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(["success" => false, "message" => "No data received"]);
    exit;
}

$title = $data['title'] ?? '';
$section = $data['section'] ?? '';
$date = $data['date'] ?? date('Y-m-d');
$time_limit = $data['timeLimit'] ?? 60;
$points = $data['points'] ?? 1;
$questions = $data['questions'] ?? [];

if (empty($title)) {
    echo json_encode(["success" => false, "message" => "Quiz title required"]);
    exit;
}

// ✅ Insert quiz
$stmt = $conn->prepare("INSERT INTO quizzes (title, section, date, time_limit, points) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssii", $title, $section, $date, $time_limit, $points);

if ($stmt->execute()) {
    $quiz_id = $stmt->insert_id;

    // ✅ Insert each question
    if (!empty($questions)) {
        $qstmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($questions as $q) {
            $qstmt->bind_param(
                "issssss",
                $quiz_id,
                $q['question_text'],
                $q['option_a'],
                $q['option_b'],
                $q['option_c'],
                $q['option_d'],
                $q['correct_option']
            );
            $qstmt->execute();
        }
    }

    echo json_encode(["success" => true, "message" => "Quiz saved successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error saving quiz"]);
}

$conn->close();
?>
