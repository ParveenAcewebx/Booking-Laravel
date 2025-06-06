setTimeout(function () {
    $(".alert").alert("close");
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
        if (willDelete) 
        {
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
                    swal("Poof! That Template has been deleted!", {
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
        } else {
            swal("That Template is safe!", {
                icon: "info",
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
        if (willDelete) 
        {
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
                    swal("Poof! That User has been deleted!", {
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
        } else {
            swal("That user is safe!", {
                icon: "info",
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
            // Submit the form directly
            document.getElementById("deleteBooking-" + id).submit();
        } else {
            swal("That booking is safe!", {
                icon: "info",
            });
        }
    });
    return false;
}

//Booking Template Builder
jQuery(function ($) {
    const templateSelect = document.getElementById("bookingTemplates");
    const fbEditor = document.getElementById("build-wrap");

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
        disableFields: ['autocomplete', 'button']
    });
    const templates = [
        {
            type: "text",
            label: "Name:",
            subtype: "text",
            className: "form-control",
            name: "text-1475765723950",
        },
        {
            type: "text",
            subtype: "email",
            label: "Email:",
            className: "form-control",
            name: "text-1475765724095",
        },
        {
            type: "text",
            subtype: "tel",
            label: "Phone:",
            className: "form-control",
            name: "text-1475765724231",
        },
        {
            type: "textarea",
            label: "Short Bio:",
            className: "form-control",
            name: "textarea-1475765724583",
        },
        {
            type: "newsection",
            required: false,
            label: "New Section",
            name: "newsection-1736235906446-0",
            access: false,
        },
    ];

    jQuery(window).on("load", function () {
        if (
            jQuery("#bookingaddpage").length === 0 &&
            jQuery("#bookingTemplates").length > 0
        ) {
            const selectedValue =
            document.getElementById("bookingTemplates").value;
            const parsedValue = JSON.parse(selectedValue);
            parsedValue.forEach((item) => {
                if (
                    item.hasOwnProperty("required") &&
                    item.required === "false"
                ) {
                    item.required = false;
                }
                if (item.hasOwnProperty("toggle") && item.toggle === "false") {
                    item.toggle = false;
                }
                if (item.hasOwnProperty("access") && item.access === "false") {
                    item.access = false;
                }
                if (item.hasOwnProperty("inline") && item.inline === "false") {
                    item.inline = false;
                }
            });
            formBuilder.actions.setData(parsedValue);
        }
    });
    jQuery(document)
        .off("click", ".save-template")
        .on("click", ".save-template", function (e) {
            e.preventDefault();
            var inputElement = document.getElementById("bookingTemplatesname");
            var inputValue = inputElement.value.trim();
            var errorMessageElement = document.getElementById("bookingTemplatesname-error");
            if (errorMessageElement) {
                errorMessageElement.remove();
            }
            if (!inputValue) {
                var errorMessage = document.createElement("span");
                errorMessage.id = "bookingTemplatesname-error";
                errorMessage.textContent = "The Template name cannot be empty.";
                inputElement.parentNode.appendChild(errorMessage);
                inputElement.focus();
                return;
            }
            var data = formBuilder.actions.getData();
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

    jQuery(window).on("load", function () {
        jQuery("#bookingTemplates").click();
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
                jQuery("#exampleModalCenter").modal();
            }
        } else {
            jQuery("#exampleModalCenter").modal();
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

        jQuery(window).on("load", function () {
            jQuery("#mymodelsformessage").click();
        });
    }

    $("#exampleModal").on("show.bs.modal", function (event) {
        var button = $(event.relatedTarget);
        var recipient = button.data("whatever");
        var modal = $(this);
        modal.find(".modal-title").text("New message to " + recipient);
        modal.find(".modal-body input").val(recipient);
    });

    jQuery(window).on("load", function () {
        jQuery("#mymodelsformessage").click();
    });
});

// Datatables for Bookings, Templates, and Users tables
$("#booking-list-table").DataTable();
$("#template-list-table").DataTable();
$("#user-list-table").DataTable();

// Add Booking
document.addEventListener("DOMContentLoaded", function () {
    // Show the modal on page load if it exists
    const bookingTemplateModal = document.getElementById("bookingTemplateModal");
    if (bookingTemplateModal) {
        $("#bookingTemplateModal")
            .modal({ backdrop: "static", keyboard: false })
            .modal("show");
    }

    const selectTemplateBtn = document.getElementById("select-template-btn");
    if (selectTemplateBtn) {
        selectTemplateBtn.addEventListener("click", function () {
            const bookingTemplateList =
                document.getElementById("booking-template-list");
            const templateError = document.getElementById(
                "booking-template-error"
            );
            const bookingtemplateid = document.getElementById("booking_template_id");
            const bookingDataInput = document.getElementById("booking_data");
            const bookingTemplate = document.getElementById("booking-template");

            if (bookingTemplateList && bookingTemplateList.value === "") {
                if (templateError) templateError.style.display = "block";
            } else {
                const selectedOption =
                    bookingTemplateList.options[bookingTemplateList.selectedIndex];
                if (templateError) templateError.style.display = "none";
                if (bookingtemplateid) bookingtemplateid.value = selectedOption.value;
                if (bookingDataInput)
                    bookingDataInput.value =
                        selectedOption.getAttribute("data-booking_data");
                if (bookingTemplateModal) $("#bookingTemplateModal").modal("hide");
                if (bookingTemplate) bookingTemplate.style.display = "block";
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const loadTemplateBtn = document.getElementById("loadTemplateBtn");

    if (loadTemplateBtn) {
        loadTemplateBtn.addEventListener("click", function () {
            const selectedOption = document.querySelector(
                "#bookingTemplateselect option:checked"
            );
            if (!selectedOption) {
                alert("Please select a Booking template.");
                return;
            }
            const bookingData = selectedOption.value;
            const bookingtemplateid = selectedOption.dataset.id;
            if (bookingData) {
                const templateFields = JSON.parse(bookingData);
                const dynamicTemplate =
                    document.getElementById("dynamictemplateFields");
                dynamicTemplate.innerHTML = "";
                let templateData = {};

                templateFields.forEach((field) => {
                    let inputHtml = "";
                    switch (field.type) {
                        case "text":
                        case "email":
                        case "number":
                        case "password":
                        case "tel":
                        case "date":
                        case "url":
                            inputHtml = `
                <div class="form-group">
                  <label>${field.label || ""}</label>
                  <input 
                    type="${field.subtype || "text"}" 
                    class="${field.className || "form-control"}"
                    placeholder="${field.placeholder || ""}" 
                    name="${field.name || ""}" 
                    ${field.required === "true" ? "required" : ""}>
                </div>`;
                            break;
                        case "textarea":
                            inputHtml = `
                <div class="form-group">
                  <label>${field.label || ""}</label>
                  <textarea 
                    class="${field.className || "form-control"}"
                    placeholder="${field.placeholder || ""}" 
                    name="${field.name || ""}" 
                    ${field.required === "true" ? "required" : ""}></textarea>
                </div>`;
                            break;
                        case "select":
                            const options = field.values
                                .map(
                                    (option) =>
                                        `<option value="${option.value}">${option.label}</option>`
                                )
                                .join("");
                            inputHtml = `
                <div class="form-group">
                  <label>${field.label || ""}</label>
                  <select 
                    class="${field.className || "form-control"}" 
                    name="${field.name || ""}" 
                    ${field.required === "true" ? "required" : ""}>
                    ${options}
                  </select>
                </div>`;
                            break;
                        case "radio-group":
                            inputHtml = `
                <div class="form-group">
                  <label>${field.label || ""}</label>
                  <div>
                    ${field.values
                        .map(
                            (option) => `
                      <label class="${field.className || "form-check-label"}">
                        <input 
                          type="radio" 
                          name="${field.name || ""}" 
                          value="${option.value}" 
                          ${field.required === "true" ? "required" : ""}>
                        ${option.label}
                      </label>
                    `
                        )
                        .join("")}
                  </div>
                </div>`;
                            break;
                        case "checkbox-group":
                            inputHtml = `
                <div class="form-group">
                  <label>${field.label || ""}</label>
                  <div>
                    ${field.values
                        .map(
                            (option) => `
                      <label class="${field.className || "form-check-label"}">
                        <input 
                          type="checkbox" 
                          name="${field.name || ""}[]" 
                          value="${option.value}" 
                          ${field.required === "true" ? "required" : ""}>
                        ${option.label}
                      </label>
                    `
                        )
                        .join("")}
                  </div>
                </div>`;
                            break;
                        default:
                            inputHtml = `
                <div class="form-group">
                  <label>${field.label || ""}</label>
                  <input 
                    type="${field.type || "text"}" 
                    class="${field.className || "form-control"}" 
                    name="${field.name || ""}" 
                    ${field.required === "true" ? "required" : ""}>
                </div>`;
                    }
                    dynamicTemplate.innerHTML += inputHtml;
                });

                // Capture values on submit
                document
                    .querySelector("form")
                    .addEventListener("submit", function (event) {
                        templateData = {};
                        templateFields.forEach((field) => {
                            if (
                                field.type === "checkbox-group" ||
                                field.type === "radio-group"
                            ) {
                                templateData[field.name] = [];
                                document
                                    .querySelectorAll(
                                        `[name="${field.name}"]:checked`
                                    )
                                    .forEach((checkbox) => {
                                        templateData[field.name].push(
                                            checkbox.value
                                        );
                                    });
                            } else {
                                const fieldElement = document.querySelector(
                                    `[name="${field.name}"]`
                                );
                                if (fieldElement) {
                                    templateData[field.name] = fieldElement.value;
                                }
                            }
                        });

                        document.getElementById("bookingTemplateId").value = bookingtemplateid;
                        document.getElementById("bookingData").value =
                            JSON.stringify(templateData);
                    });

                $("#bookingTemplateModal").modal("hide");
            } else {
                alert("Please select a Booking template.");
            }
        });
    }
});
document.getElementById("select-all-permissions")?.addEventListener("change", function () {
        const checked = this.checked;
        document
            .querySelectorAll(".permission-checkbox, .group-checkbox")
            .forEach((cb) => (cb.checked = checked));
        document.querySelectorAll(".permission-row").forEach((row) => {
            row.style.display = checked ? "table-row" : "none";
        });
    });

// Toggle Permissions Based on Group
document.querySelectorAll(".group-checkbox").forEach((groupCb) => {
    groupCb.addEventListener("change", function () {
        const group = this.dataset.group;
        const isChecked = this.checked;
        document
            .querySelectorAll(`.group-${group}`)
            .forEach((cb) => (cb.checked = isChecked));
        document.querySelectorAll(`.group-perms-${group}`).forEach((row) => {
            row.style.display = isChecked ? "table-row" : "none";
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const selectAllCheckbox = document.getElementById("select-all-permissions");

    // Update select all checkbox checked state
    function updateSelectAllCheckbox() {
        const allPerms = document.querySelectorAll(".permission-checkbox");
        const anyChecked = Array.from(allPerms).some((cb) => cb.checked);
        const allChecked = Array.from(allPerms).every((cb) => cb.checked);
        selectAllCheckbox.checked = allChecked;
        selectAllCheckbox.indeterminate = !allChecked && anyChecked;
    }

    // Toggle group permissions visibility + checkboxes when group checkbox changes
    document.querySelectorAll(".group-checkbox").forEach((groupCheckbox) => {
        groupCheckbox.addEventListener("change", function () {
            const groupKey = this.dataset.group;
            const permRows = document.querySelectorAll(
                ".group-perms-" + groupKey
            );
            permRows.forEach((row) => {
                row.style.display = this.checked ? "" : "none";
            });

            // Check/uncheck all permissions in group
            const permCheckboxes = document.querySelectorAll(
                ".permission-checkbox.group-" + groupKey
            );
            permCheckboxes.forEach((cb) => (cb.checked = this.checked));

            updateSelectAllCheckbox();
        });
    });

    // Toggle all groups and permissions on select all change
    selectAllCheckbox.addEventListener("change", function () {
        const checked = this.checked;

        document
            .querySelectorAll(".group-checkbox")
            .forEach((groupCheckbox) => {
                groupCheckbox.checked = checked;

                const groupKey = groupCheckbox.dataset.group;
                const permRows = document.querySelectorAll(
                    ".group-perms-" + groupKey
                );
                permRows.forEach(
                    (row) => (row.style.display = checked ? "" : "none")
                );

                const permCheckboxes = document.querySelectorAll(
                    ".permission-checkbox.group-" + groupKey
                );
                permCheckboxes.forEach((cb) => (cb.checked = checked));
            });
    });

    // When individual permission checkbox changes, update group checkbox accordingly
    document
        .querySelectorAll(".permission-checkbox")
        .forEach((permCheckbox) => {
            permCheckbox.addEventListener("change", function () {
                const classes = Array.from(this.classList);
                const groupClass = classes.find((c) => c.startsWith("group-"));
                if (!groupClass) return;

                const groupKey = groupClass.replace("group-", "");
                const groupCheckbox = document.querySelector(
                    '.group-checkbox[data-group="' + groupKey + '"]'
                );
                const allPerms = document.querySelectorAll(
                    ".permission-checkbox." + groupClass
                );

                const anyChecked = Array.from(allPerms).some(
                    (cb) => cb.checked
                );
                groupCheckbox.checked = anyChecked;

                // Show/hide permission rows based on group checkbox
                const permRows = document.querySelectorAll(
                    ".group-perms-" + groupKey
                );
                permRows.forEach((row) => {
                    row.style.display = anyChecked ? "" : "none";
                });

                updateSelectAllCheckbox();
            });
        });

    // On page load, initialize group checkboxes and permissions visibility
    document.querySelectorAll(".group-checkbox").forEach((groupCheckbox) => {
        const groupKey = groupCheckbox.dataset.group;
        const permCheckboxes = document.querySelectorAll(
            ".permission-checkbox.group-" + groupKey
        );

        const anyChecked = Array.from(permCheckboxes).some((cb) => cb.checked);
        groupCheckbox.checked = anyChecked;

        const permRows = document.querySelectorAll(".group-perms-" + groupKey);
        permRows.forEach((row) => {
            row.style.display = anyChecked ? "" : "none";
        });
    });

    updateSelectAllCheckbox();
});
