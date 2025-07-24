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
        text: "Once deleted, you will not be able to recover this imaginary file!",
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
        text: "Once deleted, you will not be able to recover this imaginary file!",
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
        text: "Once deleted, this role and its permissions will be gone forever!",
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
        text: "Once deleted, this category data will be gone!",
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
        text: "Once deleted, this service data will be gone!.",
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

//Booking Template Builder
jQuery(function ($) {
    const templateSelect = document.getElementById("bookingTemplates");
    const fbEditor = document.getElementById("build-wrap");
    let formBuilderInstance;

    var newfield = [
        {
            label: "Next Step",
            attrs: {
                type: "newsection",
            },
            required: false,
            icon: '<i class="fa-solid fa-section"></i>',
        },
        {
            label: "ShortCode",
            attrs: {
                type: "shortcodeblock",
            },
            icon: '<i class="fa fa-code"></i>',
        },
    ];

    var temp = {
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
                    var currentStep = 0;
                    $(document).on(
                        "click",
                        `#${fieldData.name} .next-btn`,
                        function () {
                            currentStep++;
                            showSection(currentStep);
                        }
                    );
                    $(document).on(
                        "click",
                        `#${fieldData.name} .prev-btn`,
                        function () {
                            currentStep--;
                            showSection(currentStep);
                        }
                    );

                    function showSection(step) {
                        var allSections = $(".section");
                        var totalSections = allSections.length;
                        allSections.hide();
                        if (step >= 0 && step < totalSections) {
                            $(allSections[step]).show();
                        }
                        if (step === 0) {
                            $(`#${fieldData.name} .prev-btn`).hide();
                        } else {
                            $(`#${fieldData.name} .prev-btn`).show();
                        }
                        if (step === totalSections - 1) {
                            $(`#${fieldData.name} .next-btn`).hide();
                        } else {
                            $(`#${fieldData.name} .next-btn`).show();
                        }
                    }

                    showSection(currentStep);
                },
            };
        },

        shortcodeblock: function (fieldData) {
            return {
                field: `
            <div class="shortcode-block">
                <select 
                    name="shortcode" 
                    class="form-control shortcode-select" 
                    data-placeholder="Select a shortcode"
                >
                    <option></option>
                </select>
            </div>
        `,
                onRender: function () {
                    const $select = $('.shortcode-select:last'); // Only target the most recently rendered

                    // Fetch shortcodes from your backend
                    $.ajax({
                        url: '/admin/shortcodes/list',
                        type: 'GET',
                        dataType: 'json',
                        success: function (shortcodes) {
                            $select.empty().append(`<option></option>`); // clear & add placeholder

                            shortcodes.forEach(function (code) {
                                const isSelected = fieldData.value === `[${code}]`;
                                $select.append(
                                    new Option(`[${code}]`, `[${code}]`, isSelected, isSelected)
                                );
                            });

                            // Init select2 after options loaded
                            $select.select2({
                                placeholder: "Select a shortcode",
                                allowClear: true,
                                width: '100%'
                            });
                        },
                        error: function () {
                            console.error("Failed to load shortcodes.");
                        }
                    });
                },
                onSave: function (evt, field) {
                    const input = $(field).find('select[name="shortcode"]');
                    fieldData.value = input.val(); // Save selected shortcode
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

        if (
            $("#bookingaddpage").length === 0 &&
            $("#bookingTemplates").length > 0
        ) {
            const selectedValue =
                document.getElementById("bookingTemplates").value;
            const parsedValue = JSON.parse(selectedValue);

            parsedValue.forEach((item) => {
                if (item.required === "false") item.required = false;
                if (item.toggle === "false") item.toggle = false;
                if (item.access === "false") item.access = false;
                if (item.inline === "false") item.inline = false;
            });

            fb.actions.setData(parsedValue);
        }
    });

    $(document)
        .off("click", ".save-template")
        .on("click", ".save-template", function (e) {
            e.preventDefault();

            var inputElement = document.getElementById("bookingTemplatesname");
            var inputValue = inputElement.value.trim();
            var errorMessageElement = document.getElementById(
                "bookingTemplatesname-error"
            );

            if (errorMessageElement) {
                errorMessageElement.remove();
            }

            if (!inputValue) {
                var errorMessage = document.createElement("span");
                errorMessage.id = "bookingTemplatesname-error";
                errorMessage.textContent = "The Template name cannot be empty.";
                errorMessage.style.color = "red";
                inputElement.parentNode.appendChild(errorMessage);
                inputElement.focus();
                return;
            }

            if (!formBuilderInstance) {
                console.error("Form Builder is not initialized yet.");
                return;
            }

            var data = formBuilderInstance.actions.getData();
            var templateid = document.getElementById("templateid")
                ? document.getElementById("templateid").value
                : "";
            var csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            var templateData = {
                data: data,
                templatename: inputValue,
                templateid: templateid,
                _token: csrfToken,
            };

            $.ajax({
                url: "/admin/template/save",
                method: "POST",
                data: templateData,
                success: function () {
                    window.location.href = window.location.origin + "/admin/template";
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                },
            });
        });

    $(window).on("load", function () {
        $("#bookingTemplates").click();
    });

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
        var expires = "expires=" + d.toGMTString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(";");
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) === " ") {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function checkCookie() {
        var ticks = getCookie("modelopen");
        if (ticks !== "") {
            ticks++;
            setCookie("modelopen", ticks, 1);
            if (ticks === "2" || ticks === "1" || ticks === "0") {
                $("#exampleModalCenter").modal();
            }
        } else {
            $("#exampleModalCenter").modal();
            ticks = 1;
            setCookie("modelopen", ticks, 1);
        }

        $("#exampleModal").on("show.bs.modal", function (event) {
            var button = $(event.relatedTarget);
            var recipient = button.data("whatever");
            var modal = $(this);
            modal.find(".modal-title").text("New message to " + recipient);
            modal.find(".modal-body input").val(recipient);
        });

        $(window).on("load", function () {
            $("#mymodelsformessage").click();
        });
    }

    $("#exampleModal").on("show.bs.modal", function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data("whatever");
        var modal = $(this);
        modal.find(".modal-title").text("New message to " + recipient);
        modal.find(".modal-body input").val(recipient);
    });

    $(window).on("load", function () {
        $("#mymodelsformessage").click();
    });
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
        { selector: ".select-user" }
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
document.getElementById('addStaffButton').addEventListener('click', function () {
    const template = document.getElementById('staffTemplate');
    const container = document.getElementById('dayOffRepeater');
    container.appendChild(template.content.cloneNode(true));

    // Reinitialize date range picker for new inputs (if using plugin)
    if (typeof initDateRangePicker === 'function') {
        initDateRangePicker();
    }
});