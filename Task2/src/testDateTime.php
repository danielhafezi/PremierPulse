<?php
    $date = new DateTime('2018-11-20');
    echo $date->format('d/m/Y') . '<br/>'; 
    $date->modify('-2 Days'); 
    echo $date->format('d/m/Y') . '<br/>'; 
    $date->modify('-2 Days'); 
    echo $date->format('d/m/Y'); 
?>