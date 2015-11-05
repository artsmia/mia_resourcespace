<?php

// enumerated types of message.  Note the base two offset for binary combination.
DEFINE ("MESSAGE_ENUM_NOTIFICATION_TYPE_SCREEN",1);
DEFINE ("MESSAGE_ENUM_NOTIFICATION_TYPE_EMAIL",2);
DEFINE ("MESSAGE_ENUM_NOTIFICATION_TYPE_RESERVED_1",4);
DEFINE ("MESSAGE_ENUM_NOTIFICATION_TYPE_RESERVED_2",8);
DEFINE ("MESSAGE_ENUM_NOTIFICATION_TYPE_RESERVED_3",16);

DEFINE ("MESSAGE_DEFAULT_TTL_SECONDS",60 * 60 * 24 * 7);		// 7 days

// ------------------------------------------------------------------------------------------------------------------------

// gets messages for a given user (return true if there are messages, if not false)
// note that messages are passed by reference.
function message_get(&$messages,$user,$get_all=false,$sort_desc=false)
	{
	$messages=sql_query("SELECT user_message.ref, user.username AS owner, user_message.seen, message.created, message.expires, message.message, message.url " .
		"FROM `user_message`
		INNER JOIN `message` ON user_message.message=message.ref " .
		"LEFT OUTER JOIN `user` ON message.owner=user.ref " .
		"WHERE user_message.user='{$user}'" .
		($get_all ? " " : " AND message.expires > NOW()") .
		($get_all ? " " : " AND user_message.seen='0'") .
		" ORDER BY user_message.ref " . ($sort_desc ? "DESC" : "ASC"));
	return(count($messages) > 0);
	}

// ------------------------------------------------------------------------------------------------------------------------

// add a message.
function message_add($users,$text,$url="",$owner=null,$notification_type=MESSAGE_ENUM_NOTIFICATION_TYPE_SCREEN,$ttl_seconds=MESSAGE_DEFAULT_TTL_SECONDS)
	{
	global $userref;

	$text = escape_check($text);
	$url = escape_check($url);

	if (!is_array($users))
		{
		$users=array($users);
		}

	if(is_null($owner))
		{
		$owner=$userref;
		}

	sql_query("INSERT INTO `message` (`owner`, `created`, `expires`, `message`, `url`) VALUES ({$owner}, NOW(), DATE_ADD(NOW(), INTERVAL {$ttl_seconds} SECOND), '{$text}', '{$url}')");
	$message_ref = sql_insert_id();

	foreach($users as $user)
		{
		sql_query("INSERT INTO `user_message` (`user`, `message`) VALUES ($user,$message_ref)");
		}

	}

// ------------------------------------------------------------------------------------------------------------------------

// remove a message from message table and associated user_messages
function message_remove($message)
	{
	sql_query("DELETE FROM user_message WHERE message='{$message}'");
	sql_query("DELETE FROM message WHERE ref='{$message}'");	
	}

// ------------------------------------------------------------------------------------------------------------------------

function message_seen($message,$seen_type=MESSAGE_ENUM_NOTIFICATION_TYPE_SCREEN)
	{
	sql_query("UPDATE `user_message` SET seen=seen | {$seen_type} WHERE `ref`='{$message}'");
	}

// ------------------------------------------------------------------------------------------------------------------------

// flags all non-read messages as read for given user and seen type
function message_seen_all($user,$seen_type=MESSAGE_ENUM_NOTIFICATION_TYPE_SCREEN)
	{
	$messages = array();
	if (message_get($messages,$user))
		{
		foreach($messages as $message)
			{
			message_seen($message['ref']);
			}
		}
	}

// ------------------------------------------------------------------------------------------------------------------------

// remove all messages from message and user_message tables that have expired (regardless of read).  This will be called
// from a cron job.
function message_purge()
	{
	sql_query("DELETE FROM user_message WHERE message IN (SELECT ref FROM message where expires < NOW())");
	sql_query("DELETE FROM message where expires < NOW()");
	}

// ------------------------------------------------------------------------------------------------------------------------

?>
