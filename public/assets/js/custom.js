setTimeout(function () {
    $(".alert").alert("close");
}, 5000);

setTimeout(() => {
    const alert = document.getElementById("success-alert");
    if (alert) {
        alert.classList.add("fade");
        setTimeout(() => alert.remove(), 500);
    }
}, 5000);

// Template delete alert
function deleteTemplate(id) {
    event.preventDefault();
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this booking template!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var template = document.getElementById("deleteTemplate-" + id);
            var templateData = new FormData(template);
            fetch(template.action, {
                method: "DELETE",
                body: templateData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        swal("Booking Template Deleted Successfully.", {
                            icon: "success",
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        swal("There was an errors!", {
                            icon: "error",
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    swal("There was an errors!", {
                        icon: "error",
                    });
                });
        }
    });
}
// User delete Alert
function deleteUser(id) {
    event.preventDefault();
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this user!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var user = document.getElementById("deleteUser-" + id);
            var userData = new FormData(user);
            fetch(user.action, {
                method: "DELETE",
                body: userData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success === true) {
                        swal("User Deleted Successfully.", {
                            icon: "success",
                        }).then(() => {
                            window.location.reload();
                        });
                    } else if (data.success === "login") {
                        swal("That user is currently logged in.", {
                            icon: "error",
                        });
                    } else {
                        swal("There was an error!", {
                            icon: "error",
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    swal("There was an error processing your request.", {
                        icon: "error",
                    });
                });
        }
    });
}

function deleteStaff(id) {
    event.preventDefault();
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this staff!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var user = document.getElementById("deleteStaff-" + id);
            var userData = new FormData(user);
            fetch(user.action, {
                method: "DELETE",
                body: userData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success === true) {
                        swal("Staff Deleted Successfully.", {
                            icon: "success",
                        }).then(() => {
                            window.location.reload();
                        });
                    } else if (data.success === "login") {
                        swal("That user is currently logged in.", {
                            icon: "error",
                        });
                    } else {
                        swal("There was an error!", {
                            icon: "error",
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    swal("There was an error processing your request.", {
                        icon: "error",
                    });
                });
        }
    });
}

// Booking delete alert
function deleteBooking(id) {
    event.preventDefault();
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this booking!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            const form = document.getElementById("deleteBooking-" + id);
            const formData = new FormData(form);

            fetch(form.action, {
                method: "DELETE",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        swal("Booking Deleted Successfully.", {
                            icon: "success",
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        swal("Something went wrong!", {
                            icon: "error",
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    swal("There was an error!", {
                        icon: "error",
                    });
                });
        }
    });
}
function deleteVendor(id, event) {
    event.preventDefault();

    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this vendor!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            const form = document.getElementById("delete-vendor-" + id);
            const formData = new FormData(form);

            fetch(form.action, {
                method: "POST", // Must be POST due to method spoofing
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success === true) {
                        swal("Vendor Deleted Successfully.", {
                            icon: "success",
                        }).then(() => {
                            window.location.reload(); // or use DataTable ajax.reload() if you're using DataTables
                        });
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    swal("There was an error!", {
                        icon: "error",
                    });
                });
        }
    });
}


function deleteRole(id) {
    event.preventDefault();

    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this role!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            const form = document.getElementById("delete-role-" + id);
            const formData = new FormData(form);

            fetch(form.action, {
                method: "POST", // Laravel still expects POST with _method=DELETE
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        swal("Role Deleted Successfully.", {
                            icon: "success",
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        swal(
                            "Oops! Something went wrong while deleting the role.",
                            {
                                icon: "error",
                            }
                        );
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    swal("Error occurred while processing the request!", {
                        icon: "error",
                    });
                });
        }
    });
}


function deleteCategory(id) {
    event.preventDefault();

    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this category!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            const form = document.getElementById("delete-category-" + id);
            const formData = new FormData(form);

            fetch(form.action, {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.success) {
                        swal("Category deleted successfully!", {
                            icon: "success",
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        swal("Failed to delete the category.", {
                            icon: "error",
                        });
                    }
                })
                .catch((err) => {
                    console.error(err);
                    swal("Something went wrong!", { icon: "error" });
                });
        }
    });
}

