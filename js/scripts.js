/*!
* Start Bootstrap - Shop Homepage v5.0.6 (https://startbootstrap.com/template/shop-homepage)
*/

function sendMessage() {
    const input = document.getElementById("userInput");
    const message = input.value.trim();
    if (!message) return;

    const chatbox = document.getElementById("chatbox");
    chatbox.innerHTML += `<div><strong>你:</strong> ${message.replace(/\n/g, '<br>')}</div>`;

    // Load existing history or start new one
    let history = JSON.parse(sessionStorage.getItem("chatHistory") || "[]");

    // Add user's message to history
    history.push({ role: "user", content: message });

    // Send the whole conversation history to the server
    fetch("gpt.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ history: history })
    })
    .then(response => response.json())
    .then(data => {
        const reply = data.reply;
        chatbox.innerHTML += `<div><strong>AI:</strong> ${reply}</div>`;

        // Add AI's reply to history
        history.push({ role: "assistant", content: reply });
        sessionStorage.setItem("chatHistory", JSON.stringify(history));

        chatbox.scrollTop = chatbox.scrollHeight;
    });

    input.value = "";
}

// Handle Enter and Shift+Enter
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

    document.getElementById("toggleChatbox").addEventListener("click", function() {
        document.getElementById("chatContainer").classList.toggle("expanded");
    });
});