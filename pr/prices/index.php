<?
include '../template/header.php';
headerOut('Цены товаров', array('prices'));
include '../template/jstree/jstree.php';
putTree('prices', '../products/');
include('../template/footer.php');
?>
