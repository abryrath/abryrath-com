class IntervalPicker {
    constructor(node) {
        this.node = node;
        this.value = {};
        this.update = this.update.bind(this);
        this.inputs = node.querySelectorAll('input[type="number"]');
        this.inputs.forEach(input => {
            input.addEventListener('change', this.update);
        });

        this.update();
    }

    update() {
        const types = ['weeks', 'days', 'hours'];
        types.forEach(type => {
            let val = this.node.querySelector(`[data-${type}]`).value;
            val = parseInt(val);
            if (val < 0) {
                val = 0;
            }
            this.value[type] = val;
        })

        this.node.querySelector('input[type="text"]').value = JSON.stringify(this.value);
    }
}

export const onInit = () => {
    const pickers = document.querySelectorAll('[data-interval-picker]');
    if (pickers && pickers.length) {
        pickers.forEach(picker => {
            new IntervalPicker(picker);
        });
    }
}