<?php


namespace App\Model;


class ProductModel extends BaseModel
{
    public function saveProduct(array $data)
    {
        $sql = "INSERT INTO products (name, stock, price) VALUES (:name, :stock, :price)";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':stock', $data['stock'], \PDO::PARAM_INT);
        $stmt->bindParam(':price', $data['price'], \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getAllProducts(): array
    {
        $sql = "SELECT id, name, stock, price FROM products";
        $stmt = $this->db->getConnection()->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductById(int $id): ?array
    {
        $sql = "SELECT id, name, stock, price FROM products WHERE id = :id LIMIT 1";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $product = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $product ?: null;
    }

    public function updateProduct(int $id, array $data): bool
    {
        $sql = "UPDATE products SET name = :name, stock = :stock, price = :price WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':stock', $data['stock'], \PDO::PARAM_INT);
        $stmt->bindParam(':price', $data['price'], \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function updateProductStock(int $id, int $newStock): bool
    {
        $sql = "UPDATE products SET stock = :stock WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bindParam(':stock', $newStock, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function deleteProduct(int $id): bool
    {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function isProductExists(int $id): bool
    {
        $sql = "SELECT COUNT(*) FROM products WHERE id = :id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetchColumn() > 0;
    }
}
