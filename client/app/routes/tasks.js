import Route from "@ember/routing/route";

export default class TasksRoute extends Route {
    async model() {
        var tasks;
        var doneTasksNum;
        var users;
        var id = localStorage.getItem("userId");
        await fetch("http://localhost:8000/fetch/getTasks", {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    id: id,
                }),
            })
            .then((response) => response.json())
            .then((result) => {
                tasks = result.tasks;
                doneTasksNum = result.doneTasks;
            });
        return {
            tasks: tasks,
            totalTasks: tasks.length,
            doneTasks: doneTasksNum,
            needToFinishTasks: tasks.length - doneTasksNum,
        };
    }
}