document.addEventListener("DOMContentLoaded", function () {

    const loader = document.getElementById('loader');
    const formWrapper = document.getElementById('dynamic-form');

    setTimeout(() => {
        if (loader) loader.style.display = 'none';
        if (formWrapper) formWrapper.classList.remove('hidden');
    }, 500);

    setTimeout(function () {
        $(".alert-message").fadeOut("slow", function () {
            $(this).remove();
        });
    }, 3000);
    let currentSteps = 1;
    const steps = document.querySelectorAll('.step');
    const prevButton = document.querySelector('.previous');
    const nextButtons = document.querySelectorAll('.next');
    const submitButtons = document.querySelectorAll('.submit');
    const ServiceStaffCode = document.querySelector('.get_service_staff');

    $(document).on("input change", "input, select", function () {
        const field = this;
        field.classList.remove('border-red-500');

        const err = field.nextElementSibling;
        if (err && err.classList.contains('error-message')) err.remove();

        if (field.type === 'checkbox') {
            const checkboxParent = $(field).closest('.mb-6')[0];
            $(checkboxParent).find('.checkbox-error-message').remove();
        
            const group = $(checkboxParent).find('input[type="checkbox"][name="' + field.name + '"]');
            const anyChecked = group.is(':checked');
        
            if (anyChecked) {
                group.removeAttr('required');
            } else {
                group.removeAttr('required');
                group.first().attr('required', true);
            }
        }
        
        if (field.type === 'radio') {
            const checkboxParent = $(field).closest('.mb-6')[0];
            $(checkboxParent).find('.radio-error-message').remove();
        }

        if (field.classList.contains('service_vendor_form') && field.value) {
            const placeholder = field.parentNode.querySelector('.vendor-placeholder');
            if (placeholder) placeholder.innerHTML = '';
        }
    });


    if (!steps.length || !prevButton || !nextButtons.length || !submitButtons.length) {
        // console.error('Required elements not found!');
        return;
    }

    /*------ Check ValidationReuired ------*/
    function validateRequiredFields(step) {
        let isValid = true;
        const requiredFields = step.querySelectorAll('[required]');
        const current_step = step.getAttribute('id');

        const noVendorElement = $('#' + current_step).find('.vendor-placeholder .no-vendor-text');
        const noVendorAssigned = noVendorElement.length > 0;

        const calendarWrap = $('#' + current_step).find('.calendar-wrap');

        // Only validate calendar if it's in the DOM AND visible
        if (!noVendorAssigned && calendarWrap.length && calendarWrap.is(':visible')) {
            const bookedSlots = $('#' + current_step + ' #bookslots').val();
            if (!bookedSlots) {
                $('#' + current_step).find('.select-slots').html('<p class="text-sm text-red-600 font-medium mt-1 p-4 border border-gray-300 shadow-md rounded-l text-danger">Please select a date and at least one slot.</p>');
                isValid = false;
            } else {
                $('#' + current_step).find('.select-slots').empty();
            }
        }

        // Loop through all required fields
        requiredFields.forEach(field => {
            if (field.tagName.toLowerCase() === 'select') {
                if (!field.value || field.value.trim() === "") {
                    field.classList.add('border-red-500');
                    if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('error-message')) {
                        const err = document.createElement('p');
                        err.className = 'error-message text-red-500 text-xs mt-1';
                        err.textContent = 'This field is required';
                        field.insertAdjacentElement('afterend', err);
                    }
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                    const err = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                        ? field.nextElementSibling
                        : null;
                    if (err) err.remove();
                }
                return;
            }

            if (field.type === 'checkbox') {
                const checkboxes = step.querySelectorAll(`input[name="${field.name}"]`);
                const checked = Array.from(checkboxes).some(c => c.checked);

                const otherCheckbox = Array.from(checkboxes).find(cb => cb.value.toLowerCase() === "other");
                const lastCheckbox = checkboxes[checkboxes.length - 1];

                const targetElement = otherCheckbox ? otherCheckbox.parentElement : lastCheckbox.parentElement;

                const oldErr = targetElement.parentElement.querySelector('.checkbox-error-message');
                if (oldErr) oldErr.remove();

                if (!checked) {
                    checkboxes.forEach(cb => cb.classList.add('border-red-500'));
                    const err = document.createElement('p');
                    err.className = 'checkbox-error-message text-red-500 text-xs mt-1';
                    err.textContent = 'This field is required';
                    targetElement.insertAdjacentElement('afterend', err);
                    isValid = false;
                } else {
                    checkboxes.forEach(cb => cb.classList.remove('border-red-500'));
                }

            } else if (field.type === 'radio') {
                const radios = step.querySelectorAll(`input[name="${field.name}"]`);
                const checked = Array.from(radios).some(r => r.checked);

                const otherRadio = Array.from(radios).find(r => r.value.toLowerCase() === "other");
                const lastRadio = radios[radios.length - 1];
                const targetElement = otherRadio ? otherRadio.parentElement : lastRadio.parentElement;

                const oldErr = targetElement.parentElement.querySelector('.radio-error-message');
                if (oldErr) oldErr.remove();

                if (!checked) {
                    radios.forEach(rb => rb.classList.add('border-red-500'));
                    const err = document.createElement('p');
                    err.className = 'radio-error-message text-red-500 text-xs mt-1';
                    err.textContent = 'This field is required';
                    targetElement.insertAdjacentElement('afterend', err); // ✅ below "Other" or last option
                    isValid = false;
                } else {
                    radios.forEach(rb => rb.classList.remove('border-red-500'));
                }
                return;
            }

            else if (!field.value.trim()) {
                field.classList.add('border-red-500');
                if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('error-message')) {
                    const err = document.createElement('p');
                    err.className = 'error-message text-red-500 text-xs mt-1';
                    err.textContent = 'This field is required';
                    field.insertAdjacentElement('afterend', err);
                }
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
                const err = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message') ? field.nextElementSibling : null;
                if (err) err.remove();
            }
        });

        return isValid;
    }



    function handleNextButtonClick() {
        const currentStepElement = steps[currentSteps - 1];
        if (validateRequiredFields(currentStepElement)) {
            if (currentSteps < steps.length) {
                currentStepElement.style.display = 'none';
                steps[currentSteps].style.display = 'block';
                currentSteps++;
                prevButton.style.display = 'inline-block';
                if (currentSteps === steps.length) {
                    nextButtons.forEach(btn => btn.style.display = 'none');
                    submitButtons.forEach(btn => btn.style.display = 'inline-block');
                }
            }
        } else {
            return;
        }
    }

    function handlePreviousButtonClick() {
        nextButtons.forEach(btn => btn.style.display = 'inline-block');
        submitButtons.forEach(btn => btn.style.display = 'none');

        if (currentSteps === 2) {
            prevButton.style.display = 'none';
        }

        if (currentSteps > 1) {
            steps[currentSteps - 1].style.display = 'none';
            steps[currentSteps - 2].style.display = 'block';
            currentSteps--;
        }
    }

    function handleSubmitButtonClick(event) {
        let isFormValid = true;

        steps.forEach(step => {
            if (!validateRequiredFields(step)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            event.preventDefault();
        }
    }

    /*------ Staff Select On Change ------*/
    function get_services_staff() {
        var selectslots = document.querySelector('.select-slots');
        var serviceId = ServiceStaffCode.value;
        $('.availibility,.calendar-wrap,.remove-all-slots').addClass('hidden');
        $('input[name="bookslots"]').val('');
        $('.slot-item').remove();
        $('.showMessage').addClass('hidden');
        $.ajax({
            url: '/get/services/staff',
            type: 'GET',
            data: {
                service_id: serviceId
            },
            dataType: 'json',
            success: function (response) {
                var select_service_staff = document.querySelector('.select_service_vendor');
                var staffSelect = document.querySelector('.service_vendor_form');
                var calendarHidden = document.querySelector('.calendar-wrap');

                staffSelect.innerHTML = '';
                if (serviceId != '' && response && response.length > 0) {
                    console.log('frontend Value', 1);

                    staffSelect.disabled = false;
                    staffSelect.style.display = 'block';
                    select_service_staff.classList.remove('hidden');
                    calendarHidden.classList.add('hidden');
                    selectslots.classList.add('hidden');
                    staffSelect.required = true;
                    staffSelect.classList.remove('border-red-500');
                    const err = staffSelect.parentNode.querySelector('.error-message');
                    if (err) err.remove();
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = '--- Select Vendor ---';
                    staffSelect.appendChild(defaultOption);
                    $('label[for="staff"]').removeClass('hidden');

                    response.forEach(function (staff) {
                        const option = document.createElement('option');
                        option.value = staff.id;
                        option.textContent = staff.name;
                        staffSelect.appendChild(option);
                    });
                    const placeholder = staffSelect.parentNode.querySelector(".vendor-placeholder");
                    if (placeholder) placeholder.innerHTML = "";
                } else if (serviceId != '' && response && response.length === 0) {
                    staffSelect.disabled = true;
                    staffSelect.style.display = "none";
                    select_service_staff.classList.remove('hidden');
                    calendarHidden.classList.add('hidden');
                    selectslots.classList.add('hidden');
                    staffSelect.required = false;
                    $('label[for="staff"]').addClass('hidden');
                    let placeholder = staffSelect.parentNode.querySelector(".vendor-placeholder");
                    if (placeholder) {
                        placeholder.innerHTML = "";

                        let noVendorText = document.createElement("div");
                        noVendorText.textContent = "No Vendor Assigned For This Service";
                        noVendorText.classList.add("no-vendor-text");
                        placeholder.appendChild(noVendorText);

                        staffSelect.classList.remove('border-red-500');
                        const err = staffSelect.parentNode.querySelector('.error-message');
                        if (err) err.remove();
                    }
                } else {
                    console.log('frontend Value', 0);

                    staffSelect.disabled = false;
                    staffSelect.required = true;
                    staffSelect.style.display = "none";
                    select_service_staff.classList.add('hidden');
                    calendarHidden.classList.add('hidden');
                    selectslots.classList.add('hidden');
                    staffSelect.innerHTML = "";
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = '--- Select Vendor ---';
                    staffSelect.appendChild(defaultOption);
                    staffSelect.classList.remove('border-red-500');
                    const err = staffSelect.parentNode.querySelector('.error-message');
                    if (err) err.remove();
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);
            }
        });
    }

    nextButtons.forEach(button => button.addEventListener('click', handleNextButtonClick));
    prevButton.addEventListener('click', handlePreviousButtonClick);
    submitButtons.forEach(button => button.addEventListener('click', handleSubmitButtonClick));
    if (ServiceStaffCode) {
        ServiceStaffCode.addEventListener('change', get_services_staff);
    }

    $(document).ready(function () {
        let formAction = document.querySelector('form').action;
        let urlParts = formAction.split('/');
        let formId = urlParts[urlParts.length - 1];
        let isSubmitting = false; // <-- flag

        // detect form submit
        $('form').on('submit', function () {
            isSubmitting = true;
        });

        $.ajax({
            url: '/get/session',
            method: 'GET',
            data: { formId: formId },
            success: function (response) {
                if (response.status === 'success') {
                    Object.keys(response['data']).forEach(function (parentKey) {
                        let value = response['data'][parentKey];

                        Object.keys(value).forEach(function (key) {
                            let formattedKey = key;
                            let nestedValue = value[key];
                            console.log("nestedValue:", nestedValue);

                            // always use nestedValue.value here
                            let val = (nestedValue && typeof nestedValue === "object" && "value" in nestedValue)
                                ? nestedValue.value
                                : nestedValue;

                            // ---------- input ----------
                            let inputElement = $('input[name="' + formattedKey + '"]');
                            if (inputElement.length > 0 && inputElement.attr('type') !== 'radio' && inputElement.attr('type') !== 'checkbox') {
                                inputElement.val(val);
                            }

                            // ---------- select ----------
                            let selectElement = $('select[name="' + formattedKey + '"]');
                            if (selectElement.length > 0) {
                                selectElement.val(val);
                                if (selectElement.hasClass('get_service_staff')) {
                                    setTimeout(function () {
                                        selectElement.val(val).trigger('change');
                                        get_services_staff();
                                    }, 500);
                                }
                                if (selectElement.hasClass('service_vendor_form')) {
                                    if (val != null) {
                                        setTimeout(function () {
                                            console.log(val);
                                            selectElement.val(val).trigger('change');
                                        }, 1000);
                                    }
                                }
                            }

                            // ---------- textarea ----------
                            let textareaElement = $('textarea[name="' + formattedKey + '"]');
                            if (textareaElement.length > 0) {
                                textareaElement.val(val);
                            }

                            // ---------- radio ----------
                            if (typeof val === "string") {
                                let radioElement = $('input[type="radio"][name="' + formattedKey + '"][value="' + val + '"]');
                                if (radioElement.length > 0) {
                                    radioElement.prop('checked', true);
                                }
                            }

                            // ---------- checkboxes ----------
                            let checkboxElements = $('input[type="checkbox"][name="' + formattedKey + '"');
                            if (checkboxElements.length > 0) {
                                let valuesToCheck = [];
                                if (Array.isArray(val)) {
                                    valuesToCheck = val;
                                } else if (typeof val === "string") {
                                    valuesToCheck = [val];
                                } else if (val === true || val === "true" || val === 1 || val === "1") {
                                    valuesToCheck = ["on"];
                                }

                                checkboxElements.prop("checked", false);
                                checkboxElements.each(function () {
                                    if (valuesToCheck.includes($(this).val())) {
                                        $(this).prop("checked", true);
                                    }
                                });
                            }

                            // ---------- special case bookslots ----------
                            if (key === 'bookslots') {
                                let inputElement = $('input[name="' + key + '"]');
                                if (inputElement.length > 0) {
                                    inputElement.val(val);
                                    if (val) {
                                        const $wrapper = $('.slot-list-wrapper');
                                        const decodedValue = val.replace(/&quot;/g, '"');
                                        const slots = JSON.parse(decodedValue);
                                        slots.forEach((slot, index) => {
                                            const { date, price, start, end, duration, staff_ids } = slot;
                                            const uniqueKey = `slot-${staff_ids[0]}-${index}`;
                                            const slotHTML = `
                                            <div class="slot-item flex justify-between items-center gap-4 border border-gray-300 rounded-md p-3 bg-white shadow-sm text-sm w-full sm:w-full" data-slot="${uniqueKey}">
                                                <div class="font-medium text-gray-800 flex-1">
                                                    <div>${date}</div>
                                                    <input type='hidden' name='staff_id' value='${staff_ids.join(",")}'>
                                                    <div class="text-xs text-gray-500">${start} → ${end}</div>
                                                    <div class="text-xs text-gray-500">Duration: ${duration}</div>
                                                </div>
                                                <div class="text-green-600 font-semibold whitespace-nowrap">${price}</div>
                                                <div class="text-red-500 cursor-pointer remove-slot ml-auto">&#10006;</div>
                                            </div>`;
                                            $($wrapper).append(slotHTML);
                                        });
                                        $('.remove-all-slots').removeClass('hidden');
                                    }
                                }
                            }
                        });
                    });
                }
            },
            error: function (error) {
                console.error('Error retrieving session data:', error);
            }
        });

        /*------ Save form data on page unload ------*/
        window.onbeforeunload = function (event) {
            if (isSubmitting) {
                // allow normal form submission, don't interfere
                return;
            }

            let formAction = document.querySelector('form').action;
            let urlParts = formAction.split('/');
            let formId = urlParts[urlParts.length - 1];
            let formElements = document.querySelector('form').elements;
            let dataToSave = {};

            Array.from(formElements).forEach(function (element) {
                if (element.name) {
                    if (element.type === "radio") {
                        if (element.checked) {
                            dataToSave[element.name] = { name: element.name, value: element.value };
                        }
                    } else if (element.type === "checkbox") {
                        if (!dataToSave[element.name]) {
                            dataToSave[element.name] = { name: element.name, value: [] };
                        }
                        if (element.checked) {
                            dataToSave[element.name].value.push(element.value);
                        }
                    } else {
                        dataToSave[element.name] = { name: element.name, value: element.value };
                    }
                }
            });

            let finalDataToSave = {
                formId: formId,
                data: dataToSave,
                _token: document.querySelector('meta[name="csrf-token"]').content
            };

            navigator.sendBeacon(
                '/store/session',
                new Blob([JSON.stringify(finalDataToSave)], { type: 'application/json' })
            );
        };
    });

    function isEmpty(value) {
        return (
            value === undefined ||
            value === null ||
            (typeof value === "string" && value.trim() === "") ||
            (Array.isArray(value) && value.length === 0)
        );
    }

    /*------ Check Slots based on buttons ------*/
    function checkSlots($context) {
        const steps = $context.find('.step');
        const activeStep = steps.filter(':visible');

        const bookslotsValue = $('#bookslots').val();
        const wrapper = activeStep.find('.slot-list-wrapper:visible');
        const serviceValue = $('#get_service_staff').val();
        const vendorValue = $('#service_vendor_form').val();
        const activeBtn = $context.find('.next:visible,.submit:visible');

        let valid = true;
        const slotCount = wrapper.find('.slot-item').length;
        if (wrapper.length > 0 && !isEmpty(serviceValue) && !isEmpty(vendorValue) && isEmpty(bookslotsValue) && slotCount === 0) {
            $('.select-slots').removeClass('hidden');

            valid = false;
        } else if (valid) {
            $('.select-slots').addClass('hidden');
            valid = true;
        }
    }

    /*------ On page load check data ------*/
    $(document).ready(function () {
        $('.form-navigation').each(function () {
            checkSlots($(this).closest('form, body'));
        });
    });

    $(document).on(
        'click change',
        '.slot-card, .add-slot, #get_service_staff, #service_vendor_form , .next,.submit,.previous',
        function () {
            const $section = $(this).closest('form, body');
            setTimeout(() => {
                checkSlots($section);
            }, 0);
        }
    );
    $(document).on("change", ".other_checkbox", function () {
        let relatedInput = $(this).closest(".mb-6").find(".other_checkbox_input");
        if ($(this).is(":checked")) {
            relatedInput.removeClass("hidden");
        } else {
            relatedInput.addClass("hidden").val("");
        }
    });

    $(document).on("change", "input[type=radio]", function () {
        let radiobutton = $(this).val();
        let relatedInput = $(this).closest(".mb-6").find(".other_radiobox_input");

        if (radiobutton === '__other__') {
            relatedInput.removeClass('hidden');
        } else {
            relatedInput.addClass('hidden').val('');
        }
    });
    setTimeout(function () {
        $(".radio_other:checked").each(function () {
            let radiobutton = $(this).val();
            let relatedInput = $(this).closest(".mb-6").find(".other_radiobox_input");
            if (radiobutton == '__other__') {
                relatedInput.removeClass("hidden");
            } else {
                relatedInput.addClass("hidden").val('');
            }
        });
        $(".other_checkbox").each(function () {
            let relatedInput = $(this).closest(".mb-6").find(".other_checkbox_input");

            if ($(this).is(":checked")) {
                relatedInput.removeClass("hidden");
            }
        });
    }, 1000);
});


