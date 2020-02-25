<?php
namespace Src\TableGateways;

class PhoneVerificationGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT 
                id, phone_number, code, phone_number_verified_at, created_at, updated_at
            FROM
                phone_number_verifications;
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
                id, email, code, phone_number_verified_at, created_at, updated_at
            FROM
                phone_number_verifications
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
            INSERT INTO phone_number_verifications 
                (phone_number, code, created_at, updated_at)
            VALUES
                (:phone_number, :code, NOW(), NOW());
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'phone_number' => $input['phone_number'],
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
            UPDATE phone_number_verifications
            SET 
                phone_number = :phone_number,
                code  = :code,
                phone_number_verified_at = :phone_number_verified_at,
                updated_at = NOW()
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'phone_number' => $input['phone_number'],
                'code'  => $input['code'],
                'phone_number_verified_at' => $input['phone_number_verified_at'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM phone_number_verifications
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