<?php
//error_reporting(E_ALL); ini_set('display_errors', '1');
//TODO select id, strftime('%d.%m',begin,'unixepoch') from pos where id = 'Федя Мухин' group by strftime('%d.%m',begin,'unixepoch');
//select strftime('%d.%m',utime,'unixepoch') as dt from tem where dt not in (select strftime('%d.%m',begin,'unixepoch') from pos  where id = 'Петя Васин') group by dt;
//select tema,strftime('%d.%m',utime,'unixepoch') as dt from tem where tema not in (select tem from pos where id = 'Петя Васин') group by tema;

?>
<head>
  <meta charset="utf-8">
  <title></title>
  <link href="../../style.css" rel="stylesheet">
 </head>



<div class = hd2 id = hd2></div>

<div id = "tem" class = tem>

<?php
    $db = new SQLite3("base.sql") or die($sqliteerror);
    $res = $db->query(' select * from tem order by utime desc limit 1 ') or  die($sqliteerror);
    $arr = $res->fetchArray();
    $tem = $arr['tema'];
    $utime = $arr['utime'];
    $now = date('U');

    session_start();
    
    
    //Print tema
    if( $now - $utime < 1.5*60*60 )
    {
        echo "Тема занятия: $tem";
    }
    else
        if( $_SESSION['role'] == 'prep' )
            echo'<button onclick = "tem()">Новая тема</button>';

 ?>

<p>
<table style="font-size:10pt" border =1>

<?php
    $res = $db->query("select * from tem");
    while($arr = $res->fetchArray())
    {
        echo "<tr><td>" .date('d.m',$timestamp = $arr['utime'])." <td> $arr[tema]";
    }

?>
</table>


<?php
if( $_SESSION['role'] == 'prep' ){
    echo "<p> <table style='font-size:10pt' border =1>";


    $pp = glob("*");
    $list = "";
    foreach($pp as $p)
        if(preg_match("/^[\p{Cyrillic} ]+$/u", $p)){
            echo "<tr><td>$p";
            $res = $db->query("select strftime('%d.%m',utime,'unixepoch') as dt from tem where tema not in (select tem from pos where id = '$pp') group by dt");
            while($arr = $res->fetchArray())
            {
                echo "<td>" .$arr['dt'];
            }
        }

    echo "</table>";
}
?>

</div>

<div class = list id = list></div>

<div id = "add"></div>



<?php

//Create student
if(isset($_POST['s'])){
	$s = trim($_POST[s]);
	if(preg_match("/^[\p{Cyrillic} ]+$/u", $s)){
		mkdir("$s");
		copy ('../../indexst.php',"$s/index.php");
	}
	else
		echo "<font color=red>$s - неверное имя группы</font>";
}


//Create tema
if(isset($_POST['tem'])){
	$t = trim($_POST[tem]);
        $now = date("U");
        $db->query("insert into tem values ( \"$_POST[tem]\" , $now )") or  die($sqliteerror);
        header("Location:".$_SERVER['PHP_SELF']);
    }

?>



<script type="text/javascript" src="../../jquery.min.js"></script>
<script>


<?php

//Print navigation
//echo "DIR=";echo __DIR__;
$a=preg_split("/[\/,]+/", __DIR__);
//echo "a=$a";
$c=count($a);
$c--;
$g = $a[$c];
$c--;
$p = $a[$c];
$hd2 = "<a href = ../..>Главная</a> / <a href = .. >$p</a> / $g";
echo" document.getElementById('hd2').innerHTML='$hd2'; \n";


// Print students
$pp = glob("*");
$list = "";
foreach($pp as $p)
	if(preg_match("/^[\p{Cyrillic} ]+$/u", $p))
		$list = "$list <a href = \"$p\">$p</a><br>";
        $list = "$list <p> <button onclick =add()>Новый студент</button>";
    echo"    document.getElementById('list').innerHTML='$list';\n  ";

?>




function add(){
    var html = ' \
    <h3>Введите ФИО</h3> \
    <form method=POST action = index.php> \
    <input name=s type = text size = 50> \
    <input name=set type = hidden value = true > \
    <input value = Добавить type = submit></form>';
    document.getElementById("add").innerHTML=html;
}



function tem(){
        var html = ' \
        Добавление темы занятия \
        <form method=POST action = index.php> \
        <input name="tem" type = text > \
        <input value = "Добавить" type = submit></form>';
        
        document.getElementById("tem").innerHTML=html;
}

</script>







