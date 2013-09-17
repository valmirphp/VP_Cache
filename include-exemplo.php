<?php
/*
 * Esse arquivo sera chamado através da classe VP_Cache
 * Apenas uma vez e depodeis seus dados serão salvos no cache
 */




echo "<br /><b>Data do cache do arquivo: </b> " . date('h:i:s') . "<br />";

sleep(2);

for ($index = 0; $index < 10; $index++) {
    echo "$index: teste; <br />";
}


