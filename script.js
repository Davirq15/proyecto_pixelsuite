const darkBtn = document.getElementById("darkModeBtn");
const body = document.body;

darkBtn.addEventListener("click", () => {
    body.classList.toggle("dark-mode");
    darkBtn.textContent = body.classList.contains("dark-mode") ? "☀️" : "🌙";
});
