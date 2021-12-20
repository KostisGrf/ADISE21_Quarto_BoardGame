<?php

function show_users() {
	global $mysqli;
	$sql = 'select username,id from players';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}
function show_user($method,$id) {
	global $mysqli;
	$sql = 'select username,id from players where id=?';
	$st = $mysqli->prepare($sql);
	$st->bind_param('i',$id);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}

function set_user($input) {
	$id=0;
    if(!isset($input['username']) || $input['username']=='') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"No username given."]);
		exit;
	}
	$username=$input['username'];
	global $mysqli;
	$sql = 'select count(*) as c from players where id=1 and username is not null';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	$r = $res->fetch_all(MYSQLI_ASSOC);
	if($r[0]['c']>0) {
        $id='2';
        $sql = 'update players set username=?, token=md5(CONCAT( ?, NOW()))  where id=?';
	    $st2 = $mysqli->prepare($sql);
	    $st2->bind_param('ssi',$username,$username,$id);
	    $st2->execute();
	}else{
        $id='1';
        $sql = 'update players set username=?, token=md5(CONCAT( ?, NOW()))  where id=?';
	    $st2 = $mysqli->prepare($sql);
	    $st2->bind_param('ssi',$username,$username,$id);
	    $st2->execute();
    }
	

	update_game_status();
	$sql = 'select * from players where id=?';
	$st = $mysqli->prepare($sql);
	$st->bind_param('i',$id);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
		
}

function current_player($token) {
	
	global $mysqli;
	if($token==null) {return(null);}
	$sql = 'select * from players where token=?';
	$st = $mysqli->prepare($sql);
	$st->bind_param('s',$token);
	$st->execute();
	$res = $st->get_result();
	if($row=$res->fetch_assoc()) {
		return($row['id']);
	}
	return(null);
}

?>