(function ($) {
    "use strict";

    const today = new Date();
    let selectedDate = null;
    let activeCalendar = null;

    $(document).ready(function () {

        // Reset calendar cells
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

        $(document).on("click", "#calendar td.available", function () {
            if (!activeCalendar) return;
            activeCalendar.clickDay(this);
        });

        // Vendor selection
        $('.service_vendor_form').on('change', function () {
            const selectedValue = $(this).val() || 0;
            $('.select-slots p').addClass('hidden');

            $.ajax({
                url: '/get/vendor/get_booking_calender',
                type: 'GET',
                data: { vendor_id: selectedValue },
                dataType: 'json',
                success: function(response) {
                    $('.remove-all-slots').addClass('hidden');
                    $('.slot-item').remove();
                    $('input[name="bookslots"]').val('');
                    $('.select-slots p').addClass('hidden');

                    let workondayoff = response.success && response.data ? response.data : [];
                    let workingDays = workondayoff.map(item => item.Working_day);

                    resetCalendar();

                    if (selectedValue != 0) {

                        // 🔥 Check if any date is available for all months
                        const anyAvailableDates = workondayoff.some(staff => {
                            const wd = staff.Working_day || {};
                            return Object.keys(wd).some(dayName => {
                                const slot = wd[dayName];
                                return slot && slot.start && !slot.start.includes("00:00:00");
                            });
                        });

                        if (anyAvailableDates) {
                            console.log('Staff exists and dates available');
                            activeCalendar = new Calendar(workingDays, workondayoff);
                            $('.availibility').addClass('hidden');
                            $('.showMessage').addClass('hidden');
                            $('.calendar-wrap').removeClass('hidden');
                        } else {
                            console.log('Staff exists but no available dates');
                            activeCalendar = new Calendar([], []); // empty calendar
                            $('.calendar-wrap').addClass('hidden');
                            $('.availibility').removeClass('hidden');
                            $('.showMessage').removeClass('hidden');
                        }

                        updateUIState();
                    } else {
                        console.log('Vendor not selected or not exists');
                        activeCalendar = new Calendar([],[]); // empty calendar
                        $('.calendar-wrap').addClass('hidden');
                        $('.availibility').removeClass('hidden');
                        $('.showMessage').addClass('hidden');

                    }
                },
                error: function () {
                    console.error("Failed to fetch calendar data.");
                }
            });
        });

        class Calendar {
            constructor(workingDays, workondayoff) {
                this.workingDays = workingDays || [];
                this.workOnoff = workondayoff || [];

                const now = new Date();
                this.year = now.getFullYear();
                this.month = now.getMonth();

                this.draw();
                this.addNavigationListeners();
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

            addNavigationListeners() {
                const pre = document.querySelector('.pre-button');
                const next = document.querySelector('.next-button');
                if (pre) pre.onclick = () => this.changeMonth(-1);
                if (next) next.onclick = () => this.changeMonth(1);
            }

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

            clickDay(dayElem) {
                const day = parseInt(dayElem.innerHTML);
                selectedDate = `${this.year}-${String(this.month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
                document.querySelector('.selected')?.classList.remove('selected');
                dayElem.classList.add('selected');
                this.BookeAslot(selectedDate);
            }

            BookeAslot(date) {
                if (!date) return;
                const serviceId = document.querySelector('.get_service_staff')?.value;
                const vendorId = document.querySelector('.service_vendor_form')?.value;
                if (!serviceId || !vendorId) return alert('Service or Vendor not selected.');

                $.ajax({
                    url: '/get/slotbooked',
                    method: 'GET',
                    data: { dates: date, serviceid: serviceId, vendorid: vendorId },
                    success: function (response) {
                        let sessionsHTML = '';
                        const formattedDate = response?.date || '';
                        const price = `${response?.serviceCurrency || ''}${response?.price || ''}`;
                        const duration = parseInt(response?.duration, 10) || 0;
                        const staffOffIds = response.staff_off_ids ? response.staff_off_ids.split(',') : [];
                        const formatDates = response?.changeDate || '';
                        alert(formattedDate);
                        $('.select-slots').addClass('hidden');

                        if (response?.merged_slots?.length > 0) {
                            console.log('running data');
                            $('.availibility').removeClass('hidden');
                            sessionsHTML += `<div class="date-section mb-3">
                                <h5 class="date-header text-lg font-semibold mb-2">${formattedDate}</h5>
                                <div class="overflow-x-auto scrollbar-thin">
                                    <div class="flex gap-4 pb-2 w-max min-w-full">`;
                            response.merged_slots.forEach(slot => {
                                let slotStaffIds = slot.available_staff_ids.filter(id => !staffOffIds.includes(String(id)));
                                sessionsHTML += createSlotHTML(formattedDate, price, slot.start_time, slot.end_time, duration, slotStaffIds);
                            });
                            sessionsHTML += `</div></div></div>`;
                        } else {
                            console.log('no running data');
                            sessionsHTML = `<p class="text-sm text-red-500">No available slots found for this date ${date}.</p>`;
                            $('.availibility').removeClass('hidden');
                        }
                        sessionsHTML += `<input type="hidden" id="staffOffIds" value="${staffOffIds.join(',')}">`;
                        $('.availibility').html(sessionsHTML);
                        bindSlotClickEvent();
                    },
                    error: function () { alert('Error fetching session details'); }
                });
            }
        }
        function formatDuration(minutes) {
            if (!minutes || minutes <= 0) return '0 minutes';
            const hrs = Math.floor(minutes / 60);
            const mins = minutes % 60;
            return (hrs > 0 ? `${hrs} hour${hrs > 1 ? 's' : ''}` : '') +
                   (hrs > 0 && mins > 0 ? ' ' : '') +
                   (mins > 0 ? `${mins} minute${mins > 1 ? 's' : ''}` : '');
        }

        let slotDataArray = []; // Global

        // Bind slot click event
        function bindSlotClickEvent() {
            $(document).off('click.slotCard').on('click.slotCard', '.slot-card', function () {
                const { date, price, start, end, duration, id: staffIds } = $(this).data();
                AppendSlotBoxOnce(date, price, start, end, duration, staffIds);
            });
        }

        // Create slot card HTML
        function createSlotHTML(date, price, start, end, duration, staffIds) {

            const staffIdsString = Array.isArray(staffIds) ? staffIds.join(',') : staffIds;
            return `
        <div class="min-w-[170px] bg-white border border-gray-300 rounded-lg p-4 shadow-sm slot-card cursor-pointer transition hover:shadow-md"
            data-date="${date}"
            data-price="${price}"
            data-start="${start}"
            data-end="${end}"
            data-id="${staffIdsString}"
            data-duration="${formatDuration(duration)}">
            <p class="text-sm font-bold text-gray-700">${start} - ${end}</p>
            <p class="text-sm text-gray-600">Duration: ${formatDuration(duration)}</p>
            <p class="text-sm text-gray-600">Price: ${price}</p>
        </div>`;
        }

        // Append slot item only once
        function AppendSlotBoxOnce(date, price, start, end, duration, staffIds) {
            const $wrapper = $('.slot-list-wrapper');
            const uniqueKey = `${date}-${start}-${end}`;
            let existingValue = $('#bookslots').val();
            if (existingValue) {
                try {
                    slotDataArray = JSON.parse(existingValue);
                } catch { slotDataArray = []; }
            } else { slotDataArray = []; }

            if (!$wrapper.find(`[data-slot="${uniqueKey}"]`).length) {
                slotDataArray.push({ date, price, start, end, duration, staff_ids: Array.isArray(staffIds) ? staffIds : [staffIds] });
                $('#bookslots').val(JSON.stringify(slotDataArray));
                $wrapper.append(`
            <div class="slot-item flex justify-between items-center gap-4 border border-gray-300 rounded-md p-3 bg-white shadow-sm text-sm w-full sm:w-full" 
                data-slot="${uniqueKey}">
                <div class="font-medium text-gray-800 flex-1">
                    <div>${date}</div>
                    <input type='hidden' name='staff_id' value='${staffIds}'>
                    <div class="text-xs text-gray-500">${start} → ${end}</div>
                    <div class="text-xs text-gray-500">Duration: ${duration}</div>
                </div>
                <div class="text-green-600 font-semibold whitespace-nowrap">${price}</div>
                <div class="text-red-500 cursor-pointer remove-slot ml-auto">&#10006;</div>
            </div>`);
            }
            updateUIState();
        }

        // Update UI State
        function updateUIState() {
            const bookslotsVal = $('#bookslots').val();
            const hasSlots = bookslotsVal && bookslotsVal.trim() !== '';
            $('.remove-all-slots').toggleClass('hidden', !hasSlots);
            // $('.select_slots').toggleClass('hidden d-none', hasSlots);
        }

        // Remove single slot
        $(document).on('click', '.remove-slot', function () {
            const $item = $(this).closest('.slot-item');
            const uniqueKey = $item.data('slot');
            slotDataArray = slotDataArray.filter(slot => `${slot.date}-${slot.start}-${slot.end}` !== uniqueKey);
            $item.remove();
            $('#bookslots').val(slotDataArray.length ? JSON.stringify(slotDataArray) : '');
            updateUIState();
        });

        // Remove all slots
        $(document).on('click', '.remove-all-slots', function () {
            $('.slot-list-wrapper').find('.slot-item').remove();
            slotDataArray = [];
            $('#bookslots').val('');
            updateUIState();
        });

        // Page load
        $(document).ready(function () {
            updateUIState();
            bindSlotClickEvent();
        });

    });

})(jQuery);
