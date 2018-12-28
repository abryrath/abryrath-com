import { onInit as newProject } from './modules/project';
import { onInit as newServer } from './modules/server';
import { onInit as createBackupBtn } from './modules/createBackupBtn';
import { onInit as intervalPicker } from './modules/intervalPicker';
import '../scss/Admin.scss';

class AdminModule {
    constructor() {
        console.log('Admin');
        newProject();
        newServer();
        createBackupBtn();
        intervalPicker();
    }
}

window.onload = () => {
    new AdminModule();
};