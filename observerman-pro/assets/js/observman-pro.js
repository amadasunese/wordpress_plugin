(function ($) {
    'use strict';

    /* ===============================
       Chat toggle handlers
    =============================== */

    // Open chat
    $(document).on('click', '#observman-chat-toggle', function () {
        $('#observman-chat-widget').addClass('open');
    });

    // Close chat
    $(document).on('click', '#observman-chat-close', function () {
        $('#observman-chat-widget').removeClass('open minimized');
    });

    // Minimize chat
    $(document).on('click', '#observman-chat-minimize', function () {
        $('#observman-chat-widget').toggleClass('minimized');
    });

    /* ===============================
       Message helpers
    =============================== */

    function appendMessage(role, message) {
        const chatBody = $('#observman-chat-body');

        const html = `
            <div class="observman-message ${role}">
                <span>${message}</span>
            </div>
        `;

        chatBody.append(html);
        chatBody.scrollTop(chatBody[0].scrollHeight);
    }

    function showTyping() {
        const chatBody = $('#observman-chat-body');

        const typingHtml = `
            <div class="observman-message ai observman-typing">
                <span>ObserverMan is typing…</span>
            </div>
        `;

        chatBody.append(typingHtml);
        chatBody.scrollTop(chatBody[0].scrollHeight);
    }

    function removeTyping() {
        $('.observman-typing').remove();
    }

    /* ===============================
       Send message
    =============================== */

    $(document).on('click', '#observman-send-btn', function (e) {
        e.preventDefault();

        const input = $('#observman-chat-input');
        const message = input.val().trim();

        if (!message) return;

        input.val('');

        appendMessage('user', $('<div>').text(message).html());
        showTyping();

        $('#observman-send-btn').prop('disabled', true);

        $.ajax({
            url: observermanPro.ajaxUrl,
            method: 'POST',
            dataType: 'json',
            data: {
                action: 'observerman_chat',
                nonce: observermanPro.nonce,
                message: message,
                post_id: observermanPro.postId
            }
        })
        .done(function (res) {
            removeTyping();

            if (!res.success && res.data && res.data.message) {
                appendMessage('ai', res.data.message);
                return;
            }

            if (res.success && res.data.reply) {
                appendMessage('ai', res.data.reply);
            } else {
                appendMessage('ai', 'Sorry, I could not process that request.');
            }
        })
        .fail(function () {
            removeTyping();
            appendMessage('ai', 'A network error occurred. Please try again.');
        })
        .always(function () {
            $('#observman-send-btn').prop('disabled', false);
        });
    });
    

    /* ===============================
       Enter-to-send support
    =============================== */

    $(document).on('keydown', '#observman-chat-input', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            $('#observman-send-btn').trigger('click');
        }
    });

// })(jQuery);
})(window.jQuery || jQuery);