document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("bookingTemplateModal");
    const bookingSection = document.querySelector(".booking-section");
    const bookingHeader = document.querySelector(".booking-header");
    const noBookings = document.querySelector(".no-bookings");
    const openBtns = document.querySelectorAll(".open-popup");
    const closeBtn = document.getElementById("closeModal");

    const fbEditor = $("#build-wrap");
    let formBuilderInstance;

    function initFormBuilder(data = []) {
        if (!fbEditor.length || formBuilderInstance) return;

        const fields = [
            {
                label: "Next Step",
                attrs: { type: "newsection" },
                icon: '<i class="fa-solid fa-section"></i>',
            },
            {
                label: "ShortCode",
                attrs: { type: "shortcodeblock" },
                icon: '<i class="fa fa-code"></i>',
            },
        ];

        const templates = {
            newsection: (fieldData) => ({
                field: `<div id="${fieldData.name}" class="section border p-2 rounded mb-2">
                          <div class="section-content"></div>
                          <div class="section-navigation mt-2">
                            <button class="prev-btn hidden">Previous</button>
                            <button class="next-btn">Next</button>
                          </div>
                        </div>`,
            }),
            shortcodeblock: (fieldData) => {
                const uniqueId = "shortcode-select-" + Date.now();
                return {
                    field: `<div class="shortcode-block mb-2">
                              <select id="${uniqueId}" name="shortcode" class="w-full border border-gray-300 rounded p-2">
                                <option value="">Select a shortcode</option>
                              </select>
                            </div>`,
                    onRender: () => {
                        $.get("/admin/shortcodes/list", (shortcodes) => {
                            shortcodes.forEach((code) =>
                                $(`#${uniqueId}`).append(
                                    `<option value="[${code}]">[${code}]</option>`
                                )
                            );
                        });
                    },
                    onSave: () => {
                        fieldData.value = $(`#${uniqueId}`).val() || "";
                    },
                };
            },
        };

        formBuilderInstance = fbEditor.formBuilder({
            fields,
            templates,
            disableFields: ["autocomplete", "button"],
            controlPosition: "left",
        });

        if (data.length) formBuilderInstance.actions.setData(data);
    }

    function resetModal() {
        const form = document.getElementById("templateForm");
        if (form) form.reset();
        if (formBuilderInstance) formBuilderInstance.actions.setData([]);
        const errorMessageElement = document.getElementById("bookingTemplatesname-error");
        if (errorMessageElement) errorMessageElement.remove();
    }

    // Open Template Form
    if (openBtns.length > 0) {
        openBtns.forEach((btn) => {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                if (bookingSection) bookingSection.classList.add("hidden");
                if (bookingHeader) bookingHeader.classList.add("hidden");
                if (noBookings) noBookings.classList.add("hidden");

                if (modal) modal.classList.remove("hidden");
                if (!formBuilderInstance) initFormBuilder();
            });
        });
    }

    // Close Template Form
    if (closeBtn) {
        closeBtn.addEventListener("click", () => {
            if (modal) modal.classList.add("hidden");
            if (bookingSection) bookingSection.classList.remove("hidden");
            if (bookingHeader) bookingHeader.classList.remove("hidden");
            if (noBookings) noBookings.classList.remove("hidden");
            resetModal();
        });
    }

    const inputElement = document.getElementById("bookingTemplatesname");

    // Remove error on typing
    if (inputElement) {
        inputElement.addEventListener("input", () => {
            const oldErr = document.getElementById("bookingTemplatesname-error");
            if (oldErr) oldErr.remove();
        });
    }

    // Validation & Save
    document.addEventListener("click", function (e) {
        if (e.target.classList.contains("save-template")) {
            if (!inputElement) return;

            const name = inputElement.value.trim();
            if (!name) {
                const errorSpan = document.createElement("span");
                errorSpan.id = "bookingTemplatesname-error";
                errorSpan.style.color = "red";
                errorSpan.textContent = "The Template name cannot be empty.";
                inputElement.parentNode.appendChild(errorSpan);
                inputElement.focus();
                return;
            }

            const data = formBuilderInstance
                ? formBuilderInstance.actions.getData()
                : [];

            $.post("/admin/template/save", {
                _token: document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
                templatename: name,
                templatestatus: document.querySelector(".select-template-status")?.value || "",
                vendorid: document.querySelector(".select-template-vendor")?.value || "",
                data: data,
            })
                .done(() => {
                    if (modal) modal.classList.add("hidden");
                    if (bookingSection) bookingSection.classList.remove("hidden");
                    if (bookingHeader) bookingHeader.classList.remove("hidden");
                    if (noBookings) noBookings.classList.remove("hidden");
                    resetModal();

                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        positionClass: "toast-top-right",
                        preventDuplicates: false,
                        onclick: null,
                        showDuration: "300",
                        hideDuration: "500",
                        timeOut: "3000",
                    };

                    toastr.success("Booking Template saved successfully!");
                })
                .fail((err) => {
                    console.error(err);
                    toastr.error("Error saving template.");
                });
        }
    });
});
