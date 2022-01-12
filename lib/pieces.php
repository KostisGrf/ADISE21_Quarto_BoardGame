<?php

require_once "../lib/users.php"; 
require_once "../lib/game.php";
require_once "../lib/board.php";



function read_available_pieces(){
    header('Content-type: application/json');
    global $mysqli;

    $sql = 'select * from pieces where is_available=1';
	    $st = $mysqli->prepare($sql);
	    $st->execute();
	    $res = $st->get_result();
        return $res;
}
    
function select_piece($input){

    $res=read_available_pieces();    
    $available_pieces=array();
    while($row=$res->fetch_assoc()){
        array_push($available_pieces,$row['id']);
    }
    
    $player = current_player($input['token']);
    if($player==null ) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"You are not a player of this game."]);
		exit;
	}
	$status = read_status();
    if($status['result']!=null) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"The game is over."]);
		exit;
	}
	if($status['status']!='started') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"Game is not in action."]);
		exit;
	}
    if($status['p_turn']!=$player) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"It is not your turn."]);
		exit;
    }
    if($status['selected_piece']!=null){
        header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"There is already a selected piece."]);
		exit;
    }
    if($input['piece_id']<=0 || $input['piece_id']>16){
        header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"The piece number must be in the range 1-16"]);
		exit;
    }
    if(!in_array($input['piece_id'],$available_pieces)){
        header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"this piece is not available."]);
		exit;
    }
    

    do_select($input['piece_id']);
}

function show_available_pieces(){
    $res=read_available_pieces();
    print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}
       
    
function do_select($id){
    global $mysqli;
	
    $sql = 'call select_piece(?)';
    $st = $mysqli->prepare($sql);
    $st->bind_param('i',$id);
	$st->execute();

    header('Content-type: application/json');
	print json_encode(['message'=>"the piece has been selected."]);

}

function readSelectedPiece(){
    global $mysqli;

    $sql='SELECT selected_piece FROM game_status';
    $st = $mysqli->prepare($sql);
    $st->execute();
    $res = $st->get_result();

    return($res->fetch_assoc());
}
    
function getSelectedPiece(){
    $res=readSelectedPiece();
    print json_encode($res, JSON_PRETTY_PRINT);
}

function move_piece($input){
    
    $player = current_player($input['token']);
	if($player==null ) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"You are not a player of this game."]);
		exit;
	}
	$status = read_status();
    if($status['result']!=null) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"The game is over."]);
		exit;
	}
	if($status['status']!='started') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"Game is not in action."]);
		exit;
	}
    if($status['p_turn']!=$player) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"It is not your turn."]);
		exit;
    }
    if($status['selected_piece']==null){
        header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"You must choose a piece for your opponent."]);
		exit;
    }
    if($input['x']<=0 ||$input['x']>4||$input['y']<=0||$input['y']>4){
        header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"This move is illegal."]);
		exit;
    }

    if(empty_cell($input['x'],$input['y'])){
        do_move($input['x'],$input['y']);
        if(check_win()){
            setResult($player);
            exit;
        }elseif(check_draw()){
            $draw='d';
            setResult($draw);
            exit;
        }
        
    }else{
        header("HTTP/1.1 400 Bad Request");
        print json_encode(['errormesg'=>"You cannot place here."]);
        exit;}
}


function empty_cell($x,$y){
    global $mysqli;

    $sql='SELECT piece_id FROM board WHERE x=? and y=?';
    $st = $mysqli->prepare($sql);
    $st->bind_param('ii',$x,$y);
    $st->execute();
    $res = $st->get_result();
    $piece_id=$res->fetch_assoc();
    if($piece_id['piece_id']==null){
        return true;
    }else{return false;}
}

function do_move($x,$y){
    global $mysqli;
	
    $selected_piece=readSelectedPiece();
    $sql = 'call move_piece(?,?,?)';
    $st = $mysqli->prepare($sql);
    $st->bind_param('iii',$selected_piece['selected_piece'],$x,$y);
	$st->execute();

    header('Content-type: application/json');
	print json_encode(['message'=>"the move has been completed."]);
}

function readPieceId($input){
    global $mysqli;

    $sql = 'select * from pieces where id=?';
    $st = $mysqli->prepare($sql);
    $st->bind_param('i',$input['piece_id']);
	$st->execute();
    $res = $st->get_result();
    print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}


?>