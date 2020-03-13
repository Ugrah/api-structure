<?php
namespace Src\TableGateways;

class VerificationGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT 
                *
            FROM
                verifications;
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
                *
            FROM
                verifications
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
            INSERT INTO verifications 
                (phone, phone_code, phone_created_at, phone_code_generated_at, phone_verified_at, email, email_code, email_created_at, email_code_generated_at, email_verified_at, lead_id, customer_id)
            VALUES
                (:phone, :phone_code, :phone_created_at, :phone_code_generated_at, :phone_verified_at, :email, :email_code, :email_created_at, :email_code_generated_at, :email_verified_at, :lead_id, :customer_id);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'phone' => $input['phone'] ?? null,
                'phone_code' => $input['phone_code'] ?? null,
                'phone_created_at' => $input['phone_created_at'] ?? null,
                'phone_code_generated_at' => $input['phone_code_generated_at'] ?? null,
                'phone_verified_at' => $input['phone_verified_at'] ?? null,
                'email' => $input['email'] ?? null,
                'email_code' => $input['email_code'] ?? null,
                'email_created_at' => $input['email_created_at'] ?? null,
                'email_code_generated_at' => $input['email_code_generated_at'] ?? null,
                'email_verified_at' => $input['email_verified_at'] ?? null,
                'lead_id' => $input['lead_id'] ?? null,
                'customer_id' => $input['customer_id'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE verifications
            SET 
                phone = :phone,
                phone_code = :phone_code,
                phone_created_at = :phone_created_at,
                phone_code_generated_at = :phone_code_generated_at,
                phone_verified_at = :phone_verified_at,
                email = :email,
                email_code = :email_code,
                email_created_at = :email_created_at,
                email_code_generated_at = :email_code_generated_at,
                email_verified_at = :email_verified_at,
                lead_id = :lead_id,
                customer_id = :customer_id
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'phone' => $input['phone'] ?? null,
                'phone_code' => $input['phone_code'] ?? null,
                'phone_created_at' => $input['phone_created_at'] ?? null,
                'phone_code_generated_at' => $input['phone_code_generated_at'] ?? null,
                'phone_verified_at' => $input['phone_verified_at'] ?? null,
                'email' => $input['email'] ?? null,
                'email_code' => $input['email_code'] ?? null,
                'email_created_at' => $input['email_created_at'] ?? null,
                'email_code_generated_at' => $input['email_code_generated_at'] ?? null,
                'email_verified_at' => $input['email_verified_at'] ?? null,
                'lead_id' => $input['lead_id'] ?? null,
                'customer_id' => $input['customer_id'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM verifications
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