<?php

namespace App\Model;


class UserModel extends BaseModel implements \Core\Auth\User
{
    public function saveUser(array $userData): bool
    {
        $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([
            ':username' => $userData['username'],
            ':password' => password_hash($userData['password'], PASSWORD_BCRYPT),
            ':email' => $userData['email'],
        ]);
    }

    public function isEmailUnique(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([':email' => $email]);
        $count = $stmt->fetchColumn();
        return $count == 0;
    }

    public function getUserByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function getUserById(int $id)
    {
        $sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $user ?: null;
    }
}
