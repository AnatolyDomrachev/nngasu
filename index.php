<head>
  <meta charset="utf-8">
  <title></title>
  <link href="style.css" rel="stylesheet">
 </head>



<div  id = hd class = hd2>
    Отправить файл преподавателю 
</div>

<div id = set class = set>

<?php
error_reporting(E_ALL);
session_start();
if( $_SESSION['role'] != 'prep' )
    echo '<button onclick = "login()">Login</button>';
else
    echo "Осуществлён вход преподавателя";
?>

</div> 

<?php
$db = new SQLite3("root.sql") ;      

if(isset($_POST[pas])){
    $res = $db->query("select val from settings where var = 'password'");
    $pas = $res->fetchArray()[0];
    if( $_POST['pas'] == $pas )
        $_SESSION['role']='prep';
    else
        $_SESSION['role']='stud';
    header("Location:".$_SERVER['PHP_SELF']);
}

if(isset($_POST[prep])){
	$p = trim($_POST[prep]);
	if(preg_match("/^[\p{Cyrillic} ]+$/u", $p)){
		mkdir("$p");
		copy('indexpr.php',"$p/index.php");
		copy('arhiv.php',"$p/arhiv.php");
	}
	else
		echo "<font color=red>$p - неверное имя</font>";
}

?>


<div  class = list>
<?php
$pp = glob("*");
sort($pp);
foreach($pp as $p){
	if(preg_match("/^[\p{Cyrillic} ]+$/u", $p))
		echo "<a href = \"$p?p=$p\">$p</a><p>";
}

if( $_SESSION['role'] == 'prep' )
    echo'<button onclick = "add()">Новый преподаватель</button>';

?>
</div>



<script type="text/javascript" src="jquery.min.js"></script>
<script>

function add(){
	var html = ' \
    <h3>Введите ФИО</h3> \
    <form method=POST action = index.php> \
    <input name=prep type = text size = 50> \
    <input name=set type = hidden value = true > \
    <input value = Добавить type = submit></form>';
    
    document.getElementById("add").innerHTML=html;
}

function login(){
	var html = ' \
    <form method=POST action = index.php> \
        Пароль:<input name=pas type = password size = 10> \
    <input value = Авторизация type = submit></form>';
    
    document.getElementById("set").innerHTML=html;
	
}
</script>
<div id = "add"></div>





