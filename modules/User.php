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
}
