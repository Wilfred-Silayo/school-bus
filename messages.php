<?php
include 'parent/session.php'; 
include 'parent/header.php';
?>

<body class="bg-light" style=" min-height:90vh;">
    <?php include 'parent/navbar.php'; ?>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <?php include 'parent/menubar.php'; ?>
            <!-- Content Wrapper. Contains page content -->
            <div class="col-md-9 col-sm-9">
                <div class="container-fluid bg-white my-3">
                    <h3 align="start">Chat With Admins</h3><br />
                    <br />
                    <div class="row">
                        <div class="col-md-8 col-sm-6">
                        </div>
                    </div>
                    <div class="table-responsive">

                        <div id="user_details"></div>
                        <div id="user_model_details"></div>
                    </div>
                    <br />
                    <br />
                </div>
            </div>
        </div>
    </div>
    <script src="bootstrap/js/bootstrap.js"></script>
</body>

</html>

<style>
.chat_message_area {
    position: relative;
    width: 100%;
    height: 50%;
    background-color: #FFF;
    border: 1px solid #CCC;
    border-radius: 3px;
}
</style>

<script>
$(document).ready(function() {
    fetch_user();

    setInterval(function() {
        update_last_activity();
        fetch_user();
        update_chat_history_data();
    }, 5000);

    function fetch_user() {
        $.ajax({
            url: "fetch_admins.php",
            method: "POST",
            success: function(data) {
                $('#user_details').html(data);
            }
        });
    }

    function update_last_activity() {
        $.ajax({
            url: "update_last_activity.php",
            success: function() {

            }
        });
    }



    function make_chat_dialog_box(recipient_id, to_user_name) {
        var modal_content = '<div id="user_dialog_' + recipient_id +
            '" class="user_dialog" title="You have a chat with ' + to_user_name + '">';
        modal_content +=
            '<div style="height:300px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="' +
            recipient_id + '" id="chat_history_' + recipient_id + '">';
        modal_content += fetch_user_chat_history(recipient_id);
        modal_content += '</div>';
        modal_content += '<div class="form-group">';
        modal_content += '<textarea name="chat_message_' + recipient_id + '" id="chat_message_' + recipient_id +
            '" class="form-control chat_message"></textarea>';
        modal_content += '</div><div class="form-group" align="right">';
        modal_content += '<button type="button" name="send_chat" id="' + recipient_id +
            '" class="btn btn-info send_chat">Send</button></div></div>';
        $('#user_model_details').html(modal_content);
    }

    $(document).on('click', '.start_chat', function() {
        var recipient_id = $(this).data('touserid');
        var to_user_name = $(this).data('tousername');
        make_chat_dialog_box(recipient_id, to_user_name);
        $("#user_dialog_" + recipient_id).dialog({
            autoOpen: false,
            width: 400
        });
        $('#user_dialog_' + recipient_id).dialog('open');
    });

    $(document).on('click', '.send_chat', function() {
        var recipient_id = $(this).attr('id');
        var content = $.trim($('#chat_message_' + recipient_id).val());
        if (content != '') {
            $.ajax({
                url: "insert_chat.php",
                method: "POST",
                data: {
                    recipient_id: recipient_id,
                    content: content
                },
                success: function(data) {
                    $('#chat_history_' + recipient_id).html(data);
                    $('#chat_message_' + recipient_id).val('');
                }
            });
        } else {
            alert('Please type something');
        }
    });

    function fetch_user_chat_history(recipient_id) {
        $.ajax({
            url: "fetch_user_chat_history.php",
            method: "POST",
            data: {
                recipient_id: recipient_id
            },
            success: function(data) {
                $('#chat_history_' + recipient_id).html(data);
            }
        });
    }

    function update_chat_history_data() {
        $('.chat_history').each(function() {
            var recipient_id = $(this).data('touserid');
            fetch_user_chat_history(recipient_id);
        });
    }

    $(document).on('click', '.ui-button-icon', function() {
        $('.user_dialog').dialog('destroy').remove();
        $('#is_active_group_chat_window').val('no');
    });

    $(document).on('focus', '.chat_message', function() {
        var is_type = 'yes';
        $.ajax({
            url: "update_is_type_status.php",
            method: "POST",
            data: {
                is_type: is_type
            },
            success: function() {

            }
        });
    });

    $(document).on('blur', '.chat_message', function() {
        var is_type = 'no';
        $.ajax({
            url: "update_is_type_status.php",
            method: "POST",
            data: {
                is_type: is_type
            },
            success: function() {

            }
        });
    });

    $(document).on('click', '.remove_chat', function() {
        var chat_message_id = $(this).attr('id');
        console.log(chat_message_id);
        if (confirm("Are you sure you want to delete this chat?")) {
            $.ajax({
                url: "remove_chat.php",
                method: "POST",
                data: {
                    id: chat_message_id
                },
                success: function(data) {
                    update_chat_history_data();
                }
            });
        }
    });
});
</script>