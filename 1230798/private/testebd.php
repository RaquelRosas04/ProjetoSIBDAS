<?php
require_once __DIR__ . '/../config/config.php';
//try {

    // $ligacao = new PDO(
    //     "mysql:host=vsgate-s1.dei.isep.ipp.pt:10464;dbname=db1230798;charset=utf8",
    //     "1230798",
    //     "rosas_798"
    // );

/* 
        $ligacao = new PDO(
        "mysql:host=127.0.0.1;dbname=db1230798;charset=utf8",
        "root",
        ""
    );
 //*/


    try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );

   
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Ligação à BD realizada com sucesso!";

} catch(PDOException $e) {

    echo "Erro: " . $e->getMessage();
}