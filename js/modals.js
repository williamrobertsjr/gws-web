const askGWSBtn = document.querySelector(".ask-gws-btn");
const askClose = document.querySelector(".ask-close");
const askPopover = document.querySelector("#ask-gws-popover");

// Function to show popover
function showPopover() {
  askPopover.classList.remove('hidden');
  askPopover.classList.remove("animate__fadeOutUp");
  askPopover.classList.add("animate__fadeInDown");
}

// Function to hide popover
function hidePopover() {
  askPopover.classList.remove("animate__fadeInDown");
  askPopover.classList.add("animate__fadeOutUp");
  setTimeout(() => {
    askPopover.classList.add("hidden");
  }, 500); // Adjust timeout to match animation duration
}

// Toggle popover on button click
askGWSBtn.onclick = function (e) {
  e.preventDefault();
  if (askPopover.classList.contains('hidden')) {
    showPopover();
  } else {
    hidePopover();
  }
};

// // Close button inside popover
// askClose.onclick = function () {
//   hidePopover();
// };

// Clicking outside to close popover
document.addEventListener('click', function (e) {
  const isClickInsidePopover = askPopover.contains(e.target);
  const isClickInsideButton = askGWSBtn.contains(e.target);

  if (!isClickInsidePopover && !isClickInsideButton) {
    hidePopover();
  }
});

// Prevent event from closing popover when clicking inside
askPopover.onclick = function(e) {
  e.stopPropagation();
};
