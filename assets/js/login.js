document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('wpml-magic-login');
    const innerForm = document.getElementById('wpml-in-form-wrapper');
    const loader = document.getElementById('wpml-loader');
    const nonce = document.querySelector('[name="nonce"]');
    const action = document.querySelector('[name="action"]');
    const email = document.getElementById('wpml-magic-email');
    const submit = document.getElementById('wpml-magic-submit');
    const label = document.getElementById('wpml-magic-email-label');
    const titleHeading = document.getElementById('title-heading');
    const successHeading = document.getElementById('success-heading');

    const insertAfter = (newNode, referenceNode) => {
        referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
    }

    const triggerError = (referenceNode, errorMessage) => {
        let textElement = document.createElement('p');
        let textContent = document.createTextNode(errorMessage);

        textElement.appendChild(textContent);
        textElement.classList.add('wpml-error-message');
        referenceNode.classList.add('wpml-error-border');

        insertAfter(textElement, referenceNode);
    }

    const resetForm = () => {
        if(innerForm.classList.contains('wpml-d-none')){
            innerForm.classList.remove('wpml-d-none');
        }

        if(!loader.classList.contains('wpml-d-none')){
            loader.classList.add('wpml-d-none');
        }
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if(email.value.length === 0){
            triggerError(email, 'The email field cannot be empty')
            return;
        }

        innerForm.classList.add('wpml-d-none');
        loader.classList.remove('wpml-d-none');

        let formData = new FormData();
        formData.append('action', action.value);
        formData.append('nonce', nonce.value);
        formData.append('wpml-email', email.value);

        fetch(wpmlAjax.ajax_url, {
            method: "POST",
            credentials: 'same-origin',
            body: formData
        }).then((response) => response.text())
            .then((data) => {
                data = JSON.parse(data.slice(0, -1));

                if(data.status === 'error'){
                    resetForm();
                    triggerError(email, data.message);
                }

                if(data.status === 'success'){
                    email.classList.add('wpml-d-none');
                    submit.classList.add('wpml-d-none');
                    label.classList.add('wpml-d-none');
                    titleHeading.classList.add('wpml-d-none');

                    resetForm();

                    successHeading.innerText = data.message;
                    successHeading.classList.remove('wpml-d-none');
                }
            });
    });
}, false);