<?php

require_once "../lib/dbconnect.php";
require_once "../lib/board.php";
require_once "../lib/game.php";
require_once "../lib/users.php";

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);


// print "Path_info=".$_SERVER['PATH_INFO']."\n";
// print_r($request);

switch ($r=array_shift($request)) {
    case 'board' :
        switch ($b=array_shift($request)) {
            case '':
            case null: handle_board($method);
                        break;
        }

    case 'status':
        if(sizeof($request)==0) {
            handle_status($method);}
			else {
                header("HTTP/1.1 404 Not Found");}
			break;
    case 'players': handle_player($method, $request,$input);
            break;
default:  header("HTTP/1.1 404 Not Found");
                    exit;                              
}


function handle_board($method) {
    if($method=='GET') {
        show_board();
    } else if ($method=='POST') {
        reset_board();
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
    } 
}

function handle_status($method){
    if($method=='GET') {
        show_status();
    } else {
        header('HTTP/1.1 405 Method Not Allowed');
    }

}

function handle_player($method, $p,$input) {
    $b=array_shift($p);
    if($b=='' or null){
        if($method=='GET'){show_users($method);}
        elseif($method=='PUT'){set_user($input);}
        else{header("HTTP/1.1 400 Bad Request");
            print json_encode(['errormesg'=>"Method $method not allowed here."]);}
        
    }else{show_user($method,$b);}
    
    // switch ($b=array_shift($p)) {
	
    
    
    //     //	case '':
	// //	case null: if($method=='GET') {show_users($method);}
	// //			   else {header("HTTP/1.1 400 Bad Request"); 
	// //					 print json_encode(['errormesg'=>"Method $method not allowed here."]);}
    // //                break;
    //     case 'B': 
	// 	case 'W': handle_user($method, $b,$input);
	// 				break;
	// 	default: header("HTTP/1.1 404 Not Found");
	// 			 print json_encode(['errormesg'=>"Player $b not found."]);
    //              break;
	// }
}


                    


?>