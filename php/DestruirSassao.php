<?php
    session_start();
    destruirSassao();
    function destruirSassao(){
        session_destroy();
        
        }
?>