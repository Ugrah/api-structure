<?php
namespace Src\TableGateways;

class EmailVerificationGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT 
                id, email, code, email_verified_at, created_at, updated_at
            FROM
                email_verifications;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT 
                id, email, code, email_verified_at, created_at, updated_at
            FROM
                email_verifications
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO email_verifications 
                (email, code, created_at, updated_at)
            VALUES
                (:email, :code, NOW(), NOW());
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'email' => $input['email'],
                'code'  => $input['code'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE email_verifications
            SET 
                email = :email,
                code  = :code,
                email_verified_at = :email_verified_at,
                updated_at = NOW()
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'email' => $input['email'],
                'code'  => $input['code'],
                'email_verified_at' => $input['email_verified_at'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM email_verifications
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}