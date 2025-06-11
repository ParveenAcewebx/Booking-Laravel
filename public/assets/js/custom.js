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
                    swal("Booking Template has been deleted!", {
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
            swal("Booking Template is safe!", {
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
                    swal("User has been deleted!", {
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
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("Booking has been deleted!", {
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
            .catch(error => {
                console.error("Error:", error);
                swal("There was an error!", {
                    icon: "error",
                });
            });
        } else {
            swal("That booking is safe!", {
                icon: "info",
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
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    swal("Role deleted successfully!", {
                        icon: "success",
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    swal("Oops! Something went wrong while deleting the role.", {
                        icon: "error",
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
                swal("Error occurred while processing the request!", {
                    icon: "error",
                });
            });
        } else {
            swal("This role is safe and sound ✨", {
                icon: "info",
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
                    $(document).on("click", `#${fieldData.name} .next-btn`, function () {
                        currentStep++;
                        showSection(currentStep);
                    });
                    $(document).on("click", `#${fieldData.name} .prev-btn`, function () {
                        currentStep--;
                        showSection(currentStep);
                    });

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
        disableFields: ['autocomplete', 'button'],
    });

    formBuilder.promise.then(function (fb) {
        formBuilderInstance = fb;

        if (
            $("#bookingaddpage").length === 0 &&
            $("#bookingTemplates").length > 0
        ) {
            const selectedValue = document.getElementById("bookingTemplates").value;
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


// Datatables for Bookings, Templates, and Users tables
// $("#booking-list-table").DataTable();
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
document.addEventListener("DOMContentLoaded", function () {
    const selectAll = document.getElementById("select-all-permissions");

    function updateSelectAllCheckbox() {
        const all = document.querySelectorAll(".permission-checkbox");
        const checked = Array.from(all).filter(cb => cb.checked);
        selectAll.checked = checked.length === all.length;
        selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
    }

    // Handle group checkbox -> select all in that group
    document.querySelectorAll(".group-checkbox").forEach(groupCheckbox => {
        groupCheckbox.addEventListener("change", function () {
            const groupKey = this.dataset.group;
            const checkboxes = document.querySelectorAll(`.permission-checkbox.group-${groupKey}`);
            checkboxes.forEach(cb => cb.checked = this.checked);
            updateSelectAllCheckbox();
        });
    });

    // Handle global select all
    selectAll.addEventListener("change", function () {
        const checked = this.checked;
        document.querySelectorAll(".permission-checkbox").forEach(cb => cb.checked = checked);
        document.querySelectorAll(".group-checkbox").forEach(cb => cb.checked = checked);
        updateSelectAllCheckbox();
    });

    // Update group checkbox when a permission is toggled
    document.querySelectorAll(".permission-checkbox").forEach(cb => {
        cb.addEventListener("change", function () {
            const groupClass = Array.from(cb.classList).find(cls => cls.startsWith("group-"));
            if (!groupClass) return;
            const groupKey = groupClass.replace("group-", "");
            const groupCB = document.querySelector(`.group-checkbox[data-group="${groupKey}"]`);
            const perms = document.querySelectorAll(`.permission-checkbox.group-${groupKey}`);
            const anyChecked = Array.from(perms).some(p => p.checked);
            groupCB.checked = anyChecked;
            updateSelectAllCheckbox();
        });
    });

    // Toggle expand/collapse on icon click
    document.querySelectorAll(".toggle-icon").forEach(icon => {
        icon.addEventListener("click", function () {
            const groupKey = this.dataset.group;
            const rows = document.querySelectorAll(`.group-perms-${groupKey}`);
            const isVisible = Array.from(rows).some(r => r.style.display !== "none");

            rows.forEach(r => r.style.display = isVisible ? "none" : "table-row");

            this.classList.toggle("icon-chevron-right", isVisible);
            this.classList.toggle("icon-chevron-down", !isVisible);
        });
    });

    // ✅ On page load: ALWAYS start collapsed, even if some permissions are checked
    document.querySelectorAll(".group-checkbox").forEach(groupCheckbox => {
        const groupKey = groupCheckbox.dataset.group;
        const perms = document.querySelectorAll(`.permission-checkbox.group-${groupKey}`);
        const anyChecked = Array.from(perms).some(p => p.checked);
        groupCheckbox.checked = anyChecked;

        const icon = document.querySelector(`.toggle-icon[data-group="${groupKey}"]`);
        if (icon) {
            icon.classList.remove("icon-chevron-down");
            icon.classList.add("icon-chevron-right"); // Always default collapsed
        }

        // Keep rows hidden always on load
        document.querySelectorAll(`.group-perms-${groupKey}`).forEach(r => r.style.display = "none");
    });

    updateSelectAllCheckbox();
});
