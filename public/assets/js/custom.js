// Form delete alert

setTimeout(function () {
    $(".alert").alert("close");
}, 5000); // hide after 5 seconds

function deleteForm(id) {
    event.preventDefault();
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var form = document.getElementById("deleteForm-" + id);
            var formData = new FormData(form);

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
                        swal("Poof! That form has been deleted!", {
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
            swal("That form is safe!", {
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
        if (willDelete) {
            var form = document.getElementById("deleteUser-" + id);
            var formData = new FormData(form);
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
        text: "Once deleted, you will not be able to recover this imaginary file!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            var booking = document.getElementById("deleteBooking-" + id);
            var bookingData = new FormData(booking);

            fetch(booking.action, {
                method: "DELETE",
                body: bookingData,
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
                        swal("Poof! That booking has been deleted!", {
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
            swal("That booking is safe!", {
                icon: "info",
            });
        }
    });
}
//  Form builder
jQuery(function ($) {
    const templateSelect = document.getElementById("formTemplates");
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
            jQuery("#formsaddpage").length === 0 &&
            jQuery("#formTemplates").length > 0
        ) {
            // console.log("fsdfsdfsdfsd testing");
            const selectedValue =
                document.getElementById("formTemplates").value;
            console.log("Selected Value:", selectedValue);

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
            console.log(parsedValue);

            formBuilder.actions.setData(parsedValue);
        }
    });
    jQuery(document)
        .off("click", ".save-template")
        .on("click", ".save-template", function (e) {
            e.preventDefault();

            var inputElement = document.getElementById("formTemplatesname");
            var inputValue = inputElement.value.trim();
            var errorMessageElement = document.getElementById(
                "formTemplatesname-error"
            );

            if (errorMessageElement) {
                errorMessageElement.remove();
            }

            if (!inputValue) {
                var errorMessage = document.createElement("span");
                errorMessage.id = "formTemplatesname-error";
                errorMessage.textContent = "The form name cannot be empty.";
                inputElement.parentNode.appendChild(errorMessage);
                inputElement.focus();
                return;
            }

            var data = formBuilder.actions.getData();

            //   if (data.length === 0) {
            //     alert("Please add at least one form field before saving.");
            //     return;
            //   }

            var formid = document.getElementById("formid")
                ? document.getElementById("formid").value
                : "";
            var csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            var formData = {
                data: data,
                formname: inputValue,
                formid: formid,
                _token: csrfToken,
            };

            $.ajax({
                url: "/form/save",
                method: "POST",
                data: formData,
                success: function (response) {
                    window.location.href = window.location.origin + "/form";
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                },
            });
        });

    jQuery(window).on("load", function () {
        jQuery("#formTemplates").click();
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
        // DataTable For Users Lists
        $("#user-list-table").DataTable();
        // DataTable For Forms Lists
        $("#form-list-table").DataTable();
        // DataTable For Booking Lists
        $("#booking-list-table").DataTable();
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

// Datatables for Bookings, Forms, and Users tables
$("#booking-list-table").DataTable();
$("#form-list-table").DataTable();
$("#user-list-table").DataTable();

// Add Booking
document.addEventListener("DOMContentLoaded", function () {
    // Show the modal on page load if it exists
    const formTemplateModal = document.getElementById("formTemplateModal");
    if (formTemplateModal) {
        $("#formTemplateModal")
            .modal({ backdrop: "static", keyboard: false })
            .modal("show");
    }

    const selectTemplateBtn = document.getElementById("select-template-btn");
    if (selectTemplateBtn) {
        selectTemplateBtn.addEventListener("click", function () {
            const formTemplateList =
                document.getElementById("form-template-list");
            const templateError = document.getElementById(
                "form-template-error"
            );
            const bookingFormId = document.getElementById("booking_form_id");
            const bookingDataInput = document.getElementById("booking_data");
            const bookingForm = document.getElementById("booking-form");

            if (formTemplateList && formTemplateList.value === "") {
                if (templateError) templateError.style.display = "block";
            } else {
                const selectedOption =
                    formTemplateList.options[formTemplateList.selectedIndex];
                if (templateError) templateError.style.display = "none";
                if (bookingFormId) bookingFormId.value = selectedOption.value;
                if (bookingDataInput)
                    bookingDataInput.value =
                        selectedOption.getAttribute("data-booking_data");
                if (formTemplateModal) $("#formTemplateModal").modal("hide");
                if (bookingForm) bookingForm.style.display = "block";
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const loadTemplateBtn = document.getElementById("loadTemplateBtn");

    if (loadTemplateBtn) {
        loadTemplateBtn.addEventListener("click", function () {
            const selectedOption = document.querySelector(
                "#formTemplateSelect option:checked"
            );
            if (!selectedOption) {
                alert("Please select a form template.");
                return;
            }

            const bookingData = selectedOption.value;
            const bookingFormId = selectedOption.dataset.id;

            if (bookingData) {
                const formFields = JSON.parse(bookingData);
                const dynamicForm =
                    document.getElementById("dynamicFormFields");
                dynamicForm.innerHTML = "";

                let formData = {};

                formFields.forEach((field) => {
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
                    dynamicForm.innerHTML += inputHtml;
                });

                // Capture values on submit
                document
                    .querySelector("form")
                    .addEventListener("submit", function (event) {
                        formData = {};
                        formFields.forEach((field) => {
                            if (
                                field.type === "checkbox-group" ||
                                field.type === "radio-group"
                            ) {
                                formData[field.name] = [];
                                document
                                    .querySelectorAll(
                                        `[name="${field.name}"]:checked`
                                    )
                                    .forEach((checkbox) => {
                                        formData[field.name].push(
                                            checkbox.value
                                        );
                                    });
                            } else {
                                const fieldElement = document.querySelector(
                                    `[name="${field.name}"]`
                                );
                                if (fieldElement) {
                                    formData[field.name] = fieldElement.value;
                                }
                            }
                        });

                        document.getElementById("bookingFormId").value =
                            bookingFormId;
                        document.getElementById("bookingData").value =
                            JSON.stringify(formData);
                    });

                $("#formTemplateModal").modal("hide");
            } else {
                alert("Please select a form template.");
            }
        });
    }
});
const container = document.getElementById("permissions-container");

document.querySelectorAll(".role-group-toggle").forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
        const group = this.dataset.group;

        if (this.checked) {
            // Add group permissions
            roleGroups[group]["roles"].forEach((perm, index) => {
                const id = `perm_${group}_${index}`;
                const row = document.createElement("tr");
                row.classList.add(`perm-row-${group}`);
                row.innerHTML = `
                        <td><input type="checkbox" name="permissions[]" value="${perm}" id="${id}"></td>
                        <td><label for="${id}">${perm.replace(
                    /_/g,
                    " "
                )}</label></td>
                    `;
                container.appendChild(row);
            });
        } else {
            // Remove group permissions
            document
                .querySelectorAll(`.perm-row-${group}`)
                .forEach((row) => row.remove());
        }
    });
});

document
    .getElementById("select-all-permissions")
    .addEventListener("change", function () {
        const checkboxes = container.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach((cb) => (cb.checked = this.checked));
    });

document.querySelectorAll(".role-group-toggle").forEach((checkbox) => {
    function togglePermissions() {
        const groupKey = checkbox.dataset.group;
        const checked = checkbox.checked;
        document.querySelectorAll(`.perm-row-${groupKey}`).forEach((row) => {
            row.style.display = checked ? "" : "none";
            if (!checked) {
                row.querySelector("input.permission-checkbox").checked = false;
            }
        });
    }

    // initial toggle on page load
    togglePermissions();

    checkbox.addEventListener("change", togglePermissions);
});

// Select all permissions toggle
document
    .getElementById("select-all-permissions")
    .addEventListener("change", function () {
        const checked = this.checked;
        document
            .querySelectorAll(".permission-checkbox")
            .forEach((cb) => (cb.checked = checked));
        document
            .querySelectorAll(".perm-row-{{ $groupKey }}")
            .forEach((row) => {
                row.style.display = checked ? "" : "none";
            });
        document
            .querySelectorAll(".role-group-toggle")
            .forEach((cb) => (cb.checked = checked));
    });
 $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })