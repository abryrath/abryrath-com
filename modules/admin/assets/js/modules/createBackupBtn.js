class CreateBackupBtn {
    constructor(node) {
        this.node = node;
        this.projectId = node.dataset.projectId;
        this.setDisabled = this.setDisabled.bind(this);
        this.node.addEventListener('click', this.createBackup.bind(this));
    }

    createBackup() {
        this.setDisabled(true);
        fetch(`/admin/admin/projects/${this.projectId}/backups/create`, {
                method: 'GET',
                credentials: 'same-origin',
                mode: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(resp => resp.json())
            .then(data => console.log(data))
            .finally(() => this.setDisabled(false));
    }

    setDisabled(disabled = true) {
        if (disabled) {
            this.node.classList.add('disabled');
            this.node.innerText = 'Loading...';
        } else {
            this.node.classList.remove('disabled');
            this.node.innerText = 'Create backup';
        }
    }
}

export const onInit = () => {
    const btn = document.querySelector('#CreateBackup');
    if (btn) {
        new CreateBackupBtn(btn);
    }
}