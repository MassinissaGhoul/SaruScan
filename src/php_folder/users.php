<?php
class UserManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function deleteUser($userId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM user WHERE id_user = :userId");
            $stmt->execute([':userId' => $userId]);
            echo "Utilisateur supprimé avec succès !<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage() . "<br>";
        }
    }

    public function getAllUsers() {
        try {
            $stmt = $this->db->query("SELECT id_user, email, pseudo FROM user");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des utilisateurs : " . $e->getMessage() . "<br>";
        }
    }
}
?>
