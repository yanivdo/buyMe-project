import EmberRouter from '@ember/routing/router';
import config from 'client/config/environment';

export default class Router extends EmberRouter {
    location = config.locationType;
    rootURL = config.rootURL;
    login = 0;
}



Router.map(function() {
    this.route('entrance', { path: "/" });
    this.route('userPage', { path: "/user" });
    this.route('tasks');
});