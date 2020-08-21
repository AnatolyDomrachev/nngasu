<head>
  <meta charset="utf-8">
  <title></title>
  <link href="../../../style.css" rel="stylesheet">
 </head>

<div class = hd2 id = hd2>
<?php
        //$a=split('/', __DIR__);
	$a=preg_split("/[\/,]+/", __DIR__);
	$c=count($a);
	$c--;
	$s = $a[$c];
	$c--;
	$g = $a[$c];
	$c--;
	$p = $a[$c];
	echo"<a href = ../../..>Главная</a> / <a href = ../.. >$p</a> / <a href = .. >$g</a> / $s";
?>
</div>

 <?php
    $db = new SQLite3("../base.sql") or die($sqliteerror);
    $res=$db->query("select * from pos where id = \"$s\" order by begin desc limit 1 ");
    $last_begin = $res->fetchArray()['begin'];
    $now = date("U");
    $res = $db->query(' select * from tem order by utime desc limit 1 ') or  die($sqliteerror);
    $arr = $res->fetchArray();
    $tem = $arr['tema'];
    $tem_time = $arr['utime'];

    $db2 =  new SQLite3("../../../root.sql");
    $res = $db2->query(' select val from settings where var = "lab_time" ');
    $lab_time = $res->fetchArray()[0];
    $res = $db2->query(' select val from settings where var = "lab_time2" ');
    $lab_time2 = $res->fetchArray()[0];
 

//insert begin
 if($_POST[begin])
 {
     //$db->query("insert into pos values ( '$s' , $now , $now , '$tem' , '' )");
     header("Location:".$_SERVER['PHP_SELF']);
 }
?>

<div class = tem2 id = tem2>

<?php
//print button Begin
  if( $last_begin < $tem_time and $now - $tem_time < 2*60*60 )
 {
    echo "Тема занятия: $tem";
    echo "
        <form  method=\"post\">
        <input name=begin type = hidden value = True >
        <input value = \"Начать занятие\" type = submit></form>";
 }

// if($last_begin > $tem_time and $now - $tem_time < 2*60*60 )
// {
     //echo ("Тема занятия: ".$tem."<p> Время начала: ".date('G:i',$timestamp = $last_begin) );
     echo '
          <p>Выберите файл отчёта
          <form enctype="multipart/form-data" method="post"> 
          <input name=f type = file>
          <input name=set type = hidden value = true > 
          <input value = Отправить type = submit></form>
          ';
// }        
?>

</div>

<?php
//Put file
if(isset($_FILES['f']['name'])){
	$fn = $_FILES['f']['name'];
	$fn = str_replace ( "index" , "index__" , $fn );
    move_uploaded_file($_FILES['f']['tmp_name'], $fn);
    header("Location:".$_SERVER['PHP_SELF']);
     $db->query("insert into pos values ( '$s' , $now , $now , '$tem' , '$fn' )");
    //$db->query("update pos set end = $now , fname = '$fn' where id = '$s' and begin = (select max(begin) from pos where id  = '$s' )");
}
?>

<div class = tem>
Статистика:<p>
<table style="font-size:10pt" border =1>
<?php
//Print files
/*
$pp = glob("*");
foreach($pp as $p)
	if(!preg_match("/^index.php/u", $p))
		echo "<p>$p";
*/
    $res = $db->query(" select * from pos where id = '$s' ");
    while($arr = $res->fetchArray())
    {
        $dlit = ($arr['end'] - $arr['begin'])/60;
        echo "<tr><td>" .date('d.m',$timestamp = $arr['begin'])." <td>".round($dlit)." <td> $arr[tem] <td> $arr[fname]";
    }

?>
</table>
</div>


<script>
function add(){
var html = ' \
    <h3>Выберите файл</h3> \
    <form enctype="multipart/form-data" method="post"> \
    <input name=f type = file>\
    <input name=set type = hidden value = true > \
    <input value = Отправить type = submit></form>';
document.getElementById("add").innerHTML=html;
	
}
</script>

<div id = "add"></div>




