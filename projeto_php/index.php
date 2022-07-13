<?php 
require_once 'core/int.php';

$sql="SELECT produto.idprod, produto.nome, produto.cor, preco.idpreco, preco.preco FROM  produto LEFT JOIN preco ON produto.idpreco = preco.idpreco ORDER BY nome";
$results=$db->query($sql);
$errors = array();
//Editar Produto
if (isset($_GET['edit']) && !empty($_GET['edit'])) {
  $edit_id = (int)$_GET['edit'];
  $edit_id = sanitize($edit_id);
  $sql2="SELECT produto.idprod, produto.nome, produto.cor, preco.idpreco, preco.preco FROM  produto LEFT JOIN preco ON produto.idpreco = preco.idpreco WHERE idprod='$edit_id'";
  $edit_result = $db->query($sql2);
  $eproduto = mysqli_fetch_assoc($edit_result);

}
//Apagar Produto
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
  $delete_id = (int)$_GET['delete'];
  $delete_id = sanitize($delete_id);
  $sql="DELETE FROM  produto WHERE idprod='$delete_id'";
  $sql2="DELETE FROM  preco WHERE idpreco='$delete_id'";
  $db->query($sql);
  $db->query($sql2);
  echo "<script>alert('Apagado Com sucesso'); window.location = './index.php';</script>";


}
// Se adicionar

if (isset($_POST['add_submit'])) {
  $prod_nome = sanitize($_POST['nome']);
  $prod_cor = sanitize($_POST['cor']);
  $prod_preco = sanitize($_POST['preco']);
  if ($_POST['nome']== '' || $_POST['cor']== '' || $_POST['preco']== '') {
    $errors[].='Por favor insira todos os campos';
  }
  // Verificar se o produto ja existe
  $sql="SELECT * FROM  produto WHERE nome='$prod_nome'";
  if (isset($_GET['edit'])) {
    $sql="SELECT * FROM  produto WHERE nome='$prod_nome' AND idprod != '$edit_id'";
  }
  $result=$db->query($sql);
  $count = mysqli_num_rows($result);
  if ($count>0) {
    $errors[].=$prod_nome.' Este produto ja exite na base de dados';
  }
  if (!empty($errors)) {
    echo display_errors($errors);
  }else {
    // adicionar na base de dados 
    $sql= "INSERT INTO preco (preco) VALUES ('$prod_preco')";
    $sql2= "INSERT INTO produto (idpreco,nome,cor) VALUES (LAST_INSERT_ID(), '$prod_nome', '$prod_cor')";

    if (isset($_GET['edit'])) {
      $sql = "UPDATE preco SET preco ='$prod_preco' WHERE idpreco  = '$edit_id'";
      $sql2 = "UPDATE produto SET nome ='$prod_nome', cor = '$prod_cor' WHERE idprod  = '$edit_id'";
    }
    $db->query($sql);
    $db->query($sql2);
    echo "<script>alert('Salvo'); window.location = './index.php';</script>";


  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
  <title>PHP projeto</title>
</head>
<body>
<div class="principal">
  <div class="produto">
    <h2>Inserir Produto</h2>
    <form class="" action="index.php<?=((isset($_GET['edit'])))?'?edit='.$edit_id:'';?>" method="post">
 <div class="">
 
      <label><?=((isset($_GET['edit'])))?'Editar':'Adicionar';?> Produto</label>
    </div>
     <div class="form-group mx-sm-3 mb-2">
        <?php 
        $prod_nome = '';
        $prod_cor = '';
        $prod_preco = '';
      if (isset($_GET['edit'])) {
    $prod_nome = $eproduto['nome'];
    $prod_cor = $eproduto['cor'];
    $prod_preco = $eproduto['preco'];

  } else{
    if (isset($_POST['brand'])) {
      $brand_value = sanitize($_POST['brand']);
    }

  } ?>
      <input type="text" name="nome" id="nome" value="<?=$prod_nome; ?>" placeholder="Nome do Produto">
       <input type="text" name="cor" id="cor" class="" value="<?=$prod_cor; ?>" placeholder="Cor">
        <input type="text" name="preco" id="preco" class="" value="<?=$prod_preco; ?>" placeholder="Preço">
        </div>
        <?php if (isset($_GET['edit'])) : ?>
          <a href="index.php" class="">Cancelar</a>

         <?php endif;  ?>
      <input class="" name="add_submit" type="submit" value="<?=((isset($_GET['edit'])))?'Edita':'Adicionar';?> Produto">
</form>
  
</div>
<div class="container">
  <div class="row">
<h2>Produtos</h2>
<p>Digite algo no campo de entrada para pesquisar na tabela por nome do produto, cor ou preço:</p>  
<input id="myInput" type="text" placeholder="Search..">
<br><br>
<table>
  <thead>
  <tr>
    <th>Nome do Produto</th>
    <th>Cor</th>
    <th>Preço</th>
    <th>Acão</th>
  </tr>
  </thead>
  <tbody id="myTable">
  <?php while ($productdesc = mysqli_fetch_assoc($results)) : ?>
  <tr>
     <td><?=$productdesc['nome'];?></td>
    <td><a href="selecor.php?select=<?=$productdesc['cor']; ?>" class=""><?=$productdesc['cor'];?></a></td>
    <td>R$ <?=$productdesc['preco'];?></td>
    <td>
      <a href="selecprodut.php?select=<?=$productdesc['idprod']; ?>" class="">Selecionar</a>
      <a href="index.php?edit=<?=$productdesc['idprod']; ?>" class="">Editar</a>
      <a href="index.php?delete=<?=$productdesc['idprod']; ?>" class="">Apagar</a>
    </td>
  </tr>
  <?php endwhile; ?>
  </tbody>
</table>
</div>
</div>
</div>
</body>
</html>