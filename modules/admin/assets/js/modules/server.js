class ServerForm {
    constructor(node) {
        this.node = node;
        this.toggleBtn = node.querySelector('[data-toggle]');
        this.form = node.querySelector('form');
        this.submitBtn = this.form.querySelector('.submit');

        this.submitBtn.addEventListener('click', this.submit.bind(this));
        this.toggleBtn.addEventListener('click', this.toggleForm.bind(this));
    }

    toggleForm() {
        this.form.classList.toggle('active');
    }

    submit() {
        const formData = new FormData();
        this.form.querySelectorAll('input').forEach(input => {
            formData.append(input.name, input.value);
        });

        fetch('/admin/admin/servers/new', {
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
    const form = document.querySelector('#newServer');
    if (form) {
        new ServerForm(form);
    }
};