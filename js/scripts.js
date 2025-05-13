/*!
* Start Bootstrap - Shop Homepage v5.0.6 (https://startbootstrap.com/template/shop-homepage)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-shop-homepage/blob/master/LICENSE)
*/

function sendMessage() {
    const input = document.getElementById("userInput");
    const message = input.value;
    if (!message.trim()) return;

    const chatbox = document.getElementById("chatbox");
    chatbox.innerHTML += `<div><strong>你:</strong> ${message.replace(/\n/g, '<br>')}</div>`;

    fetch("gpt.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message: message })
    })
    .then(response => response.json())
    .then(data => {
        chatbox.innerHTML += `<div><strong>AI:</strong> ${data.reply}</div>`;
        chatbox.scrollTop = chatbox.scrollHeight;
    });

    input.value = "";
}

// Enter/Shift+Enter handling
document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById("userInput");
    input.addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            if (event.shiftKey) {
                // Allow newline
                return;
            } else {
                event.preventDefault();
                sendMessage();
            }
        }
    });

    // Expand/collapse toggle
    const toggleButton = document.getElementById("toggleChatbox");
    toggleButton.addEventListener("click", function() {
        const chatContainer = document.getElementById("chatContainer");
        chatContainer.classList.toggle("expanded");
    });
});