import { onInit as newProject } from './modules/project';
import { onInit as newServer } from './modules/server';
import { onInit as createBackupBtn } from './modules/createBackupBtn';

import '../scss/Admin.scss';

class AdminModule {
    constructor() {
        console.log('Admin');
        newProject();
        newServer();
        createBackupBtn();
    }
}

window.onload = () => {
    new AdminModule();
};