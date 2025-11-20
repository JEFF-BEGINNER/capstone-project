<?php
include "dbconnection.php";

$quizzes = [];

$res = $conn->query("SELECT * FROM quizzes");
while($quiz = $res->fetch_assoc()){
    $quiz_id = $quiz["id"];

    // get questions
    $resQ = $conn->query("SELECT * FROM quiz_questions WHERE quiz_id=$quiz_id");
    $questions = [];
    while($q = $resQ->fetch_assoc()){
        $question_id = $q["id"];

        // get choices
        $resC = $conn->query("SELECT * FROM quiz_choices WHERE question_id=$question_id");
        $choices = [];
        $correctIndex = null;
        $idx = 0;
        while($c = $resC->fetch_assoc()){
            $choices[] = $c["choice_text"];
            if($c["is_correct"]) $correctIndex = $idx;
            $idx++;
        }

        $q["choices"] = $choices;
        $q["correctIndex"] = $correctIndex;
        $questions[] = $q;
    }

    $quiz["questions"] = $questions;
    $quizzes[] = $quiz;
}

echo json_encode($quizzes);
