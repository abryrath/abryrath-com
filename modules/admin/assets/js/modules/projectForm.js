class ProjectForm {
    constructor(node) {
        this.node = node;
        this.toggleBtn = node.querySelector('[data-toggle]');
        this.form = node.querySelector('form');
        this.submitBtn = this.form.querySelector('.submit');
        this.edit = false;
        
        if (this.form.dataset.edit) {
            this.edit = true;
        }

        this.submitBtn.addEventListener('click', this.submit.bind(this));
        this.toggleBtn.addEventListener('click', this.toggleForm.bind(this));
    }

    toggleForm() {
        this.form.classList.toggle('active');
    }

    submit() {
        const formData = new FormData();
        this.form.querySelectorAll('input,select').forEach(input => {
            formData.append(input.name, input.value);
        });

        let url;
        if (this.edit) {
            const id = this.form.querySelector('[name="id"').value;
            url = `/admin/admin/projects/update/${id}`;
        } else {
            url = '/admin/admin/projects/new';
        }
        fetch(url, {
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            method: "POST",
            body: formData,
            })
            .then(resp => resp.json())
            .then(data => console.log(data));
    }
}

export const onInit = () => {
    const form = document.querySelector('#newProject');
    if (form) {
        new ProjectForm(form);
    }
};