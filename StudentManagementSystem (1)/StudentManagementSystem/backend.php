<?php
session_start();

if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
    $_SESSION['student_id_counter'] = 1;
}

const PRIMARY_SECONDARY_WEIGHTS = [
    'classwork' => 0.15,
    'homework' => 0.15,
    'participation' => 0.10,
    'projects' => 0.20,
    'quizzes' => 0.20,
    'exam' => 0.20
];

const TERTIARY_WEIGHTS = [
    'attendance' => 0.05,
    'activities' => 0.25,
    'quizzes' => 0.30,
    'exam' => 0.40
];

function calculatePerformance($average) {
    if ($average >= 90) return 'Excellent';
    if ($average >= 80) return 'Very Good';
    if ($average >= 70) return 'Good';
    if ($average >= 60) return 'Passed';
    return 'Failed';
}

function calculatePrimarySecondaryScore($subjects) {
    $total_subject_score = 0;
    $subject_count = count($subjects);

    foreach ($subjects as &$subject) {
        $subject_score = 0;
        $score_breakdown = [];
    
        foreach (PRIMARY_SECONDARY_WEIGHTS as $component => $weight) {
            $raw = $subject["{$component}_score_raw"] ?? 0;
            $total = $subject["{$component}_score_total"] ?? 100;

            if ($total <= 0) $total = 1;
            
            $percentage = ($raw / $total) * 100;
            $weighted = $percentage * $weight;
            $subject_score += $weighted;

            $score_breakdown[$component] = [
                'raw_score' => $raw,
                'total_score' => $total,
                'percentage' => round($percentage, 2),
                'weighted_score' => round($weighted, 2)
            ];
        }
        
        $subject['score_breakdown'] = $score_breakdown;
        $total_subject_score += $subject_score;
    }
   
    $average_score = $subject_count > 0 ? round($total_subject_score / $subject_count, 2) : 0;
    
    return [
        'total_score' => $average_score,
        'subjects' => $subjects
    ];
}

function calculateTertiaryScore($subjects) {
    $total_subject_score = 0;
    $subject_count = count($subjects);
    
    foreach ($subjects as &$subject) {
        $subject_score = 0;
        $score_breakdown = [];
     
        foreach (TERTIARY_WEIGHTS as $component => $weight) {
            $raw = $subject["{$component}_score_raw"] ?? 0;
            $total = $subject["{$component}_score_total"] ?? 100;
 
            if ($total <= 0) $total = 1;
        
            $percentage = ($raw / $total) * 50 + 50;
            $weighted = $percentage * $weight;
            $subject_score += $weighted;
            
            $score_breakdown[$component] = [
                'raw_score' => $raw,
                'total_score' => $total,
                'percentage' => round($percentage, 2),
                'weighted_score' => round($weighted, 2)
            ];
        }
        
        $subject['score_breakdown'] = $score_breakdown;
        $total_subject_score += $subject_score;
    }
    
    return [
        'total_score' => $subject_count > 0 ? round($total_subject_score / $subject_count, 2) : 0,
        'subjects' => $subjects
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'add_student':
            handleAddStudent();
            break;
        case 'reset':
            $_SESSION['student_id_counter'] = 1;
            redirect('index.php?reset=form');
            break;
        case 'delete_student':
            handleDeleteStudent();
            break;
        case 'update_student':
            handleUpdateStudent();
            break;
    }
}

