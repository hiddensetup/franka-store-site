function limitInputLength(element, maxLength) {
    if (element.textContent.length > maxLength) {
        element.textContent = element.textContent.slice(0, maxLength);
    }
}

function getCurrentDate() {
    const currentDateTime = new Date();

    const optionsDate = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit', timeZoneName: 'long' };

    const dateFormatter = new Intl.DateTimeFormat('es', optionsDate);
    const timeFormatter = new Intl.DateTimeFormat('es', optionsTime);

    const currentDate = dateFormatter.format(currentDateTime);
    const currentTime = timeFormatter.format(currentDateTime);

    return [currentDate, currentTime];
}

function createColumn() {
    const columnId = `column-${Math.random().toString(36).substring(7)}`;
    const newColumn = document.createElement('div');
    newColumn.className = ' sm-w-100 mt-4 mb-2 col-sm-8 col-md-6 col-lg-5 col-xl-4 col-xxl-4 col-9';
    newColumn.id = columnId;

    newColumn.innerHTML = `
        <div class="shadow card rounded-4 bg-dark fadeIn">
            <div class="py-1 card-header rounded-top-4 bg-dark text-white">
                <div class="m-0 d-flex align-items-center justify-content-between">
                    <p contenteditable="true" class="rounded focus-ring focus-ring-light mb-0 p-1" oninput="limitInputLength(this, 20)">Task list</p>
                    <li style="list-style: none;" class="nav-item dropdown mb-0 p-1">
                        <button class="rounded-4 btn btn-sm btn-dark dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i>
                        </button>
                        <ul class="dropdown-menu rounded-3 dropdown-menu-light">
                            <li><a class="dropdown-item" href="#" onclick="addColumn()"><i class="bi bi-list"></i> Create a new list</a></li>
                            <li><a class="dropdown-item" href="#" onclick="deleteColumn('${columnId}')"><i class="bi bi-trash"></i> Delete list</a></li>
                        </ul>
                    </li>
                </div>
            </div>
            <div class="task-container m-1">
                <div class="list-group task-container-inner" ondrop="drop(event, '${columnId}')" ondragover="allowDrop(event)">
                    <div onclick="addTask('${columnId}')" class="list-group-item text-center  placeholder"><i style="position: relative;top: 35%;" class="bi bi-plus-lg text-light"></i></div>
                </div>
            </div>
            <div class="rounded-bottom-4 bg-dark card-footer">
                <button type="button" class="rounded-4 btn-sm btn btn-outline-light btn-column" onclick="addTask('${columnId}')">Add a task <i class="bi bi-pencil-square"></i></button>
            </div>
        </div>
    `;

    return newColumn;
}

function addColumn() {
    const board = document.getElementById('kanban-board');
    const newColumn = createColumn();
    board.insertBefore(newColumn, board.lastElementChild);
}

function deleteColumn(columnId) {
    const column = document.getElementById(columnId);
    column.remove();
}

function addTask(columnId) {
    const column = document.getElementById(columnId).querySelector('.task-container-inner');
    const taskId = `task-${Math.random().toString(36).substring(7)}`;

    const [dateLine, timeLine] = getCurrentDate();

    column.innerHTML += `
        <div id="${taskId}" class="list-group-item task" draggable="true" ondragstart="drag(event, '${taskId}')">
            <div class="fw-bold" contenteditable="true" onclick="openTaskModal('${taskId}')">New Task</div>
            <small class="text-muted"><i class="bi bi-calendar-plus"></i> ${dateLine}<br><i class="bi bi-clock"></i> ${timeLine}</small>
            <div class="task-tags"></div>
        </div>
    `;

    const placeholder = column.querySelector('.placeholder');

    if (placeholder) {
        placeholder.remove();
    }

    currentTaskId = taskId;
    openTaskModal(taskId);
}


function openTaskModal(taskId) {
    currentTaskId = taskId;
    const task = document.getElementById(taskId).querySelector('div');
    const taskTitleInput = document.getElementById('taskTitle');
    const taskContentTextarea = document.getElementById('taskContent');
    const taskPhoneInput = document.getElementById('taskPhone');
    const taskTagsInput = document.getElementById('taskTags');

    taskTitleInput.value = task.textContent;
    taskContentTextarea.value = task.dataset.content || '';
    taskPhoneInput.value = task.dataset.phone || '';
    taskTagsInput.value = task.dataset.tags || '';

    const taskModal = new bootstrap.Modal(document.getElementById('taskModal'), { backdrop: 'static' });
    taskModal.show();
}

