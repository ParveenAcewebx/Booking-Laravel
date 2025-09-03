document.addEventListener("DOMContentLoaded", function () {
   /* ================================== Get service data   =============================*/

   $(document).on("input change", "input, select", function () {
      const field = this;
      field.classList.remove('border-danger');
      const $field = $(field);
      $field.siblings('.error-message').remove();
      $field.closest('.form-group, .mb-3').find('.error-message').remove();
      if (field.type === 'checkbox') {
         $field.closest('.form-group, .mb-3').find('.checkbox-error-message').remove();
      }
      if (field.type === 'radio') {
         $field.closest('.form-group, .mb-3').find('.radio-error-message').remove();
      }
      if (field.classList.contains('service_vendor_form') && field.value) {
         const placeholder = $field.closest('.form-group, .mb-3').find('.vendor-placeholder');
         if (placeholder.length) {
            placeholder.html('');
         }
      }
   });
   function get_services_staff(selectedvalue) {

      $('.slot-list-wrapper').empty();
      $('.calendar-wrap, .remove-all-slots').addClass('d-none');

      $('#bookslots').val('');
      $('.select-slots').css('display', 'none');

      var serviceId = selectedvalue.value;
      if (serviceId === '') {
         $('.no-vendor-msg').addClass('d-none');
      } else {
         $('.no-vendor-msg').removeClass('d-none');

      }
      var selectedStaff = document.querySelector('.selected_vendor').value;
      $('.vendor-loder').removeClass('d-none');
        $('.showMessage').addClass('d-none');
      $.ajax({
         url: '/get/services/staff',
         type: 'GET',
         data: {
            service_id: serviceId
         },
         dataType: 'json',
         success: function (response) {
            $('.vendor-loder').addClass('d-none');
            var select_service_staff = document.querySelector('.select_service_vendor');
            var staffSelect = document.querySelector('.service_vendor_form');
            var bookingcalendar = document.querySelector('.calendar-wrap');
            staffSelect.innerHTML = '';
            var defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '---Select Vendor---';
            staffSelect.appendChild(defaultOption);
            if (response && response.length > 0) {
               var existingMsg = document.querySelector('.no-vendor-msg');
               if (existingMsg) {
                  existingMsg.remove();
               }
               staffSelect.disabled = false;
               select_service_staff.classList.remove('d-none');
               // Add each staff member to the dropdown
               response.forEach(function (staff) {
                  var option = document.createElement('option');
                  option.value = staff.id;
                  option.textContent = staff.name;
                  staffSelect.appendChild(option);
                  staffSelect.setAttribute('required', '');
               });
               if (selectedStaff) {
                  var options = staffSelect.querySelectorAll('option');
                  options.forEach(function (option) {
                     if (option.value == selectedStaff) {
                        option.selected = true;
                     }
                  });
               }
            } else {
               if (serviceId != '' && response && response.length === 0) {
                  var vendorPlaceholder = document.querySelector('.select_service_vendor');
                  if ($('.no-vendor-msg').length === 0) {
                     vendorPlaceholder.insertAdjacentHTML('beforebegin', '<p class="no-vendor-msg">No Vendor available for this service.</p>');
                  }
               }
               staffSelect.disabled = true;
               $('.showMessage').addClass('d-none');
               select_service_staff.classList.add('d-none');
               bookingcalendar.classList.add('d-none');
               staffSelect.removeAttribute('required');
               var noStaffOption = document.createElement('option');
               noStaffOption.value = '';
               noStaffOption.textContent = 'No Vendor available';
               staffSelect.appendChild(noStaffOption);
            }
         },
         error: function (xhr, status, error) {
            console.error("AJAX error:", status, error);
         }
      });

      /* ================================== Reset callender data   =============================*/
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
      let activeCalendar = null;

      //  $(document).on("click", "#calendar td.available", function () {
      //      if (!activeCalendar) return;
      //      activeCalendar.clickDay(this);
      //  });

      const availableDates = typeof availabledatesArray !== 'undefined' ? availabledatesArray : [];

      $('.service_vendor_form').on('change', function () {
         let selectedValue = $(this).val();
         $('.slot-list-wrapper').empty();
         $('.remove-all-slots').addClass('d-none');
         $('.select-slots').css('display', 'none');
         $('#bookslots').val('');
         if (selectedValue === "") {
            $('.calendar-wrap').addClass('d-none');
            selectedValue = 0;
         } else {
            selectedValue = selectedValue;
            // $('.calendar-wrap').removeClass('d-none');
         }
         $.ajax({
            url: '/get/vendor/get_booking_calender',
            type: 'GET',
            data: {
               vendor_id: selectedValue
            },
            dataType: 'json',
            success: function (response) {
               $('.availibility').addClass('d-none');

               if (response.success === true && response.data && response.data[0]) {
                  const workondayoff = response.data;
                  const workingDays = response.data.map(item => item.Working_day);

                  resetCalendar();

                  // Destroy previous calendar instance before creating new
                  if (activeCalendar) {
                     activeCalendar.destroy();
                     activeCalendar = null;
                  }

                  if (selectedValue != 0) {

                  const anyAvailableDates = workondayoff.some(staff => {
                        const wd = staff.Working_day || {};
                        return Object.keys(wd).some(dayName => {
                           const slot = wd[dayName];
                           return slot && slot.start && !slot.start.includes("00:00:00");
                        });
                  });

                  if (anyAvailableDates) {
                        $('.showMessage').addClass('d-none');
                        activeCalendar = new Calendar(workingDays, workondayoff);
                        $('.availibility').addClass('d-none');
                        $('.calendar-wrap').removeClass('d-none');
                        const bookslotsVal = $('#bookslots').val();
                        const hasSlots = bookslotsVal && bookslotsVal.trim() !== '';
                        $('.remove-all-slots').toggleClass('d-none', !hasSlots);
                  } else {
                        activeCalendar = new Calendar([], []);
                        $('.calendar-wrap').addClass('d-none');
                        $('.availibility').removeClass('d-none');
                        $('.showMessage').removeClass('d-none');
                  }
                  } else {
                     activeCalendar = new Calendar([], []);
                     $('.calendar-wrap').addClass('d-none');
                     $('.availibility').removeClass('d-none');
                  }
               } else {
                  const workondayoff = 0;
                  const workingDays = 0;
                  resetCalendar();

                  if (activeCalendar) {
                     activeCalendar.destroy();
                     activeCalendar = null;
                  }

                  activeCalendar = new Calendar(workingDays, workondayoff);
               }
            },
         });
      });

      /* ================================== Add staff data in the callender   =============================*/
      class Calendar {
         constructor(workingDays, workondayoff) {
            this.workingDays = workingDays || [];
            this.workOnoff = workondayoff || [];
            const now = new Date();
            this.year = now.getFullYear();
            this.month = now.getMonth();
            this.draw();
            this.addNavigationListeners();
            this.addDayClickListener();
         }

         draw() {
            this.drawDays();
         }

         drawDays() {
            const monthNames = [
               "January", "February", "March", "April", "May", "June",
               "July", "August", "September", "October", "November", "December"
            ];

            const startDay = new Date(this.year, this.month, 1).getDay();
            const totalDays = new Date(this.year, this.month + 1, 0).getDate();

            const monthNameElem = document.getElementById("month-name");
            if (monthNameElem) {
               monthNameElem.textContent = `${monthNames[this.month]} ${this.year}`;
            }
            const days = document.querySelectorAll('#calendar td');
            days.forEach(cell => {
               cell.innerHTML = '';
               cell.classList.remove('available', 'disabled', 'selected');
               cell.style.backgroundColor = '';
               cell.style.pointerEvents = '';
            });
            const currentDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
            let dayIndex = 0;

            // Flatten dayoffs for all staff
            const allDayoffs = this.workOnoff.flatMap(staff =>
               (staff.Dayoff || []).flat().map(d => new Date(d.date).toDateString())
            );
            const hasAnyDayoff = allDayoffs.length > 0;

            for (let i = 0; i < days.length; i++) {
               const cell = days[i];
               if (i >= startDay && dayIndex < totalDays) {
                  const dayNum = ++dayIndex;
                  const fullDate = `${this.year}-${String(this.month + 1).padStart(2, "0")}-${String(dayNum).padStart(2, "0")}`;
                  const dayDate = new Date(this.year, this.month, dayNum);
                  cell.innerHTML = dayNum;
                  const dayName = dayDate.toLocaleString('en-us', { weekday: 'long' }).toLowerCase();

                  // Collect which staff works on this weekday
                  const staffWorking = this.workOnoff.map(staff => {
                     const wd = staff.Working_day || {};
                     const slot = wd[dayName];
                     return slot && slot.start && !slot.start.includes("00:00:00");
                  });

                  const atLeastOneWorks = staffWorking.includes(true);
                  let isDisabled = false;

                  if (dayDate < currentDate) {
                     isDisabled = true;
                  } else if (!atLeastOneWorks) {
                     isDisabled = true;
                  } else if (hasAnyDayoff) {
                     const staffOnDayoff = this.workOnoff.filter(staff =>
                        (staff.Dayoff || []).flat().some(d => new Date(d.date).toDateString() === dayDate.toDateString())
                     );
                     if (staffOnDayoff.length > 0) {
                        const othersWork = this.workOnoff.some(staff => {
                           const wd = staff.Working_day || {};
                           const slot = wd[dayName];
                           return slot && slot.start && !slot.start.includes("00:00:00") &&
                              !(staff.Dayoff || []).flat().some(d => new Date(d.date).toDateString() === dayDate.toDateString());
                        });
                        if (!othersWork) {
                           isDisabled = true;
                        }
                     }
                  }

                  if (isDisabled) {
                     cell.classList.add("disabled");
                     cell.style.backgroundColor = "#d3d3d3";
                     cell.style.pointerEvents = "none";
                  } else {
                     cell.classList.add("available");
                     cell.style.backgroundColor = "rgb(18 163 46)";
                  }
               }
            }
         }

         /* ================================== Calender button   =============================*/
         addNavigationListeners() {
            const pre = document.querySelector('.pre-button');
            const next = document.querySelector('.next-button');
            if (pre) pre.addEventListener('click', () => this.changeMonth(-1));
            if (next) next.addEventListener('click', () => this.changeMonth(1));
         }

         /* ================================== Calender change month  =============================*/
         changeMonth(direction) {
            this.month += direction;
            if (this.month < 0) {
               this.month = 11;
               this.year--;
            } else if (this.month > 11) {
               this.month = 0;
               this.year++;
            }
            this.drawDays();
         }

         addDayClickListener() {
            const calendar = document.getElementById('calendar');
            if (!calendar) return;
            const newCalendar = calendar.cloneNode(true);
            calendar.parentNode.replaceChild(newCalendar, calendar);
            newCalendar.addEventListener('click', e => {
               const dayElem = e.target;
               if (dayElem.tagName === 'TD' && dayElem.classList.contains("available")) {
                  this.clickDay(dayElem);
               }
            });
         }
         clickDay(dayElem) {
            document.querySelector('.selected')?.classList.remove('selected');
            const day = parseInt(dayElem.innerHTML);
            selectedDate = `${this.year}-${String(this.month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
            dayElem.classList.add('selected');
            this.BookeAslot(selectedDate);
            $('.select-slots').addClass('d-none');
         }


         /* ================================== Booke  slotes  =============================*/
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
                     const price = `${response.serviceCurrency}${response.price}`;
                     $('.availibility').removeClass('d-none');
                     const date = response.date;
                     const staffOffIds = response.staff_off_ids ? response.staff_off_ids.split(',').map(id => id.trim()) : [];
                     sessionsHTML += `<div class="date-section mb-3">
                            <h5 class="date-header text-lg font-semibold mb-2">${formattedDate}</h5>
                            <div class="d-flex gap-4 pb-2 overflow-auto mx-auto max-w-800px" style="scrollbar-width: thin;">
                            <div class="d-flex gap-4 pb-2 w-max min-w-full" style="-ms-overflow-style: none; scrollbar-width: thin;">`;
                     if (response && response.merged_slots?.length > 0) {
                        response.merged_slots.forEach((slot) => {
                           let slotStaffIds = slot.available_staff_ids;
                           if (staffOffIds.length > 0) {
                              slotStaffIds = slotStaffIds.filter(id => !staffOffIds.includes(String(id)));
                           }
                           sessionsHTML += `
                                    <div class="rounded-lg p-2 bg-white border border-gray-300 cursor-pointer m-2 slot-card" style="min-width: 170px; max-width: 100%;" 
                                        data-date="${formattedDate}" 
                                        data-price="${price}"
                                        data-start="${slot.start_time}"
                                        data-end="${slot.end_time}"
                                        data-duration=" ${formatDuration(response.duration)}"
                                        data-id="${slot.id}">
                                        <input type="hidden" name="staff_id" value="${slot.id}">
                                        <p class="text-sm mb-1 font-medium text-gray-700">${slot.start_time} - ${slot.end_time}</p>
                                        <p class="text-sm text-gray-600 m-0">Duration: ${formatDuration(response.duration)}</p>
                                        <p class="text-sm text-gray-600 m-0">Price: ${response.serviceCurrency}${response.price}</p>
                                    </div>`
                        });
                     } else {
                        sessionsHTML = `<p class="text-danger text-center">No available slots found for this date ${date}.</p>`;
                        $('.availibility').removeClass('d-none');
                        $('.availibility').removeClass('hidden');
                     }
                     sessionsHTML += `</div></div></div>`;
                  } else {
                     sessionsHTML = `<p class="text-sm text-red-500">No available slots found for this date.</p>`;
                  }
                  $('.availibility').html(sessionsHTML);
                  bindSlotClickEvent();
               },
               error: function () {
                  alert('Error fetching session details');
               }
            });
         }

         /* ================================== Destroy previous calendar listeners =============================*/
         destroy() {
            const calendar = document.getElementById('calendar');
            if (calendar) {
               const newCalendar = calendar.cloneNode(true);
               calendar.parentNode.replaceChild(newCalendar, calendar);
            }
         }
      }
      /* ================================== Calculate service duration function  =============================*/
      function formatDuration(minutes) {
         const hrs = Math.floor(minutes / 60);
         const mins = minutes % 60;
         let label = '';
         if (hrs > 0) label += hrs + ' hour' + (hrs > 1 ? 's' : '');
         if (hrs > 0 && mins > 0) label += ' ';
         if (mins > 0) label += mins + ' minutes';
         return label;
      }
      /* ================================== Add total slotes box function    =============================*/
      function bindSlotClickEvent() {
         $('.slot-card').off('click').on('click', function () {
            const date = $(this).data('date');
            const price = $(this).data('price');
            const start = $(this).data('start');
            const end = $(this).data('end');
            const duration = $(this).data('duration');
            const staffIds = $(this).data('id');
            AppendSlotBoxOnce(date, price, start, end, duration, staffIds);
         });
      }

      let bookSlots = $('#bookslots').val();
      let slotDataArray = [];
      if (bookSlots !== "" && bookSlots !== null && bookSlots !== undefined) {
         let slots = JSON.parse(bookSlots);
         slots.forEach(function (slot) {
            let {
               date,
               price,
               start,
               end,
               staff_ids,
               duration
            } = slot;
            let staffId = staff_ids[0];
            AppendSlotBoxOnce(date, price, start, end, duration, staffId);
         });

      }
      /* ================================== Add slotes box function    =============================*/
      function AppendSlotBoxOnce(date, price, start, end, duration, id) {
         const $wrapper = $('.slot-list-wrapper');
         const uniqueKey = `${date}-${start}-${end}`;
         const exists = $wrapper.find(`[data-slot="${uniqueKey}"]`).length;
         if (!exists) {
            $('.remove-all-slots').removeClass('d-none');
            slotDataArray.push({
               date: date,
               price: price,
               start: start,
               end: end,
               duration: duration,
               staff_ids: id,
            });
            $('#bookslots').val(JSON.stringify(slotDataArray));
            const slotHTML = `
                <div class="slot-item d-flex align-items-center justify-content-between gap-4 border border-gray-300 rounded-md p-3 bg-white shadow-sm text-sm w-full sm:w-full" data-slot="${uniqueKey}">
                <div class="d-flex w-100 justify-content-between align-items-center mr-2">    
                <div class="font-medium text-gray-800 flex-1">
                        <div>${date}</div>
                            <input type="hidden" class=""value="${id}"/>
                            <div class="text-xs text-gray-500">${start} → ${end}</div>
                            <div class="text-xs text-gray-500">Duration: ${duration}</div>
                    </div>
                    <div class="text-success font-semibold whitespace-nowrap">${price}</div>
                    </div>
                    ${bookSlots ? '' : '<div class="text-danger font-bold cursor-pointer remove-slot ml-auto">✖</div>'}
                </div>
            `;
            $wrapper.append(slotHTML);
         }
         toggleRemoveAllButton();
      }
      /* ================================== Remove  slotes function    =============================*/

      function toggleRemoveAllButton() {
         const hasSlots = $('.slot-list-wrapper .slot-item').length > 0;
         $('.remove-all-slots').toggleClass('d-none', !hasSlots);
      }
      /* ================================== Remove  slotes one by one   =============================*/
      $(document).on('click', '.remove-slot', function () {
         const $item = $(this).closest('.slot-item');
         const uniqueKey = $item.data('slot');
         slotDataArray = slotDataArray.filter(slot => `${slot.date}-${slot.start}-${slot.end}` !== uniqueKey);
         $item.remove();
         $('#bookslots').val(slotDataArray.length ? JSON.stringify(slotDataArray) : '');
         toggleRemoveAllButton();
      });

      /* ================================== Remove All slotes  =============================*/
      $(document).on('click', '.remove-all-slots', function () {
         $('.slot-list-wrapper').empty();
         slotDataArray = [];
         $('#bookslots').val('');
         toggleRemoveAllButton();
      });
   }
   /* ================================== Function for booking form =============================*/
   function twostepform() {
      /*================== on click other checkbox show other input to fill and hide when they unchecked the checkbox and radio button  ==============*/
      $(document).on("change", ".other_checkbox", function () {
         let relatedInput = $(this).closest(".mb-3").find(".other_checkbox_input");
         if ($(this).is(":checked")) {
            relatedInput.removeClass("d-none");
         } else {
            relatedInput.addClass("d-none").val(" ");
         }
      });

     
      $(document).on("change", "input[type=radio]", function () {
         let radiobutton = $(this).val();
         let relatedInput = $(this).closest(".mb-3").find(".other_radiobox_input");
         if (radiobutton == '__other__') {
            if (relatedInput) {
               $(relatedInput).removeClass('d-none');
            }
         } else {
            $(relatedInput).addClass('d-none').val('');
         }
      });
      /*================= on click other checkbox show other input to fill and hide when they unchecked the checkbox and radio button End ==========================*/

      const services_short_code_get_staff = document.querySelector('.get_service_staff');
      if (services_short_code_get_staff) {
         services_short_code_get_staff.addEventListener('change', function () {
            var customValue = services_short_code_get_staff;
            get_services_staff(customValue);
         });
      }
      var selectedStaff = document.querySelector('.selected_vendor');
      if (selectedStaff) {
         const services_selected = document.querySelector('.get_service_staff');
         get_services_staff(services_selected);
      }

      let currentSteps = 1;
      const steps = document.querySelectorAll('.step');
      const prevButton = document.querySelector('.previous');
      const nextButtons = document.querySelectorAll('.next');
      const submitButtons = document.querySelectorAll('.submit');
      const form = document.querySelector('form');
      const ServiceStaffCode = document.querySelector('.get_service_staff');
      // console.log('ServiceStaffCode', ServiceStaffCode);
      if (!steps.length || !prevButton || !nextButtons.length || !submitButtons.length) {
         console.error('Required elements not found!');
         return;
      }
      /* ================================== Form validation =============================*/
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
                $('.select-slots').removeClass('d-none').show();
            } else {
                $('#' + current_step).find('.select-slots').empty();
            }
        }
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
                  let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message') ?
                     field.nextElementSibling :
                     document.createElement('p');
                  if (!errorMessage.classList.contains('error-message')) {
                     errorMessage.classList.add('error-message', 'text-danger', 'text-xs', 'mt-1');
                     errorMessage.textContent = 'Please enter a valid email address';
                     field.insertAdjacentElement('afterend', errorMessage);
                  }
                  isValid = false;
               } else {
                  field.classList.remove('border-danger');
                  let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message') ?
                     field.nextElementSibling :
                     null;
                  if (errorMessage) errorMessage.remove();
               }
            } else if (!field.value.trim()) {
               field.classList.add('border-danger');
               let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message') ?
                  field.nextElementSibling :
                  document.createElement('p');
               if (!errorMessage.classList.contains('error-message')) {
                  errorMessage.classList.add('error-message', 'text-danger', 'text-xs', 'mt-1');
                  errorMessage.textContent = 'This field is required';
                  field.insertAdjacentElement('afterend', errorMessage);
               }
               isValid = false;
            } else {
               field.classList.remove('border-danger');
               let errorMessage = field.nextElementSibling && field.nextElementSibling.classList.contains('error-message') ?
                  field.nextElementSibling :
                  null;
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
            // console.warn("Form validation failed!");
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
         const bookslotsValue = $('#bookslots').val();
         const serviceValue = $('#get_service_staff').val();
         const vendorValue = $('#service_vendor_form').val();
         if (!isEmpty(serviceValue) && !isEmpty(vendorValue) && isEmpty(bookslotsValue)) {

         } else {
            $('.select-slots').addClass('d-none');
              $('.showMessage').addClass('d-none');
         }
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
   window.BookingFunction = twostepform;
});