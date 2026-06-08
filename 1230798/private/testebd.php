<?php

try {

    // $ligacao = new PDO(
    //     "mysql:host=vsgate-s1.dei.isep.ipp.pt:10464;dbname=db1230798;charset=utf8",
    //     "1230798",
    //     "raquel_798"
    // );


        $ligacao = new PDO(
        "mysql:host=127.0.0.1;dbname=db1230798;charset=utf8",
        "root",
        ""
    );

    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Ligação à BD realizada com sucesso!";

} catch(PDOException $e) {

    echo "Erro: " . $e->getMessage();
}