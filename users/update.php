<?php
session_start();
if (!$_SESSION['user']) {
  header('Location: /index.php');
}

$_SESSION['user'] = $_POST;

require_once('../db/db.php');
$db = new Database();
$db->getConnection();
$users = $db->selectAll('users');

function uniqueEmail($email)
{
  $db = new Database();
  $db->getConnection();
  $users = $db->selectAll('users');
  foreach ($users as $value) {
    if ($value['email'] == $email) {
      return 0;
    }
  }
  return 1;
}
//print_r(uniqueEmail('a@a.ru'));


if ($_POST) {

  $myimg = '../images/' . basename($_FILES['file']['name']);
  $img = $_FILES['file']['name'] ? $_FILES['file']['name'] : 'no_file.png';
  if (move_uploaded_file($_FILES['file']['tmp_name'], $myimg)) {
    $img = $_FILES['file']['name'];
  }
  $em = $_POST['email'];
  if ($image) {
    $img = $image;
  } else {
    foreach ($users as $user) {
      if ($_GET['upt'] == $user['id']) {
        $img = $user['img'];
      }
    }
  }
  foreach ($users as $user) {
    if ($_GET['upt'] == $user['id']) {
      $pass = $user['pass'];
      $roles = $user['roles'];
      $ban = $user['ban'];
      if ($_POST['email'] == $user['email']) {
        $email = $_POST['email'] ? $_POST['email'] : $user['email'];
        $error[] = "Такой пароль уже существует";
      } else {
        $email = $_POST['email'];
      }
    }
  }
  $fio = $_POST['fio'];
  $phone = $_POST['phone'];
  $id = $_GET['upt'];

  $res = $db->updateAdmin($img, $fio, $phone, $email, $pass, $roles, $ban, $id);
  header("Location: ../users/user.php");
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
        <div>
          <ul>
            <?php if ($error) foreach ($error as $key => $value) : ?>
              <li style="color:red"><?= $value ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div>
          <?php foreach ($users as $user) : ?>
            <?php if ($_GET['upt'] == $user['id']) : ?>
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

                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a class="btn btn-primary" href="../users/user.php">ВЫХОД</a>
                  </div>
                </div>
              </div>
            <?php endif ?>
          <?php endforeach ?>
      </form>
    </div>
</body>

</html>