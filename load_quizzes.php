<?php
// load_quizzes.php
header('Content-Type: application/json; charset=utf-8');
// in dev only: allow CORS if your frontend served from different origin
// header('Access-Control-Allow-Origin: *');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "school_db";

mysqli_report(MYSQLI_REPORT_OFF); // suppress warnings to avoid contaminating JSON

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    http_response_code(500);
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$sql = "SELECT id, title, section, published, date, questions_json FROM quizzes ORDER BY id DESC";
$res = $conn->query($sql);

$quizzes = [];
if($res){
    while($row = $res->fetch_assoc()){
        // assume questions are stored as JSON in questions_json column
        $questions = [];
        if(!empty($row['questions_json'])){
            $q = json_decode($row['questions_json'], true);
            if(json_last_error() === JSON_ERROR_NONE) $questions = $q;
        }
        $quizzes[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'section' => $row['section'],
            'published' => (int)$row['published'],
            'date' => $row['date'],
            'questions' => $questions
        ];
    }
}
echo json_encode($quizzes);
$conn->close();
