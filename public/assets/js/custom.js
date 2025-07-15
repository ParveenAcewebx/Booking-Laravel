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
            label: "New Section",
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
                    <div class="shortcode-block"  >
 <input type="text" 
                    name="shortcode" 
                    value="${fieldData.value || ""}" 
                    placeholder="[your-shortcode]" 
                    class="form-control"
                    ${fieldData.required ? "required" : ""} 
                />
                    </div>
                `,
                onRender: function () {
                    // No-op
                },
                onSave: function (evt, field) {
                    const input = $(field).find('input[name="shortcode"]');
                    fieldData.shortcode = input.val();
                },
            };
        },
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
        { selector: ".status", placeholder: "Select a status" },
        { selector: ".durartion", placeholder: "Select Duration" },
        { selector: ".category", placeholder: "Select Category" },
        { selector: ".select2-mash", placeholder: "Select an option" },
        { selector: ".currency", placeholder: "Select Currency" },
        { selector: ".currency_unit" },
        { selector: ".cancelling_value" },
        { selector: ".appointment_status" },
        { selector: ".payment_mode" },
        { selector: ".select_role" },
        { selector: ".select2_working_days" },
        { selector: ".select_start_time" },
        { selector: ".select_end_time" }
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

    $('.apply-to-all-days').on('change', function () {
        const isChecked = this.checked;
        const $sourceCard = $(this).closest('.card-body');

        const startTime = $sourceCard.find('.start-time').val() || '00:00';
        const endTime = $sourceCard.find('.end-time').val() || '00:00';
        const services = $sourceCard.find('.service-select').val();

        $('.card-body').each(function () {
            const $this = $(this);
            if ($this.is($sourceCard)) return;

            if (isChecked) {
                $this.find('.start-time').val(startTime).trigger('change');
                $this.find('.end-time').val(endTime).trigger('change');
                $this.find('.service-select').val(services).trigger('change');
            } else {
                $this.find('.start-time').val('00:00').trigger('change');
                $this.find('.end-time').val('00:00').trigger('change');
                $this.find('.service-select').val([]).trigger('change');
            }
        });
    });

    $('.select2_working_days').select2({
        theme: 'bootstrap',
        width: '100%'
    });
});

$(function () {
    const addedServices = new Set();
    const totalServiceCount = $('.service-option').length;

    // Initialize with preloaded services
    $('#servicesTable tbody tr[data-id]').each(function () {
        addedServices.add($(this).data('id').toString());
    });

    // Show/hide "No services" message
    function toggleNoServicesRow() {
        const hasRows = $('#servicesTable tbody tr[data-id]').length > 0;
        if (!hasRows) {
            if (!$('#noServiceRow').length) {
                $('#servicesTable tbody').append(`
                    <tr id="noServiceRow">
                        <td colspan="3" class="text-center text-muted">No services assigned yet.</td>
                    </tr>
                `);
            }
        } else {
            $('#noServiceRow').remove();
        }
    }

    // Enable/Disable Add button
    function updateAddButtonState() {
        $('#addServicesBtn').prop('disabled', addedServices.size >= totalServiceCount);
    }

    // Refresh dropdowns based on assigned services
    function refreshWorkingDaysServices() {
        let services = [];

        $('#servicesTable tbody tr[data-id]').each(function () {
            const id = $(this).data('id');
            const name = $(this).find('td:first').text().trim();
            services.push({ id, name });
        });

        $('.service-select').each(function () {
            const $select = $(this);
            const currentValues = $select.val() || [];
            $select.empty();

            if (services.length === 0) {
                // $select.append('');
            } else {
                services.forEach(service => {
                    const selected = currentValues.includes(String(service.id)) ? 'selected' : '';
                    $select.append(`<option value="${service.id}" ${selected}>${service.name}</option>`);
                });
            }

            if ($select.hasClass('select2-hidden-accessible')) {
                $select.trigger('change.select2');
            }
        });
    }

    // On modal open - reset checkboxes
    $('#servicesModal').on('show.bs.modal', function () {
        $('.service-checkbox').prop('checked', false);
        $('.service-option').show();

        addedServices.forEach(id => {
            $('#service_' + id).closest('.service-option').hide();
        });
    });

    // Add selected services from modal
    $('#addSelectedServices').on('click', function () {
        $('.service-checkbox:checked').each(function () {
            const id = $(this).val();
            if (addedServices.has(id)) return;

            const name = $(this).data('name');
            const rawPrice = $(this).data('price');
            let price = parseFloat(String(rawPrice).replace(/[^0-9.]/g, '')).toFixed(2);
            if (isNaN(price)) price = '0.00';

            const row = `
                <tr data-id="${id}">
                    <td>
                        <input type="hidden" name="assigned_services[${id}][id]" value="${id}">
                        ${name}
                    </td>
                    <td class="text-center">
                        <input type="text" name="assigned_services[${id}][price]"
                            class="form-control form-control-sm text-center"
                            value="$${price}" readonly>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger remove-service">REMOVE</button>
                    </td>
                </tr>
            `;

            $('#servicesTable tbody').append(row);
            addedServices.add(id);
        });

        $('#servicesModal').modal('hide');
        toggleNoServicesRow();
        updateAddButtonState();
        refreshWorkingDaysServices();
    });

    // Remove a service
    $('#servicesTable').on('click', '.remove-service', function () {
        const row = $(this).closest('tr');
        const id = row.data('id').toString();
        row.remove();
        addedServices.delete(id);

        const $checkbox = $('#service_' + id);
        if ($checkbox.length > 0) {
            $checkbox.closest('.service-option').show();
        }

        toggleNoServicesRow();
        updateAddButtonState();
        refreshWorkingDaysServices();
    });

    // Initial load setup
    toggleNoServicesRow();
    updateAddButtonState();
    refreshWorkingDaysServices();
});
/* ------------------------  End Work Hours Function  ---------------------------- */


