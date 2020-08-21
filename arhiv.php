<head>
  <meta charset="utf-8">
  <title></title>
 </head>

<?php
$ff = glob("*/*/*");

$zip = new ZipArchive;
if ($zip->open('nngasu-student-files.zip', ZipArchive::OVERWRITE) === TRUE) {

foreach($ff as $f)
	if(!preg_match("/index.php*/", $f)){
		echo "<p>$f";
		$newf = iconv('utf-8', 'cp866', $f);
		$zip->addFile($f, $newf);
	}

     $zip->close();
}
else {
    echo '<p>ошибка';
}

die("<p>Выход !!!");

$zip = new ZipArchive();
$ret = $zip->open('application.zip', ZipArchive::OVERWRITE);
if ($ret !== TRUE) {
    printf('Failed with code %d', $ret);
} else {
    $options = array('remove_all_path' => FALSE);
    $zip->addGlob('*/*/*', GLOB_BRACE, $options);
    $zip->close();
}

?>
