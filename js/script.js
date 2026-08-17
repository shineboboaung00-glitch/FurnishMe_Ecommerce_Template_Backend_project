let closer = document.querySelector("#closer");

if (closer) {
    closer.onclick = () => {
        closer.style.display = "none";
        navbar.classList.remove("active");
        cart.classList.remove("active");
    };
}

let navbar = document.querySelector(".navbar");

if (document.querySelector("#menu-btn")) {
    document.querySelector("#menu-btn").onclick = () => {
        closer.style.display = "block";
        navbar.classList.toggle("active");
    };
}

let cart = document.querySelector(".shopping-cart");

if (document.querySelector("#cart-btn")) {
    document.querySelector("#cart-btn").onclick = () => {
        closer.style.display = "block";
        cart.classList.toggle("active");
    };
}

let searchForm = document.querySelector(".header .search-form");

if (document.querySelector("#search-btn")) {
    document.querySelector("#search-btn").onclick = () => {
        searchForm.classList.toggle("active");
    };
}

window.onscroll = () => {
    if (searchForm) searchForm.classList.remove("active");
};

let slides = document.querySelectorAll(".home .slides-container .slide");
let index = 0;

function next() {
    if (!slides.length) return;
    slides[index].classList.remove("active");
    index = (index + 1) % slides.length;
    slides[index].classList.add("active");
}

function prev() {
    if (!slides.length) return;
    slides[index].classList.remove("active");
    index = (index - 1 + slides.length) % slides.length;
    slides[index].classList.add("active");
}

// =============================================================================================

// Helper Function: Get Corract Controller Path (Root vs Admin Folder)
function getProcessUrl() {
    return window.location.pathname.includes("/admin/")
        ? "../controllers/process.php"
        : "controllers/process.php";
}

// =============================================================================================

// Dynamic Modal Function

