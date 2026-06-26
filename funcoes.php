<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

function usuarioLogado(){

    return isset($_SESSION['usuario']);

}

function redirecionar($pagina){

    header("Location: $pagina");
    exit;

}

function limpar($texto){

    return htmlspecialchars(trim($texto));

}

function resumo($texto,$limite=150){

    if(strlen($texto) <= $limite){
        return $texto;
    }

    return substr($texto,0,$limite)." ...";

}

function formatarData($data){

    return date("d/m/Y H:i",strtotime($data));

}