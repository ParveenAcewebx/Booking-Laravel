document.addEventListener("DOMContentLoaded", function () {
  /* ================================== Get service data   =============================*/
   function get_services_staff(selectedvalue) {
      var serviceId = selectedvalue.value;
      var selectedStaff = document.querySelector('.selected_vendor').value;
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
            var bookingcalendar = document.querySelector('.calendar-wrap');
            staffSelect.innerHTML = '';
            var defaultOption = document.createElement('option');
            defaultOption.value = '';
            defaultOption.textContent = '---Select Vendor---';
            staffSelect.appendChild(defaultOption);
            if (response && response.length > 0) {
               staffSelect.disabled = false;
               select_service_staff.classList.remove('d-none');
               // Add each staff member to the dropdown
               response.forEach(function (staff) {
                  var option = document.createElement('option');
                  option.value = staff.id;
                  option.textContent = staff.name;
                  staffSelect.appendChild(option);
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
               staffSelect.disabled = true;
               select_service_staff.classList.add('d-none');
               bookingcalendar.classList.add('d-none');
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
      const availableDates = typeof availabledatesArray !== 'undefined' ? availabledatesArray : [];
      $('.service_vendor_form').on('change', function () {
         let selectedValue = $(this).val();
         if (selectedValue === "") {
            selectedValue = 0;
         } else {
            selectedValue = selectedValue;
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
               $('.calendar-wrap').removeClass('d-none');
               if (response.success === true && response.data && response.data[0]) {
                  const workondayoff = response.data;
                  const workingDays = response.data.map(item => item.Working_day);
                  resetCalendar();
                  new Calendar(workingDays, workondayoff);
               } else {
                  const workondayoff = 0;
                  const workingDays = 0;
                  resetCalendar();
                  new Calendar(workingDays, workondayoff);
               }
            },
         });
      });
/* ================================== Add staff data in the callender   =============================*/
      class Calendar {
         constructor(workingDays, workondayoff) {
            this.workingDays = workingDays;
            this.workOnoff = workondayoff;
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
            const startDay = new Date(year, month, 1).getDay();
            const totalDays = new Date(year, month + 1, 0).getDate();
            const monthNameElem = document.getElementById("month-name");
            if (monthNameElem) {
               monthNameElem.textContent = `${monthNames[month]} ${year}`;
            }
            const days = document.querySelectorAll('#calendar td');
            days.forEach((dayCell) => {
               dayCell.innerHTML = '';
               dayCell.classList.remove('available', 'disabled', 'selected');
               dayCell.style.backgroundColor = '';
               dayCell.style.pointerEvents = '';
            });
            const currentDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
            let dayIndex = 0;
            for (let i = 0; i < days.length; i++) {
               const dayCell = days[i];
               if (i >= startDay && dayIndex < totalDays) {
                  const dayNum = ++dayIndex;
                  const fullDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(dayNum).padStart(2, "0")}`;
                  const dayDate = new Date(year, month, dayNum);
                  dayCell.innerHTML = dayNum;
                  const validWorkingDays = [];
                  if (this.workingDays) {
                     this.workingDays.forEach(week => {
                        Object.entries(week).forEach(([day, time]) => {
                           if (time.start) {
                              const timePart = time.start.split("T")[1].split(".")[0];
                              if (timePart !== "00:00:00") {
                                 validWorkingDays.push(day.toLowerCase());
                              }
                           }
                        });
                     });
                  }
                  const dayOffData = [];
                  if (this.workOnoff) {
                     this.workOnoff.forEach(items => {
                        const workingDay = items.Working_day;
                        const dayoffDates = items.Dayoff;
                        Object.entries(workingDay).forEach(([day, time]) => {
                           if (time.start) {
                              const timePart = time.start.split("T")[1].split(".")[0];
                              if (timePart !== "00:00:00") {
                                 dayoffDates.forEach(dayoff => {
                                    const dayOfdates = dayoff.flat();
                                    dayOffData.push(dayOfdates);
                                 });
                              }
                           }
                        });
                     });
                  }
                  const isDayOff = dayOffData.some(dayoff => {
                     let leaveapproved;
                     let ofdates = [];
                     dayoff.forEach(dayoof => {
                        ofdates.push(dayoof.date);
                     });
                     return ofdates.some(date => {
                        leaveapproved = true;
                        const dateObj = new Date(date);
                        if (dateObj.toDateString() === dayDate.toDateString()) {
                           const dayNameFull = dateObj.toLocaleString('en-us', {
                              weekday: 'long'
                           }).toLowerCase();
                           if (this.workOnoff) {
                              this.workOnoff.forEach(items => {
                                 const workingDay = items.Working_day;
                                 const dayoffDates = items.Dayoff;
                                 if (dayoffDates) {
                                    const minDayoffArray = this.workOnoff.reduce((minArr, currentArr) => {
                                       const currentDayoffDatesCount = currentArr.Dayoff.flat().length;
                                       const minDayoffDatesCount = minArr.Dayoff.flat().length;
                                       return currentDayoffDatesCount < minDayoffDatesCount ? currentArr : minArr;
                                    });
                                    if (minDayoffArray) {
                                       const dayof = minDayoffArray.Dayoff.flat();
                                       if (dayof && dayof.length > 0) {
                                          leaveapproved = false;
                                          const sortedDayoff = dayof.sort((a, b) => new Date(a.date) - new Date(b.date));
                                          const startDate = sortedDayoff[0].date;
                                          const endDate = sortedDayoff[sortedDayoff.length - 1].date;
                                          const formattedDate = new Date(dateObj).toLocaleDateString('en-US', {
                                             year: 'numeric',
                                             month: 'long',
                                             day: 'numeric'
                                          });
                                          if (formattedDate >= startDate && formattedDate <= endDate) {
                                             leaveapproved = true;
                                          }
                                       }
                                    }
                                 } else {
                                    leaveapproved = false;
                                 }
                                 Object.entries(workingDay).forEach(([day, time]) => {
                                    if (time.start) {
                                       const timePart = time.start.split("T")[1].split(".")[0];
                                       if (timePart !== "00:00:00") {
                                          dayoffDates.forEach(dayoff => {
                                             const dayOfdates = dayoff.flat();
                                             const excludedDateStrings = dayOfdates.map(item => item.date);
                                             const filteredWorkOnoff = this.workOnoff.filter(items => {
                                                const allDayoffDatesFormatted = items.Dayoff.flat().map(obj =>
                                                   new Date(obj.date).toLocaleDateString('en-US', {
                                                      year: 'numeric',
                                                      month: 'long',
                                                      day: 'numeric'
                                                   })
                                                );
                                                return !allDayoffDatesFormatted.some(date => excludedDateStrings.includes(date));
                                             });
                                             filteredWorkOnoff.forEach(filteredItems => {
                                                const workingDays = filteredItems.Working_day;
                                                if (workingDays.hasOwnProperty(dayNameFull)) {
                                                   const dayData = workingDays[dayNameFull];
                                                   if (dayData.start) {
                                                      const timePart = dayData.start.split("T")[1].split(".")[0];
                                                      if (timePart !== "00:00:00") {
                                                         leaveapproved = false;
                                                      }
                                                   }
                                                };
                                             });
                                          });
                                       }
                                    }
                                 });
                              });
                           }
                           return leaveapproved;
                        } else {
                           return false;
                        }
                     });
                  });
                  const dayName = dayDate.toLocaleString('en-us', {
                     weekday: 'long'
                  }).toLowerCase();
                  if (dayDate < currentDate || isDayOff || !validWorkingDays.includes(dayName)) {
                     dayCell.classList.add("disabled");
                     dayCell.style.backgroundColor = "#d3d3d3";
                     dayCell.style.pointerEvents = "none";
                  } else {
                     if (availableDates) {
                        dayCell.classList.add("available");
                        dayCell.style.backgroundColor = "rgb(18 163 46)";
                     }
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
            month += direction;
            if (month < 0) {
               month = 11;
               year--;
            } else if (month > 11) {
               month = 0;
               year++;
            }
            this.drawDays();
         }
         addDayClickListener() {
            const calendar = document.getElementById('calendar');
            if (calendar) {
               calendar.addEventListener('click', (e) => {
                  const dayElem = e.target;
                  if (dayElem.tagName === 'TD' && dayElem.classList.contains("available")) {
                     this.clickDay(dayElem);
                  }
               });
            }
         }
         clickDay(dayElem) {
            const day = parseInt(dayElem.innerHTML);
            selectedDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
            document.querySelector('.selected')?.classList.remove('selected');
            dayElem.classList.add('selected');
            this.BookeAslot(selectedDate);
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
                     const price = `${response.serviceCurrency} ${response.price}`;
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
                                <div class="rounded-lg p-2 bg-white border border-gray-300 cursor-pointer m-2 slot-card"style="min-width: 170px; max-width: 100%;" 
                                data-date="${formattedDate}" 
                                data-price="${price}"
                                data-start="${slot.start_time}"
                                data-end="${slot.end_time}"
                                data-duration=" ${formatDuration(response.duration)}"
                                data-id="${slot.id}">
                                    <input type="hidden" name="staff_id" value="${slot.id}">
                                    <p class="text-sm mb-1 font-medium text-gray-700">${slot.start_time} - ${slot.end_time}</p>
                                    <p class="text-sm text-gray-600 m-0">Duration: ${formatDuration(response.duration)}</p>
                                    <p class="text-sm text-gray-600 m-0">Price: ${response.serviceCurrency} ${response.price}</p>
                                </div>`
                        });
                     } else {
                        sessionsHTML = `<p class="text-danger text-center">No available slots found for this date ${date}.</p>`;
                        $('.availibility').removeClass('d-none');
                        $('.availibility').removeClass('hidden');
                     }
                     sessionsHTML += `
                                </div>
                            </div>
                        </div>`;
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
      const services_short_code_get_staff = document.querySelector('.get_service_staff');
      services_short_code_get_staff.addEventListener('change', function () {
         var customValue = services_short_code_get_staff;
         get_services_staff(customValue);

      });
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
         const bookslotsValue = $('#bookslots').val();
         const serviceValue = $('#get_service_staff').val();
         const vendorValue = $('#service_vendor_form').val();
         if (!isEmpty(serviceValue) && !isEmpty(vendorValue) && isEmpty(bookslotsValue)) {
            $('.submit').prop('disabled', true);
            $('.next').prop('disabled', true);
            $('.select-slots').removeClass('d-none');
         } else {
            $('.select-slots').addClass('d-none');
            $('.submit').prop('disabled', false);
            $('.next').prop('disabled', false);
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
   $('#loadTemplateBtn').on('click', function () {
      setTimeout(function () {
         var templateId = $("#bookingTemplateId").val();
         if (templateId) {
            twostepform();
         }
      }, 500);
   });

});