<?php

$pas = $_GET[pas];
$f = file(password);
if($pas - $f[0] == 0)
    echo 1;
else
    echo 0;

?>

