<?php
session_start();
require_once('../db/db.php');

$db = new Database();
$db->getConnection();
$users = $db->selectAll('users');

$post = $_POST;

if ($post) {
  $flag = 0;
  $ban = 0;
  foreach ($users as $key => $value) {

    if ($post['email'] == $value['email'] && password_verify($post['password'], $value['pass'])) {
      if ($value['roles'] == 1) {
        $_SESSION['user'] = $post;
        header('Location: /admin/admin.php');;
      } else {
        if ($value['ban'] == 0) {
          $_SESSION['user'] = $post;
          header('Location: /users/user.php');
        } else {
          $error[] = "Доступ закрыт администратором";
          $ban++;
          break;
        }
      }
    } else {
      $flag++;
    }
  }
  if ($flag != 0 && $ban == 0) {
    $error[] = "Некорректный логин или пароль";
    $flag = 0;
    $ban = 0;
  }
}
//print_r($post);
//$_SESSION['user'] = $post;



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
      <form method="post">
        <h3 class="text-center">Авторизация</h3>
        <div>
          <ul>
            <?php if ($error) foreach ($error as $key => $value) : ?>
              <li style="color:red"><?= $value ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div>
          <div class="mb-3">
            <label for="Email1" class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= $post['email'] ?>" required>
            <div id="emailHelp" class="form-text"></div>
          </div>
          <div class="mb-3">
            <label for="example" class="form-label">Пароль</label>
            <input type="password" name="password" class="form-control" id="examp" required>
          </div>

          <button type="submit" class="btn btn-primary">Войти</button>
          <a type="button" href="../index.php" class="btn btn-primary">Назад</a>
      </form>
    </div>
  </div>


</body>

</html>