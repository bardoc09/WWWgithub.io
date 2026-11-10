<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student/Staff Booking Form</title>
  <link rel="stylesheet" href="student_staff_booking_form.css">
  <style>
    .time-slot.selected-start {
      background-color: #4CAF50;
      color: white;
    }
    .time-slot.selected-end {
      background-color: #2196F3;
      color: white;
    }
    .time-slot.disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }
    .time-slot {
      cursor: pointer;
      padding: 8px 12px;
      margin: 4px;
      border: 1px solid #ddd;
      border-radius: 4px;
      background-color: #f5f5f5;
    }
    .time-slot:hover:not(.disabled) {
      background-color: #e0e0e0;
    }
    #time-selection-info {
      margin-top: 10px;
      padding: 8px;
      border-radius: 4px;
      background-color: #f5f5f5;
    }
  </style>
</head>
<body>
  <header class="header">
    <a href="classification.html" class="back-button" title="Back to Classification">&#8592;</a>
    <h1>Student/Staff Form</h1>
  </header>

  <main class="main-container">
    <form class="booking-form" method="POST" action="submit_booking.php" onsubmit="return prepareStudentSubmission()">
      <input type="hidden" name="booker_type" value="student">

      <!-- Name, ID and Phone -->
      <div class="name-id-row">
        <div class="form-group">
          <label for="full-name">Full Name:</label>
          <input type="text" id="full-name" name="full-name" placeholder="Enter your full name" required>
        </div>
        <div class="form-group">
          <label for="id-number">ID Number:</label>
          <input type="number" id="id-number" name="id-number" placeholder="Enter your ID number" required>
        </div>
      </div>

      <div class="form-group">
        <label for="phone-number">Phone Number:</label>
        <input type="number" id="phone-number" name="phone-number" placeholder="Enter your phone number" required>
      </div>

      <!-- Department and Activity -->
      <div class="form-group">
        <label for="department">Department:</label>
        <input type="text" id="department" name="department" placeholder="Enter your department" required>
      </div>
      <div class="form-group">
        <label for="activity-title">Activity Title:</label>
        <input type="text" id="activity-title" name="activity-title" placeholder="Enter activity title" required>
      </div>

      <!-- Booking Date -->
      <div class="form-group">
        <label for="booking-date">Select Date:</label>
        <input type="date" id="booking-date" name="booking_date" required>
      </div>

      <!-- Time Slots -->
      <div class="time-slots">
        <label>Select Time Slots (Click start time, then end time):</label>
        <div class="slots">
          <button type="button" class="time-slot" data-time="07:00:00">7:00 AM</button>
          <button type="button" class="time-slot" data-time="08:00:00">8:00 AM</button>
          <button type="button" class="time-slot" data-time="09:00:00">9:00 AM</button>
          <button type="button" class="time-slot" data-time="12:00:00">12:00 PM</button>
          <button type="button" class="time-slot" data-time="13:00:00">1:00 PM</button>
          <button type="button" class="time-slot" data-time="14:00:00">2:00 PM</button>
          <button type="button" class="time-slot" data-time="15:00:00">3:00 PM</button>
          <button type="button" class="time-slot" data-time="16:00:00">4:00 PM</button>
        </div>
        <div id="time-selection-info">No time slots selected</div>
      </div>

      <button class="submit-btn" type="submit">Submit</button>
    </form>
  </main>

  <script>
    // Time slot selection
    const timeSlots = document.querySelectorAll('.time-slot');
    let startTime = null;
    let endTime = null;

    timeSlots.forEach(button => {
      button.addEventListener('click', function() {
        const time = this.getAttribute('data-time');
        
        // If clicking the current start time, deselect it
        if (startTime === time && this.classList.contains('selected-start')) {
          this.classList.remove('selected-start');
          startTime = null;
          if (endTime) {
            // Also clear end time if start is cleared
            document.querySelector(`.time-slot[data-time="${endTime}"]`).classList.remove('selected-end');
            endTime = null;
          }
          updateTimeSelectionInfo();
          updateSlotAvailability();
          return;
        }
        
        // If clicking the current end time, deselect it
        if (endTime === time && this.classList.contains('selected-end')) {
          this.classList.remove('selected-end');
          endTime = null;
          updateTimeSelectionInfo();
          updateSlotAvailability();
          return;
        }
        
        // If no start time selected, this becomes start time
        if (!startTime) {
          // Clear any existing selections
          timeSlots.forEach(btn => btn.classList.remove('selected-start', 'selected-end'));
          
          this.classList.add('selected-start');
          startTime = time;
          endTime = null;
          updateTimeSelectionInfo();
          updateSlotAvailability();
          return;
        }
        
        // If start time is selected but no end time
        if (startTime && !endTime) {
          // Only allow selection if time is after start time
          if (time > startTime) {
            this.classList.add('selected-end');
            endTime = time;
          } else {
            alert("End time must be after start time");
            return;
          }
          
          updateTimeSelectionInfo();
          updateSlotAvailability();
          return;
        }
      });
    });

    function updateSlotAvailability() {
      timeSlots.forEach(button => {
        const time = button.getAttribute('data-time');
        button.classList.remove('disabled');
        
        // If we have both start and end, disable all others
        if (startTime && endTime) {
          if (time !== startTime && time !== endTime) {
            button.classList.add('disabled');
          }
        }
        // If we only have start time, disable earlier times
        else if (startTime) {
          if (time <= startTime && time !== startTime) {
            button.classList.add('disabled');
          }
        }
      });
    }

    function updateTimeSelectionInfo() {
      const infoDiv = document.getElementById('time-selection-info');
      
      if (!startTime && !endTime) {
        infoDiv.textContent = 'No time slots selected - click your start time';
        infoDiv.style.backgroundColor = '#f5f5f5';
      } else if (startTime && !endTime) {
        const timeStr = formatTimeForDisplay(startTime);
        infoDiv.textContent = `Start time: ${timeStr} - now click your end time`;
        infoDiv.style.backgroundColor = '#fff3e0';
      } else if (startTime && endTime) {
        const startTimeStr = formatTimeForDisplay(startTime);
        const endTimeStr = formatTimeForDisplay(endTime);
        infoDiv.textContent = `Selected time: ${startTimeStr} to ${endTimeStr} (click either to change)`;
        infoDiv.style.backgroundColor = '#e8f5e9';
      }
    }

    function formatTimeForDisplay(timeString) {
      const [hours, minutes] = timeString.split(':');
      const date = new Date();
      date.setHours(parseInt(hours));
      date.setMinutes(parseInt(minutes));
      return date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    }

    function prepareStudentSubmission() {
      const bookingDate = document.getElementById('booking-date').value;
      const fullName = document.getElementById('full-name').value;
      const phoneNumber = document.getElementById('phone-number').value;
      
      // Get additional fields
      const department = document.getElementById('department').value;
      const idNumber = document.getElementById('id-number').value;
      const activityTitle = document.getElementById('activity-title').value;

      if (!startTime || !endTime || !bookingDate || !fullName || !phoneNumber || !department || !idNumber || !activityTitle) {
        alert('Please fill out all required fields and select both start and end times.');
        return false;
      }

      // Add hidden fields to the form before submission
      const form = document.querySelector('.booking-form');
      form.insertAdjacentHTML('beforeend', `
        <input type="hidden" name="full_name" value="${fullName}">
        <input type="hidden" name="phone-number" value="${phoneNumber}">
        <input type="hidden" name="booking_date" value="${bookingDate}">
        <input type="hidden" name="start_time" value="${startTime}">
        <input type="hidden" name="end_time" value="${endTime}">
        <input type="hidden" name="department" value="${department}">
        <input type="hidden" name="id_number" value="${idNumber}">
        <input type="hidden" name="activity_title" value="${activityTitle}">
      `);

      return true;
    }
  </script>
</body>
</html>