<div id="chat-circle-toggle" class="btn shadow-lg">
    <i class="bi bi-chat-dots-fill"></i>
</div>

<div id="chat-box-wrapper" class="chat-box-main shadow-lg">
    <div class="chat-header p-3 d-flex justify-content-between align-items-center">
        <span class="fw-bold small text-uppercase text-white"><i class="bi bi-robot me-2"></i>Hỗ trợ Travela</span>
        <button type="button" class="btn-close btn-close-white" id="chat-box-close"></button>
    </div>
    
    <div class="chat-body p-3" id="chat-content-display">
        <div class="text-start mb-2">
            <div class="msg-ai-bubble shadow-sm">
                Chào <strong><?php echo $_SESSION['fullname'] ?? 'bạn'; ?></strong>! Mình có thể giúp gì cho bạn về các Tour du lịch không?
            </div>
        </div>
    </div>

    <div class="chat-footer p-2 border-top border-secondary">
        <div class="input-group">
            <input type="text" id="chat-input-field" class="form-control form-control-sm shadow-none" placeholder="Nhập nội dung...">
            <button class="btn btn-send-custom btn-sm" id="chat-btn-send">
                <i class="bi bi-send-fill text-white"></i>
            </button>
        </div>
    </div>
</div>

<style>
    /* Nút bấm tròn màu xanh lá */
    #chat-circle-toggle { 
        position: fixed; 
        bottom: 25px; 
        right: 25px; 
        width: 60px; 
        height: 60px; 
        border-radius: 50%; 
        background-color: #27ae60 !important; 
        display: flex !important; 
        align-items: center; 
        justify-content: center; 
        z-index: 10000; 
        border: none;
        color: white;
        font-size: 24px;
        transition: all 0.3s ease;
    }
    #chat-circle-toggle:hover { transform: scale(1.1); background-color: #219150 !important; }

    /* Khung chat chính - Tone Đen */
    .chat-box-main { 
        position: fixed; 
        bottom: 95px; 
        right: 25px; 
        width: 330px; 
        background: #1a1a1a; /* Nền đen nhạt */
        border-radius: 15px; 
        overflow: hidden; 
        z-index: 10000; 
        border: 1px solid rgba(255,255,255,0.1);
        display: none; 
        flex-direction: column;
        box-shadow: 0 10px 40px rgba(0,0,0,0.6) !important;
    }

    .chat-header { background: #27ae60; }

    /* Vùng nội dung chat */
    #chat-content-display { 
        height: 380px; 
        overflow-y: auto; 
        background: #121212; /* Nền vùng chat tối hơn */
        display: flex; 
        flex-direction: column;
        gap: 12px;
        scroll-behavior: smooth;
    }

    /* Bubble tin nhắn AI (Xám đen) */
    .msg-ai-bubble {
        background: #2c2c2c;
        color: #e0e0e0;
        padding: 10px 14px;
        border-radius: 15px 15px 15px 2px;
        font-size: 0.85rem;
        max-width: 85%;
        border: 1px solid rgba(255,255,255,0.05);
    }

    /* Bubble tin nhắn User (Xanh lá) */
    .msg-user-bubble {
        background: #27ae60;
        color: white;
        padding: 10px 14px;
        border-radius: 15px 15px 2px 15px;
        font-size: 0.85rem;
        max-width: 85%;
        text-align: left;
    }

    /* Footer & Input */
    .chat-footer { background: #1a1a1a; border-color: #333 !important; }
    #chat-input-field { 
        background: #2c2c2c; 
        border: none; 
        color: white; 
        font-size: 0.85rem;
        border-radius: 8px 0 0 8px;
    }
    #chat-input-field::placeholder { color: #888; }
    .btn-send-custom { background: #27ae60; border-radius: 0 8px 8px 0; }

    /* Custom Scrollbar */
    #chat-content-display::-webkit-scrollbar { width: 4px; }
    #chat-content-display::-webkit-scrollbar-thumb { background: #27ae60; border-radius: 10px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const circleBtn = document.getElementById('chat-circle-toggle');
    const chatWrapper = document.getElementById('chat-box-wrapper');
    const closeBtn = document.getElementById('chat-box-close');
    const inputField = document.getElementById('chat-input-field');
    const sendBtn = document.getElementById('chat-btn-send');
    const contentDisplay = document.getElementById('chat-content-display');

    circleBtn.onclick = function() {
        chatWrapper.style.setProperty('display', 'flex', 'important');
        circleBtn.style.setProperty('display', 'none', 'important');
        inputField.focus();
    };

    closeBtn.onclick = function() {
        chatWrapper.style.setProperty('display', 'none', 'important');
        circleBtn.style.setProperty('display', 'flex', 'important');
    };

    async function handleSendMessage() {
        const messageText = inputField.value.trim();
        if (messageText === "") return;

        // User Message
        const userHtml = `
            <div class="text-end mb-2">
                <div class="msg-user-bubble d-inline-block shadow-sm">
                    ${messageText}
                </div>
            </div>`;
        contentDisplay.insertAdjacentHTML('beforeend', userHtml);
        inputField.value = "";
        contentDisplay.scrollTop = contentDisplay.scrollHeight;

        // Loading AI
        const loadingId = 'loading-' + Date.now();
        const loadingHtml = `
            <div class="text-start mb-2" id="${loadingId}">
                <div class="msg-ai-bubble d-inline-block shadow-sm">
                    <span class="spinner-border spinner-border-sm text-success"></span> Đang trả lời...
                </div>
            </div>`;
        contentDisplay.insertAdjacentHTML('beforeend', loadingHtml);
        contentDisplay.scrollTop = contentDisplay.scrollHeight;

        try {
            const response = await fetch('/tour_web/chat_bot.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'message=' + encodeURIComponent(messageText)
            });
            const data = await response.text();

            document.getElementById(loadingId).remove();
            const botHtml = `
                <div class="text-start mb-2">
                    <div class="msg-ai-bubble d-inline-block shadow-sm">
                        ${data}
                    </div>
                </div>`;
            contentDisplay.insertAdjacentHTML('beforeend', botHtml);
            contentDisplay.scrollTop = contentDisplay.scrollHeight;

        } catch (error) {
            document.getElementById(loadingId).innerHTML = '<div class="msg-ai-bubble text-danger">Lỗi kết nối server!</div>';
        }
    }

    sendBtn.onclick = handleSendMessage;
    inputField.onkeydown = function(e) { 
        if (e.key === 'Enter') {
            e.preventDefault();
            handleSendMessage(); 
        }
    };
});
</script>