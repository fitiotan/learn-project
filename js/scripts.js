// scripts.js

function sendMessage() {
    const message = userInput.value.trim();
    const fileInput = document.getElementById("fileUpload");
    const file = fileInput.files[0];

    if (!message && !file) return;

    appendMessage("你", message || (file ? `[檔案] ${file.name}` : ""));
    userInput.value = "";

    const formData = new FormData();
    formData.append("message", message);
    if (file) {
        formData.append("file", file);
    }

    fetch("gpt.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        appendMessage("AI", data.reply || "發生錯誤，請稍後再試。");
        if (file) {
            fileInput.value = ""; // clear file input after sending
        }
    })
    .catch(() => appendMessage("AI", "無法連線到伺服器。"));
}


document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById("userInput");

    input.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            if (!event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        }
    });

    const toggleButton = document.getElementById("chatToggleBtn");
    if (toggleButton) {
        toggleButton.addEventListener("click", function() {
            const chatContainer = document.getElementById("chatContainer");
            chatContainer.classList.toggle("active");
        });
    }
});