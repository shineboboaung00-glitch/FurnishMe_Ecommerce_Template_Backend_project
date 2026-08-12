let closer = document.querySelector('#closer');

if (closer) {
    closer.onclick = () => {
        closer.style.display = 'none';
        navbar.classList.remove('active');
        cart.classList.remove('active');
    }
}

let navbar = document.querySelector('.navbar');

if (document.querySelector('#menu-btn')) {
    document.querySelector('#menu-btn').onclick = () => {
        closer.style.display = "block";
        navbar.classList.toggle('active');
    }
}

let cart = document.querySelector('.shopping-cart');

if (document.querySelector('#cart-btn')) {
    document.querySelector('#cart-btn').onclick = () => {
        closer.style.display = "block";
        cart.classList.toggle('active');
    }
}

let searchForm = document.querySelector('.header .search-form');

if (document.querySelector('#search-btn')) {
    document.querySelector('#search-btn').onclick = () => {
        searchForm.classList.toggle('active');
    }
}

window.onscroll = () => {
    if (searchForm) searchForm.classList.remove('active');
}

let slides = document.querySelectorAll('.home .slides-container .slide');
let index = 0;

function next() {
    if (!slides.length) return;
    slides[index].classList.remove('active');
    index = (index + 1) % slides.length;
    slides[index].classList.add('active');
}

function prev() {
    if (!slides.length) return;
    slides[index].classList.remove('active');
    index = (index - 1 + slides.length) % slides.length;
    slides[index].classList.add('active');
}

// =============================================================================================

function openDynamicModal(config) {
    let container = document.getElementById('dynamic_modal_container');
    let form = document.getElementById('dynamic_form');
    let moduleInput = document.getElementById('modal_module');
    let actionInput = document.getElementById('modal_action_type');
    let idInput = document.getElementById('modal_item_id');
    let title = document.getElementById('modal_title');
    let message = document.getElementById('modal_message');
    let inputsContainer = document.getElementById('modal_inputs_container');
    let submitBtn = document.getElementById('modal_submit_btn');
    let globalErrorMsg = document.getElementById('modal_error_msg');

    // Reset Errors
    if (globalErrorMsg) {
        globalErrorMsg.style.display = 'none';
        globalErrorMsg.innerText = '';
    }

    moduleInput.value = config.module || '';
    actionInput.value = config.action || '';
    title.innerText = config.title || 'Form';
    inputsContainer.innerHTML = '';

    // Old Image Hidden Input
    let oldImageInput = document.getElementById('modal_old_image');
    if (!oldImageInput) {
        oldImageInput = document.createElement('input');
        oldImageInput.type = 'hidden';
        oldImageInput.name = 'old_image';
        oldImageInput.id = 'modal_old_image';
        form.appendChild(oldImageInput);
    }
    oldImageInput.value = (config.data && config.data.old_image) ? config.data.old_image : '';

    if (config.action === 'delete') {
        idInput.value = config.data ? config.data.id : '';
        message.innerText = config.message || 'Are you sure you want to delete this item?';
        message.style.display = 'block';
        submitBtn.innerText = 'Yes, Delete';
    } else {
        message.style.display = 'none';
        idInput.value = config.data ? (config.data.id || '') : '';
        submitBtn.innerText = config.action === 'create' ? 'Save' : 'Update Changes';

        if (config.fields && config.fields.length > 0) {
            config.fields.forEach(field => {
                let value = (config.data && config.data[field.name] !== undefined) ? config.data[field.name] : (field.default || '');
                let fieldError = field.error ? `<small style="color: #e74c3c; font-size: 1.2rem; display: block; margin-top: 0.3rem;">${field.error}</small>` : '';
                let fieldHTML = '';

                // 1. Textarea Input
                if (field.type === 'textarea') {
                    fieldHTML = `
                    <div class="input-box">
                        <span>${field.label}</span>
                        <textarea name="${field.name}" class="box" placeholder="${field.placeholder || ''}">${value}</textarea>
                        ${fieldError}
                    </div>`;

                // 2. Select Dropdown Input
                } else if (field.type === 'select') {
                    let options = (field.options || []).map(opt =>
                        `<option value="${opt.value}" ${value == opt.value ? 'selected' : ''}>${opt.label}</option>`
                    ).join('');
                    fieldHTML = `
                    <div class="input-box">
                        <span>${field.label}</span>
                        <select name="${field.name}" class="box">${options}</select>
                        ${fieldError}
                    </div>`;

                // 3. Checkbox Input
                } else if (field.type === 'checkbox') {
                    let isChecked = value ? 'checked' : '';
                    fieldHTML = `
                    <div class="input-box" style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="${field.name}" value="${field.value || '1'}" ${isChecked} id="field_${field.name}">
                        <label for="field_${field.name}">${field.label}</label>
                        ${fieldError}
                    </div>`;

                // 4. Radio Input Group
                } else if (field.type === 'radio') {
                    let radios = (field.options || []).map(opt =>
                        `<label style="margin-right: 15px;">
                            <input type="radio" name="${field.name}" value="${opt.value}" ${value == opt.value ? 'checked' : ''}> ${opt.label}
                        </label>`
                    ).join('');
                    fieldHTML = `
                    <div class="input-box">
                        <span>${field.label}</span>
                        <div>${radios}</div>
                        ${fieldError}
                    </div>`;

                // 5. File Input (with Preview feature)
                } else if (field.type === 'file') {
                    let preview = (config.data && config.data.old_image) 
                        ? `<img src="${config.data.old_image}" style="width: 80px; height: 80px; object-fit: cover; margin-top: 5px; border-radius: 5px;" />` 
                        : '';
                    fieldHTML = `
                    <div class="input-box">
                        <span>${field.label}</span>
                        <input type="file" name="${field.name}" class="box" accept="${field.accept || '*'}">
                        ${preview}
                        ${fieldError}
                    </div>`;

                // 6. Default Standard Inputs (text, number, date, time, password, email, color, range, hidden, etc.)
                } else {
                    fieldHTML = `
                    <div class="input-box" ${field.type === 'hidden' ? 'style="display:none;"' : ''}>
                        <span>${field.label || ''}</span>
                        <input type="${field.type || 'text'}" 
                            name="${field.name}" 
                            value="${value}" 
                            class="box" 
                            placeholder="${field.placeholder || ''}" 
                            ${field.min !== undefined ? `min="${field.min}"` : ''} 
                            ${field.max !== undefined ? `max="${field.max}"` : ''} 
                            ${field.step !== undefined ? `step="${field.step}"` : ''}>
                        ${fieldError}
                    </div>`;
                }

                inputsContainer.innerHTML += fieldHTML;
            });
        }
    }

    if (config.globalError && globalErrorMsg) {
        globalErrorMsg.innerText = config.globalError;
        globalErrorMsg.style.display = 'block';
    }

    container.style.display = 'flex';
}

