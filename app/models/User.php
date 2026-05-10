<?php

require_once '../../core/Database.php';
class User
{
    private $id;
    private $name;
    private $email;
    private $password;
    protected $db;

    public function __construct(
        $name = null,
        $password = null,
        $email = null
    ) {
        $this->name = $name;
        $this->password = $password;
        $this->email = $email;

        $this->db = Database::getInstance();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function findByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = '$email'";

        return $this->db->select($query);
    }

    public function create()
    {
        $hashedPassword = password_hash(
            $this->password,
            PASSWORD_DEFAULT
        );

        $name = $this->name;
        $email = $this->email;

        $query = "
        INSERT INTO users(name,email,password)
        VALUES (
            '$name',
            '$email',
            '$hashedPassword'
        )
    ";

        return $this->db->insert($query);
    }
}
