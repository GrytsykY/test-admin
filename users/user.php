<?php
session_start();
require_once('../db/db.php');
session_start();
if (!$_SESSION['user']) {
  header('Location: /index.php');
}

$emails = $_SESSION['user']['email'];

$db = new Database();
$db->getConnection();
$users = $db->selectAll('users');
//print_r($users);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <link rel="stylesheet" href="/css/style.css">
</head>

<body>
  <div class="container">
    <div class="center">
      <h3 class="text-center">Пользователь <b></b></h3>
      <?php foreach ($users as $user) : ?>
        <?php if ($emails == $user['email']) : ?>
          <div class="card" style="width: 25rem;">
            <img src="../images/<?= $user['img'] ? $user['img'] : 'no_file.png' ?>" class="card-img-top" alt="...">
            <div class="card-body">
              <h5 class="card-title"><?= $user['fio'] ?></h5>
              <p class="card-text">Email <?= $user['email'] ?></p>
              <p class="card-text">Телефон <?= $user['phone'] ?></p>
              <a href="../users/update.php?upt=<?= $user['id'] ?>" class="btn btn-primary">Редактировать</a>
              <a class="btn btn-primary" href="../auth/logout.php">ВЫХОД</a>
            </div>
          </div>
        <?php endif ?>
      <?php endforeach ?>

    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>
</body>

</html>