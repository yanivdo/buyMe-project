import Controller from "@ember/controller";
import { action } from "@ember/object";
import { tracked } from "@glimmer/tracking";
import fetch from "fetch";
import { storageFor } from 'ember-local-storage';


export default class EntranceController extends Controller {
    @tracked loginOrRegister = 1;
    @tracked emailErrMsg = "";
    @tracked passwordErrMsg = "";
    @tracked passwordRErrMsg = "";
    @tracked loginFinalMsg = "";
    @tracked nameErrMsg = "";

    @action
    submitLogin() {
        let err = 0;
        let email = this.get("email");
        let password = this.get("password");
        this.resetMsg();
        if (email == "" || email == undefined) {
            err++;
            this.emailErrMsg = "Please enter valid Email";
        }
        if (password == "" || password == undefined) {
            err++;
            this.passwordErrMsg = "Please enter valid password";
        }
        if (err == 0) {

            fetch("http://localhost:8000/fetch/Login", {
                    method: "POST",
                    credentials: 'same-origin',
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                    }),
                    cwithCredentials: true
                })
                .then((response) => response.json())
                .then((result) => {
                    this.emailErrMsg = result.emailMsg;
                    this.passwordErrMsg = result.passwordMsg;
                    this.loginFinalMsg = result.finalMsg;
                    var id = result.id;
                    localStorage.setItem("userId", id);
                    if (result.answer) {
                        window.location.href = "/tasks";
                    }
                });
        }
    }

    @action
    submitRegister() {
        let err = 0;
        let email = this.get("Remail");
        let password = this.get("Rpassword");
        let REpassword = this.get("REpassword");
        let name = this.get("name");
        this.resetMsg();
        if (email == "" || email == undefined) {
            err++;
            this.emailErrMsg = "Please enter valid Email";
        }
        if (password == "" || password == undefined) {
            err++;
            this.passwordErrMsg = "Please enter valid password";
        }
        if (password != REpassword) {
            err++;
            this.passwordRErrMsg = "Passwords does not match";
        }
        if (name == "" || name == undefined) {
            err++;
            this.nameErrMsg = "Please enter valid name";
        }
        if (err == 0) {
            fetch("http://localhost:8000/fetch/Register", {
                    method: "POST",
                    headers: {
                        Accept: "application/json",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password,
                        name: name
                    }),
                })
                .then((response) => response.json())
                .then((result) => {
                    this.emailErrMsg = result.emailMsg;
                    this.passwordErrMsg = result.passwordMsg;
                    this.loginFinalMsg = result.finalMsg;
                    if (result.answer) {
                        var id = result.id;
                        localStorage.setItem("userId", id);
                        setTimeout(function() {
                            window.location.href = "/tasks";
                        }, 1000);
                    }
                });
        }
    }

    @action
    changeLoginSubmit(change) {
        this.loginOrRegister = change;
        this.resetMsg();
    }

    resetMsg() {
        this.emailErrMsg = "";
        this.passwordErrMsg = "";
        this.passwordRErrMsg = "";
        this.loginFinalMsg = "";
        this.nameErrMsg = "";
    }
}