// Service Delete Confirmation Alert
function deleteService(id, event) {
    event.preventDefault(); // Stop default form behavior

    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this service!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            const form = document.getElementById("delete-service-" + id); // Correct ID
            const formData = new FormData(form);

            fetch(form.action, {
                method: "POST", // Laravel expects POST with _method=DELETE
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        swal(
                            "Deleted!",
                            "Service deleted successfully.",
                            "success"
                        ).then(() => {
                            window.location.reload();
                        });
                    } else {
                        swal(
                            "Failed",
                            "Something went wrong while deleting.",
                            "error"
                        );
                    }
                })
                .catch((error) => {
                    console.error("Delete error:", error);
                    swal(
                        "Error",
                        "There was an error processing your request.",
                        "error"
                    );
                });
        }
    });
}
$(function ($) {
    const templateSelect = $("#bookingTemplates");
    const fbEditor = $("#build-wrap");
    let formBuilderInstance;

    const newfield = [
        { label: "Next Step", attrs: { type: "newsection" }, required: false, icon: '<i class="fa-solid fa-section"></i>' },
        { label: "ShortCode", attrs: { type: "shortcodeblock" }, icon: '<i class="fa fa-code"></i>' },
    ];

    const temp = {
        newsection: function (fieldData) {
            return {
                field: `
                    <div id="${fieldData.name}" class="section">
                        <div class="section-content"></div>
                        <div class="section-navigation">
                            <button class="prev-btn" style="display:none;">Previous</button>
                            <button class="next-btn">Next</button>
                        </div>
                    </div>`,
                onRender: function () {
                    let currentStep = 0;
                    const $section = $(`#${fieldData.name}`);
                    $section.on("click", ".next-btn", () => showSection(++currentStep));
                    $section.on("click", ".prev-btn", () => showSection(--currentStep));

                    function showSection(step) {
                        const allSections = $(".section");
                        allSections.hide().eq(step).show();
                        $section.find(".prev-btn").toggle(step > 0);
                        $section.find(".next-btn").toggle(step < allSections.length - 1);
                    }

                    showSection(currentStep);
                },
            };
        },
        
        shortcodeblock: function (fieldData) {
            return {
                field: `<div class="shortcode-block">
                            <select name="shortcode" class="form-control shortcode-select" data-placeholder="Select a shortcode">
                                <option></option>
                            </select>
                        </div>`,
                onRender: function () {
                    const $select = $(".shortcode-select:last");
                    $.ajax({
                        url: '/admin/shortcodes/list',
                        type: 'GET',
                        dataType: 'json',
                        success: (shortcodes) => {
                            $select.empty().append("<option></option>");
                            shortcodes.forEach(code => {
                                const isSelected = fieldData.value === `[${code}]`;
                                $select.append(new Option(`[${code}]`, `[${code}]`, isSelected, isSelected));
                            });
                            $select.select2({ placeholder: "Select a shortcode", allowClear: true, width: '100%' });
                        },
                        error: () => console.error("Failed to load shortcodes."),
                    });
                },
                onSave: function (evt, field) {
                    const input = $(field).find('select[name="shortcode"]');
                    fieldData.value = input.val();
                },
            };
        }
    };

    const formBuilder = $(fbEditor).formBuilder({
        fields: newfield,
        templates: temp,
        controlPosition: "left",
        disableFields: ["autocomplete", "button"],
    });

    formBuilder.promise.then(function (fb) {
        formBuilderInstance = fb;
        if ($("#bookingaddpage").length === 0 && templateSelect.length > 0) {
            const selectedValue = JSON.parse(templateSelect.val());
            selectedValue.forEach(item => {
                Object.keys(item).forEach(key => {
                    if (item[key] === "false") item[key] = false;
                });
            });
            fb.actions.setData(selectedValue);
        }
    });

    $(document).on("click", ".save-template", function (e) {
        e.preventDefault();
        const inputElement = $("#bookingTemplatesname");
        const inputValue = inputElement.val().trim();
        const errorMessageElement = $("#bookingTemplatesname-error");

        if (errorMessageElement.length) errorMessageElement.remove();

        if (!inputValue) {
            $("<span id='bookingTemplatesname-error' style='color: red;'>The Template name cannot be empty.</span>")
                .appendTo(inputElement.parent());
            inputElement.focus();
            return;
        }

        if (!formBuilderInstance) return console.error("Form Builder is not initialized yet.");

        const data = formBuilderInstance.actions.getData();
        const templateid = $("#templateid").val() || "";
        const csrfToken = $('meta[name="csrf-token"]').attr("content");

        $.ajax({
            url: "/admin/template/save",
            method: "POST",
            data: { data, templatename: inputValue, templateid, _token: csrfToken },
            success: () => window.location.href = `${window.location.origin}/admin/template`,
            error: (xhr) => console.error(xhr.responseText),
        });
    });

    $(window).on("load", function () {
        $("#bookingTemplates").click();
    });

    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
        document.cookie = `${cname}=${cvalue};expires=${d.toGMTString()};path=/`;
    }

    function getCookie(cname) {
        const name = `${cname}=`;
        const decodedCookie = decodeURIComponent(document.cookie);
        const ca = decodedCookie.split(";");
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(name) === 0) return c.substring(name.length);
        }
        return "";
    }

    function checkCookie() {
        let ticks = getCookie("modelopen");
        if (ticks !== "") {
            ticks++;
            setCookie("modelopen", ticks, 1);
            if (["0", "1", "2"].includes(ticks)) $("#exampleModalCenter").modal();
        } else {
            $("#exampleModalCenter").modal();
            setCookie("modelopen", "1", 1);
        }
    }

    $(document).on("show.bs.modal", "#exampleModal", function (event) {
        const button = $(event.relatedTarget);
        const recipient = button.data("whatever");
        const modal = $(this);
        modal.find(".modal-title").text(`New message to ${recipient}`);
        modal.find(".modal-body input").val(recipient);
    });

    $(document).on("click", "#copyTemplateBtn", function() {
        const copytemplateid = $("#Copy_template_id").val();
        if (copytemplateid) gettemplatedatafield(copytemplateid);
    });

    function gettemplatedatafield(copytemplateid) {
        $.ajax({
            url: '/admin/get/copytemplateid', 
            type: 'GET',
            data: { templateid: copytemplateid },
            dataType: 'json',
            success: (response) => {
                if (response?.length > 0) {
                    const templateFields = JSON.parse(response[0]);
                    addFieldsToBuilder(templateFields);
                    $('#copyTemplateModal').modal('hide');
                }
            },
            error: (xhr) => console.error('Error fetching template data:', xhr.responseText),
        });
    }

    function addFieldsToBuilder(templateFields) {
        if (fbEditor.find('.form-builder').length) fbEditor.empty();

        const formBuilder = $(fbEditor).formBuilder({
            fields: newfield,
            templates: temp,
            controlPosition: "left",
            disableFields: ["autocomplete", "button"],
        });

        formBuilder.promise.then(function (fb) {
            formBuilderInstance = fb;

            templateFields.forEach(fieldData => {
                Object.keys(fieldData).forEach(key => {
                    if (fieldData[key] === "false") fieldData[key] = false;
                });
                formBuilderInstance.actions.addField(fieldData);
            });
        });
    }

});


