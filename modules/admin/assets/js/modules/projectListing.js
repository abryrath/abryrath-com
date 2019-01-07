export class ProjectListing {
    constructor(node) {
        this.node = node;
        this.id = this.node.dataset.id;
        this.removeUrl = `/admin/admin/projects/remove/${this.id}`;
        this.deleteBtn = this.node.querySelector('.delete');
        this.deleteBtn.addEventListener('click', this.delete);

            this.delete = this.delete.bind(this); this.remove = this.remove.bind(this);

        }

        delete() {
            fetch(this.removeUrl, {
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(resp => resp.json())
                .then(data => {
                    console.log(data);
                    if (data.success == 1) {
                        this.remove();
                    }
                });
        }

        remove() {
            this.deleteBtn.removeEventListener('click', this.delete);
            this.node.innerHTML = '';
        }
    }

    export const onInit = () => {
        document.querySelectorAll('.ProjectListing')
            .forEach(listing => {
                new ProjectListing(listing);
            });
    }