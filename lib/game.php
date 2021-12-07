<?php 
    function show_status(){
        header('Content-type: application/json');
        global $mysqli;
        
        $sql='select * from game_status';
        $st=$mysqli->prepare($sql);
        $st->execute();
        $res=$st->get_result();

        print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
    }

?>