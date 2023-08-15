const settingsButton = document.getElementsByClassName("settings")[0];
const settingsModal = document.getElementById("settings-modal");
const closeButton = document.getElementById("close-modal");
const modalWrapper = document.getElementById("modal-wrapper");

// Settings button to open modal
settingsButton.addEventListener("click", function () {
  settingsModal.classList.add("open");
});


// Close button
closeButton.addEventListener("click", function () {
  settingsModal.classList.remove("open");
});


// CLose modal when clicking outside of it
settingsModal.addEventListener("click", function (e) {
  if (e.target == settingsModal) {
    settingsModal.classList.remove("open");
  }
});