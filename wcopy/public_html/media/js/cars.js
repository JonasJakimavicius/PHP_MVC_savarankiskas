'use strict';

const endpoints = {
    get: 'api/cars/get.php',
    create: 'api/cars/create.php',
    update: 'api/cars/update.php',
    delete: 'api/cars/delete.php'
};

function api(url, formData, success, fail) {
    fetch(url, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .catch(e => {
            console.log(e)
            fail(['Could not read json'])
        })
        .then(response => {
            if (response.status === 'success') {
                success(response.data)
            } else {
                fail(response.errors)
            }
        })
        .catch(e => {
            console.log(e);
            fail(['Could not connect to API'])
        })
}

const forms = {
    create: {
        init: function () {
            console.log('Setting eventListeners on create form');
            this.getElement().addEventListener('submit', this.onSubmitListener)
        },
        getElement: function () {
            return document.querySelector('#create-form')
        },
        onSubmitListener: function (e) {
            e.preventDefault();
            let formData = new FormData(e.target);
            api(endpoints.create, formData, forms.create.success, forms.create.fail)
        },
        success: function (data) {
            const element = forms.create.getElement();

            carTable.card.insertCard(data);
            forms.ui.errors.hide(element);
            forms.ui.clear(element);
        },
        fail: function (errors) {
            forms.ui.errors.show(forms.create.getElement(), errors);
        }
    },
    update: {
        init: function () {
            console.log('Initializing update form...');
            this.elements.form().addEventListener('submit', forms.update.onSubmitListener);

            const closeBtn =forms.update.elements.modal().querySelector('.close');
            closeBtn.addEventListener('click', forms.update.onCloseListener);
        },
        elements: {
            form: function () {
                return document.querySelector('#update-form');
            },
            modal: function () {
                return document.querySelector('#update-modal');
            }
        },
        onSubmitListener: function (e) {
            e.preventDefault();
            console.log(e.target)
            let formData = new FormData(e.target);
            let id = forms.update.elements.modal().getAttribute('card-id');

            formData.append('card-id', id);

            api(endpoints.update, formData, forms.update.success, forms.update.fail);
        },
        success: function (data) {
            console.log(data)
            carTable.card.update(data);
            forms.update.hide();
        },
        fail: function (errors) {
            forms.ui.errors.show(forms.update.elements.form(), errors)
        },
        fill: function (data) {
            forms.ui.fill(forms.update.elements.modal(), data)
        },
        onCloseListener: function (e) {
            forms.update.hide();
        },
        // onClickListener: function (e) {
        //     console.log(e)
        //     this.update.hide();
        // },
        show: function () {
            this.elements.modal().style.display = 'block';
        },
        hide: function () {
           this.elements.modal().style.display = 'none';
        },
    },

    ui: {
        init: function () {
            //empty function
        },

        fill: function (form, data) {
            console.log(form)
            console.log(data)
            form.setAttribute('card-id', data.id)
            console.log(Object.keys(data))
            Object.keys(data).forEach(data_id => {

                if (data_id !== 'id') {
                    const input = form.querySelector('input[name="' + data_id + '"]');
                    if (input) {
                        input.value = data[data_id];
                    }

                }
            })
        },
        errors: {
            hide: function (form) {
                const errors = form.querySelectorAll('.field-error');
                if (errors) {
                    errors.forEach(node => {
                        node.remove();
                    });
                }
                ;
            },
            show: function (form, errors) {
                this.hide(form);

                Object.keys(errors).forEach(function (error_id) {
                    const field = form.querySelector('input[name="' + error_id + '"]');
                    console.log(error_id);
                    const span = document.createElement("span");
                    span.className = 'field-error';
                    span.innerHTML = errors[error_id];
                    field.parentNode.append(span);
                    console.log('Form error in field: ' + error_id + ':' + errors[error_id]);
                });
            },
        },
        clear: function (form) {
            let fields = Array.from(form);
            fields.forEach(field => {
                field.value = '';
            })
        },
    }

}

const carTable = {
    init: function () {
        this.data.load();

        Object.keys(this.buttons).forEach(buttonId => {
            carTable.buttons[buttonId].init();
        });
    },
    data: {
        load: function () {
            api(endpoints.get, null, this.success, this.fail);
        },
        success: function (data) {
            Object.keys(data).forEach(i => {
                carTable.card.insertCard(data[i]);
            });
        },
        fail: function (errors) {
            console.log(errors)
        }
    },
    card: {
        buildContent: function (data) {
            let cardContainer = document.createElement('div');
            cardContainer.className = 'card-container';
            cardContainer.setAttribute('card-id', data.id);
            cardContainer.innerHTML = `
            <div class="image-container">
            <img src="${data.image}" alt="nerado">
            </div>
            <div class="car-brand">Gamintojas: ${data.brand}</div>
            <div class="car-model">Modelis: ${data.model}</div>
            <div class="car-year">Pagaminimo metai ${data.year}</div>
            <div class="car-fuel">Kuro tipas:${data.fuel_type}</div>
           <div class="car-doors">Durų skaičius: ${data.doors}</div>
           </div> `;

            let buttons = {
                delete: 'Ištrinti',
                edit: 'Redaguoti'
            };

            Object.keys(buttons).forEach(button_id => {
                let btn = document.createElement('button');
                btn.innerHTML = buttons[button_id];
                btn.className = button_id;
                btn.type = 'submit';
                cardContainer.append(btn);
            });

            return cardContainer;
        },
        insertCard: function (data) {
            carTable.getElement().append(this.buildContent(data));
        },
        delete: function (id) {
            console.log(id);
            const card = carTable.getElement().querySelector('div[card-id="' + id + '"]');
            console.log(card);
            card.remove();
        },
        update: function (data) {
            console.log(data)
            let card = carTable.getElement().querySelector('div[card-id="' + data.id + '"]');
            card.replaceWith(this.buildContent(data));

        }
    },
    getElement: function () {
        return document.querySelector('#cardsContainer')
    },
    buttons: {
        delete: {
            init: function () {
                carTable.getElement().addEventListener('click', this.onClickListener);
            },
            getElements: function () {
                return document.querySelectorAll('.delete-btn');
            },
            onClickListener: function (e) {
                if (e.target.className === 'delete') {
                    let formData = new FormData();

                    let card = e.target.closest('.card-container');

                    formData.append('data-id', card.getAttribute('card-id'));
                    api(endpoints.delete, formData, carTable.buttons.delete.success, carTable.buttons.delete.fail);
                }
            },
            success: function (data) {
                console.log(data[0]);
                carTable.card.delete(data[0].id);
            },
            fail: function (errors) {
                alert(errors[0]);
            }
        },
        edit: {
            init: function () {
                carTable.getElement().addEventListener('click', this.onClickListener);
            },
            getElements: function () {
                return document.querySelectorAll('.edit-btn');
            },
            onClickListener: function (e) {
                if (e.target.className === 'edit') {
                    let formData = new FormData();

                    let card = e.target.closest('.card-container');
                    formData.append('card-id', card.getAttribute('card-id'));
                    api(endpoints.get, formData, carTable.buttons.edit.success, carTable.buttons.edit.fail);
                }
            },
            success: function (data) {

                let person_data = data[0];
                forms.update.show();
                forms.update.fill(person_data);
            },
            fail: function (errors) {
                alert(errors[0]);
            }
        }
    }
};


/**
 * Core page functionality
 */
const app = {
    init: function () {
        // Initialize all forms
        Object.keys(forms).forEach(formId => {
            forms[formId].init();
        });

        carTable.init();
    }
};

// Launch App
app.init();