function saveTask() {
    const taskTitleInput = document.getElementById('taskTitle');
    const taskContentTextarea = document.getElementById('taskContent');
    const taskPhoneInput = document.getElementById('taskPhone');
    const taskTagsInput = document.getElementById('taskTags');
    const taskEmailInput = document.getElementById('taskEmail');
    const task = document.getElementById(currentTaskId).querySelector('div');
    const taskTagsContainer = document.getElementById(currentTaskId).querySelector('.task-tags');

    task.textContent = taskTitleInput.value;
    task.dataset.content = taskContentTextarea.value;
    task.dataset.phone = taskPhoneInput.value;
    task.dataset.tags = taskTagsInput.value;
    task.dataset.email = taskEmailInput.value;

    taskTagsContainer.innerHTML = '';
    const tags = taskTagsInput.value.split(',').map(tag => tag.trim());
    tags.forEach(tag => {
        if (tag !== '') {
            const tagDiv = document.createElement('div');
            tagDiv.classList.add('badge', 'bg-secondary', 'me-1');
            tagDiv.textContent = tag;
            taskTagsContainer.appendChild(tagDiv);
        }
    });

    const taskModal = new bootstrap.Modal(document.getElementById('taskModal'), { backdrop: 'static' });
    taskModal.hide();

    const taskTitle = taskTitleInput.value;
    const taskContent = taskContentTextarea.value;
    const taskPhone = taskPhoneInput.value;
    const taskTags = taskTagsInput.value;
    const taskEmail = taskEmailInput.value;

    const column = document.getElementById(currentTaskId).closest('.col-xxl-4');
    const columnNameElement = column.querySelector('p[contenteditable=true]');
    const columnId = column.id;

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'php/save.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Handle the response if needed
        }
    };

    xhr.send(`taskId=${currentTaskId}&taskTitle=${taskTitle}&taskContent=${taskContent}&taskPhone=${taskPhone}&taskTags=${taskTags}&taskEmail=${taskEmail}&columnName=${columnNameElement.textContent}&columnId=${columnId}`);
}

function validatePhoneInput() {
    const phoneInput = document.getElementById('taskPhone');
    const cleanedInput = phoneInput.value.replace(/\D/g, '');
    phoneInput.value = cleanedInput.length > 0 ? '+' + cleanedInput : '';
}

function deleteTask() {
    const task = document.getElementById(currentTaskId);
    const column = task.closest('.task-container-inner');
    task.remove();

    if (!column.innerHTML.trim()) {
        column.innerHTML += `<div class="list-group-item text-center placeholder"><i style="position: relative;top: 35%;" class="bi bi-three-dots text-light"></i></div>`;
    }

    const taskModal = new bootstrap.Modal(document.getElementById('taskModal'), { backdrop: 'static' });
    taskModal.hide();
}

function allowDrop(event) {
    event.preventDefault();
}

function drag(event, taskId) {
    event.dataTransfer.setData('text', taskId);
}

function drop(event, columnId) {
    event.preventDefault();
    const taskId = event.dataTransfer.getData('text');
    const task = document.getElementById(taskId);
    const taskContainer = document.getElementById(columnId).querySelector('.task-container-inner');

    const placeholder = taskContainer.querySelector('.placeholder');
    if (placeholder) {
        placeholder.remove();
    }

    task.remove();
    taskContainer.appendChild(task);
}

function loadTasks() {
    fetch('php/fetch.php', {
        method: 'GET',
    })
    .then(response => response.json())
    .then(data => {
        const board = document.getElementById('kanban-board');
        board.innerHTML = ''; // Clear existing columns

        data.forEach(columnData => {
            const columnId = columnData.column_id;
            const columnName = columnData.column_name;
            const columnTasks = columnData.tasks;

            const newColumn = createColumn();
            newColumn.id = columnId;
            const columnNameElement = newColumn.querySelector('p[contenteditable=true]');
            columnNameElement.textContent = columnName;

            const columnContainer = newColumn.querySelector('.task-container-inner');

            columnTasks.forEach(task => {
                const taskId = task.task_id;
                const taskTitle = task.task_title;

                const [dateLine, timeLine] = getCurrentDate();

                columnContainer.innerHTML += `
                    <div id="${taskId}" class="list-group-item task" draggable="true" ondragstart="drag(event, '${taskId}')">
                        <div class="fw-bold" contenteditable="true" onclick="openTaskModal('${taskId}')">${taskTitle}</div>
                        <small class="text-muted"><i class="bi bi-calendar-plus"></i> ${dateLine}<br><i class="bi bi-clock"></i> ${timeLine}</small>
                        <div class="task-tags"></div>
                    </div>
                `;
            });

            const placeholder = columnContainer.querySelector('.placeholder');

            if (placeholder) {
                placeholder.remove();
            }

            board.insertBefore(newColumn, board.lastElementChild);
        });
    })
    .catch(error => console.error('Error fetching tasks:', error));
}




document.addEventListener('DOMContentLoaded', function () {
    loadTasks();

    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');
    const noResultsMessage = document.getElementById('noResultsMessage');

    searchInput.addEventListener('input', function () {
        const searchTerm = searchInput.value.trim().toLowerCase();
        let anyMatches = false; 

        const columns = document.querySelectorAll('.task-container-inner');
        columns.forEach(function (column) {
            let columnHasMatch = false;

            const tasks = column.querySelectorAll('.task');
            tasks.forEach(function (task) {
                const taskTitleElement = task.querySelector('.fw-bold');
                const taskTitle = taskTitleElement ? taskTitleElement.textContent.toLowerCase() : '';

                if (searchTerm === '' || taskTitle.includes(searchTerm)) {
                    columnHasMatch = true;
                    task.style.display = 'block';
                    anyMatches = true;
                } else {
                    task.style.display = 'none';
                }
            });

            if (columnHasMatch || searchTerm === '') {
                column.closest('.col-xxl-4').style.display = 'block';
            } else {
                column.closest('.col-xxl-4').style.display = 'none';
            }
        });

        if (anyMatches || searchTerm === '') {
            noResultsMessage.style.display = 'none';
        } else {
            noResultsMessage.style.display = 'block';
        }
    });
});