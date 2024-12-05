<?php

class UserManager
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllUsers()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM user");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage() . "<br>";
            return [];
        }
    }

    public function deleteUser($userId)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM user WHERE id_user = :userId");
            $stmt->execute([':userId' => $userId]);
            echo "Utilisateur supprimé avec succès !<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage() . "<br>";
        }
    }

    public function updateUser($userId, $email, $username, $is_admin)
    {
        try {
            $stmt = $this->db->prepare("UPDATE user SET email = :email, username = :username, is_admin = :is_admin WHERE id_user = :userId");
            $stmt->execute([
                ':email' => $email,
                ':username' => $username,
                ':is_admin' => $is_admin,
                ':userId' => $userId,
            ]);
            echo "Utilisateur mis à jour avec succès !<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage() . "<br>";
        }
    }

    public function addUser($email, $username, $password, $is_admin)
    {
        try {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->db->prepare("INSERT INTO user (email, username, password, is_admin) VALUES (:email, :username, :password, :is_admin)");
            $stmt->execute([
                ':email' => $email,
                ':username' => $username,
                ':password' => $hashedPassword,
                ':is_admin' => $is_admin,
            ]);
            echo "Utilisateur ajouté avec succès !<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'utilisateur : " . $e->getMessage() . "<br>";
        }
    }
}
