<?php
session_start();
require_once('../db/db.php');

if (!$_SESSION['user']) {
  header('Location: /index.php');
}
$db = new Database();
$db->getConnection();
$users = $db->selectAll('users');

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
  <link rel="stylesheet" href="/css/style.css">
</head>

<body>
  <div class="container">
    <h3 class="text-center">Администратор</h3>
    <div>
      <ul>
        <?php if ($error) foreach ($error as $key => $value) : ?>
          <li style="color:red"><?= $value ?></li>
        <?php endforeach; ?>
      </ul>
    </div>

    <table class="table table-striped">
      <tr>
        <th>Фото</th>
        <th>ФИО</th>
        <th>email</th>
        <th>телефон</th>
        <th>роль</th>
        <th>бан</th>
        <th>ред</th>
        <th>удалить</th>
      </tr>

      <?php if ($users) foreach ($users as $key => $value) : ?>
        <tr>
          <td><img width="50px" src="../images/<?= $value['img'] ?>"></td>
          <td><?= $value['fio'] ?></td>
          <td><?= $value['email'] ?></td>
          <td><?= $value['phone'] ?></td>
          <td><?= $value['roles'] ?></td>
          <td><?= $value['ban'] ?></td>
          <td>
            <a href="../admin/update.php?up=<?= $value['id'] ?>" class="btn btn-primary">Ред</a>
          </td>
          <td>
            <a href="../admin/update.php?id=<?= $value['id'] ?>" class="btn btn-danger">
              Удалить
            </a>
          </td>
        </tr>
      <?php endforeach; ?>

    </table>

    <a class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delAll">
      Удалить всех
    </a>
    <a class="btn btn-primary" href="../auth/logout.php">ВЫХОД</a>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="delAll">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="">Вы действительно хотите удалить всех?</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <?= $_GET['id'] ?>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal">НЕТ</button>
          <a href="../admin/update.php?delAll=1" type="button" class="btn btn-danger">ДА</a>
        </div>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js" integrity="sha384-KsvD1yqQ1/1+IA7gi3P0tyJcT3vR+NdBTt13hSJ2lnve8agRGXTTyNaBYmCR/Nwi" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.min.js" integrity="sha384-nsg8ua9HAw1y0W1btsyWgBklPnCUAFLuTMS2G72MMONqmOymq585AcH49TLBQObG" crossorigin="anonymous"></script>

</body>

</html>