function openDynamicModal(config) {
    let container = document.getElementById("dynamic_modal_container");
    let form = document.getElementById("dynamic_form");
    let moduleInput = document.getElementById("modal_module");
    let actionInput = document.getElementById("modal_action_type");
    let idInput = document.getElementById("modal_item_id");
    let title = document.getElementById("modal_title");
    let message = document.getElementById("modal_message");
    let inputsContainer = document.getElementById("modal_inputs_container");
    let submitBtn = document.getElementById("modal_submit_btn");
    let globalErrorMsg = document.getElementById("modal_error_msg");

    // Reset Errors
    if (globalErrorMsg) {
        globalErrorMsg.style.display = "none";
        globalErrorMsg.innerText = "";
    }

    moduleInput.value = config.module || "";
    actionInput.value = config.action || "";
    title.innerText = config.title || "Form";
    inputsContainer.innerHTML = "";

    // Old Image Hidden Input
    let oldImageInput = document.getElementById("modal_old_image");
    if (!oldImageInput) {
        oldImageInput = document.createElement("input");
        oldImageInput.type = "hidden";
        oldImageInput.name = "old_image";
        oldImageInput.id = "modal_old_image";
        form.appendChild(oldImageInput);
    }

    
    let existingImage = "";
    if (config.data) {
        existingImage =
            config.data.old_image ||
            config.data.image ||
            config.data.photo ||
            config.data.img ||
            "";
    }
    oldImageInput.value = existingImage;

    if (config.action === "delete") {
        idInput.value = config.data ? config.data.id : "";
        message.innerText =
            config.message || "Are you sure you want to delete this item?";
        message.style.display = "block";
        submitBtn.innerText = "Yes, Delete";
    } else {
        message.style.display = "none";
        idInput.value = config.data ? config.data.id || "" : "";
        submitBtn.innerText =
            config.action === "create" ? "Save" : "Update Changes";

        if (config.fields && config.fields.length > 0) {
            config.fields.forEach((field) => {
                let value =
                    config.data && config.data[field.name] !== undefined
                        ? config.data[field.name]
                        : field.default || "";
                let fieldError = field.error
                    ? `<small style="color: #e74c3c; font-size: 1.2rem; display: block; margin-top: 0.3rem;">${field.error}</small>`
                    : "";
                let fieldHTML = "";

                // 1. Textarea Input
                if (field.type === "textarea") {
                    fieldHTML = `
                    <div class="input-box">
                        <span>${field.label}</span>
                        <textarea name="${field.name}" class="box" placeholder="${field.placeholder || ""}">${value}</textarea>
                        ${fieldError}
                    </div>`;

                    // 2. Select Dropdown Input
                } else if (field.type === "select") {
                    let options = (field.options || [])
                        .map(
                            (opt) =>
                                `<option value="${opt.value}" ${value == opt.value ? "selected" : ""}>${opt.label}</option>`,
                        )
                        .join("");
                    fieldHTML = `
                    <div class="input-box">
                        <span>${field.label}</span>
                        <select name="${field.name}" class="box">${options}</select>
                        ${fieldError}
                    </div>`;

                    // 3. Checkbox Input
                } else if (field.type === "checkbox") {
                    let isChecked = value ? "checked" : "";
                    fieldHTML = `
                    <div class="input-box" style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="${field.name}" value="${field.value || "1"}" ${isChecked} id="field_${field.name}">
                        <label for="field_${field.name}">${field.label}</label>
                        ${fieldError}
                    </div>`;

                    // 4. Radio Input Group
                } else if (field.type === "radio") {
                    let radios = (field.options || [])
                        .map(
                            (opt) =>
                                `<label style="margin-right: 15px;">
                            <input type="radio" name="${field.name}" value="${opt.value}" ${value == opt.value ? "checked" : ""}> ${opt.label}
                        </label>`,
                        )
                        .join("");
                    fieldHTML = `
                    <div class="input-box">
                        <span>${field.label}</span>
                        <div>${radios}</div>
                        ${fieldError}
                    </div>`;

                    // 5. File Input (with Fixed Real-time Live Previrw & Old Image)
                } else if (field.type === "file") {
                    
                    let rawImg = value || existingImage;
                    let oldImgSrc = "";

                    if (rawImg && rawImg.toString().trim() !== "") {
                        if (rawImg.startsWith("http") || rawImg.startsWith("/") || rawImg.startsWith("../")) {
                            oldImgSrc = rawImg;
                        } else if (rawImg.startsWith("uploads/")) {
                            oldImgSrc = window.location.pathname.includes('/admin/') ? '../' + rawImg : rawImg;
                        } else {
                            let uploadPrefix = window.location.pathname.includes('/admin/') ? '../uploads/' : 'uploads/';
                            oldImgSrc = uploadPrefix + rawImg;
                        }
                    }

                    let hasImg = oldImgSrc !== "";

                    fieldHTML = `
                    <div class="input-box">
                        <span>${field.label}</span>
                        <input type="file" name="${field.name}" class="box dynamic-file-input" accept="${field.accept || "image/*"}" data-preview-id="preview_${field.name}">
                        <div style="margin-top: 10px; text-align: center;">
                            <img id="preview_${field.name}" 
                                src="${oldImgSrc}" 
                                alt="Image Preview" 
                                onerror="this.style.display='none';" 
                                style="width: 110px; height: 110px; object-fit: cover; border-radius: 8px; display: ${hasImg ? "inline-block" : "none"}; border: 2px solid #ddd;" />
                        </div>
                        ${fieldError}
                    </div>`;

                    // 6. Default Standard Inputs
                } else {
                    let todayDate = new Date().toISOString().split('T')[0];
                    let maxAttr = field.max ? `max="${field.max}"` : (field.type === 'date' ? `max="${todayDate}"` : '');
                    let minAttr = field.min ? `min="${field.min}"` : '';
                    let stepAttr = field.step ? `step="${field.step}"` : '';

                    fieldHTML = `
                    <div class="input-box" ${field.type === "hidden" ? 'style="display:none;"' : ""}>
                        <span>${field.label || ""}</span>
                        <input type="${field.type || "text"}" 
                            name="${field.name}" 
                            value="${value}" 
                            class="box ${field.type === 'date' ? 'custom-date-picker' : ''}" 
                            placeholder="${field.placeholder || ""}" 
                            ${minAttr} 
                            ${maxAttr} 
                            ${stepAttr}>
                        ${fieldError}
                    </div>`;
                }
                inputsContainer.innerHTML += fieldHTML;
            });
        }
    }

    if (config.globalError && globalErrorMsg) {
        globalErrorMsg.innerText = config.globalError;
        globalErrorMsg.style.display = "block";
    }

    container.style.display = "flex";

    // Flatpickr JS Initialization 
    if (typeof flatpickr !== "undefined") {
        flatpickr("#dynamic_modal_container .custom-date-picker", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            disableMobile: "true"
        });
    }
}

function closeDynamicModal() {
    document.getElementById("dynamic_modal_container").style.display = "none";
}
// ======================================================================================================

