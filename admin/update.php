<?php
session_start();

if (!$_SESSION['user']) {
  header('Location: /index.php');
}

require_once('../db/db.php');
$db = new Database();
$db->getConnection();
$users = $db->selectAll('users');

if (!empty($_GET['id'])) {
  $db->delete($_GET['id']);
  header('Location: ../admin/admin.php');
}

if (!empty($_GET['delAll'])) {

  $db->deleteAll();
  header('Location: /index.php');
}


if ($_POST) {
  $_SESSION['user'] = $_POST;
  $image = $_POST['file'];
  $em = $_POST['email'];
  if ($image) {
    $img = $image;
  } else {
    foreach ($users as $user) {
      if ($_GET['up'] == $user['id']) {
        $img = $user['img'];
      }
    }
  }
  foreach ($users as $user) {
    if ($_GET['up'] == $user['id']) {
      $pass = $user['pass'];
      if ($_POST['email'] == $user['email']) {
        $email = $user['email'];
        $error[] = "Такой пароль уже существует";
      }
    }
  }
  $fio = $_POST['fio'];
  $phone = $_POST['phone'];
  $roles = $_POST['roles'];
  $ban = $_POST['ban'];
  $id = $_GET['up'];

  $res = $db->updateAdmin($img, $fio, $phone, $email, $pass, $roles, $ban, $id);
  header("Location: ../admin/admin.php");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.2.1/dist/jquery.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.js" type="text/javascript"></script>
  <link rel="stylesheet" href="/css/style.css">
  <script type="text/javascript">
    jQuery(function($) {
      $("#phone").mask("+38(999) 999-99-99");
    });
  </script>
</head>

<body>
  <div class="container">
    <div class="center">
      <form method="POST">
        <h3 class="text-center">Редактирование <b></b></h3>
        <?php foreach ($users as $user) : ?>
          <?php if ($_GET['up'] == $user['id']) : ?>
            <div class="card" style="width: 25rem;">

              <img src="../images/<?= $user['img'] ?>" class="card-img-top" alt="...">
              <div class="card-body">
                <div class="pd-5">
                  <label>Фото</label>
                  <input type="file" name="file">
                  <label>ФИО</label>
                  <input type="text" name="fio" value="<?= $user['fio'] ?>" required /><br>
                  <label>Email</label>
                  <input type="email" name="email" value="<?= $user['email'] ?>" required><br>
                  <label>Телефон</label>
                  <input id="phone" type="tel" name="phone" value="<?= $user['phone'] ?>" required><br>
                  <label>Роль (1)-admin (0)-user</label>
                  <input type="number" name="roles" min="0" max="1" value="<?= $user['roles'] ?>" required><br>
                  <label>Бан (1)-заблок (0)-разблок</label>
                  <input type="number" name="ban" min="0" max="1" value="<?= $user['roles'] ?>" required><br>

                  <button type="submit" class="btn btn-primary">Сохранить</button>
                  <a class="btn btn-primary" href="../admin/admin.php">НАЗАД</a>
                </div>
              </div>
            </div>
          <?php endif ?>
        <?php endforeach ?>
      </form>
    </div>



</body>

</html>