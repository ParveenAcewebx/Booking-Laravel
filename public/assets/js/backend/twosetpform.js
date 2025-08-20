document.addEventListener("DOMContentLoaded", function () {
     /* ================================== Function for booking form =============================*/
    function twostepform(){
        let currentSteps = 1;
        const steps = document.querySelectorAll('.step');
        const prevButton = document.querySelector('.previous');
        const nextButtons = document.querySelectorAll('.next');
        const submitButtons = document.querySelectorAll('.submit');
        const form = document.querySelector('form');
        const ServiceStaffCode = document.querySelector('.get_service_staff');
        console.log('ServiceStaffCode', ServiceStaffCode);
        if (!steps.length || !prevButton || !nextButtons.length || !submitButtons.length) {
            console.error('Required elements not found!');
            return; 
        }
         /* ================================== Form validation =============================*/
        function validateRequiredFields(step) {
            const requiredFields = step.querySelectorAll('[required]');
            let isValid = true;
            requiredFields.forEach(field => {
                if (field.type === 'checkbox') {
                    const checkboxGroup = step.querySelectorAll(`input[name="${field.name}"]`);
                    const checkedCheckboxes = Array.from(checkboxGroup).filter(checkbox => checkbox.checked);
                    if (checkedCheckboxes.length === 0) {
                        field.classList.add('border-danger');
                        let errorMessage = field.parentElement.querySelector('.checkbox-error-message');
                        if (!errorMessage) {
                            errorMessage = document.createElement('p');
                            errorMessage.classList.add('checkbox-error-message', 'text-danger', 'text-xs', 'mt-1');
                            errorMessage.textContent = 'This field is required';
                            field.parentElement.appendChild(errorMessage);
                        }
                        isValid = false;
                    } else {
                        field.classList.remove('border-danger');
                        let errorMessage = field.parentElement.querySelector('.checkbox-error-message');
                        if (errorMessage) errorMessage.remove();
                    }
                } else if (field.type === 'radio') {
                    const radioGroup = step.querySelectorAll(`input[name="${field.name}"]`);
                    const isChecked = Array.from(radioGroup).some(radio => radio.checked);
                    if (!isChecked) {
                        field.classList.add('border-danger');
                        let errorMessage = field.parentElement.querySelector('.radio-error-message');
                        if (!errorMessage) {
                            errorMessage = document.createElement('p');
                            errorMessage.classList.add('radio-error-message', 'text-danger', 'text-xs', 'mt-1');
                            errorMessage.textContent = 'This field is required';
                            field.parentElement.appendChild(errorMessage);
                        }
                        isValid = false;
                    } else {
                        field.classList.remove('border-danger');
                        let errorMessage = field.parentElement.querySelector('.radio-error-message');
                        if (errorMessage) errorMessage.remove();
                    }
                } else if (field.type === 'email') {
                    if (!field.checkValidity()) {
                        field.classList.add('border-danger');
                        let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                            ? field.nextElementSibling
                            : document.createElement('p');
                        if (!errorMessage.classList.contains('error-message')) {
                            errorMessage.classList.add('error-message', 'text-danger', 'text-xs', 'mt-1');
                            errorMessage.textContent = 'Please enter a valid email address';
                            field.insertAdjacentElement('afterend', errorMessage);
                        }
                        isValid = false;
                    } else {
                        field.classList.remove('border-danger');
                        let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                            ? field.nextElementSibling
                            : null;
                        if (errorMessage) errorMessage.remove();
                    }
                } else if (!field.value.trim()) {
                    field.classList.add('border-danger');
                    let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                        ? field.nextElementSibling
                        : document.createElement('p');
                    if (!errorMessage.classList.contains('error-message')) {
                        errorMessage.classList.add('error-message', 'text-danger', 'text-xs', 'mt-1');
                        errorMessage.textContent = 'This field is required';
                        field.insertAdjacentElement('afterend', errorMessage);
                    }
                    isValid = false;
                } else {
                    field.classList.remove('border-danger');
                    let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message')
                        ? field.nextElementSibling
                        : null;
                    if (errorMessage) errorMessage.remove();
                }
            });
            return isValid;
        }
          /* ================================== Handle next button =============================*/
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
                        $('.submit').removeClass('d-none');
                    }
                }
            } else {
                return; 
            }
        }
         /* ================================== Handle previous button =============================*/
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
        /* ================================== Handel submit button =============================*/
        function handleSubmitButtonClick(event) {
            let isFormValid = true;
            steps.forEach(step => {
                if (!validateRequiredFields(step)) {
                    isFormValid = false;
                }
            });
            if (!isFormValid) {
                event.preventDefault();
                console.warn("Form validation failed!");
            }
        }
        /* ================================== call function on click button =============================*/
        nextButtons.forEach(button => button.addEventListener('click', handleNextButtonClick));
        prevButton.addEventListener('click', handlePreviousButtonClick);
        submitButtons.forEach(button => button.addEventListener('click', handleSubmitButtonClick));
        function isEmpty(value) {
            return (
                value === undefined ||
                value === null ||
                (typeof value === "string" && value.trim() === "") ||
                (Array.isArray(value) && value.length === 0)
            );
        }
        /* ================================== slote validation  =============================*/
        function checkSlots($context) {
            const $wrapper = $context.find('.slot-list-wrapper:visible');
            const bookslotsValue = $('#bookslots').val();
            const serviceValue = $('#get_service_staff').val();
            const vendorValue = $('#service_vendor_form').val();
            const $activeBtn = $context.find('.next:visible, .submit:visible');
            let valid = true;
            if (!isEmpty(serviceValue) && !isEmpty(vendorValue) && isEmpty(bookslotsValue)) {
                $('.select-slots').removeClass('d-none');
                valid = false;
            } else {
                $('.select-slots').addClass('d-none');
                valid = true;
            }
            $activeBtn.prop('disabled', !valid);
        }
     /* ================================== Slot validation =============================*/
        $(document).ready(function () {
            $('.form-navigation').each(function () {
                checkSlots($(this).closest('form, body'));
            });
        });
        /* ================================== On load check slots validation =============================*/
        $(document).on(
            'click change',
            '.slot-card, .add-slot, .remove-slot, .remove-all-slots, #get_service_staff, #service_vendor_form',
            function () {
                const $section = $(this).closest('form, body');
                setTimeout(() => {
                    checkSlots($section);
                }, 0);
            }
        );
    }
 /* ================================== Onclick load template and run function =============================*/
    $('#loadTemplateBtn').on('click',function(){           
        setTimeout(function(){
            var templateId = $("#bookingTemplateId").val();
            if(templateId){
                twostepform();  
            }
        }, 500); 
    });

});