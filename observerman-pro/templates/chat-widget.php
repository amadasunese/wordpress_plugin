<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<!-- ObserverMan Chat Toggle -->
<div id="observman-chat-toggle">
    💬 Chat with ObserverMan
</div>

<!-- ObserverMan Chat Widget -->
<div id="observman-chat-widget">

    <div class="observman-chat-header">
        <span class="observman-chat-title">ObserverMan AI</span>
        <div class="observman-chat-actions">
            <button id="observman-chat-minimize" title="Minimize">—</button>
            <button id="observman-chat-close" title="Close">✕</button>
        </div>
    </div>

    <div id="observman-chat-body"></div>

    <div class="observman-chat-input">
        <textarea
            id="observman-chat-input"
            rows="1"
            placeholder="Ask me anything…"></textarea>
        <button id="observman-send-btn">Send</button>
    </div>

</div>
