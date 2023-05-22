<?php include 'parent/session.php'; ?>
<?php include 'parent/header.php'; ?>
<style>
#chat-container {
    height: 400px;
    overflow-y: scroll;
}

.received-message {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 10px;
}

.sent-message {
    background-color: #007bff;
    color: #fff;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 10px;
}

.message-column {
    height: 400px;
    overflow-y: scroll;
}
</style>

<body class="bg-light" style=" min-height:90vh;">

    <?php include 'parent/navbar.php'; ?>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include 'parent/menubar.php'; ?>
            <div class="col-md-9 mt-3">
                <div class="container bg-white my-3 mx-3">
                    <div class="container">
                        <h2 class="my-4">Live Chat</h2>
                        <div class="row message-column">
                            <div class="col-md-6 ">
                                <div id="received-messages-column" class=""></div>
                            </div>
                            <div class="col-md-6">
                                <div id="sent-messages-column" class=""></div>
                            </div>
                        </div>

                        <form id="message-form" class="d-flex mt-4 mb-4" action="send_message.php" method="POST">
                            <input type="text" id="message-input" class="form-control me-2" name="content"
                                placeholder="Type your message...">
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="bootstrap/js/bootstrap.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Function to display received messages
    function displayReceivedMessages(messages) {
        var receivedMessagesColumn = $('#received-messages-column');
        receivedMessagesColumn.empty();
        for (var i = 0; i < messages.length; i++) {
            var message = messages[i];
            receivedMessagesColumn.append('<div class="received-message">' + message.content + '</div>');
        }
        receivedMessagesColumn.scrollTop(receivedMessagesColumn[0].scrollHeight);
    }

    // Function to display sent messages
    function displaySentMessages(messages) {
        var sentMessagesColumn = $('#sent-messages-column');
        sentMessagesColumn.empty();
        for (var i = 0; i < messages.length; i++) {
            var message = messages[i];
            sentMessagesColumn.append('<div class="sent-message">' + message.content + '</div>');
        }
        sentMessagesColumn.scrollTop(sentMessagesColumn[0].scrollHeight);
    }


    // Function to periodically fetch new messages
    function fetchNewMessages() {
        $.get('fetch_messages.php', function(messages) {
            var receivedMessages = [];
            var sentMessages = [];
            for (var i = 0; i < messages.length; i++) {
                var message = messages[i];
                if (message.sender_id === 'current_user_id') {
                    sentMessages.push(message);
                } else {
                    receivedMessages.push(message);
                }
            }
            displayReceivedMessages(receivedMessages);
            displaySentMessages(sentMessages);
        });
    }

    // Event handler for the message form submission
    $('#message-form').submit(function(event) {
        event.preventDefault();
        var message = $('#message-input').val().trim();
        if (message !== '') {
            sendMessage(message);
        }
    });

    // Periodically fetch new messages every 2 seconds
    setInterval(fetchNewMessages, 2000);
    </script>
</body>

</html>