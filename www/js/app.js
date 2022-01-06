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
		case 'player_name': load_content(btn); break;
		case 'rules': load_content(btn); break;
		case 'about': load_content(btn); break;
		case 'play': draw_empty_board(); break;
		default: load_not_found(); break;
	}
}

function load_content(page) {
	$('#maincontent').load("pages/"+page+".html");
}


function draw_empty_board() {
	var t='<table id="quarto_table">';
	for(var i=1;i!=5;i++) {
		t += '<tr>';
		for(var j=1;j!=5;j++) {
			t += '<td class="quarto_square" id="square_'+j+'_'+i+'">' + j +','+i+'</td>'; 
		}
		t+='</tr>';
	}
	t+='</table>';
	
    $('#maincontent').html(t);
}

function load_not_found(){
    $('#maincontent').html('<h1>Page not found<h1>');
}