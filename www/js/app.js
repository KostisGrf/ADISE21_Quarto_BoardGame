var me=[{token:null,id:null,username:null}];
var game_status={status:null,selected_piece:null,last_change:null};
var timer=null;


$(function() {
	$(window).on('hashchange', check_status);
	check_status();
});

function check_status() {
	var page = window.location.hash.replace('#','');
	do_goto_page(page);
}

function do_goto_page(page) {
	
	var btn = page;
	switch(btn) {
		case '': load_content('main'); break;
		case 'main': load_content(btn); break;
		case 'player_name': load_content(btn);check_Playername_load (); break;
		case 'rules': load_content(btn); break;
		case 'about': load_content(btn); break;
		case 'play': load_content(btn);check_play_load(); break;
		default: load_not_found(); break;
	}
}

function load_content(page) {
	$('#maincontent').load("pages/"+page+".html");
}


function login_listeners(){
	$('#login-cta').click(function(){
		login_to_game();
	})
}

function play_listeners(){
	$('#quarto_table td').click(function(){
		id=$(this).attr('id');
		a1=id.split(/_/);
		do_move(a1[1],a1[2]);
	})
	$('#available_pieces td').click(function(){
		piece_id=$(this).attr('id');
		a2=piece_id.split(/_/);
		select_piece(a2[1]);
	});
	get_available_pieces();
	if(me[0].id==1){
		update_username1(me);
	}
	else if(me[0].id==2){
		update_username2(me);
	}
}


function do_move(x,y){
	$.ajax({url: "api.php/piece/move", 
			method: 'POST',
			data:JSON.stringify({x:x , y:y}),
			dataType:"json",
			contentType: 'application/json',
			headers: {"X-Token": me[0].token},
			success: move_result,
			error: login_error});
}

function move_result(){
	game_status_update();
}

function select_piece(piece_id){
	$.ajax({url: "api.php/piece/select", 
			method: 'POST',
			data:JSON.stringify({piece_id:piece_id}),
			dataType:"json",
			contentType: 'application/json',
			headers: {"X-Token": me[0].token},
			success: select_result,
			error: login_error});
}

function select_result(){
	game_status_update();
}

function get_available_pieces() {
	$.ajax({url: "api.php/piece/available", 
		headers: {"X-Token": me.token},
		success: fill_available_pieces });
}

function fill_available_pieces(data){
	$('#available_pieces tbody tr td').html("");
	for(var i=0;i<data.length;i++) {
		var o = data[i];
		var id = '#square_'+ o.id
		var im = '<img '+'" src="images/pieces/'+o.id+'.png">';
		$(id).html(im);
	}
}

function fill_board() {
	$.ajax({url: "api.php/board/", 
		success: fill_board_by_data });
}

function fill_board_by_data(data){
	for(var i=0;i<data.length;i++) {
		var o = data[i];
		var id = '#square_'+ o.x +'_' + o.y;
		var c = (o.piece_id!=null)?o.piece_id:'';
		var im = (o.piece_id!=null)?'<img src="images/pieces/'+c+'.png">':'';
		$(id).html(im);
	}
}


function check_Playername_load () {
	if($('#login-cta').is(':visible')){ //if the container is visible on the page
	  login_listeners() 
	} else {
	  setTimeout(check_Playername_load, 50); //wait 50 ms, then try again
	}
  }


  function check_play_load () {
	if($('.play-container').is(':visible')){ //if the container is visible on the page
	  play_listeners();
	} else {
	  setTimeout(check_play_load, 50); //wait 50 ms, then try again
	}
  }

  function login_to_game() {
	if($('#username').val()=='') {
		alert('You have to set a username');
		return;
	}
	
	$.ajax({url: "api.php/players", 
			method: 'PUT',
			dataType: "json",
			contentType: 'application/json',
			data: JSON.stringify( {username: $('#username').val()}),
			success: login_result,
			error: login_error});
}

function login_result(data) {
	me = data;
	window.location.hash="play";
	game_status_update();}


	function login_error(data,y,z,c) {
		var x = data.responseJSON;
		alert(x.errormesg);
	}



	function game_status_update() {
	
		clearTimeout(timer);
		$.ajax({url: "api.php/status/", success: update_status});
	}
	
	function update_status(data) {
		var game_stat_old = game_status;
		game_status=data[0];
		if(game_stat_old.status===null && (game_status.status=="initialized"||game_status.status==="started")){
			if(me[0].id==1){
				update_username1(me);
			}else if(me[0].id==2){
				update_username2(me);
			}
		}

		if((game_stat_old.status===null|| game_stat_old.status==="initialized") && game_status.status==="started"){
			opponentUsername();
		}

		if(me[0].id!=null){
		if(game_stat_old.last_change!=game_status.last_change){
			get_available_pieces();
			fill_board();
			if(game_status.selected_piece!=game_stat_old.selected_piece){
				var id = '#square_'+ game_stat_old.selected_piece;
				$(id).removeClass('selectedPiece');
			}
				if(game_status.p_turn==1){
					$('#player1-arrow').show();
					$('#player2-arrow').hide();
					$('#player2-message').text("Waiting...")
					if(game_status.selected_piece==null){
						$('#player1-message').text("Select piece for your opponent");
					}else{
						var id = '#square_'+ game_status.selected_piece;
						$(id).addClass('selectedPiece');
						$('#player1-message').text("put the selected piece on the board");
					}
					}
				else if(game_status.p_turn==2){
					$('#player2-arrow').show();
					$('#player1-arrow').hide();
					$('#player1-message').text("Waiting...")
					if(game_status.selected_piece==null){
						$('#player2-message').text("Select piece for your opponent");
					}else{
						var id = '#square_'+ game_status.selected_piece;
						$(id).addClass('selectedPiece');
						$('#player2-message').text("put the selected piece on the board");
					}
				}

				if(game_status.result!=null){
					$('#player1-arrow').hide();
					$('#player2-arrow').hide();
					if(game_status.result==1){
						$('#player1-message').text("Winner!!!");
						$('#player2-message').text(":(");
					}
					else if(game_status.result==2){
						$('#player2-message').text("Winner!!!");
						$('#player1-message').text(":(");
					}
					else if(game_status.result==="D"){
						$('#player1-message').text("Draw!");
						$('#player2-message').text("Draw!");
					}
					setTimeout(()=>{alert('The game is over.You will be kicked in 15 seconds')},2500)
					clearTimeout(timer);
					me=[{token:null,id:null,username:null}];
					game_status={status:null,selected_piece:null,last_change:null};
					timer=null;
					setTimeout(reset_board, 15000);
					return;
				}
		}
	}
		
		clearTimeout(timer);
		if(game_status.p_turn==me[0].id &&  me[0].id!=null) {
			timer=setTimeout(function() { game_status_update();}, 15000);
		} else {
			// must wait for something
			timer=setTimeout(function() { game_status_update();}, 4000);
		}
		 
	}

	function update_username1(data){
		$('#player1').text(data[0].username);
	}

	function opponentUsername(){
		if(me[0].id===1){
			$.ajax({url: "api.php/players/2", success: update_username2});
		}else if(me[0].id===2){
			$.ajax({url: "api.php/players/1", success: update_username1});
		}
	}

	function update_username2(data){
		$('#player2').text(data[0].username);
	}

	function reset_board() {
		$.ajax({url: "api.php/board/", 
		 method: 'POST'});
		window.location.hash="player_name";
	}

function load_not_found(){
    $('#maincontent').text('<h1>Page not found<h1>');
}