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

    <script src="script.js"></script>

</body>

</html>