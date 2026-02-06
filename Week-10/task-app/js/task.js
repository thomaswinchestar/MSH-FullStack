export function createTask(title) {
    return {
        id: Date.now(),
        title,
        completed: false
    };
}

export function toggleTask(task) {
    task.completed = !task.completed;
}