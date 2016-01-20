
// texto de antes

<?php
$id = 35;
$str = "soy una cadena de texto";
$lista = array(1,2,3);
$objeto = new stdClass();
$objeto->nombre = 'fulanito';
$objeto->apellido = 'menganito';
?>

<?php function ___Blog($data, $flag) {
	print_r($data);
	print_r($flag);
} ?>
<?php ___Blog(array('val' => "soy un valor",'persona' => $objeto,'id' => $id,'cadena' => $str,'lista' => $lista,),array (
  0 => 'flag1',
  1 => 'flag2',
  2 => 'flag3',
)); ?>

// texto de después
