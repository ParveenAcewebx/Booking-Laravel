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
            if (selectedValue) {
                $.ajax({
                    url: '/get/vendor/get_booking_calender',
                    type: 'GET',
                    data: { vendor_id: selectedValue },
                    dataType: 'json',
                    success: function (response) {
                        $('.calendar-wrap').removeClass('hidden');
                        if (response.success && response.data && response.data[0]) {
                            const workondayoff= response.data;
                              const workingDays = response.data.map(item => item.Working_day);
                                resetCalendar();
                            new Calendar(workingDays,workondayoff);
                        }
                    },
                });
            }
        });

        // Calendar Class
        class Calendar {
            constructor(workingDays,workondayoff) {
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
                           
                               if(dateObj.toDateString() === dayDate.toDateString()){
                                const dayNameFull = dateObj.toLocaleString('en-us', { weekday: 'long' }).toLowerCase();
                                if (this.workOnoff) {
                                    this.workOnoff.forEach(items => {
                                          const workingDay = items.Working_day;
                                          const dayoffDates = items.Dayoff;
                                          if(dayoffDates){
                                            const minDayoffArray =  this.workOnoff.reduce((minArr, currentArr) => {
                                                const currentDayoffDatesCount = currentArr.Dayoff.flat().length;
                                                const minDayoffDatesCount = minArr.Dayoff.flat().length;
                                                return currentDayoffDatesCount < minDayoffDatesCount ? currentArr : minArr;
                                            });
                                            if (minDayoffArray){
                                            const dayof = minDayoffArray.Dayoff.flat();
                                          if(dayof && dayof.length > 0) {
                                                leaveapproved = false;
                                            const sortedDayoff = dayof.sort((a, b) => new Date(a.date) - new Date(b.date));
                                            const startDate = sortedDayoff[0].date;
                                            const endDate = sortedDayoff[sortedDayoff.length - 1].date;
                                            const formattedDate = new Date(dateObj).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric'});    
                                            if (formattedDate >= startDate && formattedDate <= endDate) {
                                                leaveapproved = true;
                                            }
                                            } 
                                         }
                                        }else{
                                            leaveapproved= false;
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
                                                                               leaveapproved= false;
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
                               }else{
                                return false ;
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
    });

})(jQuery);
