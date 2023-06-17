<?php
	$conn = new mysqli('localhost', 'root', '', 'school_bus');

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}

	
date_default_timezone_set('Africa/Dar_es_Salaam');

function fetch_user_last_activity($user_id, $conn)
{
	$query = "
	SELECT * FROM login_details 
	WHERE user_id = '$user_id' 
	ORDER BY last_activity DESC 
	LIMIT 1
	";
	$statement = $conn->prepare($query);
	$statement->execute();

	$result = $statement->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
	foreach($result as $row)
	{
		return $row['last_activity'];
	}
}

function fetch_user_chat_history($sender_id, $recipient_id, $conn)
{
	$query = "
	SELECT * FROM messages 
	WHERE (sender_id = '".$sender_id."' 
	AND recipient_id = '".$recipient_id."') 
	OR (sender_id = '".$recipient_id."' 
	AND recipient_id = '".$sender_id."') 
	ORDER BY timestamp DESC
	";
	$statement = $conn->prepare($query);
	$statement->execute();
	$result = $statement->get_result(); 
	$count = $result->num_rows;
	$output = '<ul class="list-unstyled">';
	foreach($result as $row)
	{
		$user_name = '';
		$dynamic_background = '';
		$chat_message = '';
		if($row["sender_id"] == $sender_id)
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
				$user_name = '<b class="text-success">You</b>';
			}
			else
			{
				$chat_message = $row['content'];
				$user_name = '<button type="button" class="btn btn-danger btn-xs remove_chat" id="'.$row['id'].'">x</button>&nbsp;<b class="text-success">You</b>';
			}
			

			$dynamic_background = 'background-color:#ffe6e6;';
		}
		else
		{
			if($row["status"] == '2')
			{
				$chat_message = '<em>This message has been removed</em>';
			}
			else
			{
				$chat_message = $row["content"];
			}
			$user_name = '<b class="text-danger">'.get_user_name($row['sender_id'], $conn).'</b>';
			$dynamic_background = 'background-color:#ffffe6;';
		}
		$output .= '
		<li style="border-bottom:1px dotted #ccc;padding-top:8px; padding-left:8px; padding-right:8px;'.$dynamic_background.'">
			<p>'.$user_name.' - '.$chat_message.'
				<div align="right">
					- <small><em>'.$row['timestamp'].'</em></small>
				</div>
			</p>
		</li>
		';
	}
	$output .= '</ul>';
	$query = "
	UPDATE messages
	SET status = '0' 
	WHERE sender_id = '".$recipient_id."' 
	AND recipient_id = '".$sender_id."' 
	AND status = '1'
	";
	$statement = $conn->prepare($query);
	$statement->execute();
	return $output;
}

function get_user_name($user_id, $conn)
{
	$query = "SELECT * FROM admins WHERE id = '$user_id'";
	$statement = $conn->prepare($query);
	$statement->execute();
	$result = $statement->get_result(); 
	$count = $result->num_rows;
	if(empty($count))
	{
	$query = "SELECT * FROM parents WHERE id = '$user_id'";
	$statement = $conn->prepare($query);
	$statement->execute();
	$result = $statement->get_result(); 
	$count = $result->num_rows;
	}

	if(empty($count))
	{
	$query = "SELECT * FROM drivers WHERE licence = '$user_id'";
	$statement = $conn->prepare($query);
	$statement->execute();
	$result = $statement->get_result(); 
	$count = $result->num_rows;
	}

	foreach($result as $row)
	{
		$username = $row['firstName'] . ' ' . $row['lastName'];
		return $username;
	}
}

function count_unseen_message($sender_id, $recipient_id, $conn)
{
	$query = "
	SELECT * FROM messages 
	WHERE sender_id = '$sender_id' 
	AND recipient_id = '$recipient_id' 
	AND status = '1'
	";
	$statement = $conn->prepare($query);
	$statement->execute();
	$result = $statement->get_result(); 
	$count = $result->num_rows;
	$output = '';
	if($count > 0)
	{
		$output = '<span class="label text-danger fw-bold border border-2 rounded p-1">'.$count.'</span>';
	}
	return $output;
}

function count_all_unseen_message($current_user, $conn)
{
	$query = "
	SELECT * FROM messages WHERE
	recipient_id = '$current_user' 
	AND status = '1'
	";
	$statement = $conn->prepare($query);
	$statement->execute();
	$result = $statement->get_result(); 
	$count = $result->num_rows;
	$output = '';
	if($count > 0)
	{
		$output = '<span class="label text-danger fw-bold border  rounded p-1">'.$count.'</span>';
	}
	return $output;
}

function fetch_is_type_status($user_id, $conn)
{
	$query = "
	SELECT is_type FROM login_details 
	WHERE user_id = '".$user_id."' 
	ORDER BY last_activity DESC 
	LIMIT 1
	";	
	$statement = $conn->prepare($query);
	$statement->execute();
	$result = $statement->get_result(); 
	$count = $result->num_rows;
	$output = '';
	foreach($result as $row)
	{
		if($row["is_type"] == 'yes')
		{
			$output = ' - <small><em><span class="text-muted">Typing...</span></em></small>';
		}
	}
	return $output;
}


?>