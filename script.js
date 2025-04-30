let tasks = {}; // will be populated from the API
let selectedDate = null;
let calendar;

document.addEventListener('DOMContentLoaded', function () {
    // Initialize calendar
    initCalendar();

    // Initialize sidebar and event listeners
    initSidebar();

    // Initialize task management
    initTaskManagement();

    // Load tasks from API
    fetchTasks().then(() => {
        updateTodayTasks();
    });

});

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

// API communication functions
async function fetchTasks() {
    try {
        const response = await fetch('tasks.php?action=getAll');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        // Check if data is an array
        if (!Array.isArray(data)) {
            throw new Error('Invalid data format received from server');
        }

        // Transform the API data into our tasks structure
        tasks = {};
        data.forEach(task => {
            const dateStr = task.date.split('T')[0];
            if (!tasks[dateStr]) {
                tasks[dateStr] = [];
            }

            tasks[dateStr].push({
                id: task.id,
                text: task.title,
                description: task.description,
                completed: task.completed === '1',
                createdAt: new Date(task.created_at)
            });
        });

        // Refresh calendar if it exists
        if (calendar) {
            calendar.refetchEvents();
        }

    } catch (error) {
        console.error('Error fetching tasks:', error);
        alert('Failed to load tasks. Please try again later.');
    }
}

async function addTask(date, text, description = '') {
    if (isNaN(date.getTime())) {
        // Handle the invalid date case, e.g., show an error message
        console.error("Invalid date provided.");
        return; // Or handle appropriately
    }
    const dateStr = date.toISOString().split('T')[0];

    try {
        const response = await fetch('tasks.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'create',
                title: text,
                description: description,
                date: dateStr,
                completed: false
            })
        });

        const newTask = await response.json();

        // Add to local tasks
        if (!tasks[dateStr]) {
            tasks[dateStr] = [];
        }

        tasks[dateStr].push({
            id: newTask.id,
            text: newTask.title,
            description: newTask.description,
            completed: newTask.completed === '1',
            createdAt: new Date(newTask.created_at)
        });

        return true;
    } catch (error) {
        console.error('Error adding task:', error);
        return false;
    }
}

async function updateTaskStatus(taskId, completed) {
    try {
        const response = await fetch('tasks.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                action: 'updateStatus',
                id: taskId,
                completed: completed
            })
        });

        if (!response.ok) {
            // Try to get error message from response
            let errorMsg = 'Network response was not ok';
            try {
                const errorData = await response.json();
                if (errorData.error) errorMsg = errorData.error;
            } catch (e) { }
            throw new Error(errorMsg);
        }

        const data = await response.json();
        return data.success;
    } catch (error) {
        console.error('Error updating task:', error.message);
        return false;
    }
}

function initCalendar() {
    const calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek,dayGridDay'
        },
        dateClick: function (info) {
            showDayView(info.date);
        },
        events: function (fetchInfo, successCallback, failureCallback) {
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
        collapseToggle.addEventListener('click', function () {
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
        mobileSidebarToggle.addEventListener('click', function () {
            sidebar.classList.add('show-mobile');
        });
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.remove('show-mobile');
        });
    }

    // Navigation link click handlers
    const navLinks = document.querySelectorAll('.nav-link');
    const contentTitle = document.getElementById('content-title');

    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
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
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            sidebar.classList.remove('show-mobile');
        }
    });
}

function initTaskManagement() {
    const saveTaskBtn = document.getElementById('save-task-btn');
    const taskModal = document.getElementById('taskModal');

    if (!saveTaskBtn || !taskModal) {
        console.error('Required elements for task management not found');
        return;
    }

    // Task form submission
    saveTaskBtn.addEventListener('click', function () {
        const taskTitle = document.getElementById('task-title');
        const taskDate = document.getElementById('task-date');
        const taskDescription = document.getElementById('task-description');

        if (!taskTitle || !taskDate || !taskDescription) {
            console.error('Form elements not found');
            return;
        }

        const titleValue = taskTitle.value.trim();
        const dateValue = taskDate.value;

        if (titleValue && dateValue) {
            const date = new Date(taskDate);
            const success = addTask(date, taskTitle, taskDescription);

            if (success) {
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

            } else {
                alert('Failed to save task. Please try again.');

            }
        }
    });

    // Modal show event
    taskModal.addEventListener('show.bs.modal', function () {
        const activeSection = document.querySelector('.content-section:not(.d-none)');
        if (!activeSection) return;

        const taskDate = document.getElementById('task-date');
        if (!taskDate) return;

        let defaultDate = new Date();
        if (activeSection.id === 'day-view-content' && selectedDate) {
            defaultDate = selectedDate;
        }
        taskDate.valueAsDate = defaultDate;
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

function renderTasksForDate(date) {
    const dateStr = date.toISOString().split('T')[0];
    const tasksList = document.getElementById('tasks-list');
    const noTasksMessage = document.getElementById('no-tasks-message');

    if (!tasksList || !noTasksMessage) {
        console.error('Required DOM elements not found');
        return;
    }

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
            checkbox.addEventListener('change', async function () {
                const success = await updateTaskStatus(task.id, this.checked);
                if (success) {
                    task.completed = this.checked;
                    renderTasksForDate(date);
                    updateTodayTasks();
                    updateUpcomingTasks();
                    updateCompletedTasks();
                    calendar.refetchEvents();
                } else {
                    // Revert checkbox if update failed
                    this.checked = !this.checked;
                }
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
    checkbox.addEventListener('change', async function () {
        const success = await updateTaskStatus(task.id, this.checked);
        if (success) {
            task.completed = this.checked;
            updateTodayTasks();
            updateUpcomingTasks();
            updateCompletedTasks();
            calendar.refetchEvents();
        } else {
            // Revert checkbox if update failed
            this.checked = !this.checked;
        }
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


