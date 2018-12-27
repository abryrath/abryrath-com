class CreateBackupBtn {
    constructor(node) {
        this.node = node;
        this.projectId = node.dataset.projectId;
        this.node.addEventListener('click', this.createBackup.bind(this));
    }

    createBackup() {
        fetch(`/admin/admin/projects/${this.projectId}/backups/new`, {
                method: 'GET',
                credentials: 'same-origin',
                mode: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(resp => resp.json())
            .then(data => console.log(data));
    }
}

export const onInit = () => {
    const btn = document.querySelector('#CreateBackup');
    if (btn) {
        new CreateBackupBtn(btn);
    }
}