function closeDynamicModal() {
    document.getElementById('dynamic_modal_container').style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    const dynamicForm = document.getElementById('dynamic_form');

    if (dynamicForm) {
        dynamicForm.addEventListener('submit', function (e) {
            e.preventDefault(); 

            let formData = new FormData(this);
            let globalErrorMsg = document.getElementById('modal_error_msg');

            
            if (globalErrorMsg) {
                globalErrorMsg.style.display = 'none';
                globalErrorMsg.innerText = '';
            }
            document.querySelectorAll('#modal_inputs_container small').forEach(el => el.remove());

            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === true) {
                    
                    window.location.reload();
                } else {
                    
                    if (data.errors) {
                        for (const [key, message] of Object.entries(data.errors)) {
                            if (key === 'db') {
                                if (globalErrorMsg) {
                                    globalErrorMsg.innerText = message;
                                    globalErrorMsg.style.display = 'block';
                                }
                            } else {
                                let inputField = document.querySelector(`[name="${key}"]`);
                                if (inputField) {
                                    let errorSpan = document.createElement('small');
                                    errorSpan.style.cssText = "color: #e74c3c; font-size: 1.2rem; display: block; margin-top: 0.3rem;";
                                    errorSpan.innerText = message;
                                    inputField.parentNode.appendChild(errorSpan);
                                }
                            }
                        }
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
});


// ====================================================================================================

// =============================================================================================
// Global AJAX Form Handler
// =============================================================================================

document.querySelectorAll('.ajax-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const currentForm = this;
        const submitBtn = currentForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
        const successMsg = currentForm.getAttribute('data-success-msg') || 'Action completed successfully!';

        // 1. Clear previous errors
        currentForm.querySelectorAll('.error-msg').forEach(el => el.innerText = '');

        // 2. Disable Submit Button to prevent double submission
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Sending...';
        }

        const formData = new FormData(currentForm);

        fetch('controllers/process.php', {
            method: 'POST',
            body: formData
        })
        .then(async response => {
            // Check HTTP status code
            if (!response.ok) {
                throw new Error(`Server Error (${response.status})`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === true) {
                alert(successMsg);
                currentForm.reset();
            } else if (data.status === false && data.errors) {
                // Show errors dynamically
                for (const [key, message] of Object.entries(data.errors)) {
                    let errorSpan = currentForm.querySelector(`#error-${key}`) || currentForm.querySelector(`[data-error="${key}"]`);
                    if (errorSpan) {
                        errorSpan.innerText = message;
                    }
                }
            }
        })
        .catch(error => {
            console.error('AJAX Form Error:', error);
            alert('Something went wrong. Please try again later.');
        })
        .finally(() => {
            // 3. Restore Submit Button state
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });
    });
});