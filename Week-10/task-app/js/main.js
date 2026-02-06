import { createTask, toggleTask } from "./task.js";
import { loadTasks, saveTasks } from "./storage.js";
import { renderTasks } from "./ui.js";

const input = document.getElementById("taskInput");
const list = document.getElementById("taskList");
const addBtn = document.getElementById("addBtn");

let tasks = loadTasks();

function update() {
    saveTasks(tasks);
    renderTasks(tasks, list, handleToggle, handleDelete);
}

function handleToggle(id) {
    const task = tasks.find(t => t.id === id);
    if (task) {
        toggleTask(task);
        update();
    }
}

function handleDelete(id) {
    tasks = tasks.filter(t => t.id !== id);
    update();
}

addBtn.onclick = () => {
    const title = input.value.trim();
    if (title) {
        const newTask = createTask(title);
        tasks.push(newTask);
        input.value = '';
        update();
    }
};

// Allow pressing Enter to add task
input.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        addBtn.click();
    }
});

update();