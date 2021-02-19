<?php
session_start();
require_once('../db/db.php');

$db = new Database();
$db->getConnection();

$users = $db->selectAll('users');


$data = $_POST;
$_SESSION['user'] = $data;
$ses = $_SESSION['user'];

$myimg = '../images/' . basename($_FILES['file']['name']);
$img = $_FILES['file']['name'] ? $_FILES['file']['name'] : 'no_file.png';
if (move_uploaded_file($_FILES['file']['tmp_name'], $myimg)) {
  $img = $_FILES['file']['name'];
} else {
}


if ($data['name'] && $data['phone'] && $data['email'] && $data['password'] && $data['password_confirmation']) {
  if ($data['password'] == $data['password_confirmation']) {
    $flag = 0;
    if (!empty($users)) {
      foreach ($users as $value) {
        if ($value['email'] == $data['email']) {
          $error[] = "Такой email уже существует";
          $flag++;
          session_destroy();
          break;
        }
      }
      if ($flag != 1) {
        $hash = password_hash($data['password'], PASSWORD_BCRYPT);
        $img = $img;
        $name = $data['name'];
        $phone = $data['phone'];
        $email = $data['email'];
        $pass = $hash;
        $role = 0;
        $ban = 0;

        $insert = $db->insertUsers($img, $name, $phone, $email, $pass, $role, $ban);
        $flag = 0;
        header('Location: /users/user.php');
      }
    } else {
      $hash = password_hash($data['password'], PASSWORD_BCRYPT);
      $img = $img;
      $name = $data['name'];
      $phone = $data['phone'];
      $email = $data['email'];
      $pass = $hash;
      $role = 1;
      $ban = 0;

      $insert = $db->insertUsers($img, $name, $phone, $email, $pass, $role, $ban);
      header('Location: /admin/admin.php');
    }
  } else {
    $error[] = "Пароли не совпадают";
  }
}


if (empty($users)) {
  $success[] = "Вы регистрируетесь в роли администратора";
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

  <script>
    jQuery(function($) {
      $("#phone").mask("+38(999) 999-99-99");
    });
  </script>
</head>

<body>
  <div class="container">
    <div class="center">
      <h3 class="text-center">Регистрация</h3>
      <div>
        <ul>
          <?php if ($error) foreach ($error as $key => $value) : ?>
            <li style="color:red"><?= $value ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div>
        <ul>
          <?php if ($success) foreach ($success as $key => $value) : ?>
            <li style="color:red"><?= $value ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="">
        <form class="form-horizontal" method="POST" action="" enctype="multipart/form-data">
          <div class="form-group">
            <label for="file" class="col-md-4 control-label">Аватар</label>
            <div class="col-md-12">
              <input id="file" type="file" class="form-control" name="file" value="rtrgjhgj" placeholder="Введите имя">
            </div>
          </div>

          <div class="form-group">
            <label for="name" class="col-md-4 control-label">ФИО</label>
            <div class="col-md-12">
              <input id="name" type="text" class="form-control" name="name" value="<?= $ses['name'] ? $ses['name'] : '' ?>" placeholder="Введите ФИО" required>
            </div>
          </div>

          <div class="form-group">
            <label for="phone" class="col-md-4 control-label">Номер телефона</label>
            <div class="col-md-12">
              <input type="tel" class="form-control" placeholder="+38(XXX)XXX-XXXX" value="<?= $ses['phone'] ? $ses['phone'] : '' ?>" id="phone" name="phone" required />

              <span class="help-block">
                <strong></strong>
              </span>
            </div>
          </div>

          <div class="form-group">
            <label for="email" class="col-md-4 control-label">E-Mail</label>

            <div class="col-md-12">
              <input id="email" type="email" class="form-control" name="email" value="<?= $ses['email'] ? $ses['email'] : '' ?>" placeholder="Введите email" required>

              <span class="help-block">
                <strong></strong>
              </span>
            </div>
          </div>

          <div class="form-group">
            <label for="password" class="col-md-4 control-label">Пароль</label>
            <div class="col-md-12">
              <input id="myInput" type="password" class="form-control" name="password" required>
            </div>
          </div>

          <div class="form-group">
            <label for="password-confirm" class="col-md-4 control-label">Подтвердите Пароль</label>

            <div class="col-md-12">
              <input id="myInput2" type="password" class="form-control" name="password_confirmation" required>
              <input type="checkbox" onclick="myFunction()">Показать пароли
            </div>
          </div>
          <br>

          <div class="form-group">
            <div class="col-md-12 col-md-offset-4">
              <button type="submit" class="btn btn-primary">
                Регистрация
              </button>
              <a type="button" href="../index.php" class="btn btn-primary">Назад</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function myFunction() {
      var x = document.getElementById("myInput");
      var x2 = document.getElementById("myInput2");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
      if (x2.type === "password") {
        x2.type = "text";
      } else {
        x2.type = "password";
      }
    }
  </script>
</body>

</html>