// Dynamic Modal Real-time Instant Image Preview Event Listener
document.addEventListener("change", function (e) {
    if (e.target && e.target.classList.contains("dynamic-file-input")) {
        let input = e.target;
        let previewId = input.getAttribute("data-preview-id");
        let previewImg = document.getElementById(previewId);

        if (input.files && input.files[0] && previewImg) {
            let reader = new FileReader();

            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewImg.style.display = "inline-block";
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
});

// Dynamic Modal Form Submit Handler (Added Dynamic Path Detection)
document.addEventListener("DOMContentLoaded", () => {
    const dynamicForm = document.getElementById("dynamic_form");

    if (dynamicForm) {
        dynamicForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const submitBtn = document.getElementById("modal_submit_btn");
            const originalBtnText = submitBtn ? submitBtn.innerText : "Submit";
            let globalErrorMsg = document.getElementById("modal_error_msg");

            // 1. CLEAR PREVIOUS ERRORS
            if (globalErrorMsg) {
                globalErrorMsg.style.display = "none";
                globalErrorMsg.innerText = "";
            }
            document
                .querySelectorAll("#modal_inputs_container small")
                .forEach((el) => el.remove());

            // 2. ENABLE LOADING STATE & DISABLE SUBMIT BUTTON
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerText = "Processing...";
            }

            let formData = new FormData(this);

            fetch(getProcessUrl(), {
                method: "POST",
                body: formData,
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === true) {
                        window.location.reload();
                    } else {
                        if (data.errors) {
                            for (const [key, message] of Object.entries(data.errors)) {
                                if (key === "db") {
                                    if (globalErrorMsg) {
                                        globalErrorMsg.innerText = message;
                                        globalErrorMsg.style.display = "block";
                                    }
                                } else {
                                    let inputField = document.querySelector(`[name="${key}"]`);
                                    if (inputField) {
                                        let errorSpan = document.createElement("small");
                                        errorSpan.style.cssText =
                                            "color: #e74c3c; font-size: 1.2rem; display: block; margin-top: 0.3rem;";
                                        errorSpan.innerText = message;
                                        inputField.parentNode.appendChild(errorSpan);
                                    }
                                }
                            }
                        }
                    }
                })
                .catch((error) => console.error("Error:", error))
                .finally(() => {
                    // 3. DISABLE LOADING STATE & RE-ENABLE SUBMIT BUTTON
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerText = originalBtnText;
                    }
                });
        });
    }
});

// =============================================================================================

// Global AJAX Form Handler (Added Dynamic Path Detection with SweetAlert2)

document.querySelectorAll(".ajax-form").forEach((form) => {
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const currentForm = this;
        const submitBtn = currentForm.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : "";
        const successMsg =
            currentForm.getAttribute("data-success-msg") ||
            "Action completed successfully!";

        // 1. Clear previous errors
        currentForm
            .querySelectorAll(".error-msg")
            .forEach((el) => (el.innerText = ""));

        // 2. Disable Submit Button to prevent double submission
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = "Sending...";
        }

        const formData = new FormData(currentForm);

        fetch(getProcessUrl(), {
            method: "POST",
            body: formData,
        })
            .then(async (response) => {
                if (!response.ok) {
                    throw new Error(`Server Error (${response.status})`);
                }
                return response.json();
            })
            .then((data) => {
                if (data.status === true) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: successMsg,
                        confirmButtonColor: '#bc8a5f',
                        timer: 2000,
                        timerProgressBar: true
                    });
                    currentForm.reset();
                } else if (data.status === false && data.errors) {
                    for (const [key, message] of Object.entries(data.errors)) {
                        let errorSpan =
                            currentForm.querySelector(`#error-${key}`) ||
                            currentForm.querySelector(`#${key}-error`) ||
                            currentForm.querySelector(`[data-error="${key}"]`);
                        if (errorSpan) {
                            errorSpan.innerText = message;
                        }
                    }
                }
            })
            .catch((error) => {
                console.error("AJAX Form Error:", error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again later.',
                    confirmButtonColor: '#31231e'
                });
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
    });
});

// ==================================================================

document.querySelectorAll(".custom-dropdown").forEach((dropdown) => {
    const selected = dropdown.querySelector(".dropdown-selected");
    const options = dropdown.querySelectorAll(".option");
    const hiddenInput = dropdown.querySelector('input[type="hidden"]');

    selected.addEventListener("click", (e) => {
        e.stopPropagation();
        document.querySelectorAll(".custom-dropdown").forEach((d) => {
            if (d !== dropdown) d.classList.remove("open");
        });
        dropdown.classList.toggle("open");
    });

    // Select Option
    options.forEach((option) => {
        option.addEventListener("click", () => {
            options.forEach((o) => o.classList.remove("active"));
            option.classList.add("active");

            selected.querySelector("span").innerText = option.innerText;
            if (hiddenInput) hiddenInput.value = option.dataset.value;

            dropdown.classList.remove("open");
        });
    });
});

document.addEventListener("click", () => {
    document
        .querySelectorAll(".custom-dropdown")
        .forEach((d) => d.classList.remove("open"));
});

// ================================================================================

// message view

function viewMessage(data) {
    document.getElementById('viewSenderName').innerText = data.name || '-';
    document.getElementById('viewSenderEmail').innerText = data.email || '-';
    document.getElementById('viewSenderPhone').innerText = data.phone || '-';
    document.getElementById('viewMessageContent').innerText = data.message || '-';
    document.getElementById('messageViewModal').style.display = 'flex';
}

function closeMessageViewModal() {
    document.getElementById('messageViewModal').style.display = 'none';
}