<?php 
require_once 'core/int.php';
if (isset($_GET['select']) && !empty($_GET['select'])) {
$cor = $_GET['select'];
$sql="SELECT produto.idprod, produto.nome, produto.cor, preco.idpreco, preco.preco FROM  produto LEFT JOIN preco ON produto.idpreco = preco.idpreco WHERE cor='$cor'";
  $edit_result = $db->query($sql);
  $eproduto = mysqli_fetch_assoc($edit_result);


}
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
 	<title><?=$eproduto['cor'];?></title>
 </head>
 <body>

<h1>Cor: <?=$eproduto['cor'];?></h1>
<p>Nome do Produto: <?=$eproduto['nome'];?></p>
 <?php 
 if ($eproduto['cor']== 'Azul' || $eproduto['cor']== 'Vermelho') {
  $preco = $eproduto['preco'] * 0.2;
  echo "Desconto do produto 20% ", "Preço: R$";
  echo $preco;
 }elseif ($eproduto['cor']== 'Amarelo') {
   $preco = $eproduto['preco'] * 0.1;
  echo "Desconto do produto 10% ", "Preço: R$";
  echo $preco;
 }elseif ($eproduto['cor']== 'Vermelho' || $eproduto['preco'] > 50.00) {
  $preco = $eproduto['preco'] * 0.25;
  echo "Desconto do produto 25% ", "Preço: R$";
  echo $preco;
 }
 else{
  echo "Preço: R$", $eproduto['preco'];
 }
?>
 </body>
 </html>