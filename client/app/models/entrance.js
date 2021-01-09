import Model from "@ember-data/model";
import fetch from "fetch";

export default class EntranceModel extends Model {
    user = {
        email: "",
        passowrd: ""
    };
    model() {}
    checkLogin(email, passowrd) {}
    setupController(controller, model) {
        super.setupController(controller, model);
    }
}