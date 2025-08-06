document.addEventListener("DOMContentLoaded", function () {
    let currentSteps = 1;
    const steps = document.querySelectorAll('.step');
    const prevButton = document.querySelector('.previous');
    const nextButtons = document.querySelectorAll('.next');
    const submitButton = document.querySelector('.submit');
    const form = document.querySelector('form');
    const ServiceStaffCode = document.querySelector('.get_service_staff');
    console.log('ServiceStaffCode' + ServiceStaffCode);
    if (steps.length <= 1) {
        // Handle case where there is only 1 step
    }

    if (!steps.length || !prevButton || !nextButtons.length || !submitButton) {
        console.error('Required elements not found!');
        return; // Exit if elements aren't found
    }

    // Function to validate required fields
    function validateRequiredFields(step) {
        const requiredFields = step.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (field.type === 'checkbox') {
                const checkboxGroup = step.querySelectorAll(`input[name="${field.name}"]`);
                const checkedCheckboxes = Array.from(checkboxGroup).filter(checkbox => checkbox.checked);

                if (checkedCheckboxes.length === 0) {
                    field.classList.add('border-red-500'); // Add red border to indicate error
                    let errorMessage = document.querySelector('.checkbox-error-message');

                    // Check if error message already exists, if not, create and append
                    if (!errorMessage) {
                        errorMessage = document.createElement('p');
                        errorMessage.classList.add('checkbox-error-message', 'text-red-500', 'text-xs', 'mt-1');
                        errorMessage.textContent = 'This field is required';
                        field.parentElement.appendChild(errorMessage);
                    } else {
                        errorMessage.textContent = 'This field is required'; // Update text if it already exists
                    }
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500'); // Remove red border if valid
                    let errorMessage = field.parentElement.querySelector('.checkbox-error-message');
                    if (errorMessage) {
                        errorMessage.remove(); // Remove error message if the checkbox is checked
                    }
                }
            }
            // Check for radio buttons
            else if (field.type === 'radio') {
                const radioGroup = step.querySelectorAll(`input[name="${field.name}"]`);
                const isChecked = Array.from(radioGroup).some(radio => radio.checked);

                if (!isChecked) {
                    field.classList.add('border-red-500');
                    let errorMessage = document.querySelector('.radio-error-message');
                    if (!errorMessage) {
                        errorMessage = document.createElement('p');
                        errorMessage.classList.add('radio-error-message', 'text-red-500', 'text-xs', 'mt-1');
                        errorMessage.textContent = 'This field is required';
                        field.parentElement.appendChild(errorMessage);
                    } else {
                        errorMessage.textContent = 'This field is required'; // Update text if it already exists
                    }
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500'); // Remove red border if valid
                    let errorMessage = field.parentElement.querySelector('.radio-error-message');
                    if (errorMessage) {
                        errorMessage.remove(); // Remove error message if the radio is selected
                    }
                }
            }

            else if (field.type === 'email') {
                if (!field.checkValidity()) {
                    field.classList.add('border-red-500');
                    let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                        ? field.nextElementSibling
                        : document.createElement('p');
                    if (!errorMessage.classList.contains('error-message')) {
                        errorMessage.classList.add('error-message', 'text-red-500', 'text-xs', 'mt-1');
                        errorMessage.textContent = 'Please enter a valid email address';
                        field.insertAdjacentElement('afterend', errorMessage);
                    }
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                    let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                        ? field.nextElementSibling
                        : null;
                    if (errorMessage) {
                        errorMessage.remove(); // Remove error message if the email is valid
                    }
                }
            }

            // Handle regular text inputs and other fields
            else if (!field.value.trim()) {
                field.classList.add('border-red-500'); // Add red border to indicate error
                let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                    ? field.nextElementSibling
                    : document.createElement('p');
                if (!errorMessage.classList.contains('error-message')) {
                    errorMessage.classList.add('error-message', 'text-red-500', 'text-xs', 'mt-1');
                    errorMessage.textContent = 'This field is required';
                    field.insertAdjacentElement('afterend', errorMessage);
                }
                isValid = false;
            } else {
                field.classList.remove('border-red-500'); // Remove red border if valid
                let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                    ? field.nextElementSibling
                    : null;
                if (errorMessage) {
                    errorMessage.remove(); // Remove error message if the field has value
                }
            }

        });
        return isValid;
    }

    function handleNextButtonClick() {
        const currentStepElement = steps[currentSteps - 1];

        // Validate required fields before moving to the next step
        if (validateRequiredFields(currentStepElement)) {
            if (currentSteps < steps.length) {
                currentStepElement.style.display = 'none';
                steps[currentSteps].style.display = 'block';
                currentSteps++;
                prevButton.style.display = 'inline-block';
                const currentNextButton = steps[currentSteps - 1].querySelector('.next');
                if (currentSteps === steps.length) {
                    // Last step, hide Next and show Submit
                    document.querySelector('.next').style.display = 'none';
                    submitButton.style.display = 'inline-block';
                }
            }
        } else {
            // Prevent navigation if validation fails
            return;
        }
    }

    function handlePreviousButtonClick() {
        document.querySelector('.next').style.display = 'inline-block';
        submitButton.style.display = 'none'; // Hide Submit when navigating back
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

        // Validate all steps on submit
        steps.forEach(step => {
            if (!validateRequiredFields(step)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            event.preventDefault();
        }
    }
    nextButtons.forEach(button => {
        button.addEventListener('click', handleNextButtonClick);
    });

    function get_services_staff() {
        var serviceId = ServiceStaffCode.value;
        $.ajax({
            url: '/get/services/staff',
            type: 'GET',
            data: {
                service_id: serviceId
            },
            dataType: 'json',
            success: function (response) {
                console.log('Services Staff' + response);
                var select_service_staff = document.querySelector('.select_service_vendor');
                var staffSelect = document.querySelector('.service_vendor_form');
                var calendarHidden = document.querySelector('.calendar-wrap ');

                staffSelect.innerHTML = '';
                var defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = '---Select Vendor---';
                staffSelect.appendChild(defaultOption);
                if (response && response.length > 0) {
                    staffSelect.disabled = false;
                    select_service_staff.classList.remove('hidden');

                    // Add each staff member to the dropdown
                    response.forEach(function (staff) {
                        var option = document.createElement('option');
                        option.value = staff.id;
                        option.textContent = staff.name;
                        staffSelect.appendChild(option);
                    });
                } else {
                    // If no staff available, disable dropdown and show a no staff option
                    staffSelect.disabled = true;
                    select_service_staff.classList.add('hidden');
                    calendarHidden.classList.add('hidden');

                    var noStaffOption = document.createElement('option');
                    noStaffOption.value = '';
                    noStaffOption.textContent = 'No staff available';
                    staffSelect.appendChild(noStaffOption);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX error:", status, error);
            }
        });
    }
    prevButton.addEventListener('click', handlePreviousButtonClick);
    submitButton.addEventListener('click', handleSubmitButtonClick);
    ServiceStaffCode.addEventListener('change', get_services_staff);
});
