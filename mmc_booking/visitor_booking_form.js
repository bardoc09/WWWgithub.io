function updateIndividualCost(input) {
    const rate = parseFloat(input.getAttribute("data-rate"));
    const hours = parseInt(input.value) || 0;
    const itemCost = rate * hours;
  
    const costCell = input.closest("tr").querySelector(".cost");
    costCell.textContent = `₱${itemCost.toFixed(2)}`;
  }
  
  function updateTotalCost() {
    let total = 0;
    document.querySelectorAll(".cost").forEach(cell => {
      const amount = parseFloat(cell.textContent.replace("₱", "")) || 0;
      total += amount;
    });
    document.getElementById("total-cost").textContent = `₱${total.toFixed(2)}`;
  }
  
  document.querySelectorAll(".hours").forEach(input => {
    input.addEventListener("input", () => {
      updateIndividualCost(input);
      updateTotalCost();
    });
  });
  
// Select all time slot buttons
const timeSlotButtons = document.querySelectorAll(".time-slot");

// Add click event listener to each button
timeSlotButtons.forEach(button => {
    button.addEventListener("click", () => {
        // Remove 'active' class from all buttons
        timeSlotButtons.forEach(btn => btn.classList.remove("active"));

        // Add 'active' class to the clicked button
        button.classList.add("active");
    });
});
