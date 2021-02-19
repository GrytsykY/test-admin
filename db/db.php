<?php
class Database
{
  private $host = 'localhost';
  private $db_name = 'test-admin';
  private $username = 'root';
  private $password = 'root';
  public $conn;
  public $table;

  public function getConnection()
  {
    $this->conn = null;

    try {
      $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
      $this->conn->exec('set names utf8');
    } catch (PDOException $exception) {
      echo 'Connection error: ' . $exception->getMessage();
    }

    return $this->conn;
  }


  public function setTable($table)
  {
    $this->table = $table;

    return $this;
  }

  public function selectAll($table)
  {
    $query = "SELECT * FROM `$table`";

    $stmt = $this->conn->prepare($query);

    $stmt->execute();

    $rows = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $rows[] = $row;
    }

    return $rows;
  }

  public function insertUsers($img, $fio, $phone, $email, $pass, $role, $ban)
  {
    $data = [
      'img' => $img,
      'email' => $email,
      'pass' => $pass,
      'fio' => $fio,
      'phone' => $phone,
      'roles' => $role,
      'ban' => $ban
    ];

    $query = "INSERT INTO users (img, email, pass, fio, phone, roles, ban)
    VALUES 
    (:img,:email,:pass,:fio,:phone,:roles,:ban)";

    $stmt = $this->conn->prepare($query);
    //print_r($data);
    $stmt->execute($data);
    //var_dump($stmt->errorInfo());
    return true;
  }

  public function updateAdmin($img, $fio, $phone, $email, $pass, $roles, $ban, $id)
  {
    //echo $img . '-' . $fio . '-' . $email . '-' . $pass . '-' . $phone . '-' . $roles . '-' . $ban . '-' . $id;

    $data = [
      'img' => $img,
      'email' => $email,
      'pass' => $pass,
      'fio' => $fio,
      'phone' => $phone,
      'roles' => $roles,
      'ban' => $ban,
      'id' => $id,
    ];

    $query = $this->conn->prepare(
      "UPDATE `users` SET 
      `img` = :img,
      `email` = :email,
      `pass` = :pass,
      `fio` = :fio,
      `phone` = :phone,
      `roles` = :roles,
      `ban` = :ban 
    WHERE
      `id` = :id"
    );

    $query->execute($data);

    return true;
  }

  public function getOne($id)
  {
    $sth = $this->conn->prepare("SELECT * FROM `users` WHERE `id` = ?");
    $sth->execute(array($id));
    $value = $sth->fetch(PDO::FETCH_COLUMN);
    return $value;
  }

  public function delete($id)
  {
    $query = "DELETE FROM users WHERE id=$id";

    $stmt = $this->conn->exec($query);

    return $stmt;
  }

  public function deleteAll()
  {
    $query = "TRUNCATE TABLE `users`";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return true;
  }
}
