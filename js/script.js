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

    // General Error Reset
    if (globalErrorMsg) {
        globalErrorMsg.style.display = 'none';
        globalErrorMsg.innerText = '';
    }

    moduleInput.value = config.module;
    actionInput.value = config.action;
    title.innerText = config.title || 'Form';
    inputsContainer.innerHTML = '';

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
        idInput.value = config.data.id;
        message.innerText = config.message || 'Are you sure you want to delete this item?';
        message.style.display = 'block';
        submitBtn.innerText = 'Yes, Delete';
    } else {
        message.style.display = 'none';
        idInput.value = config.data ? (config.data.id || '') : '';
        submitBtn.innerText = config.action === 'create' ? 'Save' : 'Update Changes';

        if (config.fields && config.fields.length > 0) {
            config.fields.forEach(field => {
                let value = (config.data && config.data[field.name] !== undefined) ? config.data[field.name] : '';
                let fieldError = field.error ? `<small style="color: #e74c3c; font-size: 1.2rem; display: block; margin-top: 0.3rem;">${field.error}</small>` : '';
                let fieldHTML = '';

                if (field.type === 'textarea') {
                    fieldHTML = `
                    <div class="input-box">
                        <span>${field.label}</span>
                        <textarea name="${field.name}" class="box" placeholder="${field.placeholder || ''}">${value}</textarea>
                        ${fieldError}
                    </div>`;
                } else if (field.type === 'select') {
                    let options = field.options.map(opt =>
                        `<option value="${opt.value}" ${value == opt.value ? 'selected' : ''}>${opt.label}</option>`
                    ).join('');
                    fieldHTML = `
                    <div class="input-box">
                        <span>${field.label}</span>
                        <select name="${field.name}" class="box">${options}</select>
                        ${fieldError}
                    </div>`;
                } else {
                    fieldHTML = `
                    <div class="input-box">
                        <span>${field.label}</span>
                        <input type="${field.type || 'text'}" name="${field.name}" value="${field.type !== 'file' ? value : ''}" class="box" placeholder="${field.placeholder || ''}">
                        ${fieldError}
                    </div>`;
                }
                inputsContainer.innerHTML += fieldHTML;
            });
        }
    }

    // တကယ်လို့ Database အဆင်မပြေတာမျိုး/အထွေထွေ General Error ရှိပါက ထိပ်ဆုံးမှာ ပြသရန်
    if (config.globalError && globalErrorMsg) {
        globalErrorMsg.innerText = config.globalError;
        globalErrorMsg.style.display = 'block';
    }

    container.style.display = 'flex';
}

function closeDynamicModal() {
    document.getElementById('dynamic_modal_container').style.display = 'none';
}

// Session ထဲမှ Errors များကို Input Field တစ်ခုချင်းစီအလိုက် ခွဲခြားထည့်သွင်းခြင်း
window.addEventListener('DOMContentLoaded', () => {
    if (window.FORM_ERRORS && window.OLD_INPUT) {
        const errors = window.FORM_ERRORS || {};
        const oldInput = window.OLD_INPUT || {};

        if (oldInput.module === 'categories') {
            openDynamicModal({
                module: 'categories',
                action: oldInput.action_type || 'create',
                title: oldInput.action_type === 'update' ? 'Edit Category' : 'Add New Category',
                fields: [
                    { 
                        name: 'name', 
                        label: 'Category Name', 
                        type: 'text', 
                        placeholder: 'Enter category name',
                        error: errors.name || '' // Name field အတွက် Error
                    },
                    { 
                        name: 'image', 
                        label: 'Category Image', 
                        type: 'file',
                        error: errors.image || '' // Image field အတွက် Error
                    }
                ],
                data: {
                    id: oldInput.item_id || '',
                    name: oldInput.name || '',
                    old_image: oldInput.old_image || ''
                },
                globalError: errors.db || '' // General Database error ရှိရင်ပြရန်
            });
        }
    }
});