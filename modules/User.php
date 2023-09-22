<?php
class User
{
  private $connection;

  public $id;
  public $first_name;
  public $last_name;
  public $email;
  public $address;
  public $password;
  public $phone;

  public function __construct($db)
  {
    $this->connection = $db;
  }

  public function create()
  {
    $sql = 'INSERT INTO users SET 
    first_name = :first_name,
    last_name = :last_name ,
    email = :email,
    address = :address,
    phone = :phone,
    password = :password';

    $stmt = $this->connection->prepare($sql);

    $this->first_name = htmlspecialchars(strip_tags($this->first_name));
    $this->last_name = htmlspecialchars(strip_tags($this->last_name));
    $this->email = htmlspecialchars(strip_tags($this->email));
    $this->address = htmlspecialchars(strip_tags($this->address));
    $this->phone = htmlspecialchars(strip_tags($this->phone));
    $this->password = htmlspecialchars(strip_tags($this->password));

    $stmt->bindParam(':first_name', $this->first_name);
    $stmt->bindParam(':last_name', $this->last_name);
    $stmt->bindParam(':email', $this->email);
    $stmt->bindParam(':address', $this->address);
    $stmt->bindParam(':phone', $this->phone);

    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

    $stmt->bindParam(':password', $password_hash);

    if ($stmt->execute()) {
      return true;
    }

    return false;
  }
  public function getUser()
  {
    $sql = 'SELECT * FROM users WHERE id = :id';
    $stm = $this->connection->prepare($sql);

    $stm->bindParam(':id', $this->id);

    $stm->execute();
    return $stm;
  }
  public function isEmailExists()
  {

    $sql = 'SELECT id, first_name, last_name, password
            FROM users
            WHERE email = ?
            LIMIT 0,1';

    $stmt = $this->connection->prepare($sql);

    $this->email = htmlspecialchars(strip_tags($this->email));

    $stmt->bindParam(1, $this->email);

    $stmt->execute();

    $num = $stmt->rowCount();
    if ($num > 0) {

      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $this->id = $row['id'];
      $this->first_name = $row['first_name'];
      $this->last_name = $row['last_name'];
      $this->password = $row['password'];
      return true;
    }
    return false;
  }
}
