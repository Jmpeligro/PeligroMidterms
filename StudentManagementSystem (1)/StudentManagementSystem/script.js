document.addEventListener('DOMContentLoaded', function() {
    const elements = {
        gradeLevelSelect: document.getElementById('gradeLevelSelect'),
        specificGradeSelect: document.getElementById('specificGradeSelect'),
        addSubjectBtn: document.getElementById('addSubjectBtn'),
        subjectContainer: document.getElementById('subjectContainer'),
        tabButtons: document.querySelectorAll('.tab-btn'),
        tabContents: document.querySelectorAll('.tab-content'),
        resetFormBtn: document.querySelector('.reset-form button'),
        studentForm: document.getElementById('studentForm'),
        editStudentsTableBody: document.getElementById('editStudentsTableBody'),
        editStudentButtons: document.querySelectorAll('.edit-student-btn'),
        editStudentModal: document.getElementById('editStudentModal'),
        closeModalButtons: document.querySelectorAll('.close-modal'),
        editStudentForm: document.getElementById('editStudentForm'),
        editSubjectsContainer: document.getElementById('editSubjectsContainer')
    };

    const gradeOptions = {
        Primary: ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'],
        Secondary: ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10', 'Grade 11', 'Grade 12'],
        Tertiary: ['First Year', 'Second Year', 'Third Year', 'Fourth Year']
    };
    
    const subjectTemplates = {
        PrimarySecondary: `
            <div class="subject-details">
                <input type="text" name="subject_name[]" placeholder="Subject Name" required>
                <div class="score-breakdown">
                    <div class="score-group">
                        <label>Classwork</label>
                        <input type="number" name="classwork_score_raw[]" placeholder="Raw Score" min="0" max="100" required>
                        <input type="number" name="classwork_score_total[]" placeholder="Total Score" value="100" min="1" required>
                    </div>
                    <div class="score-group">
                        <label>Homework</label>
                        <input type="number" name="homework_score_raw[]" placeholder="Raw Score" min="0" max="100" required>
                        <input type="number" name="homework_score_total[]" placeholder="Total Score" value="100" min="1" required>
                    </div>
                    <div class="score-group">
                        <label>Participation</label>
                        <input type="number" name="participation_score_raw[]" placeholder="Raw Score" min="0" max="100" required>
                        <input type="number" name="participation_score_total[]" placeholder="Total Score" value="100" min="1" required>
                    </div>
                    <div class="score-group">
                        <label>Projects</label>
                        <input type="number" name="projects_score_raw[]" placeholder="Raw Score" min="0" max="100" required>
                        <input type="number" name="projects_score_total[]" placeholder="Total Score" value="100" min="1" required>
                    </div>
                    <div class="score-group">
                        <label>Quizzes</label>
                        <input type="number" name="quizzes_score_raw[]" placeholder="Raw Score" min="0" max="100" required>
                        <input type="number" name="quizzes_score_total[]" placeholder="Total Score" value="100" min="1" required>
                    </div>
                    <div class="score-group">
                        <label>Exam</label>
                        <input type="number" name="exam_score_raw[]" placeholder="Raw Score" min="0" max="100" required>
                        <input type="number" name="exam_score_total[]" placeholder="Total Score" value="100" min="1" required>
                    </div>
                </div>
                <button type="button" class="btn btn-danger remove-subject-btn">Remove</button>
            </div>
        `,
        Tertiary: `
            <div class="subject-details">
                <input type="text" name="subject_name[]" placeholder="Subject Name" required>
                <div class="score-breakdown">
                    <div class="score-group">
                        <label>Attendance</label>
                        <input type="number" name="attendance_score_raw[]" placeholder="Raw Score" min="0" required>
                        <input type="number" name="attendance_score_total[]" placeholder="Total Score" min="1" required>
                    </div>
                    <div class="score-group">
                        <label>Activities</label>
                        <input type="number" name="activities_score_raw[]" placeholder="Raw Score" min="0" required>
                        <input type="number" name="activities_score_total[]" placeholder="Total Score" min="1" required>
                    </div>
                    <div class="score-group">
                        <label>Quizzes</label>
                        <input type="number" name="quizzes_score_raw[]" placeholder="Raw Score" min="0" required>
                        <input type="number" name="quizzes_score_total[]" placeholder="Total Score" min="1" required>
                    </div>
                    <div class="score-group">
                        <label>Exam</label>
                        <input type="number" name="exam_score_raw[]" placeholder="Raw Score" min="0" required>
                        <input type="number" name="exam_score_total[]" placeholder="Total Score" min="1" required>
                    </div>
                </div>
                <button type="button" class="btn btn-danger remove-subject-btn">Remove</button>
            </div>
        `
    };
 
    if (elements.resetFormBtn) {
        elements.resetFormBtn.addEventListener('click', function(e) {
            e.preventDefault();
            resetAddStudentForm();
        });
    }

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-subject-btn')) {
            e.target.closest('.subject-row').remove();
        }

        if (e.target === elements.editStudentModal) {
            elements.editStudentModal.style.display = 'none';
        }
    });
    
    elements.tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');
            
            elements.tabButtons.forEach(btn => btn.classList.remove('active'));
            elements.tabContents.forEach(content => content.classList.remove('active'));
            
            button.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    function updateFormFields() {
        const specificGradeSelect = elements.specificGradeSelect;
        const gradeLevel = elements.gradeLevelSelect.value;

        specificGradeSelect.innerHTML = '<option value="">Select Specific Grade</option>';
        specificGradeSelect.disabled = false;
        
        if (gradeOptions[gradeLevel]) {
            const fragment = document.createDocumentFragment();
            
            gradeOptions[gradeLevel].forEach(grade => {
                const option = document.createElement('option');
                option.value = grade;
                option.textContent = grade;
                fragment.appendChild(option);
            });
            
            specificGradeSelect.appendChild(fragment);
        }
        
        elements.subjectContainer.innerHTML = '';
    }
    
    function addSubjectRow() {
        const gradeLevel = elements.gradeLevelSelect.value;
        const subjectRow = document.createElement('div');
        subjectRow.classList.add('subject-row');

        if (gradeLevel === 'Primary' || gradeLevel === 'Secondary') {
            subjectRow.innerHTML = subjectTemplates.PrimarySecondary;
        } else if (gradeLevel === 'Tertiary') {
            subjectRow.innerHTML = subjectTemplates.Tertiary;
        }
        
        elements.subjectContainer.appendChild(subjectRow);
    }
    
    function resetAddStudentForm() {
        if (elements.studentForm) {
            elements.studentForm.reset();
            
            elements.specificGradeSelect.disabled = true;
            elements.specificGradeSelect.innerHTML = '<option value="">Select Specific Grade</option>';
            
            elements.subjectContainer.innerHTML = '';
            
            const studentIdInput = elements.studentForm.querySelector('input[name="student_id"]');
            if (studentIdInput) {
                studentIdInput.value = document.getElementById('initialStudentId').value;
            }
        }
    }
    
    function setupGradeLevelFilter() {
        const gradeLevelFilter = document.getElementById('gradeLevelFilter');
        
        if (gradeLevelFilter) {
            gradeLevelFilter.addEventListener('change', function() {
                filterTableByGradeLevel(this.value, elements.editStudentsTableBody);
            });
        }

        const studentViewFilter = document.getElementById('studentViewGradeLevelFilter');
        if (!studentViewFilter) {
            createStudentViewFilter();
        }
    }
    
    function filterTableByGradeLevel(selectedLevel, tableBody) {
        const rows = tableBody.querySelectorAll('tr');

        rows.forEach(row => {
            if (selectedLevel === '' || row.dataset.gradeLevel === selectedLevel) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }
    
    function createStudentViewFilter() {
        const studentViewGradeLevelFilter = document.createElement('select');
        studentViewGradeLevelFilter.id = 'studentViewGradeLevelFilter';
        studentViewGradeLevelFilter.innerHTML = `
            <option value="">All Levels</option>
            <option value="Primary">Primary</option>
            <option value="Secondary">Secondary</option>
            <option value="Tertiary">Tertiary</option>
        `;
        
        const studentsTableContainer = document.querySelector('.students-table-container');
        if (studentsTableContainer) {
            const filterSection = document.createElement('div');
            filterSection.classList.add('filter-section');
            filterSection.innerHTML = '<label>Filter by Grade Level:</label>';
            filterSection.appendChild(studentViewGradeLevelFilter);
            
            studentsTableContainer.insertBefore(filterSection, studentsTableContainer.querySelector('.students-table'));
            
            studentViewGradeLevelFilter.addEventListener('change', function() {
                const studentRows = document.querySelectorAll('.students-table tbody tr');
                
                studentRows.forEach(row => {
                    const gradeLevelCell = row.querySelector('td:nth-child(5)');
                    const fullGradeLevel = gradeLevelCell ? gradeLevelCell.textContent.split(' - ')[0] : '';
                    
                    if (this.value === '' || fullGradeLevel === this.value) {
                        row.classList.remove('hidden');
                    } else {
                        row.classList.add('hidden');
                    }
                });
            });
        }
    }

    function setupEditModalHandlers() {
        elements.editStudentButtons.forEach(button => {
            button.addEventListener('click', function() {
                const studentInfo = JSON.parse(this.dataset.studentInfo);
                studentInfo.index = this.dataset.index;
                openEditStudentModal(studentInfo);
            });
        });
        
        elements.closeModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                elements.editStudentModal.style.display = 'none';
            });
        });
    }
    
    function openEditStudentModal(studentInfo) {
        document.getElementById('editStudentIndex').value = studentInfo.index;
        document.getElementById('editStudentId').value = studentInfo.student_id;
        document.getElementById('editStudentName').value = studentInfo.name;
        document.getElementById('editStudentAge').value = studentInfo.age;
        document.getElementById('editStudentSection').value = studentInfo.section;
  
        elements.editSubjectsContainer.innerHTML = '';
       
        const fragment = document.createDocumentFragment();
        
        if (studentInfo.grade_level === 'Tertiary') {
            renderTertiarySubjects(studentInfo.subjects, fragment);
        } else {
            renderPrimarySecondarySubjects(studentInfo.subjects, fragment);
        }
        
        elements.editSubjectsContainer.appendChild(fragment);
        elements.editStudentModal.style.display = 'block';
    }
    
    function renderTertiarySubjects(subjects, container) {
        subjects.forEach(subject => {
            const subjectRow = document.createElement('div');
            subjectRow.classList.add('subject-row');
   
            subjectRow.innerHTML = `
                <input type="text" name="subject_name[]" value="${subject.name}" placeholder="Subject Name" required>
                
                <div class="score-breakdown">
                    <div class="score-component">
                        <label>Attendance (5%)</label>
                        <div class="score-input-group">
                            <input type="number" name="attendance_score_raw[]" value="${subject.score_breakdown.attendance.raw_score}" placeholder="Raw Score" min="0" required>
                            <input type="number" name="attendance_score_total[]" value="${subject.score_breakdown.attendance.total_score}" placeholder="Total" min="1" required>
                        </div>
                    </div>
                    <div class="score-component">
                        <label>Activities (25%)</label>
                        <div class="score-input-group">
                            <input type="number" name="activities_score_raw[]" value="${subject.score_breakdown.activities.raw_score}" placeholder="Raw Score" min="0" required>
                            <input type="number" name="activities_score_total[]" value="${subject.score_breakdown.activities.total_score}" placeholder="Total" min="1" required>
                        </div>
                    </div>
                    <div class="score-component">
                        <label>Quizzes (30%)</label>
                        <div class="score-input-group">
                            <input type="number" name="quizzes_score_raw[]" value="${subject.score_breakdown.quizzes.raw_score}" placeholder="Raw Score" min="0" required>
                            <input type="number" name="quizzes_score_total[]" value="${subject.score_breakdown.quizzes.total_score}" placeholder="Total" min="1" required>
                        </div>
                    </div>
                    <div class="score-component">
                        <label>Exam (40%)</label>
                        <div class="score-input-group">
                            <input type="number" name="exam_score_raw[]" value="${subject.score_breakdown.exam.raw_score}" placeholder="Raw Score" min="0" required>
                            <input type="number" name="exam_score_total[]" value="${subject.score_breakdown.exam.total_score}" placeholder="Total" min="1" required>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(subjectRow);
        });
    }
    
    function renderPrimarySecondarySubjects(subjects, container) {
        subjects.forEach(subject => {
            const subjectRow = document.createElement('div');
            subjectRow.classList.add('subject-row');
            
            subjectRow.innerHTML = `
                <div class="subject-details">
                    <input type="text" name="subject_name[]" value="${subject.name}" placeholder="Subject Name" required>
                    <div class="score-breakdown">
                        <div class="score-group">
                            <label>Classwork (15%)</label>
                            <input type="number" name="classwork_score_raw[]" value="${subject.score_breakdown.classwork.raw_score}" placeholder="Raw Score" min="0" max="100" required>
                            <input type="number" name="classwork_score_total[]" value="${subject.score_breakdown.classwork.total_score}" placeholder="Total Score" min="1" required>
                        </div>
                        <div class="score-group">
                            <label>Homework (15%)</label>
                            <input type="number" name="homework_score_raw[]" value="${subject.score_breakdown.homework.raw_score}" placeholder="Raw Score" min="0" max="100" required>
                            <input type="number" name="homework_score_total[]" value="${subject.score_breakdown.homework.total_score}" placeholder="Total Score" min="1" required>
                        </div>
                        <div class="score-group">
                            <label>Participation (10%)</label>
                            <input type="number" name="participation_score_raw[]" value="${subject.score_breakdown.participation.raw_score}" placeholder="Raw Score" min="0" max="100" required>
                            <input type="number" name="participation_score_total[]" value="${subject.score_breakdown.participation.total_score}" placeholder="Total Score" min="1" required>
                        </div>
                        <div class="score-group">
                            <label>Projects (20%)</label>
                            <input type="number" name="projects_score_raw[]" value="${subject.score_breakdown.projects.raw_score}" placeholder="Raw Score" min="0" max="100" required>
                            <input type="number" name="projects_score_total[]" value="${subject.score_breakdown.projects.total_score}" placeholder="Total Score" min="1" required>
                        </div>
                        <div class="score-group">
                            <label>Quizzes (20%)</label>
                            <input type="number" name="quizzes_score_raw[]" value="${subject.score_breakdown.quizzes.raw_score}" placeholder="Raw Score" min="0" max="100" required>
                            <input type="number" name="quizzes_score_total[]" value="${subject.score_breakdown.quizzes.total_score}" placeholder="Total Score" min="1" required>
                        </div>
                        <div class="score-group">
                            <label>Exam (20%)</label>
                            <input type="number" name="exam_score_raw[]" value="${subject.score_breakdown.exam.raw_score}" placeholder="Raw Score" min="0" max="100" required>
                            <input type="number" name="exam_score_total[]" value="${subject.score_breakdown.exam.total_score}" placeholder="Total Score" min="1" required>
                        </div>
                    </div>
                </div>
            `;
            
            container.appendChild(subjectRow);
        });
    }

    if (elements.gradeLevelSelect) {
        elements.gradeLevelSelect.addEventListener('change', updateFormFields);
    }
    
    if (elements.addSubjectBtn) {
        elements.addSubjectBtn.addEventListener('click', addSubjectRow);
    }
  
    setupGradeLevelFilter();
    
    setupEditModalHandlers();
 
    const style = document.createElement('style');
    style.textContent = '.hidden { display: none !important; }';
    document.head.appendChild(style);
});