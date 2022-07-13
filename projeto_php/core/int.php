<?php 
$db=mysqli_connect('localhost','root','root','projeto_php');
if (mysqli_connect_errno()) {
	echo 'Database connection failel with following errors: '.mysqli_connect_error();
	die();
}
require_once $_SERVER['DOCUMENT_ROOT'].'/projeto_php/config.php';
require_once BASEURL.'helpers/helpers.php';
	
?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             