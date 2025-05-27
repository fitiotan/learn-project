// scripts.js

function sendMessage() {
    const input = document.getElementById("userInput");
    const message = input.value.trim();
    if (!message) return;

    const chatbox = document.getElementById("chatbox");
    const userMsg = document.createElement("div");
    userMsg.innerHTML = `<strong>你:</strong> ${message.replace(/\n/g, '<br>')}`;
    chatbox.appendChild(userMsg);

    fetch("gpt.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        const aiMsg = document.createElement("div");
        aiMsg.innerHTML = `<strong>AI:</strong> ${data.reply || "沒有回應"}`;
        chatbox.appendChild(aiMsg);
        chatbox.scrollTop = chatbox.scrollHeight;
    })
    .catch(() => {
        const errMsg = document.createElement("div");
        errMsg.innerHTML = `<strong>AI:</strong> 無法連線到伺服器。`;
        chatbox.appendChild(errMsg);
        chatbox.scrollTop = chatbox.scrollHeight;
    });

    input.value = "";
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