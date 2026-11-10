// Make time slots clickable and toggle selection
document.querySelectorAll('.slots button').forEach(button => {
    button.addEventListener('click', () => {
      button.classList.toggle('active');
    });
  });