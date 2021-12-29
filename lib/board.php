<?php
    function read_board(){
		header('Content-type: application/json');
        global $mysqli;

		$sql = 'select x,y,piece_id,round_shape,big_size,light_color,top_hole 
				from board left join pieces on board.piece_id=pieces.id';
	    $st = $mysqli->prepare($sql);
	    $st->execute();
	    $res = $st->get_result();
		return($res->fetch_all(MYSQLI_ASSOC));
	}
	
	
	function show_board(){
	    $res = read_board();
	    print json_encode($res, JSON_PRETTY_PRINT);
    }

    function reset_board(){
        global $mysqli;
	
	    $sql = 'call clean_board()';
	    $mysqli->query($sql);
	    show_board();
}

	function showPieceByPosition($input){
		global $mysqli;

		$sql='SELECT piece_id FROM board WHERE x=? and y=?';
		$st = $mysqli->prepare($sql);
        $st->bind_param('ii',$input['x'],$input['y']);
        $st->execute();
		$res = $st->get_result();
		print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
	}

 	function check_win(){
		$orig_board=read_board();
		$board=convert_board($orig_board);
		
		//checks the rows
		for($i=1;$i<=count($board);$i++)
		{
			$result=check_pieces($board[$i][1],$board[$i][2],$board[$i][3],$board[$i][4]);
			if($result){
				return true;
			}
		}
		
		//checks the cols
		for($j=1;$j<=count($board);$j++){
			$result=check_pieces($board[1][$j],$board[2][$j],$board[3][$j],$board[4][$j]);
			if($result){
				return true;
			}
		}
		 
		//checks the first diagonal
		$result=check_pieces($board[1][1],$board[2][2],$board[3][3],$board[4][4]);
		if($result){
			return true;
		}

		//checks the second diagonal
		$result=check_pieces($board[1][4],$board[2][3],$board[3][2],$board[4][1]);
		if($result){
			return true;
		}
		
		return false;
	 }

	 //converts board to 2d array
 	function convert_board(&$orig_board) {
		$board=[];
		foreach($orig_board as $i=>&$row) {
			$board[$row['x']][$row['y']] = &$row;
	} 
		return($board);
}


function check_pieces($a,$b,$c,$d){

	// checks if the positions of the board are empty (no pieces on them)
	if($a['piece_id']==null || $b['piece_id']==null || $c['piece_id']==null || $d['piece_id']==null){
		return false;
	}

	 // checks if there are 4 pieces next to each other with similiar stats
	if($a['big_size']==$b['big_size']&&$a['big_size']==$c['big_size']&&$a['big_size']==$d['big_size']){
		return true;
	}
	elseif($a['light_color']==$b['light_color']&&$a['light_color']==$c['light_color']&&$a['light_color']==$d['light_color']){
		return true;
	}
	elseif($a['top_hole']==$b['top_hole']&&$a['top_hole']==$c['top_hole']&&$a['top_hole']==$d['top_hole']){
		return true;
		
	}
	elseif($a['round_shape']==$b['round_shape']&&$a['round_shape']==$c['round_shape']&&$a['round_shape']==$d['round_shape']){
		return true;
	}
	// if there arent any conditions met, that means that there isn't a winner
	return false;
	
}

function check_draw(){
	global $mysqli;
	
	//if the table is full of pieces and there is no winner the game is a draw
	$sql='SELECT count(*) as c FROM board WHERE piece_id IS NULL';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	$r=$res->fetch_assoc();
	//if the board is full
	if($r['c']==0){
		return true;
	}
	//the board is not full
	return false;
}

?>