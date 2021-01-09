import Controller from "@ember/controller";
import { action } from "@ember/object";
import { tracked } from "@glimmer/tracking";

export default class TasksController extends Controller {
    @tracked tasks = this.model.tasks;
    @tracked totalTasks = this.model.totalTasks;
    @tracked doneTasks = this.model.doneTasks;
    @tracked needToFinishTasks = this.model.needToFinishTasks;
    @tracked showPopup = 0;
    @tracked finalMsg = "";
    @tracked userId = localStorage.getItem("userId");
    @tracked showPopupShare = 0;
    @tracked showPopupEdit = 0;
    @tracked users;
    @tracked editTaskArray = [];
    @tracked editMsg = "";

    @action
    logOut() {
        localStorage.clear();
        window.location.href = "/";
    }
    @action
    async doneTask(id, checked) {
        var result = await this.fetchApi("checkDone", {
            id: id,
            checked: checked,
        });
        var tasksArray = result["data"][0];
        this.tasks.forEach(function(element, index) {
            if (element.id == tasksArray.id) {
                Ember.set(element, "done", tasksArray.done);
            }
        });
        if (checked) {
            this.doneTasks++;
            this.needToFinishTasks--;
        } else {
            this.doneTasks--;
            this.needToFinishTasks++;
        }
    }
    @action
    async addTask() {
        let name = this.get("taskName");
        this.resetMsg();
        if (name == "" || name == undefined) {
            this.finalMsg = "Please enter a valid name";
        } else {
            var result = await this.fetchApi("addTask", {
                id: this.userId,
                name: name,
            });
            if (result.answer) {
                this.tasks.pushObject(result.task[0]);
                this.showPopup = 0;
                this.resetMsg();
                this.totalTasks++;
                if (result.task[0].done) {
                    this.doneTasks++;
                } else {
                    this.needToFinishTasks++;
                }
            } else {
                this.finalMsg = result.finalMsg;
            }
        }
    }

    @action
    async editTask(id) {
        let name = this.get("editTaskSelect");
        this.resetMsg();
        if (name == "" || name == undefined) {
            this.editMsg = "Please enter a valid name";
        } else {
            var result = await this.fetchApi("editTask", {
                taskId: id,
                'name': name
            });
            if (result.answer) {
                this.tasks.forEach(function(element) {
                    if (element.id == id) {
                        Ember.set(element, "name", result.task[0].name);
                    }
                });
                this.showPopupEdit = 0;
            } else {
                this.editMsg = result.finalMsg;
            }
        }
    }

    @action
    async deleteTask(id) {
        var result = await this.fetchApi("deleteTask", {
            taskId: id,
            userId: this.userId,
        });
        var totalDone = this.doneTasks;
        var totalNeedToFinishTasks = this.needToFinishTasks;
        this.tasks.forEach(function(element) {
            if (element.id == id) {
                if (element.done) {
                    totalDone--;
                } else {
                    totalNeedToFinishTasks--;
                }
            }
        });
        this.doneTasks = totalDone;
        this.needToFinishTasks = totalNeedToFinishTasks;
        this.totalTasks--;
        if (this.totalTasks < this.needToFinishTasks) {
            this.needToFinishTasks = this.totalTasks;
        }
        if (this.totalTasks < this.doneTasks) {
            this.doneTasks = this.totalTasks;
        }
        this.tasks = result;
    }

    @action
    async shareTask(shareId, taskId, checked) {
        var result = await this.fetchApi("changeShare", {
            userId: this.userId,
            shareId: shareId,
            taskId: taskId,
            checked: checked,
        });
        this.tasks = result;
        var unsharedUsers = [];
        this.users.forEach(function(element, key) {
            if (element.id != shareId) {
                unsharedUsers.pushObject(element);
            }
        });
        this.users = unsharedUsers;
        this.tasks.forEach(function(element, key) {
            if (element.id == taskId) {
                Ember.set(element, "shared", 1);
            }
        });
    }

    @action
    showPopupDiv() {
        this.showPopup = 1;
    }

    @action
    closePopupDiv() {
        this.showPopup = 0;
        this.resetMsg();
    }

    @action
    showPopupEditDiv(id) {
        var editTask = [];
        this.showPopupEdit = 1;
        this.tasks.forEach(function(element, key) {
            if (element.id == id) {
                editTask = element;
            }
        });
        this.editTaskArray = editTask;
    }

    @action
    closePopupEdit() {
        this.showPopupEdit = 0;
        this.resetMsg();
    }

    @action
    async getSharedUsers(id, index) {
        var result = await this.fetchApi("getSharedUsers", {
            id: id,
            userId: this.userId,
        });
        this.tasks.forEach(function(element, key) {
            if (key == index) {
                Ember.set(element, "showPopupShare", 1);
            }
        });
        this.tasks[index].showPopup = 1;
        this.users = result;
    }

    @action
    closeShareDiv(index) {
        this.tasks.forEach(function(element, key) {
            if (key == index) {
                Ember.set(element, "showPopupShare", 0);
            }
        });
        this.tasks[index].showPopup = 0;
        this.resetMsg();
    }

    resetMsg() {
        this.finalMsg = "";
        this.editMsg = "";
    }

    async fetchApi(addedLocation, data = []) {
        var dataResult;
        var location = "http://localhost:8000/fetch/" + addedLocation;
        await fetch(location, {
                method: "POST",
                credentials: "same-origin",
                headers: {
                    Accept: "application/json",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    data: data,
                }),
            })
            .then((response) => response.json())
            .then((result) => {
                dataResult = result;
            });
        return dataResult;
    }
}