function handleAddStudent() {
    $required_fields = ['student_id', 'name', 'age', 'section', 'grade_level', 'specific_grade'];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            redirect('index.php?error=1');
        }
    }
    
    $_SESSION['student_id_counter']++;
    
    $student = [
        'student_id' => strip_tags(trim($_POST['student_id'])),
        'name' => strip_tags(trim($_POST['name'])),
        'age' => intval($_POST['age']),
        'section' => strip_tags(trim($_POST['section'])),
        'grade_level' => strip_tags(trim($_POST['grade_level'])),
        'specific_grade' => strip_tags(trim($_POST['specific_grade'])),
        'subjects' => []
    ];
    
    $grade_level = $student['grade_level'];
    $subject_names = $_POST['subject_name'] ?? [];
    $count = count($subject_names);

    if (in_array($grade_level, ['Primary', 'Secondary'])) {
        $student['subjects'] = collectPrimarySecondarySubjects($subject_names, $count);
        $score_result = calculatePrimarySecondaryScore($student['subjects']);
    } else {
        $student['subjects'] = collectTertiarySubjects($subject_names, $count);
        $score_result = calculateTertiaryScore($student['subjects']);
    }
    
    $student['total_score'] = $score_result['total_score'];
    $student['performance'] = calculatePerformance($student['total_score']);
    $student['subjects'] = $score_result['subjects'];
    
    $_SESSION['students'][] = $student;
    redirect('index.php?success=1');
}

function collectPrimarySecondarySubjects($subject_names, $count) {
    $subjects = [];
    $components = ['classwork', 'homework', 'participation', 'projects', 'quizzes', 'exam'];
    
    for ($i = 0; $i < $count; $i++) {
        if (empty($subject_names[$i])) continue;
        
        $subject = ['name' => strip_tags(trim($subject_names[$i]))];

        foreach ($components as $component) {
            $raw_key = "{$component}_score_raw";
            $total_key = "{$component}_score_total";
            
            $subject[$raw_key] = isset($_POST[$raw_key]) ? floatval($_POST[$raw_key][$i]) : 0;
            $subject[$total_key] = isset($_POST[$total_key]) ? floatval($_POST[$total_key][$i]) : 100;
        }
        
        $subjects[] = $subject;
    }
    
    return $subjects;
}

function collectTertiarySubjects($subject_names, $count) {
    $subjects = [];
    $components = ['attendance', 'activities', 'quizzes', 'exam'];
    
    for ($i = 0; $i < $count; $i++) {
        if (empty($subject_names[$i])) continue;
        
        $subject = ['name' => strip_tags(trim($subject_names[$i]))];
        
        foreach ($components as $component) {
            $raw_key = "{$component}_score_raw";
            $total_key = "{$component}_score_total";
            
            $subject[$raw_key] = isset($_POST[$raw_key]) ? floatval($_POST[$raw_key][$i]) : 0;
            $subject[$total_key] = isset($_POST[$total_key]) ? floatval($_POST[$total_key][$i]) : 100;
        }
        
        $subjects[] = $subject;
    }
    
    return $subjects;
}

function handleDeleteStudent() {
    $index = intval($_POST['student_index']);
    if (isset($_SESSION['students'][$index])) {
        array_splice($_SESSION['students'], $index, 1); 
    }
    redirect('index.php?deleted=1');
}

function handleUpdateStudent() {
    $index = intval($_POST['student_index']);
    
    if (!isset($_SESSION['students'][$index])) {
        redirect('index.php?error=2');
    }

    $_SESSION['students'][$index]['name'] = strip_tags(trim($_POST['name']));
    $_SESSION['students'][$index]['age'] = intval($_POST['age']);
    $_SESSION['students'][$index]['section'] = strip_tags(trim($_POST['section']));
    
    $student_grade_level = $_SESSION['students'][$index]['grade_level'];
    $subject_names = $_POST['subject_name'] ?? [];
    $count = count($subject_names);
    
    if ($student_grade_level === 'Tertiary') {
        $subjects = collectTertiarySubjects($subject_names, $count);
        $score_result = calculateTertiaryScore($subjects);
    } else {
        $subjects = collectPrimarySecondarySubjects($subject_names, $count);
        $score_result = calculatePrimarySecondaryScore($subjects);
    }
    
    $_SESSION['students'][$index]['subjects'] = $score_result['subjects'];
    $_SESSION['students'][$index]['total_score'] = $score_result['total_score'];
    $_SESSION['students'][$index]['performance'] = calculatePerformance($score_result['total_score']);
    
    redirect('index.php?updated=1');
}

function redirect($location) {
    header("Location: $location");
    exit();
}

if (basename($_SERVER['PHP_SELF']) === 'backend.php') {
    redirect('index.php');
}