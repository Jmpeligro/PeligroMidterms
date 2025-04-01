<?php
session_start();

if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
    $_SESSION['student_id_counter'] = 1;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLP Student Management System</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
    <header class="system-header">
        <div class="header-content">
            <div class="logo-container">
                <img src="PLP LOGO.png" alt="PLP Logo" class="logo-placeholder">
            </div>
            <h1>PLP Student Management System</h1>
            <form method="post" action="backend.php" class="reset-form">
                <input type="hidden" name="action" value="reset">
                <button type="submit" class="btn btn-danger">Clear Form</button>
            </form>
        </div>
    </header>

    <div class="container">
        <div class="tabs">
            <button class="tab-btn active" data-tab="add-student">Add Student</button>
            <button class="tab-btn" data-tab="manage-students">Manage Students</button>
            <button class="tab-btn" data-tab="student-view">Student View</button>
        </div>

        <div class="tab-content active" id="add-student">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">Student added successfully!</div>
            <?php endif; ?>
        
            <form method="post" action="backend.php" id="studentForm" class="student-form">
                <input type="hidden" name="action" value="add_student">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Student ID</label>
                        <input type="text" name="student_id" value="<?php echo $_SESSION['student_id_counter']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Student Name</label>
                        <input type="text" name="name" placeholder="Enter student name" required>
                    </div>
                    <div class="form-group">
                        <label>Age</label>
                        <input type="number" name="age" placeholder="Enter age" min="5" max="25" required>
                    </div>
                    <div class="form-group">
                        <label>Section</label>
                        <input type="text" name="section" placeholder="Enter section (e.g., A, B, C)" required>
                    </div>
                    <div class="form-group">
                        <label>Grade Level</label>
                        <select name="grade_level" id="gradeLevelSelect" required onchange="updateFormFields()">
                            <option value="">Select Grade Level</option>
                            <option value="Primary">Primary</option>
                            <option value="Secondary">Secondary</option>
                            <option value="Tertiary">Tertiary</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Specific Grade</label>
                        <select name="specific_grade" id="specificGradeSelect" required disabled>
                            <option value="">Select Specific Grade</option>
                        </select>
                    </div>
                </div>

                <div class="subject-container" id="subjectContainer"></div>
                <div class="form-actions">
                    <button type="button" id="addSubjectBtn" class="btn btn-primary" onclick="addSubjectRow()">Add Subject</button>
                    <button type="submit" class="btn btn-success">Submit Student</button>
                </div>
            </form>
        </div>

        <div class="tab-content" id="manage-students">
            <div class="students-management-container">
                <h2>Edit Student Information</h2>
                
                <div class="filter-section">
                    <label>Filter by Grade Level:</label>
                    <select id="gradeLevelFilter">
                        <option value="">All Levels</option>
                        <option value="Primary">Primary</option>
                        <option value="Secondary">Secondary</option>
                        <option value="Tertiary">Tertiary</option>
                    </select>
                </div>

                <table class="edit-students-table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Grade Level</th>
                            <th>Specific Grade</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="editStudentsTableBody">
                        <?php if (!empty($_SESSION['students'])): ?>
                            <?php foreach ($_SESSION['students'] as $index => $student): ?>
                                <tr data-grade-level="<?php echo htmlspecialchars($student['grade_level']); ?>">
                                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['grade_level']); ?></td>
                                    <td><?php echo htmlspecialchars($student['specific_grade']); ?></td>
                                    <td>
                                        <button class="btn btn-primary edit-student-btn" 
                                            data-index="<?php echo $index; ?>"
                                            data-student-info='<?php echo json_encode($student); ?>'>
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No students to manage.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div id="editStudentModal" class="modal">
                <div class="modal-content">
                    <span class="close-modal">&times;</span>
                    <h2>Edit Student Information</h2>
                    <form id="editStudentForm" method="post" action="backend.php">
                        <input type="hidden" name="action" value="update_student">
                        <input type="hidden" name="student_index" id="editStudentIndex">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Record ID</label>
                                <input type="text" name="student_id" id="editStudentId" readonly>
                            </div>
                            <div class="form-group">
                                <label>Student Name</label>
                                <input type="text" name="name" id="editStudentName" required>
                            </div>
                            <div class="form-group">
                                <label>Age</label>
                                <input type="number" name="age" id="editStudentAge" min="5" max="25" required>
                            </div>
                            <div class="form-group">
                                <label>Section</label>
                                <input type="text" name="section" id="editStudentSection" required>
                            </div>
                        </div>

                        <div id="editSubjectsContainer"></div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-success">Update Student</button>
                            <button type="button" class="btn btn-danger close-modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="tab-content" id="student-view">
            <div class="students-table-container">
                <table class="students-table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Section</th>
                            <th>Grade Level</th>
                            <th>Subjects</th>
                            <th>Total Score</th>
                            <th>Performance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($_SESSION['students'])): ?>
                            <?php foreach ($_SESSION['students'] as $index => $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo $student['age']; ?></td>
                                    <td><?php echo htmlspecialchars($student['section']); ?></td>
                                    <td><?php echo htmlspecialchars($student['grade_level'] . ' - ' . $student['specific_grade']); ?></td>
                                    <td>
                                        <?php foreach ($student['subjects'] as $subject): ?>
                                            <div class="subject-details">
                                                <?php echo htmlspecialchars($subject['name']); ?>:
                                                <?php if ($student['grade_level'] === 'Tertiary'): ?>
                                                    <details>
                                                        <summary>Score Breakdown</summary>
                                                        <ul>
                                                            <li>Attendance: <?php echo $subject['score_breakdown']['attendance']['raw_score']; ?> / <?php echo $subject['score_breakdown']['attendance']['total_score']; ?> (Weighted: <?php echo number_format($subject['score_breakdown']['attendance']['weighted_score'], 2); ?>%)</li>
                                                            <li>Activities: <?php echo $subject['score_breakdown']['activities']['raw_score']; ?> / <?php echo $subject['score_breakdown']['activities']['total_score']; ?> (Weighted: <?php echo number_format($subject['score_breakdown']['activities']['weighted_score'], 2); ?>%)</li>
                                                            <li>Quizzes: <?php echo $subject['score_breakdown']['quizzes']['raw_score']; ?> / <?php echo $subject['score_breakdown']['quizzes']['total_score']; ?> (Weighted: <?php echo number_format($subject['score_breakdown']['quizzes']['weighted_score'], 2); ?>%)</li>
                                                            <li>Exam: <?php echo $subject['score_breakdown']['exam']['raw_score']; ?> / <?php echo $subject['score_breakdown']['exam']['total_score']; ?> (Weighted: <?php echo number_format($subject['score_breakdown']['exam']['weighted_score'], 2); ?>%)</li>
                                                        </ul>
                                                    </details>
                                                <?php elseif ($student['grade_level'] === 'Primary' || $student['grade_level'] === 'Secondary'): ?>
                                                    <details>
                                                        <summary>Score Breakdown</summary>
                                                        <ul>
                                                            <li>Classwork: <?php echo $subject['score_breakdown']['classwork']['raw_score']; ?> / <?php echo $subject['score_breakdown']['classwork']['total_score']; ?> (Weighted: <?php echo number_format($subject['score_breakdown']['classwork']['weighted_score'], 2); ?>%)</li>
                                                            <li>Homework: <?php echo $subject['score_breakdown']['homework']['raw_score']; ?> / <?php echo $subject['score_breakdown']['homework']['total_score']; ?> (Weighted: <?php echo number_format($subject['score_breakdown']['homework']['weighted_score'], 2); ?>%)</li>
                                                            <li>Class Participation: <?php echo $subject['score_breakdown']['participation']['raw_score']; ?> / <?php echo $subject['score_breakdown']['participation']['total_score']; ?> (Weighted: <?php echo number_format($subject['score_breakdown']['participation']['weighted_score'], 2); ?>%)</li>
                                                            <li>Projects: <?php echo $subject['score_breakdown']['projects']['raw_score']; ?> / <?php echo $subject['score_breakdown']['projects']['total_score']; ?> (Weighted: <?php echo number_format($subject['score_breakdown']['projects']['weighted_score'], 2); ?>%)</li>
                                                            <li>Quizzes: <?php echo $subject['score_breakdown']['quizzes']['raw_score']; ?> / <?php echo $subject['score_breakdown']['quizzes']['total_score']; ?> (Weighted: <?php echo number_format($subject['score_breakdown']['quizzes']['weighted_score'], 2); ?>%)</li>
                                                            <li>Exam: <?php echo $subject['score_breakdown']['exam']['raw_score']; ?> / <?php echo $subject['score_breakdown']['exam']['total_score']; ?> (Weighted: <?php echo number_format($subject['score_breakdown']['exam']['weighted_score'], 2); ?>%)</li>
                                                        </ul>
                                                    </details>
                                                <?php else: ?>
                                                    Grade is <?php echo $subject['score']; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </td>
                                    <td><?php echo $student['total_score']; ?></td>
                                    <td><span class="performance-badge performance-<?php echo strtolower(str_replace(' ', '-', $student['performance'])); ?>"><?php echo $student['performance']; ?></span></td>
                                    <td>
                                        <form method="post" action="backend.php">
                                            <input type="hidden" name="action" value="delete_student">
                                            <input type="hidden" name="student_index" value="<?php echo $index; ?>">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="9" class="text-center">No students added yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>  
</body>
</html>