const settingsButton = document.getElementsByClassName("settings")[0];
const settingsModal  = document.getElementById("settings-modal");
const closeButton    = document.getElementById("close-modal");

settingsButton.addEventListener("click", function(){
  settingsModal.classList.add("open");
});

closeButton.addEventListener("click", function(){
  settingsModal.classList.remove("open");
});


