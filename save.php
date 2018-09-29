<?
if($_GET['path'])
{	header("Content-type: application/x-file-to-save");
	header("Content-Disposition: attachment; filename=".end(explode("/",$_GET['path'])));
	readfile(urldecode($_GET['path']));
	exit();
}
?>