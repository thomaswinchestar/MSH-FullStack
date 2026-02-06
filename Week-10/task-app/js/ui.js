export function renderTasks(tasks, listEl, onToggle, onDelete) {
    listEl.innerHTML = '';

    // Update stats
    updateStats(tasks);

    // Show/hide empty state
    const emptyState = document.getElementById('emptyState');
    if (tasks.length === 0) {
        emptyState.classList.remove('hidden');
        return;
    } else {
        emptyState.classList.add('hidden');
    }

    tasks.forEach((task) => {
        const li = document.createElement('li');
        li.className = `task-item ${task.completed ? 'completed' : ''}`;

        li.innerHTML = `
            <input type="checkbox" class="task-checkbox" ${task.completed ? 'checked' : ''} />
            <span class="task-title ${task.completed ? 'completed' : ''}">
                ${task.title}
            </span>
            <button class="delete-btn">Delete</button>
        `;

        li.querySelector('.task-checkbox').onclick = () => onToggle(task.id);
        li.querySelector('.delete-btn').onclick = () => onDelete(task.id);

        listEl.appendChild(li);
    });
}

function updateStats(tasks) {
    const total = tasks.length;
    const completed = tasks.filter(t => t.completed).length;
    const active = total - completed;

    document.getElementById('totalTasks').textContent = total;
    document.getElementById('activeTasks').textContent = active;
    document.getElementById('completedTasks').textContent = completed;
}

