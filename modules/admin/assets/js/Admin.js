import { onInit as newProject } from './modules/project';
import { onInit as newServer } from './modules/server';

import '../scss/Admin.scss';

class AdminModule {
    constructor() {
        console.log('Admin');
        newProject();
        newServer();
    }
}

window.onload = () => {
    new AdminModule();
};