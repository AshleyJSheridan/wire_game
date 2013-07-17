<?php

$pdo = new PDO("mysql:dbname=wire_game;host=localhost", 'root', '', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));

$image_dir = 'assets/img/competition/photos';

if(!empty($_FILES))
{
	var_dump(move_uploaded_file($_FILES['file']['tmp_name'], "$image_dir/{$_FILES['file']['name']}"));
}
/*else
{
	post_image_live('/var/www/html/wire_game/public_html/assets/img/competition/photos/28-20130717080339-01.jpg');
}*/
if(isset($_POST))
{
	// push data live or add otherwise
	$sql = "INSERT INTO tmw_wire_comp
			VALUES ({$_POST['playerId']}, '{$_POST['playerEmail']}', '{$_POST['campaign']}', '{$_POST['registerdOn']}', '{$_POST['RFHandleId']}')
			ON DUPLICATE KEY UPDATE playerId=LAST_INSERT_ID(playerId), RFHandleId='{$_POST['RFHandleId']}'";
	$pdo->query($sql);
	
	$fields = array('question', 'playerProgress', 'playerScore', 'twitterHandle', 'lastname', 'firstname', 'playerTime', 'RFHandleId', 'charity', 'wiredPhoto');
	
	foreach($_POST as $field => $value)
	{
		if(in_array($field, $fields))
		{
			$sql = "SELECT COUNT(*) AS total FROM tmw_wire_comp_details WHERE playerId={$_POST['playerId']} AND detailsField='$field'";
			$result = $pdo->query($sql)->fetch();
			if($result['total'] == 0)
			{
				$insert_sql = "INSERT INTO tmw_wire_comp_details VALUES (null, {$_POST['playerId']}, '$field', '$value');";
				$pdo->query($insert_sql);
			}
			else
			{
				$update_sql = "UPDATE tmw_wire_comp_details SET detailsData = '$value' WHERE playerId={$_POST['playerId']} AND detailsField='$field'";
				$pdo->query($update_sql);
			}
		}
	}
}



function post_image_live($image)
{
	$ch = curl_init();
	$data = array('file' => "@$image");
	curl_setopt($ch, CURLOPT_URL, 'http://wire-game.dev/sync.php');
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($ch);
	
	echo($result);
}