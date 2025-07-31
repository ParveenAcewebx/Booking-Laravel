(function ($) {
    "use strict";

    const today = new Date();
    let year = today.getFullYear();
    let month = today.getMonth();
    let selectedDate = null;
    const availableDates = typeof availabledatesArray !== 'undefined' ? availabledatesArray : [];

    $(document).ready(function () {
        // Event listener when vendor is selected
        $('#service_vendor_form').on('change', function () {
            let selectedValue = $(this).val();

            if (selectedValue) {
                $.ajax({
                    url: '/get/vendor/get_booking_calender',
                    type: 'GET',
                    data: { vendor_id: selectedValue },
                    dataType: 'json',
                    success: function (response) {
                        // console.log('Response:', response);
                        $('.calendar-wrap').removeClass('hidden');
                        if (response.success && response.data) {
                            const workHoursData = response.data[0].Working_day;
                            const daysOffData = response.data[0].Dayoff;
                            const dayOffDates = daysOffData.flat();
                            const workingDays = response.data.map(item => item.Working_day);
                            new Calendar(workingDays, dayOffDates);
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('There was an error fetching the staff details. Please try again later.');
                    }
                });
            }
        });

        // Calendar Class
        class Calendar {
            constructor(workingDays, dayOffDates) {
                this.workingDays = workingDays;
                this.dayOffDates = dayOffDates;
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

                        // Check if this day is in dayOffDates
                        const isDayOff = this.dayOffDates.some(dayoff => {
                          
                            const dateObj = new Date(dayoff.date);
                            return dateObj.toDateString() === dayDate.toDateString();
                        });

                        // Collect valid working days
                        const validWorkingDays = [];
                       if (this.workingDays) {  
                       this.workingDays.forEach(week => {
                                Object.entries(week).forEach(([day, time]) => {
                                    if (time.start) {
                                    const timePart = time.start.split("T")[1].split(".")[0];  // Extract the time part (HH:MM:SS)
                                    if (timePart !== "00:00:00") {
                                        validWorkingDays.push(day.toLowerCase());  // Add valid day to the array
                                    }
                                    }
                                });
                            });
                        }

                        const dayName = dayDate.toLocaleString('en-us', { weekday: 'long' }).toLowerCase();

                        // Disable past days, day-offs, or non-working days
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

                this.getSessionDetails(selectedDate);
            }

            // Get session details for the selected date
            getSessionDetails(date) {
                if (!date) return;
                const pathParts = window.location.pathname.split("/").filter(Boolean);
                const coach_id = $('#coach_id').val();
                const type = pathParts[2];
                const coaching_program_id = pathParts[1];
                const baselink = pathParts[0];

                $.ajax({
                    url: '/get/session_details',
                    method: 'GET',
                    data: {
                        date,
                        sessiontype: type,
                        coaching_program_id,
                        coach_id
                    },
                    success: function (response) {
                        let sessionsHTML = '';

                        if (baselink === 'book-training' && response[date]) {
                            const sessions = response[date];

                            sessionsHTML += `
                                <div class="date-section mb-3">
                                    <h5 class="date-header">${date}</h5>
                                    <div class="timings">
                                        <div id="myButton" class="slot">`;

                            sessions.forEach(session => {
                                sessionsHTML += `
                                    <div class="slot-box" data-session-id="${session.sessin_detail_id}" 
                                         data-time="${session.time}" data-price="${session.price}" 
                                         data-from-time="${session.from_time}" data-to-time="${session.to_time}" 
                                         data-slots-left="${session.slots_left}">
                                    <span><i class="fa fa-clock-o"></i> ${session.time}</span>
                                    <span class="gbtn">${session.slots_left} Slot left</span>
                                    <span class="gbtn">Price: ${session.price}</span>
                                </div>`;
                            });

                            sessionsHTML += `</div>`;

                            sessions.forEach(session => {
                                sessionsHTML += `
                                    <ul class="select_slot">
                                        <li class="select-and-continue" data-session-id="${session.sessin_detail_id}" data-time="${session.time}" data-price="${session.price}" data-from-time="${session.from_time}" data-to-time="${session.to_time}" data-slots-left="${session.slots_left}" data-date="${date}" data-action="continue">Select and continue</li>
                                        <li class="select-and-add-another-time" data-session-id="${session.sessin_detail_id}" data-time="${session.time}" data-price="${session.price}" data-from-time="${session.from_time}" data-to-time="${session.to_time}" data-slots-left="${session.slots_left}" data-date="${date}" data-action="add-another-time">Select and add another time</li>`;
                                if (!session.session_type) {
                                    sessionsHTML += `<li data-bs-target="#recurring" data-session-id="${session.sessin_detail_id}" data-time="${session.time}" data-price="${session.price}" data-from-time="${session.from_time}" data-to-time="${session.to_time}" data-slots-left="${session.slots_left}" data-date="${date}" data-session-day-name="${session.session_day_name}" data-session-day="${session.session_day}" data-session-date-count="${session.session_date_count}" data-bs-toggle="modal">Select and make recurring</li>`;
                                }
                                sessionsHTML += `</ul>`;
                            });

                            sessionsHTML += `</div></div>`;
                        }

                        const availabilityDiv = document.querySelector('.availibility .timings');
                        if (availabilityDiv) {
                            availabilityDiv.innerHTML = sessionsHTML;
                        }
                    },
                    error: function () {
                        alert('Error fetching session details');
                    }
                });
            }
        }

        
        // Optional: If you want default calendar without selecting a vendor first
        // new Calendar(); 
    });

})(jQuery);
