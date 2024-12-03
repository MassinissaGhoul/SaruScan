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
            $stmt = $this->db->query("SELECT * FROM users");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage();
        }
    }

    public function deleteUser($userId)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id_user = :userId");
            $stmt->execute([':userId' => $userId]);
            echo "Utilisateur supprimé avec succès !";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
        }
    }

    public function updateUser($userId, $email, $username)
    {
        try {
            $stmt = $this->db->prepare("UPDATE users SET email = :email, pseudo = :username WHERE id_user = :userId");
            $stmt->execute([
                ':email' => $email,
                ':username' => $username,
                ':userId' => $userId,
            ]);
            echo "Utilisateur mis à jour avec succès !";
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de l'utilisateur : " . $e->getMessage();
        }
    }
}
?>