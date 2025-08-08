(function ($) {
    "use strict";

    const today = new Date();
    let year = today.getFullYear();
    let month = today.getMonth();
    let selectedDate = null;
    const availableDates = typeof availabledatesArray !== 'undefined' ? availabledatesArray : [];

    $(document).ready(function () {

        // Function to reset the calendar's HTML
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

        // Vendor selection event listener
        $('.service_vendor_form').on('change', function () {
            let selectedValue = $(this).val();
            if (selectedValue === "") {
                selectedValue = 0;
            } else {
                selectedValue = selectedValue;
            }
            console.log('selectedValue', selectedValue);
            $.ajax({
                url: '/get/vendor/get_booking_calender',
                type: 'GET',
                data: { vendor_id: selectedValue },
                dataType: 'json',
                success: function (response) {
                    $('.availibility').addClass('hidden');

                    console.log('Calendar Response' + JSON.stringify(response))
                    $('.calendar-wrap').removeClass('hidden');
                    if (response.success === true && response.data && response.data[0]) {
                        const workondayoff = response.data;
                        const workingDays = response.data.map(item => item.Working_day);
                        resetCalendar();
                        new Calendar(workingDays, workondayoff);
                    } else {
                        const workondayoff = 0;
                        const workingDays = 0;

                        // Reset the calendar before initializing it
                        resetCalendar();

                        // Initialize the calendar with the working days and days off
                        new Calendar(workingDays, workondayoff);

                    }
                },
            });
        });

        // Calendar Class
        class Calendar {
            constructor(workingDays, workondayoff) {
                this.workingDays = workingDays;
                this.workOnoff = workondayoff;
                this.draw();
                this.addNavigationListeners();
                this.addDayClickListener();
            }

            // Draw the calendar days
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


                        // Collect valid working days
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
                        // Collect valid working days and check if working time is not "00:00:00"
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

                        // **Check if the selected day is a day off**
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
                                    const dayNameFull = dateObj.toLocaleString('en-us', { weekday: 'long' }).toLowerCase();
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
                                                        const formattedDate = new Date(dateObj).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
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
                        const dayName = dayDate.toLocaleString('en-us', { weekday: 'long' }).toLowerCase();

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

            // Navigation buttons (prev/next month)
            addNavigationListeners() {
                const pre = document.querySelector('.pre-button');
                const next = document.querySelector('.next-button');

                if (pre) pre.addEventListener('click', () => this.changeMonth(-1));
                if (next) next.addEventListener('click', () => this.changeMonth(1));
            }

            // Change month
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

            // Add day click listener
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

            // Handle day click
            clickDay(dayElem) {
                const day = parseInt(dayElem.innerHTML);
                selectedDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

                document.querySelector('.selected')?.classList.remove('selected');
                dayElem.classList.add('selected');

                this.BookeAslot(selectedDate);
            }

            // Get session details for the selected date
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
                        console.log('success response', response);

                        let sessionsHTML = '';
                        if (response && response.staffdata.length > 0) {
                            $('.availibility').removeClass('hidden');

                            const formattedDate = response.date;
                            const price = `${response.serviceCurrency} ${response.price}`;

                            sessionsHTML += `
                    <div class="date-section mb-3">
                        <h5 class="date-header text-lg font-semibold mb-2">${formattedDate}</h5>
                        <div class="overflow-x-auto scrollbar-thin">
                            <div class="flex gap-4 pb-2 w-max min-w-full">
                `;

                            response.staffdata.forEach((staff) => {
                                const firstSlot = staff.slots[0];
                                const lastSlot = staff.slots[staff.slots.length - 1];

                                if (firstSlot && lastSlot) {
                                    sessionsHTML += `
                            <div 
                                class="min-w-[200px] bg-white border border-gray-300 rounded-lg p-4 shadow-sm slot-card cursor-pointer transition hover:shadow-md"
                                data-date="${formattedDate}" 
                                data-price="${price}"
                                data-start="${staff.day_start}"
                                data-end="${staff.day_end}"
                            >
                                <input type="hidden" name="staff_id" value="${staff.id}">
                                <p class="text-sm mb-1 font-medium text-gray-700">${staff.day_start} - ${staff.day_end}</p>
                                <p class="text-sm text-gray-600">Slots: ${staff.slots.length}</p>
                                <p class="text-sm text-gray-600">Duration: ${formatDuration(response.duration)}</p>
                                <p class="text-sm text-gray-600">Price: ${price}</p>
                            </div>
                        `;
                                }
                            });

                            sessionsHTML += `
                            </div>
                        </div>
                    </div>
                    
                `;
                        } else {
                            sessionsHTML = `<p class="text-sm text-red-500">No available slots found for this date.</p>`;
                        }

                        $('.availibility').html(sessionsHTML);
                        bindSlotClickEvent(); // ✨ Important
                    },
                    error: function () {
                        alert('Error fetching session details');
                    }
                });
            }

        }

        function formatDuration(minutes) {
            console.log(minutes);
            const hrs = Math.floor(minutes / 60);
            const mins = minutes % 60;
            let label = '';

            if (hrs > 0) label += hrs + ' hour' + (hrs > 1 ? 's' : '');
            if (hrs > 0 && mins > 0) label += ' ';
            if (mins > 0) label += mins + ' minutes';

            return label;
        }

        function bindSlotClickEvent() {
            $('.slot-card').off('click').on('click', function () {
                const date = $(this).data('date');
                const price = $(this).data('price');
                const start = $(this).data('start');
                const end = $(this).data('end');

                AppendSlotBoxOnce(date, price, start, end);
            });
        }

        function AppendSlotBoxOnce(date, price, start, end) {
            const $wrapper = $('.slot-list-wrapper');
            const uniqueKey = `${date}-${start}-${end}`;
            const exists = $wrapper.find(`[data-slot="${uniqueKey}"]`).length;

            if (!exists) {
                $('.remove-all-slots').removeClass('hidden');
                const slotHTML = `
            <div class="slot-item flex justify-between items-center gap-4 border border-gray-300 rounded-md p-3 bg-white shadow-sm text-sm w-full sm:w-full" data-slot="${uniqueKey}">
                <div class="font-medium text-gray-800 flex-1">
                    <div>${date}</div>
                    <div class="text-xs text-gray-500">${start} → ${end}</div>
                </div>
                <div class="text-green-600 font-semibold whitespace-nowrap">${price}</div>
                <div class="text-red-500 font-bold cursor-pointer remove-slot ml-auto">&#10006;</div>
            </div>
        `;
                $wrapper.append(slotHTML);
            }

            toggleRemoveAllButton();
        }

        function toggleRemoveAllButton() {
            const hasSlots = $('.slot-list-wrapper .slot-item').length > 0;
            $('.remove-all-slots').toggleClass('hidden', !hasSlots);
        }

        $(document).on('click', '.remove-slot', function () {
            $(this).closest('.slot-item').remove();
            toggleRemoveAllButton();
        });

        // Remove all slots
        $(document).on('click', '.remove-all-slots', function () {
            $('.slot-list-wrapper').empty();
            toggleRemoveAllButton(); // ✅ Call this again after clearing
        });
    });
})(jQuery);
