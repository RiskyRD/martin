<?php

namespace App\Model;

use Core\Auth\Auth;
use Core\Database\DB;

class TransactionModel extends BaseModel
{

    protected Auth $auth;
    public function __construct(DB $db, Auth $auth)
    {
        parent::__construct($db);
        $this->auth = $auth;
    }

    public function newTransaction()
    {
        $data = [];
        $user = $this->auth->getCurrentUser();
        $data['user_id'] = $user['id'];
        return $this->createNewTransaction($data);
    }
    public function getAllTransactions()
    {
        $stmt = $this->db->getConnection()->prepare("SELECT t.id,u.name AS user_name,t.created_at  FROM transactions t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createNewTransaction(array $data)
    {
        $sql = "INSERT INTO transactions (user_id) VALUES (:user_id)";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([
            ':user_id' => $data['user_id'],
        ]);

        $id = $this->db->getConnection()->lastInsertId();
        return $id;
    }

    public function addItemToTransaction(int $transactionId, array $data)
    {

        $sql = "INSERT INTO transaction_details (transaction_id, product_id, amount) VALUES (:transaction_id, :product_id, :amount)";
        $newData = [
            ':transaction_id' => $transactionId,
            ':product_id' => $data['id'],
            ':amount' => $data['amount'],
        ];
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute($newData);
    }

    public function getTransactionById(int $id)
    {
        $sql = "SELECT * FROM transactions WHERE id = :id LIMIT 1";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $transaction = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $transaction ?: null;
    }

    public function getTransactionDetailsByTransactionId(int $id)
    {
        $sql = "SELECT * FROM transaction_details WHERE transaction_id = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTransactionDetailsById(int $id)
    {
        $sql = "SELECT * FROM transaction_details WHERE id = :id LIMIT 1";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $transactionDetails = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $transactionDetails ?: null;
    }
    public function deleteTransactionDetailsById(int $id)
    {
        $sql = "DELETE FROM transaction_details WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($sql);

        return $stmt->execute([':id' => $id]);
    }
}
