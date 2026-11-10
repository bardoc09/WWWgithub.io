<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Visitor Booking Form</title>
  <link rel="stylesheet" href="booking-visitor.css">
  <style>
    .cost { text-align: right; padding-right: 20px; }
    #total-cost { font-weight: bold; }
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
    }
  </style>
</head>
<body>
  <header class="header">
    <a href="classification.html" class="back-button" title="Back to Classification">&#8592;</a>
    <h1>Visitor Form</h1>
  </header>

  <main class="main-container">
    <form class="booking-form" method="POST" action="submit_booking.php" onsubmit="return prepareSubmission()">
      <input type="hidden" name="booker_type" value="visitor">

      <div class="name-phone-row">
        <div class="form-group">
          <label for="full-name">Full Name:</label>
          <input type="text" id="full-name" name="full_name" placeholder="Enter your full name" required>
        </div>
        <div class="form-group">
          <label for="phone-number">Phone Number:</label>
          <input type="number" id="phone-number" name="phone" placeholder="Enter your phone number" required>
        </div>
      </div>

      <div class="form-group">
        <label for="activity-title">Activity Title:</label>
        <input type="text" id="activity-title" name="activity_title" placeholder="Enter activity title" required>
      </div>

      <div class="services-table">
        <h2>Service Description and Costs</h2>
        <table>
          <thead>
            <tr>
              <th>Description</th>
              <th>Unit Cost</th>
              <th>Hours</th>
              <th>Cost</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>MMC Room Use (Electricity + ACUs)</td>
              <td>₱500.00/hr</td>
              <td><input type="number" min="0" max="24" value="" class="hours" data-rate="500" oninput="calculateCost(this)"></td>
              <td class="cost">₱0.00</td>
            </tr>
            <tr>
              <td>Sound System w/ Operator</td>
              <td>₱1,500.00/4 hrs</td>
              <td><input type="number" min="0" max="24" value="" class="hours" data-rate="1500" oninput="calculateCost(this)"></td>
              <td class="cost">₱0.00</td>
            </tr>
            <tr>
              <td>Projector & Screen</td>
              <td>₱1,500.00/set/day</td>
              <td><input type="number" min="0" max="24" value="" class="hours" data-rate="1500" oninput="calculateCost(this)"></td>
              <td class="cost">₱0.00</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3" style="text-align: right;">Total Cost:</td>
              <td id="total-cost">₱0.00</td>
            </tr>
          </tfoot>
        </table>
      </div>

      <div class="booking-container">
        <h2>Select Date</h2>
        <div class="booking-grid">
          <input type="date" id="booking-date" name="booking_date" required />
          <h3>Select Time Slots (Click start time, then end time)</h3>
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

    // Cost calculation functions
    function calculateCost(input) {
      const row = input.closest('tr');
      const rate = parseFloat(input.getAttribute('data-rate'));
      const hours = parseFloat(input.value) || 0;
      const costCell = row.querySelector('.cost');
      const cost = rate * hours;
      costCell.textContent = '₱' + cost.toFixed(2);
      updateTotalCost();
    }

    function updateTotalCost() {
      let total = 0;
      document.querySelectorAll('.hours').forEach(input => {
        const rate = parseFloat(input.getAttribute('data-rate'));
        const hours = parseFloat(input.value) || 0;
        total += rate * hours;
      });
      document.getElementById('total-cost').textContent = '₱' + total.toFixed(2);
    }

    function prepareSubmission() {
      const bookingDate = document.getElementById('booking-date').value;
      const fullName = document.getElementById('full-name').value;
      const phoneNumber = document.getElementById('phone-number').value;
      const activityTitle = document.getElementById('activity-title').value;

      if (!startTime || !endTime) {
        alert('Please select both start and end times');
        return false;
      }

      if (!bookingDate || !fullName || !phoneNumber || !activityTitle) {
        alert('Please fill out all required fields');
        return false;
      }

      let totalCost = 0;
      document.querySelectorAll(".hours").forEach(input => {
        const rate = parseFloat(input.getAttribute("data-rate"));
        const hours = parseInt(input.value) || 0;
        totalCost += rate * hours;
      });

      const form = document.querySelector('.booking-form');
      form.insertAdjacentHTML('beforeend', `
        <input type="hidden" name="full_name" value="${fullName}">
        <input type="hidden" name="phone" value="${phoneNumber}">
        <input type="hidden" name="booking_date" value="${bookingDate}">
        <input type="hidden" name="start_time" value="${startTime}">
        <input type="hidden" name="end_time" value="${endTime}">
        <input type="hidden" name="activity_title" value="${activityTitle}">
        <input type="hidden" name="total_payment" value="${totalCost.toFixed(2)}">
      `);

      return true;
    }

    // Initialize calculations
    document.querySelectorAll('.hours').forEach(input => {
      input.addEventListener('input', function() {
        calculateCost(this);
      });
      calculateCost(input);
    });
  </script>
</body>
</html>