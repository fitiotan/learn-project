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

// Handle Enter and Shift+Enter & toggle sliding chat
document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById("userInput");
    const chatContainer = document.getElementById("chatContainer");
    const toggleButton = document.getElementById("toggleChatButton");
    const chatbox = document.getElementById("chatbox");

    // Load chat history from sessionStorage
    let history = JSON.parse(sessionStorage.getItem("chatHistory") || "[]");
    history.forEach(msg => {
        const role = msg.role === "user" ? "你" : "AI";
        chatbox.innerHTML += `<div><strong>${role}:</strong> ${msg.content.replace(/\n/g, "<br>")}</div>`;
    });
    chatbox.scrollTop = chatbox.scrollHeight;

    // Send message on Enter (Shift+Enter new line)
    input.addEventListener("keydown", function(event) {
        if (event.key === "Enter" && !event.shiftKey) {
            event.preventDefault();
            sendMessage();
        }
    });

    // Toggle chat panel sliding in/out
    toggleButton.addEventListener("click", () => {
        if (chatContainer.style.right === "20px") {
            chatContainer.style.right = "-320px";
        } else {
            chatContainer.style.right = "20px";
            input.focus();
        }
    });
});