// Add Booking
document.addEventListener("DOMContentLoaded", function () {
    const bookingTemplateModal = document.getElementById(
        "bookingTemplateModal"
    );
    if (bookingTemplateModal) {
        $("#bookingTemplateModal")
            .modal({ backdrop: "static", keyboard: false })
            .modal("show");
    }

    const selectTemplateBtn = document.getElementById("select-template-btn");
    if (selectTemplateBtn) {
        selectTemplateBtn.addEventListener("click", function () {
            const bookingTemplateList = document.getElementById(
                "booking-template-list"
            );
            const templateError = document.getElementById(
                "booking-template-error"
            );
            const bookingtemplateid = document.getElementById(
                "booking_template_id"
            );
            const bookingDataInput = document.getElementById("booking_data");
            const bookingTemplate = document.getElementById("booking-template");

            if (bookingTemplateList && bookingTemplateList.value === "") {
                if (templateError) templateError.style.display = "block";
            } else {
                const selectedOption =
                    bookingTemplateList.options[
                    bookingTemplateList.selectedIndex
                    ];
                if (templateError) templateError.style.display = "none";
                if (bookingtemplateid)
                    bookingtemplateid.value = selectedOption.value;
                if (bookingDataInput)
                    bookingDataInput.value =
                        selectedOption.getAttribute("data-booking_data");
                if (bookingTemplateModal)
                    $("#bookingTemplateModal").modal("hide");
                if (bookingTemplate) bookingTemplate.style.display = "block";
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const loadTemplateBtn = document.getElementById("loadTemplateBtn");

    if (loadTemplateBtn) {
        loadTemplateBtn.addEventListener("click", function () {
            const templateSelect = document.getElementById(
                "bookingTemplateselect"
            );
            const selectedOption =
                templateSelect?.options[templateSelect.selectedIndex];

            if (!selectedOption || !selectedOption.dataset.id) {
                alert("Please select a Booking template.");
                return;
            }

            const bookingtemplateid = selectedOption.dataset.id;

            fetch(`/admin/booking/load-template-html/${bookingtemplateid}`)
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Template fetch failed.");
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.success) {
                        const dynamicTemplate = document.getElementById(
                            "dynamictemplateFields"
                        );
                        dynamicTemplate.innerHTML = data.html;

                        document.getElementById("bookingTemplateId").value =
                            bookingtemplateid;

                        const form = document.querySelector("form");
                        if (form) {
                            form.addEventListener("submit", function (event) {
                                let templateData = {};
                                const inputs =
                                    form.querySelectorAll("[name^='dynamic']");

                                inputs.forEach((input) => {
                                    const nameMatch =
                                        input.name.match(/dynamic\[(.+?)\]/);
                                    if (nameMatch) {
                                        const fieldName = nameMatch[1];

                                        if (input.type === "checkbox") {
                                            if (!templateData[fieldName]) {
                                                templateData[fieldName] = [];
                                            }
                                            if (input.checked) {
                                                templateData[fieldName].push(
                                                    input.value
                                                );
                                            }
                                        } else if (input.type === "radio") {
                                            if (input.checked) {
                                                templateData[fieldName] =
                                                    input.value;
                                            }
                                        } else {
                                            templateData[fieldName] =
                                                input.value;
                                        }
                                    }
                                });

                                document.getElementById("bookingData").value =
                                    JSON.stringify(templateData);
                            });
                        }

                        $("#bookingTemplateModal").modal("hide");
                            const services_short_code_get_staftf = document.querySelector('.get_service_staff');  
                        services_short_code_get_staftf.addEventListener('change', function() {
                            var customValue = services_short_code_get_staftf; 
                            get_services_staff(customValue);
                        });
                      
                    } else {
                        alert(data.message || "Failed to load template.");
                    }
                })
                .catch((error) => {
                    console.error("Error loading template:", error);
                    alert("An error occurred while loading the template.");
                });
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const selectAll = document.getElementById("select-all-permissions");
    if (!selectAll) return; // ⛔ Exit if element not found

    function updateSelectAllCheckbox() {
        const all = document.querySelectorAll(".permission-checkbox");
        const checked = Array.from(all).filter((cb) => cb.checked);
        selectAll.checked = checked.length === all.length;
        selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
    }

    // Group checkbox toggle
    document.querySelectorAll(".group-checkbox").forEach((groupCheckbox) => {
        groupCheckbox.addEventListener("change", function () {
            const groupKey = this.dataset.group;
            const checkboxes = document.querySelectorAll(`.permission-checkbox.group-${groupKey}`);
            checkboxes.forEach((cb) => (cb.checked = this.checked));
            updateSelectAllCheckbox();
        });
    });

    // Select all permissions
    selectAll.addEventListener("change", function () {
        const checked = this.checked;
        document.querySelectorAll(".permission-checkbox").forEach((cb) => (cb.checked = checked));
        document.querySelectorAll(".group-checkbox").forEach((cb) => (cb.checked = checked));
        updateSelectAllCheckbox();
    });

    // Single permission checkbox change
    document.querySelectorAll(".permission-checkbox").forEach((cb) => {
        cb.addEventListener("change", function () {
            const groupClass = Array.from(cb.classList).find((cls) => cls.startsWith("group-"));
            if (!groupClass) return;
            const groupKey = groupClass.replace("group-", "");
            const groupCB = document.querySelector(`.group-checkbox[data-group="${groupKey}"]`);
            const perms = document.querySelectorAll(`.permission-checkbox.group-${groupKey}`);
            const anyChecked = Array.from(perms).some((p) => p.checked);
            groupCB.checked = anyChecked;
            updateSelectAllCheckbox();
        });
    });

    // Toggle icon to collapse/expand group
    document.querySelectorAll(".toggle-icon").forEach((icon) => {
        icon.addEventListener("click", function () {
            const groupKey = this.dataset.group;
            const rows = document.querySelectorAll(`.group-perms-${groupKey}`);
            const isVisible = Array.from(rows).some((r) => r.style.display !== "none");

            rows.forEach((r) => (r.style.display = isVisible ? "none" : "table-row"));
            this.classList.toggle("icon-chevron-right", isVisible);
            this.classList.toggle("icon-chevron-down", !isVisible);
        });
    });

    // Initial state setup
    document.querySelectorAll(".group-checkbox").forEach((groupCheckbox) => {
        const groupKey = groupCheckbox.dataset.group;
        const perms = document.querySelectorAll(`.permission-checkbox.group-${groupKey}`);
        const anyChecked = Array.from(perms).some((p) => p.checked);
        groupCheckbox.checked = anyChecked;

        const icon = document.querySelector(`.toggle-icon[data-group="${groupKey}"]`);
        if (icon) {
            icon.classList.remove("icon-chevron-down");
            icon.classList.add("icon-chevron-right");
        }

        document.querySelectorAll(`.group-perms-${groupKey}`).forEach((r) => (r.style.display = "none"));
    });

    updateSelectAllCheckbox();
});


document.addEventListener("DOMContentLoaded", function () {
    // Toggle input visibility
    function toggleOtherInput(inputField, isVisible) {
        if (inputField) {
            inputField.style.display = isVisible ? "block" : "none";
        }
    }

    // Initialize inputs on load
    function initializeOtherInputs() {
        document
            .querySelectorAll('input[name^="dynamic["][type="text"]')
            .forEach(function (input) {
                const nameMatch = input.name.match(
                    /^dynamic\[(.+?)_other\](\[\])?$/
                );
                if (!nameMatch) return;

                const baseName = nameMatch[1];
                const isCheckbox = !!nameMatch[2];

                if (isCheckbox) {
                    const checkboxOther = document.querySelector(
                        `input[type="checkbox"][name="dynamic[${baseName}][]"][value="__other__"]`
                    );
                    if (
                        checkboxOther &&
                        checkboxOther.value === "__other__" &&
                        !checkboxOther.checked
                    ) {
                        toggleOtherInput(input, false);
                    }
                } else {
                    const radioOther = document.querySelector(
                        `input[type="radio"][name="dynamic[${baseName}]"][value="__other__"]`
                    );
                    if (
                        radioOther &&
                        radioOther.value === "__other__" &&
                        !radioOther.checked
                    ) {
                        toggleOtherInput(input, false);
                    }
                }
            });
    }

    // Radio change event
    function handleRadioChange(radio) {
        const nameMatch = radio.name.match(/^dynamic\[(.+?)\]$/);
        if (!nameMatch) return;

        const baseName = nameMatch[1];
        const input = document.querySelector(
            `input[name="dynamic[${baseName}_other]"]`
        );

        if (radio.value === "__other__") {
            toggleOtherInput(input, radio.checked);
        } else {
            toggleOtherInput(input, false);
        }
    }

    // Checkbox change event
    function handleCheckboxChange(checkbox) {
        const nameMatch = checkbox.name.match(/^dynamic\[(.+?)\]\[\]$/);
        if (!nameMatch) return;

        const baseName = nameMatch[1];
        const input = document.querySelector(
            `input[name="dynamic[${baseName}_other][]"]`
        );
        toggleOtherInput(input, checkbox.checked);
    }

    // Set listeners
    function setEventListeners() {
        document
            .querySelectorAll('input[type="radio"]')
            .forEach(function (radio) {
                radio.addEventListener("change", function () {
                    handleRadioChange(radio);
                });
            });

        document
            .querySelectorAll('input[type="checkbox"]')
            .forEach(function (checkbox) {
                if (checkbox.value === "__other__") {
                    checkbox.addEventListener("change", function () {
                        handleCheckboxChange(checkbox);
                    });
                }
            });
    }

    // Run on load
    initializeOtherInputs();
    setEventListeners();
});
document.addEventListener("DOMContentLoaded", function () {
    const mobileToggle = document.getElementById("mobile-collapse");
    const switchBackText = document.getElementById("switchBackText");
    const navbarSwitchButton = document.getElementsByClassName("navbar-switch-button")[0];
    const navbar = document.querySelector(".pcoded-navbar");

    // Check if all elements are found before proceeding
    if (!mobileToggle || !switchBackText || !navbarSwitchButton || !navbar) {
        console.warn("One or more navbar elements are missing.");
        return;
    }

    function hideOrShowSwitchBackText() {
        if (navbar.classList.contains("navbar-collapsed")) {
            switchBackText.style.display = "none";
            navbarSwitchButton.classList.add("justify-content-center");
        } else {
            switchBackText.style.display = "inline";
            navbarSwitchButton.classList.remove("justify-content-center");
        }
    }

    // Initial delay to allow class state to settle
    setTimeout(hideOrShowSwitchBackText, 100);

    // Toggle collapse state
    mobileToggle.addEventListener("click", function () {
        setTimeout(hideOrShowSwitchBackText, 100);
    });

    // Show text on hover when collapsed
    navbar.addEventListener("mouseenter", function () {
        if (navbar.classList.contains("navbar-collapsed")) {
            switchBackText.style.display = "inline";
            navbarSwitchButton.classList.remove("justify-content-center");
        }
    });

    // Hide text when mouse leaves the navbar
    navbar.addEventListener("mouseleave", function () {
        if (navbar.classList.contains("navbar-collapsed")) {
            switchBackText.style.display = "none";
            navbarSwitchButton.classList.add("justify-content-center");
        }
    });
});


$(function () {
    $("#payment_mode").on("change", function () {
        const mode = $(this).val();
        $(".stripe-options").toggleClass("d-none", mode !== "stripe");

        if (mode !== "stripe") {
            $(".stripe-credentials").addClass("d-none");
        }
    });

    $('input[name="payment_account"]').on("change", function () {
        $(".stripe-credentials").toggleClass(
            "d-none",
            $(this).val() !== "custom"
        );
    });

    $("#payment__is_live").on("change", function () {
        if (this.checked) {
            $(".stripe-test").addClass("d-none");
            $(".stripe-live").removeClass("d-none");
        } else {
            $(".stripe-live").addClass("d-none");
            $(".stripe-test").removeClass("d-none");
        }
    });
});

function updateCancellingValueOptions(unit, selectedValue = null) {
    let $select = $("#cancelling_value");
    $select.empty();

    if (unit === "hours") {
        for (let i = 1; i <= 24; i++) {
            $select.append(
                `<option value="${i}" ${parseInt(selectedValue) === i ? "selected" : ""
                }>${i}</option>`
            );
        }
    } else if (unit === "days") {
        for (let i = 1; i <= 365; i++) {
            $select.append(
                `<option value="${i}" ${parseInt(selectedValue) === i ? "selected" : ""
                }>${i}</option>`
            );
        }
    }
}

$(document).ready(function () {
    const $unitSelect = $("#cancelling_unit");
    const selectedValue = $("#cancel_value").val();
    updateCancellingValueOptions($unitSelect.val(), selectedValue);
    $unitSelect.on("change", function () {
        updateCancellingValueOptions($(this).val());
    });
});

$(document).ready(function () {
    function toggleStripeFields() {
        const mode = $("#payment_mode").val();
        if (mode === "stripe") {
            $(".stripe-options").removeClass("d-none");
        } else {
            $(".stripe-options").addClass("d-none");
        }
    }

    function toggleStripeCredentials() {
        if ($('input[name="payment_account"]:checked').val() === "custom") {
            $(".stripe-credentials").removeClass("d-none");
        } else {
            $(".stripe-credentials").addClass("d-none");
        }
    }

    function toggleLiveMode() {
        if ($("#payment__is_live").is(":checked")) {
            $(".stripe-test").addClass("d-none");
            $(".stripe-live").removeClass("d-none");
        } else {
            $(".stripe-test").removeClass("d-none");
            $(".stripe-live").addClass("d-none");
        }
    }

    $("#payment_mode").on("change", toggleStripeFields);
    $('input[name="payment_account"]').on("change", toggleStripeCredentials);
    $("#payment__is_live").on("change", toggleLiveMode);

    toggleStripeFields();
    toggleStripeCredentials();
    toggleLiveMode();
});

/* --------------------------  Start Quill Editor  ------------------------ */
document.addEventListener("DOMContentLoaded", function () {
    const quill = new Quill("#quill-editor", {
        theme: "snow",
    });

    const hiddenTextarea = document.querySelector("#description");
    if (hiddenTextarea) {
        if (hiddenTextarea.value) {
            quill.clipboard.dangerouslyPasteHTML(hiddenTextarea.value);
        }

        document.querySelector("form").addEventListener("submit", function () {
            hiddenTextarea.value = quill.root.innerHTML;
        });
    }
});
/* --------------------------  End Quill Editor  ------------------------ */

/* --------------------------  Start Gallery Logics  ------------------------ */

document.addEventListener("DOMContentLoaded", function () {
    let selectedFiles = new DataTransfer();
    const galleryInput = document.getElementById("galleryInput");
    const previewContainer = document.getElementById("galleryPreviewContainer");

    if (galleryInput && previewContainer) {
        // Handle new uploads
        galleryInput.addEventListener("change", function (e) {
            Array.from(e.target.files).forEach((file) => {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const col = document.createElement("div");
                    col.className = "col-md-3 mb-3 position-relative new-upload";
                    col.dataset.filename = file.name;
                    col.innerHTML = `
                        <div class="card shadow-sm">
                            <img src="${event.target.result}" class="card-img-top img-thumbnail" alt="Preview">
                            <button type="button" class="btn btn-sm btn-dark text-white position-absolute top-0 end-0 m-1 rounded-pill delete-new-upload" title="Remove image">&times;</button>
                        </div>
                    `;
                    previewContainer.appendChild(col);
                };
                reader.readAsDataURL(file);
                selectedFiles.items.add(file);
            });
            galleryInput.files = selectedFiles.files;
        });

        // Handle deletions (existing and new)
        previewContainer.addEventListener("click", function (e) {
            // Delete existing image
            if (e.target.classList.contains("delete-existing-image")) {
                const parent = e.target.closest(".existing-image");
                const imagePath = parent.dataset.image;
                parent.style.display = "none";

                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "delete_gallery[]";
                input.value = imagePath;
                document.querySelector("form").appendChild(input);
            }

            // Delete newly uploaded image
            if (e.target.classList.contains("delete-new-upload")) {
                const upload = e.target.closest(".new-upload");
                const fileName = upload.dataset.filename;

                const dt = new DataTransfer();
                Array.from(selectedFiles.files).forEach((file) => {
                    if (file.name !== fileName) {
                        dt.items.add(file);
                    }
                });
                selectedFiles = dt;
                galleryInput.files = selectedFiles.files;
                upload.remove();
            }
        });
    } else {
        console.warn("galleryInput or galleryPreviewContainer not found in DOM.");
    }
});
/* --------------------------  End Gallery Logics  ------------------------ */


/* --------------------------  Start Select2 Dropdown Classes  ------------------------ */
$(function () {
    const select2Fields = [
        { selector: ".durartion", placeholder: "Select Duration" },
        { selector: ".category", placeholder: "Select Category" },
        { selector: ".select2-mash", placeholder: "Select an option" },
        { selector: ".currency", placeholder: "Select Currency" },
        { selector: ".select-user" },
        { selector: ".select-users" },
        { selector: ".select-service" },
        { selector: ".select-status" }
        
    ];

    select2Fields.forEach((field) => {
        $(field.selector).select2({
            theme: "bootstrap",
            placeholder: field.placeholder || "Select",
            width: "100%",
        });
    });
});
/* --------------------------  End Select2 Dropdown Classes  ------------------------ */

/* --------------------------  Start Work Hours Function  ------------------------ */
$(document).ready(function () {

    function applyEndTimeRestrictions($startSelect) {
        const selectedStart = $startSelect.val();
        const parent = $startSelect.closest('.d-flex');
        const $endSelect = parent.find('select[name$="[end]"]');

        if (!$endSelect.length) return;

        const [startHour, startMinute] = selectedStart.split(':').map(Number);
        const startMinutes = startHour * 60 + startMinute;

        $endSelect.find('option').each(function () {
            const [optHour, optMinute] = this.value.split(':').map(Number);
            const optMinutes = optHour * 60 + optMinute;
            this.disabled = optMinutes <= startMinutes;
        });

        if ($endSelect.find('option:selected').prop('disabled')) {
            $endSelect.val('00:00').trigger('change');
        }
    }

    $('select[name^="working_days"][name$="[start]"]').on('change', function () {
        applyEndTimeRestrictions($(this));
    });

    $('select[name^="working_days"][name$="[start]"]').each(function () {
        applyEndTimeRestrictions($(this));
    });

    $('#workingHoursAccordion')
        .on('show.bs.collapse', function (e) {
            const targetId = $(e.target).attr('id');
            $(this).find(`[data-target="#${targetId}"] i`)
                .removeClass('icon-chevron-down')
                .addClass('icon-chevron-up');
        })
        .on('hide.bs.collapse', function (e) {
            const targetId = $(e.target).attr('id');
            $(this).find(`[data-target="#${targetId}"] i`)
                .removeClass('icon-chevron-up')
                .addClass('icon-chevron-down');
        });
});


/* ------------------------ Start Services Thumbnail Function  ---------------------------- */


$(document).ready(function () {
    function handleFileInput(fileInputId, previewContainerId, previewImageId, removeButtonId, removeFlagId = null) {
        const fileInput = $(fileInputId);
        const previewContainer = $(previewContainerId);
        const previewImage = $(previewImageId);
        const removeButton = $(removeButtonId);
        const fileLabel = fileInput.closest('.custom-file').find('.custom-file-label');
        const removeFlag = removeFlagId ? $(removeFlagId) : null;

        // Handle change
        fileInput.on('change', function () {
            const file = this.files[0];
            if (!file) return;

            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                alert('Only JPG, PNG, and GIF files are allowed.');
                fileInput.val('');
                fileLabel.text('Choose file...');
                previewContainer.addClass('d-none');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.attr('src', e.target.result);
                previewContainer.removeClass('d-none');
                fileLabel.text(file.name);
                if (removeFlag) removeFlag.val(0);
            };
            reader.readAsDataURL(file);
        });

        // Handle remove
        removeButton.on('click', function () {
            fileInput.val('');
            fileLabel.text('Choose file...');
            previewImage.attr('src', '');
            previewContainer.addClass('d-none');
            if (removeFlag) removeFlag.val(1);
        });
    }

    // Init both file uploaders
    handleFileInput('#validatedCustomFile', '#image-preview-container', '#image-preview', '#remove-preview');
    handleFileInput('#thumbnailInput', '#edit-thumbnail-preview-container', '#edit-thumbnail-preview', '#remove-preview', '#removeThumbnailFlag');
    handleFileInput('#thumbnail', '#image-preview-container, #preview-container', '#image-preview, #preview-image', '#remove-preview', '#remove_existing_thumbnail');
    handleFileInput('#addAvatarInput', '#add-avatar-preview-container', '#add-avatar-preview', '#remove-add-avatar-preview', '#removeAddAvatarFlag');
    handleFileInput('#avatarInput', '#avatar-preview-container', '#avatar-preview', '#remove-avatar-preview', '#removeAvatarFlag');
});
/* ------------------------ End Services Thumbnail Function  ---------------------------- */

const services_short_code_get_staff = document.querySelector('.get_service_staff');  
    services_short_code_get_staff.addEventListener('change', function() {
        var customValue = services_short_code_get_staff; 
        get_services_staff(customValue);

    });
var selectedStaff = document.querySelector('.selected_vendor');
if(selectedStaff){
    const services_selected = document.querySelector('.get_service_staff'); 
    get_services_staff(services_selected);
}
function get_services_staff(selectedvalue){
     var serviceId = selectedvalue.value;
     var selectedStaff = document.querySelector('.selected_vendor').value;    
       $.ajax({
        url: '/get/services/staff', 
        type: 'GET',
        data: {
            service_id: serviceId  
        },
        dataType: 'json',
         success: function(response) {
            var select_service_staff = document.querySelector('.select_service_vendor');
            var staffSelect = document.querySelector('.service_vendor_form');
            var bookingcalendar = document.querySelector('.calendar-wrap');

            staffSelect.innerHTML = '';
            var defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '---Select Vendor---';
            staffSelect.appendChild(defaultOption);
            if (response && response.length > 0) {
                staffSelect.disabled = false;
                select_service_staff.classList.remove('d-none');  

                // Add each staff member to the dropdown
                response.forEach(function(staff) {
                    var option = document.createElement('option');
                    option.value = staff.id;
                    option.textContent = staff.name;
                    staffSelect.appendChild(option);
                });
                 if (selectedStaff) {
                    var options = staffSelect.querySelectorAll('option');
                    options.forEach(function(option) {
                        if (option.value == selectedStaff) {
                            option.selected = true;
                            
                        }
                    });
                }
            } else {
                staffSelect.disabled = true;
                select_service_staff.classList.add('d-none');
                bookingcalendar.classList.add('d-none');
                var noStaffOption = document.createElement('option');
                noStaffOption.value = '';
                noStaffOption.textContent = 'No Vendor available';
                staffSelect.appendChild(noStaffOption);
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX error:", status, error);
        }
    });
      function resetCalendar() {
            const calendar = document.getElementById('calendar');
            if (calendar) {
                const days = calendar.querySelectorAll('td');
                days.forEach(dayCell => {
                    dayCell.innerHTML = '';
                    dayCell.classList.remove('available', 'disabled', 'selected');
                    dayCell.style.backgroundColor = '';
                    dayCell.style.pointerEvents = '';
                });
            }
        }
        const today = new Date();
    let year = today.getFullYear();
    let month = today.getMonth();
    let selectedDate = null;
    const availableDates = typeof availabledatesArray !== 'undefined' ? availabledatesArray : [];
      $('.service_vendor_form').on('change', function () {
            let selectedValue = $(this).val();
            if (selectedValue === "") {
                selectedValue = 0;
            } else {
                selectedValue = selectedValue;
            }
            $.ajax({
                url: '/get/vendor/get_booking_calender',
                type: 'GET',
                data: { vendor_id: selectedValue },
                dataType: 'json',
                success: function (response) {
                    $('.availibility').addClass('d-none');
                    $('.calendar-wrap').removeClass('d-none');
                    if (response.success === true && response.data && response.data[0]) {
                        const workondayoff = response.data;
                        const workingDays = response.data.map(item => item.Working_day);
                        resetCalendar();
                        new Calendar(workingDays, workondayoff);
                    } else {
                        const workondayoff = 0;
                        const workingDays = 0;
                        resetCalendar();
                        new Calendar(workingDays, workondayoff);
                    }
                },
            });
        });
        class Calendar {
            constructor(workingDays, workondayoff) {
                this.workingDays = workingDays;
                this.workOnoff = workondayoff;
                this.draw();
                this.addNavigationListeners();
                this.addDayClickListener();
            }

            // Draw the calendar days
            draw() {
                this.drawDays();
            }

            drawDays() {
                const monthNames = [
                    "January", "February", "March", "April", "May", "June",
                    "July", "August", "September", "October", "November", "December"
                ];

                const startDay = new Date(year, month, 1).getDay();
                const totalDays = new Date(year, month + 1, 0).getDate();

                const monthNameElem = document.getElementById("month-name");
                if (monthNameElem) {
                    monthNameElem.textContent = `${monthNames[month]} ${year}`;
                }

                const days = document.querySelectorAll('#calendar td');
                days.forEach((dayCell) => {
                    dayCell.innerHTML = '';
                    dayCell.classList.remove('available', 'disabled', 'selected');
                    dayCell.style.backgroundColor = '';
                    dayCell.style.pointerEvents = '';
                });

                const currentDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

                let dayIndex = 0;
                for (let i = 0; i < days.length; i++) {
                    const dayCell = days[i];
                    if (i >= startDay && dayIndex < totalDays) {
                        const dayNum = ++dayIndex;
                        const fullDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(dayNum).padStart(2, "0")}`;
                        const dayDate = new Date(year, month, dayNum);
                        dayCell.innerHTML = dayNum;


                        // Collect valid working days
                        const validWorkingDays = [];
                        if (this.workingDays) {
                            this.workingDays.forEach(week => {
                                Object.entries(week).forEach(([day, time]) => {
                                    if (time.start) {
                                        const timePart = time.start.split("T")[1].split(".")[0];
                                        if (timePart !== "00:00:00") {
                                            validWorkingDays.push(day.toLowerCase());
                                        }
                                    }
                                });
                            });
                        }
                        // Collect valid working days and check if working time is not "00:00:00"
                        const dayOffData = [];
                        if (this.workOnoff) {
                            this.workOnoff.forEach(items => {
                                const workingDay = items.Working_day;
                                const dayoffDates = items.Dayoff;
                                Object.entries(workingDay).forEach(([day, time]) => {
                                    if (time.start) {
                                        const timePart = time.start.split("T")[1].split(".")[0];
                                        if (timePart !== "00:00:00") {
                                            dayoffDates.forEach(dayoff => {
                                                const dayOfdates = dayoff.flat();
                                                dayOffData.push(dayOfdates);
                                            });
                                        }
                                    }
                                });
                            });
                        }

                        // **Check if the selected day is a day off**
                        const isDayOff = dayOffData.some(dayoff => {

                            let leaveapproved;
                            let ofdates = [];
                            dayoff.forEach(dayoof => {
                                ofdates.push(dayoof.date);
                            });
                            return ofdates.some(date => {
                                leaveapproved = true;
                                const dateObj = new Date(date);

                                if (dateObj.toDateString() === dayDate.toDateString()) {
                                    const dayNameFull = dateObj.toLocaleString('en-us', { weekday: 'long' }).toLowerCase();
                                    if (this.workOnoff) {
                                        this.workOnoff.forEach(items => {
                                            const workingDay = items.Working_day;
                                            const dayoffDates = items.Dayoff;
                                            if (dayoffDates) {
                                                const minDayoffArray = this.workOnoff.reduce((minArr, currentArr) => {
                                                    const currentDayoffDatesCount = currentArr.Dayoff.flat().length;
                                                    const minDayoffDatesCount = minArr.Dayoff.flat().length;
                                                    return currentDayoffDatesCount < minDayoffDatesCount ? currentArr : minArr;
                                                });
                                                if (minDayoffArray) {
                                                    const dayof = minDayoffArray.Dayoff.flat();
                                                    if (dayof && dayof.length > 0) {
                                                        leaveapproved = false;
                                                        const sortedDayoff = dayof.sort((a, b) => new Date(a.date) - new Date(b.date));
                                                        const startDate = sortedDayoff[0].date;
                                                        const endDate = sortedDayoff[sortedDayoff.length - 1].date;
                                                        const formattedDate = new Date(dateObj).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                                                        if (formattedDate >= startDate && formattedDate <= endDate) {
                                                            leaveapproved = true;
                                                        }
                                                    }
                                                }
                                            } else {
                                                leaveapproved = false;
                                            }


                                            Object.entries(workingDay).forEach(([day, time]) => {
                                                if (time.start) {
                                                    const timePart = time.start.split("T")[1].split(".")[0];
                                                    if (timePart !== "00:00:00") {
                                                        dayoffDates.forEach(dayoff => {
                                                            const dayOfdates = dayoff.flat();
                                                            const excludedDateStrings = dayOfdates.map(item => item.date);
                                                            const filteredWorkOnoff = this.workOnoff.filter(items => {
                                                                const allDayoffDatesFormatted = items.Dayoff.flat().map(obj =>
                                                                    new Date(obj.date).toLocaleDateString('en-US', {
                                                                        year: 'numeric',
                                                                        month: 'long',
                                                                        day: 'numeric'
                                                                    })
                                                                );
                                                                return !allDayoffDatesFormatted.some(date => excludedDateStrings.includes(date));
                                                            });
                                                            filteredWorkOnoff.forEach(filteredItems => {
                                                                const workingDays = filteredItems.Working_day;
                                                                if (workingDays.hasOwnProperty(dayNameFull)) {
                                                                    const dayData = workingDays[dayNameFull];
                                                                    if (dayData.start) {
                                                                        const timePart = dayData.start.split("T")[1].split(".")[0];
                                                                        if (timePart !== "00:00:00") {
                                                                            leaveapproved = false;
                                                                        }
                                                                    }
                                                                };
                                                            });
                                                        });
                                                    }
                                                }
                                            });
                                        });
                                    }

                                    return leaveapproved;
                                } else {
                                    return false;
                                }
                            });
                        });
                        const dayName = dayDate.toLocaleString('en-us', { weekday: 'long' }).toLowerCase();

                        if (dayDate < currentDate || isDayOff || !validWorkingDays.includes(dayName)) {
                            dayCell.classList.add("disabled");
                            dayCell.style.backgroundColor = "#d3d3d3";
                            dayCell.style.pointerEvents = "none";
                        } else {
                            if (availableDates) {
                                dayCell.classList.add("available");
                                dayCell.style.backgroundColor = "rgb(18 163 46)";
                            }
                        }
                    }
                }
            }

            // Navigation buttons (prev/next month)
            addNavigationListeners() {
                const pre = document.querySelector('.pre-button');
                const next = document.querySelector('.next-button');

                if (pre) pre.addEventListener('click', () => this.changeMonth(-1));
                if (next) next.addEventListener('click', () => this.changeMonth(1));
            }

            // Change month
            changeMonth(direction) {
                month += direction;
                if (month < 0) {
                    month = 11;
                    year--;
                } else if (month > 11) {
                    month = 0;
                    year++;
                }
                this.drawDays();
            }

            // Add day click listener
            addDayClickListener() {
                const calendar = document.getElementById('calendar');
                if (calendar) {
                    calendar.addEventListener('click', (e) => {
                        const dayElem = e.target;
                        if (dayElem.tagName === 'TD' && dayElem.classList.contains("available")) {
                            this.clickDay(dayElem);
                        }
                    });
                }
            }
            clickDay(dayElem) {
                const day = parseInt(dayElem.innerHTML);
                selectedDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

                document.querySelector('.selected')?.classList.remove('selected');
                dayElem.classList.add('selected');

                this.BookeAslot(selectedDate);
            }
        BookeAslot(date) {
                if (!date) return;
                const serviceId = document.querySelector('.get_service_staff').value;
                const vendorId = document.querySelector('.service_vendor_form').value;
                $.ajax({
                    url: '/get/slotbooked',
                    method: 'GET',
                    data: {
                        dates: date,
                        serviceid: serviceId,
                        vendorid: vendorId,
                    },
                    success: function (response) {
                        let sessionsHTML = '';
                        if (response && response.staffdata.length > 0) {
                             const formattedDate = response.date;
                             const price = `${response.serviceCurrency} ${response.price}`;
                            $('.availibility').removeClass('d-none');
                            const date = response.date;

                            sessionsHTML += `<div class="date-section mb-3">
                                  <h5 class="date-header text-lg font-semibold mb-2">${formattedDate}</h5>
                                    <div class="d-flex gap-4 pb-2 overflow-auto mx-auto max-w-800px" style="scrollbar-width: thin;">
                                    <div class="d-flex gap-4 pb-2 w-max min-w-full" style="-ms-overflow-style: none; scrollbar-width: thin;">`;

                            response.staffdata.forEach((staff, index) => {
                                const firstSlot = staff.slots[0];
                                const lastSlot = staff.slots[staff.slots.length - 1];

                                if (firstSlot && lastSlot) {
                                    sessionsHTML += `
                                <div class="rounded-lg p-2 bg-white border border-gray-300 cursor-pointer m-2 slot-card"style="min-width: 170px; max-width: 100%;" 
                                data-date="${formattedDate}" 
                                data-price="${price}"
                                data-start="${staff.day_start}"
                                data-end="${staff.day_end}">
                                    <input type="hidden" name="staff_id" value="${staff.id}">
                                    <p class="text-sm mb-1 font-medium text-gray-700">${staff.day_start} - ${staff.day_end}</p>
                                    <p class="text-sm text-gray-600 m-0">Slots: ${staff.slots.length}</p>
                                    <p class="text-sm text-gray-600 m-0">Duration: ${formatDuration(response.duration)}</p>
                                    <p class="text-sm text-gray-600 m-0">Price: ${response.serviceCurrency} ${response.price}</p>
                                </div>`;
                                }
                            });

                                    sessionsHTML += `
                                </div>
                            </div>
                        </div>`;
                        } else {
                            sessionsHTML = `<p class="text-sm text-red-500">No available slots found for this date.</p>`;
                        }
                        
                        $('.availibility').html(sessionsHTML);
                        bindSlotClickEvent();
                    }
                    ,
                    error: function () {
                        alert('Error fetching session details');
                    }
                });
            }
        }
        function formatDuration(minutes) {
        const hrs = Math.floor(minutes / 60);
        const mins = minutes % 60;
        let label = '';

        if (hrs > 0) label += hrs + ' hour' + (hrs > 1 ? 's' : '');
        if (hrs > 0 && mins > 0) label += ' ';
        if (mins > 0) label += mins + ' minutes';

        return label;
    }
    function bindSlotClickEvent() {
            $('.slot-card').off('click').on('click', function () {
                const date = $(this).data('date');
                const price = $(this).data('price');
                const start = $(this).data('start');
                const end = $(this).data('end');
                const id = $(this).data('id');
                AppendSlotBoxOnce(date, price, start, end, id);
            });
        }
          function AppendSlotBoxOnce(date, price, start, end, id) {
            const $wrapper = $('.slot-list-wrapper');
            const uniqueKey = `${date}-${start}-${end}`;
            const exists = $wrapper.find(`[data-slot="${uniqueKey}"]`).length;
            if (!exists) {
                $('.remove-all-slots').removeClass('d-none');
                const slotHTML = `
            <div class="slot-item d-flex align-items-center justify-content-between gap-4 border border-gray-300 rounded-md p-3 bg-white shadow-sm text-sm w-full sm:w-full" data-slot="${uniqueKey}">
            <div class="d-flex w-100 justify-content-between align-items-center mr-2">    
            <div class="font-medium text-gray-800 flex-1">
                    <div>${date}</div>
                        <input type="hidden" class=""value="${id}"/>
                    <div class="text-xs text-gray-500">${start} → ${end}</div>
                </div>
                <div class="text-success font-semibold whitespace-nowrap">${price}</div>
                </div>
                <div class="text-danger font-bold cursor-pointer remove-slot ml-auto">&#10006;</div>
            </div>
        `;
                $wrapper.append(slotHTML);
            }
            toggleRemoveAllButton();
        }
        function toggleRemoveAllButton() {
            const hasSlots = $('.slot-list-wrapper .slot-item').length > 0;
            $('.remove-all-slots').toggleClass('d-none', !hasSlots);
        }
        $(document).on('click', '.remove-slot', function () {
            $(this).closest('.slot-item').remove();
            toggleRemoveAllButton();
        });
        // Remove all slots
        $(document).on('click', '.remove-all-slots', function () {
            $('.slot-list-wrapper').empty();
            toggleRemoveAllButton(); 
        });
}