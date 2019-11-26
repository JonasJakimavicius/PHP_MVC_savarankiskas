'use strict';

const endpoints = {
    get: 'API/products/get.php',
    delete: 'API/products/delete.php',
    create: 'APi/products/create.php',
    update: 'API/products/update.php',
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
            return document.querySelector('.createForm');
        },
        onSubmitListener: function (e) {
            e.preventDefault();
            let formData = new FormData(e.target);
            api(endpoints.create, formData, forms.create.success, forms.create.fail)
        },
        success: function (data) {
            productsTable.card.insert(data);
            let element = forms.create.getElement();
            forms.ui.errors.hide(element);
            forms.ui.clear(element);
        },
        fail: function (errors) {
            forms.ui.errors.show(forms.create.getElement(), errors);
        },

    },

    update: {
        init: function () {
            console.log('Setting eventListeners on update form');
            this.elements.form().addEventListener('submit', this.onSubmitListener)
        },
        elements: {
            form: function () {
                return document.querySelector('.updateForm');
            },
            modal: function () {
                return document.querySelector('.UpdateForm');
            }
        },
        onSubmitListener: function (e) {
            e.preventDefault();
            let formData = new FormData(e.target);
            formData.append('id', forms.update.elements.form().getAttribute('card-id'));
            api(endpoints.update, formData, forms.update.success, forms.update.fail)
        },
        success: function (data) {
            productsTable.card.update(data);
            forms.update.hide(forms.update.elements.modal())


        },
        fail: function (errors) {
            errors.forEach(error => {
                console.log(error)
            })
        },
        show: function () {
            forms.update.elements.modal().style.display = 'block';
        },
        hide: function () {
            forms.update.elements.modal().style.display = 'none';
        },
        fill: function (product) {
            forms.ui.fill(forms.update.elements.form(), product)
        }


    },

    ui: {
        init: function () {
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
        fill: function (form, product) {
            form.setAttribute('card-id', product.id);
            Object.keys(product).forEach(input_id => {
                if (input_id !== 'id') {
                    let input = form.querySelector('input[name="' + input_id + '"]');
                    input.value = product[input_id];
                }
            });
        }
    }
}

const productsTable = {
    init: function () {
        this.data.load();

        Object.keys(this.buttons).forEach(buttonId => {
            productsTable.buttons[buttonId].init();
        });
    },

    getElement: function () {
        return document.querySelector('.productTable');
    },
    data: {
        load: function () {
            api(endpoints.get, null, this.success, this.fail);
        },
        success: function (data) {
            data.forEach(product => {
                productsTable.card.insert(product);
            })
        },
        fail: function (errors) {
            errors.forEach(error => {
                console.log(error)
            })
        }

    },

    card: {
        render: function (product) {
            let cardCont = document.createElement('div');
            cardCont.className = 'card-container';
            cardCont.setAttribute('card-id', product.id);
            cardCont.innerHTML = `
            <div class="image-container">
            <img src="${product.image}" alt="nerado">
            </div>
            <div class="product-title">Pavadinimas: ${product.title}</div>
            <div class="product-color">Spalva: ${product.color}</div>
            <div class="product-amount">Kiekis: ${product.amount}</div>
            <div class="product-price">Kaina:${product.price}</div>
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
                cardCont.append(btn);
            });

            return cardCont;

        },
        insert: function (product) {
            productsTable.getElement().append(productsTable.card.render(product));
        },
        delete: function (product) {
            let id = product.id;
            const card = productsTable.getElement().querySelector('div[card-id="' + id + '"]');
            card.remove();
        },
        update: function (data) {
            let card = document.querySelector('div[card-id="' + data[0].id + '"]')
            card.replaceWith(this.render(data[0]));
        }
    },

    buttons: {
        delete: {
            init: function () {
                productsTable.getElement().addEventListener('click', this.onClickListener)
            },
            onClickListener: function (e) {

                if (e.target.className === 'delete') {
                    let formData = new FormData();
                    let card = e.target.closest('.card-container');
                    formData.append('data-id', card.getAttribute('card-id'));
                    api(endpoints.delete, formData, productsTable.buttons.delete.success, productsTable.buttons.delete.fail);
                }
            },

            success: function (data) {
                productsTable.card.delete(data[0]);
            },
            fail: function (errors) {
                errors.forEach(error => {
                    console.log(error)
                })
            }

        },
        edit: {
            init: function () {
                productsTable.getElement().addEventListener('click', this.onClickListener)
            },
            onClickListener: function (e) {
                if (e.target.className === 'edit') {
                    let formData = new FormData;
                    let card = e.target.closest('.card-container');
                    formData.append('id', card.getAttribute('card-id'))
                    api(endpoints.get, formData, productsTable.buttons.edit.success, productsTable.buttons.edit.fail);
                }
            },
            success: function (data) {
                let product = data[0];
                forms.update.show();
                forms.update.fill(product);
            },
            fail: function (errors) {
                console.log(errors)
            },
        },
    }
}


/**
 * Core page functionality
 */
const app = {
    init: function () {
        // Initialize all forms
        Object.keys(forms).forEach(formId => {
            forms[formId].init();
        });

        productsTable.init();
    }
};

// Launch App
app.init();