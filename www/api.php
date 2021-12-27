<?php

require_once "../lib/dbconnect.php";
require_once "../lib/board.php";
require_once "../lib/game.php";
require_once "../lib/users.php";
require_once "../lib/pieces.php";


$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$input = json_decode(file_get_contents('php://input'),true);

if($input==null) {
    $input=[];
}
if(isset($_SERVER['HTTP_X_TOKEN'])) {
    $input['token']=$_SERVER['HTTP_X_TOKEN'];
} else {
    $input['token']='';
}


// print "Path_info=".$_SERVER['PATH_INFO']."\n";
// print_r($request);

switch ($r=array_shift($request)) {
    case 'board' :
        switch ($b=array_shift($request)) {
            case '':
            case null: handle_board($method);
                        break;
            // case 'piece':handle_piece($method, $request[0], $input);
            //             break;
            default:header("HTTP/1.1 404 Not Found");
                        break;        
        }break;
    case 'piece':handle_piece($method,$request[0],$input);
                    break;

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
}

function handle_piece($method, $request, $input){
    if($request=='select'){
        if($method=='POST'){
            select_piece($input);
        }elseif($method=='GET'){
            getSelectedPiece();
        }
    }elseif($request=='move'){
        move_piece($input);
    }
}

// function handle_piece2($method,$request,$input){
//     if($request=='select'){
//         if($method=='POST'){
//             select_piece($input);
//         }
//     }
// }


                    


?>