import { onInit as projectForm } from './modules/projectForm';
import { onInit as projectListing } from './modules/projectListing';
import { onInit as newServer } from './modules/server';
import { onInit as createBackupBtn } from './modules/createBackupBtn';
import { onInit as intervalPicker } from './modules/intervalPicker';
import '../scss/Admin.scss';

class AdminModule {
    constructor() {
        console.log('Admin');
        projectForm();
        projectListing();
        newServer();
        createBackupBtn();
        intervalPicker();
    }
}

window.onload = () => {
    new AdminModule();
};