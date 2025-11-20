<?php
include "dbconnection.php";

$data = json_decode(file_get_contents("php://input"), true);

$quiz_id = $data['quiz_id'];
$question = $data['question'];
$choices = json_encode($data['choices']);
$correct_answer = $data['correct_answer'];

$stmt = $conn->prepare("INSERT INTO quiz_questions (quiz_id, question, choices, correct_answer) 
                        VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $quiz_id, $question, $choices, $correct_answer);

if($stmt->execute()){
    echo json_encode(["status"=>"success", "id"=>$stmt->insert_id]);
} else {
    echo json_encode(["status"=>"error", "message"=>$stmt->error]);
}
