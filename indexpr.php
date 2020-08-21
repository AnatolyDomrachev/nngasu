<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link href="../style.css" rel="stylesheet">
</head>
<body>

<div class = hd2 id = hd2>
<?php
        $a=preg_split('/', __DIR__);
	$c=count($a);
	$c--;
	$p = $a[$c];
	echo"<a href = ..>Главная</a> / $p";
?>
</div>

<?php
if(isset($_POST[group])){
	$g = trim($_POST[group]);
	if(preg_match("/^[\p{Cyrillic}0-9\s\- ]+$/u", $g)){
		mkdir("$g");
        copy ('../indexgr.php',"$g/index.php");
        if( ! $db = new SQLite3("$g/base.sql")) die($sqliteerror);
        $db->query('create table tem ( tema varchar(50), utime bigint )') or  die($sqliteerror);
        $db->query('create table pos (id varchar(50), begin bigint, end bigint, tem varchar(50), fname varchar(50));') or  die($sqliteerror);
        header("Location:".$_SERVER['PHP_SELF']);
    }
	else
		echo "<font color=red>$g - неверное имя группы</font>";
}
?>

<div class = list id = list>

    <?php
    $gg = glob("*");
    foreach($gg as $g){
        if(preg_match("/^[\p{Cyrillic}0-9\s\- ]+$/u", $g))
            echo "<a href=\"$g?p=$p&g=$g\">$g<a><p>";
    }


    session_start();
    if( $_SESSION['role'] == 'prep' )
        echo '<button onclick = "add()">Добавить группу</button>
        <button onclick = "get()">Получить все файлы</button>';
    
?>

</div>


<script type="text/javascript" src="../jquery.min.js"></script>
<script type="text/javascript">

function add(){
	var html = ' \
Добавление группы \
<form method=POST action = index.php> \
<input name="group" type = text > \
<input name="set" type = hidden value = true > \
<input value = "Добавить" type = submit></form>';
		document.getElementById("add").innerHTML=html;
}

function get(){
		document.getElementById("add").innerHTML="Создаём архив...";
		$.ajax({
		  url: 'arhiv.php',
		  success: function(data) {
		    document.getElementById("add").innerHTML="Архив создан.";
		    document.location.href = "nngasu-student-files.zip";
		}
	});
}

</script>
<div id = "add"></div>



