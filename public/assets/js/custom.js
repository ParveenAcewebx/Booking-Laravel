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

//Booking Template Builder
jQuery(function ($) {
    const templateSelect = document.getElementById("bookingTemplates");
    const fbEditor = document.getElementById("build-wrap");
    let formBuilderInstance; // store the formBuilder object after initialization

    var newfield = [
        {
            label: "New Section",
            attrs: {
                type: "newsection",
            },
            required: false,
            icon: '<i class="fa-solid fa-section"></i>',
        },
    ];

    var temp = {
        newsection: function (fieldData) {
            return {
                field: `
                <div id="${fieldData.name}" class="section">
                    <div class="section-content">
                    <!-- Add your content here, e.g., form fields, text, etc. -->
                    </div>
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
                url: "/template/save",
                method: "POST",
                data: templateData,
                success: function (response) {
                    window.location.href = window.location.origin + "/template";
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                },
            });
        });

    // Triggers bookingTemplates click
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
            while (c.charAt(0) == " ") {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function checkCookie() {
        var ticks = getCookie("modelopen");
        if (ticks != "") {
            ticks++;
            setCookie("modelopen", ticks, 1);
            if (ticks == "2" || ticks == "1" || ticks == "0") {
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

            fetch(`/booking/load-template-html/${bookingtemplateid}`)
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

    function updateSelectAllCheckbox() {
        const all = document.querySelectorAll(".permission-checkbox");
        const checked = Array.from(all).filter((cb) => cb.checked);
        selectAll.checked = checked.length === all.length;
        selectAll.indeterminate =
            checked.length > 0 && checked.length < all.length;
    }

    document.querySelectorAll(".group-checkbox").forEach((groupCheckbox) => {
        groupCheckbox.addEventListener("change", function () {
            const groupKey = this.dataset.group;
            const checkboxes = document.querySelectorAll(
                `.permission-checkbox.group-${groupKey}`
            );
            checkboxes.forEach((cb) => (cb.checked = this.checked));
            updateSelectAllCheckbox();
        });
    });

    selectAll.addEventListener("change", function () {
        const checked = this.checked;
        document
            .querySelectorAll(".permission-checkbox")
            .forEach((cb) => (cb.checked = checked));
        document
            .querySelectorAll(".group-checkbox")
            .forEach((cb) => (cb.checked = checked));
        updateSelectAllCheckbox();
    });

    document.querySelectorAll(".permission-checkbox").forEach((cb) => {
        cb.addEventListener("change", function () {
            const groupClass = Array.from(cb.classList).find((cls) =>
                cls.startsWith("group-")
            );
            if (!groupClass) return;
            const groupKey = groupClass.replace("group-", "");
            const groupCB = document.querySelector(
                `.group-checkbox[data-group="${groupKey}"]`
            );
            const perms = document.querySelectorAll(
                `.permission-checkbox.group-${groupKey}`
            );
            const anyChecked = Array.from(perms).some((p) => p.checked);
            groupCB.checked = anyChecked;
            updateSelectAllCheckbox();
        });
    });

    document.querySelectorAll(".toggle-icon").forEach((icon) => {
        icon.addEventListener("click", function () {
            const groupKey = this.dataset.group;
            const rows = document.querySelectorAll(`.group-perms-${groupKey}`);
            const isVisible = Array.from(rows).some(
                (r) => r.style.display !== "none"
            );

            rows.forEach(
                (r) => (r.style.display = isVisible ? "none" : "table-row")
            );

            this.classList.toggle("icon-chevron-right", isVisible);
            this.classList.toggle("icon-chevron-down", !isVisible);
        });
    });

    document.querySelectorAll(".group-checkbox").forEach((groupCheckbox) => {
        const groupKey = groupCheckbox.dataset.group;
        const perms = document.querySelectorAll(
            `.permission-checkbox.group-${groupKey}`
        );
        const anyChecked = Array.from(perms).some((p) => p.checked);
        groupCheckbox.checked = anyChecked;

        const icon = document.querySelector(
            `.toggle-icon[data-group="${groupKey}"]`
        );
        if (icon) {
            icon.classList.remove("icon-chevron-down");
            icon.classList.add("icon-chevron-right");
        }
        document
            .querySelectorAll(`.group-perms-${groupKey}`)
            .forEach((r) => (r.style.display = "none"));
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
    const navbarSwitchButton = document.getElementsByClassName(
        "navbar-switch-button"
    )[0];
    const navbar = document.querySelector(".pcoded-navbar");

    function hideOrShowSwitchBackText() {
        if (navbar.classList.contains("navbar-collapsed")) {
            switchBackText.style.display = "none";
            navbarSwitchButton.classList.add("justify-content-center");
        } else {
            switchBackText.style.display = "inline";
            navbarSwitchButton.classList.remove("justify-content-center");
        }
    }

    setTimeout(hideOrShowSwitchBackText, 100);
    mobileToggle.addEventListener("click", function () {
        setTimeout(hideOrShowSwitchBackText, 100);
    });

    navbar.addEventListener("mouseenter", function () {
        if (navbar.classList.contains("navbar-collapsed")) {
            switchBackText.style.display = "inline";
            navbarSwitchButton.classList.remove("justify-content-center");
        }
    });

    navbar.addEventListener("mouseleave", function () {
        if (navbar.classList.contains("navbar-collapsed")) {
            switchBackText.style.display = "none";
            navbarSwitchButton.classList.add("justify-content-center");
        }
    });
});
