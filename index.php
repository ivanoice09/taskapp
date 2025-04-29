<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile-Friendly Task Calendar</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="index_style.css" rel="stylesheet">
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <div class="d-flex flex-column h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <span class="fs-4 d-none d-md-inline">Menu</span>
                <button class="toggle-btn d-md-none" id="sidebarToggle">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link" data-content="account">
                        <i class="bi bi-person me-2"></i>
                        <span class="nav-link-text">Account</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-content="today">
                        <i class="bi bi-calendar-day me-2"></i>
                        <span class="nav-link-text">Today</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link active" data-content="calendar">
                        <i class="bi bi-calendar3 me-2"></i>
                        <span class="nav-link-text">Calendar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-content="upcoming">
                        <i class="bi bi-calendar-week me-2"></i>
                        <span class="nav-link-text">Upcoming</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link" data-content="completed">
                        <i class="bi bi-check-circle me-2"></i>
                        <span class="nav-link-text">Completed</span>
                    </a>
                </li>
            </ul>
            <div class="mt-auto">
                <hr>
                <button class="toggle-btn d-none d-md-block" id="collapseToggle">
                    <i class="bi bi-chevron-double-left"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content">
        <div class="container-fluid px-md-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <button class="toggle-btn d-md-none" id="mobileSidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <h2 id="content-title">Calendar</h2>
            </div>
            
            <div id="dynamic-content">
                <!-- Calendar Content -->
                <div class="content-section" id="calendar-content">
                    <div id="calendar"></div>
                </div>
                
                <!-- Day View Content (hidden by default) -->
                <div class="content-section d-none" id="day-view-content">
                    <div class="day-header">
                        <h4 id="day-view-title" class="mb-0">Tasks for <span id="selected-date"></span></h4>
                        <div class="back-to-calendar" onclick="backToCalendar()">
                            <i class="bi bi-arrow-left"></i> <span class="d-none d-md-inline">Back to Calendar</span>
                        </div>
                    </div>
                    
                    <div id="tasks-container" class="mt-3">
                        <div id="no-tasks-message" class="empty-state">
                            <i class="bi bi-calendar-check"></i>
                            <h5>No tasks for this day</h5>
                            <p>Add your first task using the button below</p>
                        </div>
                        <div id="tasks-list"></div>
                    </div>
                </div>
                
                <!-- Other content sections (hidden by default) -->
                <div class="content-section d-none" id="account-content">
                    <h3>Account Information</h3>
                    <p>This is your account page. You can manage your profile settings here.</p>
                </div>
                
                <div class="content-section d-none" id="today-content">
                    <h3>Today's Tasks</h3>
                    <div id="today-tasks-list" class="list-group">
                        <!-- Today's tasks will be loaded here -->
                    </div>
                </div>
                
                <div class="content-section d-none" id="upcoming-content">
                    <h3>Upcoming Tasks</h3>
                    <div id="upcoming-tasks-list" class="list-group">
                        <!-- Upcoming tasks will be loaded here -->
                    </div>
                </div>
                
                <div class="content-section d-none" id="completed-content">
                    <h3>Completed Tasks</h3>
                    <div id="completed-tasks-list" class="list-group">
                        <!-- Completed tasks will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Task Button (Floating Action Button) -->
    <button class="btn btn-primary add-task-btn d-none" id="addTaskButton" data-bs-toggle="modal" data-bs-target="#taskModal">
        <i class="bi bi-plus"></i>
    </button>

    <!-- Task Modal -->
    <div class="modal fade task-modal" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalLabel">Add New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="add-task-form">
                        <div class="mb-3">
                            <label for="task-title" class="form-label">Task Title</label>
                            <input type="text" class="form-control" id="task-title" required>
                        </div>
                        <div class="mb-3">
                            <label for="task-date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="task-date" required>
                        </div>
                        <div class="mb-3">
                            <label for="task-description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="task-description" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="save-task-btn">Add Task</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    
    <script>
        // Store tasks in memory (in a real app, this would be in a database)
        const tasks = {};
        let selectedDate = null;
        let calendar;

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize calendar
            initCalendar();
            
            // Initialize sidebar and event listeners
            initSidebar();
            
            // Initialize task management
            initTaskManagement();
            
            // Load today's tasks by default
            updateTodayTasks();
        });

        function initCalendar() {
            const calendarEl = document.getElementById('calendar');
            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay'
                },
                dateClick: function(info) {
                    showDayView(info.date);
                },
                events: function(fetchInfo, successCallback, failureCallback) {
                    // Convert our tasks to FullCalendar events
                    const events = [];
                    for (const date in tasks) {
                        tasks[date].forEach(task => {
                            if (!task.completed) {
                                events.push({
                                    title: task.text,
                                    start: date,
                                    allDay: true,
                                    color: '#0d6efd'
                                });
                            }
                        });
                    }
                    successCallback(events);
                }
            });
            calendar.render();
        }

        function initSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const collapseToggle = document.getElementById('collapseToggle');
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            // Toggle sidebar collapse on desktop
            if (collapseToggle) {
                collapseToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('collapsed');
                    
                    const icon = this.querySelector('i');
                    if (sidebar.classList.contains('collapsed')) {
                        icon.classList.remove('bi-chevron-double-left');
                        icon.classList.add('bi-chevron-double-right');
                    } else {
                        icon.classList.remove('bi-chevron-double-right');
                        icon.classList.add('bi-chevron-double-left');
                    }
                });
            }
            
            // Mobile sidebar toggle
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function() {
                    sidebar.classList.add('show-mobile');
                });
            }
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.remove('show-mobile');
                });
            }
            
            // Navigation link click handlers
            const navLinks = document.querySelectorAll('.nav-link');
            const contentTitle = document.getElementById('content-title');
            
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all links
                    navLinks.forEach(l => l.classList.remove('active'));
                    
                    // Add active class to clicked link
                    this.classList.add('active');
                    
                    // Get the content to show
                    const contentId = this.getAttribute('data-content');
                    
                    // Update the title
                    const linkText = this.querySelector('.nav-link-text').textContent;
                    contentTitle.textContent = linkText;
                    
                    // Hide all content sections
                    document.querySelectorAll('.content-section').forEach(section => {
                        section.classList.add('d-none');
                    });
                    
                    // Show the selected content section
                    document.getElementById(`${contentId}-content`).classList.remove('d-none');
                    
                    // Show/hide add task button based on section
                    const addTaskBtn = document.getElementById('addTaskButton');
                    if (contentId === 'calendar' || contentId === 'today' || contentId === 'upcoming') {
                        addTaskBtn.classList.remove('d-none');
                    } else {
                        addTaskBtn.classList.add('d-none');
                    }
                    
                    // On mobile, hide sidebar after selection
                    if (window.innerWidth < 768) {
                        sidebar.classList.remove('show-mobile');
                    }
                    
                    // Refresh content if needed
                    if (contentId === 'today') {
                        updateTodayTasks();
                    } else if (contentId === 'upcoming') {
                        updateUpcomingTasks();
                    } else if (contentId === 'completed') {
                        updateCompletedTasks();
                    } else if (contentId === 'calendar') {
                        calendar.refetchEvents();
                    }
                });
            });
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    sidebar.classList.remove('show-mobile');
                }
            });
        }

        function initTaskManagement() {
            // Task form submission
            document.getElementById('save-task-btn').addEventListener('click', function() {
                const taskTitle = document.getElementById('task-title').value.trim();
                const taskDate = document.getElementById('task-date').value;
                const taskDescription = document.getElementById('task-description').value.trim();
                
                if (taskTitle && taskDate) {
                    const date = new Date(taskDate);
                    addTask(date, taskTitle, taskDescription);
                    
                    // Reset form
                    document.getElementById('add-task-form').reset();
                    
                    // Hide modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('taskModal'));
                    modal.hide();
                    
                    // Update UI based on current view
                    const activeSection = document.querySelector('.content-section:not(.d-none)').id;
                    
                    if (activeSection === 'day-view-content' && isSameDay(date, selectedDate)) {
                        renderTasksForDate(selectedDate);
                    } else if (activeSection === 'today-content' && isToday(date)) {
                        updateTodayTasks();
                    } else if (activeSection === 'upcoming-content' && isUpcoming(date)) {
                        updateUpcomingTasks();
                    }
                    
                    calendar.refetchEvents();
                }
            });
            
            // Set default date in modal to today or selected date
            document.getElementById('taskModal').addEventListener('show.bs.modal', function() {
                const activeSection = document.querySelector('.content-section:not(.d-none)').id;
                let defaultDate = new Date();
                
                if (activeSection === 'day-view-content' && selectedDate) {
                    defaultDate = selectedDate;
                }
                
                document.getElementById('task-date').valueAsDate = defaultDate;
            });
        }

        function showDayView(date) {
            selectedDate = date;
            const formattedDate = formatDate(date);
            
            // Update UI
            document.getElementById('selected-date').textContent = formattedDate;
            document.getElementById('calendar-content').classList.add('d-none');
            document.getElementById('day-view-content').classList.remove('d-none');
            document.getElementById('content-title').textContent = `Tasks for ${formattedDate}`;
            
            // Show add task button
            document.getElementById('addTaskButton').classList.remove('d-none');
            
            // Render tasks for this date
            renderTasksForDate(date);
        }

        function backToCalendar() {
            document.getElementById('day-view-content').classList.add('d-none');
            document.getElementById('calendar-content').classList.remove('d-none');
            document.getElementById('content-title').textContent = 'Calendar';
        }

        function formatDate(date) {
            return date.toLocaleDateString('en-US', { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });
        }

        function addTask(date, text, description = '') {
            const dateStr = date.toISOString().split('T')[0];
            
            if (!tasks[dateStr]) {
                tasks[dateStr] = [];
            }
            
            tasks[dateStr].push({
                id: Date.now(),
                text: text,
                description: description,
                completed: false,
                createdAt: new Date()
            });
        }

        function renderTasksForDate(date) {
            const dateStr = date.toISOString().split('T')[0];
            const tasksList = document.getElementById('tasks-list');
            const noTasksMessage = document.getElementById('no-tasks-message');
            
            tasksList.innerHTML = '';
            
            if (tasks[dateStr] && tasks[dateStr].length > 0) {
                noTasksMessage.classList.add('d-none');
                
                // Sort tasks by creation date (newest first)
                tasks[dateStr].sort((a, b) => b.createdAt - a.createdAt);
                
                tasks[dateStr].forEach(task => {
                    const taskElement = document.createElement('div');
                    taskElement.className = 'task-item';
                    
                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.className = 'task-checkbox form-check-input';
                    checkbox.checked = task.completed;
                    checkbox.addEventListener('change', function() {
                        task.completed = this.checked;
                        renderTasksForDate(date);
                        updateTodayTasks();
                        updateUpcomingTasks();
                        updateCompletedTasks();
                        calendar.refetchEvents();
                    });
                    
                    const taskText = document.createElement('span');
                    taskText.className = 'task-text' + (task.completed ? ' task-completed' : '');
                    taskText.textContent = task.text;
                    
                    if (task.description) {
                        const taskDesc = document.createElement('p');
                        taskDesc.className = 'text-muted small mb-0 mt-1' + (task.completed ? ' task-completed' : '');
                        taskDesc.textContent = task.description;
                        taskText.appendChild(taskDesc);
                    }
                    
                    taskElement.appendChild(checkbox);
                    taskElement.appendChild(taskText);
                    tasksList.appendChild(taskElement);
                });
            } else {
                noTasksMessage.classList.remove('d-none');
            }
        }

        function updateTodayTasks() {
            const today = new Date();
            const todayStr = today.toISOString().split('T')[0];
            const todayList = document.getElementById('today-tasks-list');
            
            todayList.innerHTML = '';
            
            if (tasks[todayStr] && tasks[todayStr].length > 0) {
                tasks[todayStr].forEach(task => {
                    if (!task.completed) {
                        todayList.appendChild(createTaskListItem(task, today));
                    }
                });
            }
            
            if (todayList.children.length === 0) {
                todayList.innerHTML = '<div class="empty-state p-3"><i class="bi bi-check2-circle"></i><p>No tasks for today</p></div>';
            }
        }

        function updateUpcomingTasks() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const upcomingList = document.getElementById('upcoming-tasks-list');
            
            upcomingList.innerHTML = '';
            let hasUpcomingTasks = false;
            
            for (const dateStr in tasks) {
                const taskDate = new Date(dateStr);
                if (taskDate > today) {
                    tasks[dateStr].forEach(task => {
                        if (!task.completed) {
                            upcomingList.appendChild(createTaskListItem(task, taskDate));
                            hasUpcomingTasks = true;
                        }
                    });
                }
            }
            
            if (!hasUpcomingTasks) {
                upcomingList.innerHTML = '<div class="empty-state p-3"><i class="bi bi-calendar2-week"></i><p>No upcoming tasks</p></div>';
            }
        }

        function updateCompletedTasks() {
            const completedList = document.getElementById('completed-tasks-list');
            
            completedList.innerHTML = '';
            let hasCompletedTasks = false;
            
            for (const dateStr in tasks) {
                tasks[dateStr].forEach(task => {
                    if (task.completed) {
                        const taskDate = new Date(dateStr);
                        completedList.appendChild(createTaskListItem(task, taskDate));
                        hasCompletedTasks = true;
                    }
                });
            }
            
            if (!hasCompletedTasks) {
                completedList.innerHTML = '<div class="empty-state p-3"><i class="bi bi-emoji-smile"></i><p>No completed tasks yet</p></div>';
            }
        }

        function createTaskListItem(task, date) {
            const item = document.createElement('a');
            item.href = '#';
            item.className = 'list-group-item list-group-item-action';
            
            const dateBadge = document.createElement('span');
            dateBadge.className = 'badge bg-secondary me-2';
            dateBadge.textContent = formatShortDate(date);
            
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'form-check-input me-2';
            checkbox.checked = task.completed;
            checkbox.addEventListener('change', function() {
                task.completed = this.checked;
                updateTodayTasks();
                updateUpcomingTasks();
                updateCompletedTasks();
                calendar.refetchEvents();
            });
            
            const taskText = document.createElement('span');
            taskText.className = task.completed ? 'task-completed' : '';
            taskText.textContent = task.text;
            
            item.appendChild(checkbox);
            item.appendChild(dateBadge);
            item.appendChild(taskText);
            
            return item;
        }

        function formatShortDate(date) {
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric' 
            });
        }

        function isSameDay(date1, date2) {
            return date1.getFullYear() === date2.getFullYear() &&
                   date1.getMonth() === date2.getMonth() &&
                   date1.getDate() === date2.getDate();
        }

        function isToday(date) {
            const today = new Date();
            return isSameDay(date, today);
        }

        function isUpcoming(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            return date > today;
        }
    </script>
</body>
</html>