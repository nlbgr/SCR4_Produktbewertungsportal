<?php

namespace Infrastructure;

class Repository
    implements
    \Application\Interfaces\ProductRepository,
    \Application\Interfaces\UserRepository,
    \Application\Interfaces\RatingsRepository,
    \Application\Interfaces\ManufacturerRepository
{
    private $server;
    private $userName;
    private $password;
    private $database;

    public function __construct(string $server, string $userName, string $password, string $database)
    {
        $this->server = $server;
        $this->userName = $userName;
        $this->password = $password;
        $this->database = $database;
    }

    // === private helper methods ===

    private function getConnection()
    {
        $con = new \mysqli($this->server, $this->userName, $this->password, $this->database);
        if (!$con) {
            die('Unable to connect to database. Error: ' . mysqli_connect_error());
        }
        return $con;
    }

    private function executeQuery($connection, $query)
    {
        $result = $connection->query($query);
        if (!$result) {
            die("Error in query '$query': " . $connection->error);
        }
        return $result;
    }

    private function executeStatement($connection, $query, $bindFunc)
    {
        $statement = $connection->prepare($query);
        if (!$statement) {
            die("Error in prepared statement '$query': " . $connection->error);
        }
        $bindFunc($statement);
        if (!$statement->execute()) {
            die("Error executing prepared statement '$query': " . $statement->error);
        }
        return $statement;
    }



    public function getProducts(): array {
        $products = [];

        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            "SELECT p.id, p.name, u.uname, m.name
                    FROM products p
                    JOIN users u ON p.userId = u.id
                    JOIN manufacturers m ON p.manufacturerId = m.id;",
            function($s) {
                return; // prepare the statement altough no value is set. Was a tip by another teacher
            });
        $stat->bind_result($id, $name, $uname, $mname);
        while($stat->fetch()) {
            $products[] = new \Application\Entities\Product($id, $name, $uname, $mname);
        }
        $stat->close();
        $con->close();

        return $products;
    }

    public function getProductsForFilter(string $filter): array {
        $filter = "%$filter%";
        $products = [];

        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            "SELECT p.id, p.name, u.uname, m.name
                    FROM products p
                        JOIN users u ON p.userId = u.id
                        JOIN manufacturers m ON p.manufacturerId = m.id
                    WHERE p.name LIKE ? OR m.name LIKE ?",
            function($s) use ($filter) {
                $s->bind_param('ss', $filter, $filter);
            });
        $stat->bind_result($id, $name, $uname, $mname);
        while($stat->fetch()) {
            $products[] = new \Application\Entities\Product($id, $name, $uname, $mname);
        }
        $stat->close();
        $con->close();

        return $products;
    }

    public function getProductById(int $productId): ?\Application\Entities\Product {
        $product = null;

        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            "SELECT p.id, p.name, u.uname, m.name 
                    FROM products p 
                        JOIN users u ON p.userId = u.id
                        JOIN manufacturers m ON p.manufacturerId = m.id
                    WHERE p.id = ?",
            function($s) use ($productId) {
                $s->bind_param("i", $productId);
            }
        );
        $stat->bind_result($id, $name, $uname, $manname);
        if ($stat->fetch()) {
            $product = new \Application\Entities\Product($id, $name, $uname, $manname);
        }
        $stat->close();
        $con->close();

        return $product;
    }

    public function getProductByNameAndManufacturer(string $pname, string $manId): ?\Application\Entities\Product {
        $product = null;

        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            "SELECT p.id, p.name, u.uname, m.name 
                    FROM products p 
                        JOIN users u ON p.userId = u.id
                        JOIN manufacturers m ON p.manufacturerId = m.id
                    WHERE p.name = ? AND p.manufacturerId = ?",
            function($s) use ($pname, $manId) {
                $s->bind_param("si", $pname, $manId);
            }
        );
        $stat->bind_result($id, $name, $uname, $manname);
        if ($stat->fetch()) {
            $product = new \Application\Entities\Product($id, $name, $uname, $manname);
        }
        $stat->close();
        $con->close();

        return $product;
    }

    public function createProduct(string $pname, int $userId, int $manId): ?\Application\Entities\Product {
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'INSERT INTO products (name, userId, manufacturerId)
                    VALUES (?, ?, ?)',
            function ($s) use ($pname, $userId, $manId) {
                $s->bind_param('sii', $pname, $userId, $manId);
            }
        );
        $prodId = $stat->insert_id;
        $stat->close();
        $con->commit();
        $con->close();

        return $this->getProductById($prodId);
    }

    public function editProduct(int $pid, string $pname, int $userId, int $manId): ?\Application\Entities\Product {
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'UPDATE products
                    SET name = ?, manufacturerId = ?
                    WHERE id = ? AND userId = ?',
            function ($s) use ($pid, $pname, $userId, $manId) {
                $s->bind_param('siii', $pname, $manId, $pid, $userId);
            }
        );
        $stat->close();
        $con->commit();
        $con->close();

        return $this->getProductById($pid);
    }

    public function deleteProduct(string $productId, int $userId): bool {
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'DELETE FROM products WHERE id = ? AND userId = ?',
            function($s) use ($productId, $userId) {
                $s->bind_param('ii', $productId, $userId);
            }
        );
        $stat->close();
        $con->commit();
        $con->close();

        return $this->getProductById($productId) === null;
    }



    public function getRatingsForProduct(int $productId): array {
        $ratings = [];

        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            "SELECT r.id, r.date, r.comment, r.grade, u.uname, r.productId
                    FROM ratings r JOIN users u ON r.userId = u.id
                    WHERE r.productId = ?",
            function($s) use ($productId) {
                $s->bind_param("i", $productId);
            }
        );
        $stat->bind_result($id, $date, $comment, $grade, $uname, $pId);
        while ($stat->fetch()) {
            $ratings[] = new \Application\Entities\Rating($id, $date, $comment !== null ? $comment : "", $grade, $uname, $pId);
        }
        $stat->close();
        $con->close();

        return $ratings;
    }

    public function getRatingsChronoForProduct(int $productId): array {
        $ratings = [];

        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            "SELECT r.id, r.date, r.comment, r.grade, u.uname, r.productId
                    FROM ratings r JOIN users u ON r.userId = u.id
                    WHERE r.productId = ?
                    ORDER BY r.date DESC",
            function($s) use ($productId) {
                $s->bind_param("i", $productId);
            }
        );
        $stat->bind_result($id, $date, $comment, $grade, $uname, $pId);
        while ($stat->fetch()) {
            $ratings[] = new \Application\Entities\Rating($id, $date, $comment !== null ? $comment : "", $grade, $uname, $pId);
        }
        $stat->close();
        $con->close();

        return $ratings;
    }

    public function getRatingById(int $ratingId): ?\Application\Entities\Rating {
        $rating = null;

        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            "SELECT r.id, r.date, r.comment, r.grade, u.uname, r.productId
                    FROM ratings r
                        JOIN users u ON r.userId = u.id
                    WHERE r.id = ?",
            function($s) use ($ratingId) {
                $s->bind_param("i", $ratingId);
            }
        );
        $stat->bind_result($id, $date, $comment, $grade, $uname, $pid);
        if ($stat->fetch()) {
            $rating = new \Application\Entities\Rating($id, $date, $comment, $grade, $uname, $pid);
        }
        $stat->close();
        $con->close();

        return $rating;
    }

    public function createRating(int $grade, string $comment, int $userId, int $prodId): ?\Application\Entities\Rating {
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'INSERT INTO ratings (date, comment, grade, userId, productId)
                    VALUES (SYSDATE(), ?, ?, ?, ?)',
            function ($s) use ($comment, $grade, $userId, $prodId) {
                $s->bind_param('siii', $comment, $grade, $userId, $prodId);
            }
        );
        $ratingId = $stat->insert_id;
        $stat->close();
        $con->commit();
        $con->close();

        return $this->getRatingById($ratingId);
    }

    public function deleteRating(int $ratingId, int $userId): bool {
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'DELETE FROM ratings WHERE id = ? AND userId = ?',
            function($s) use ($ratingId, $userId) {
                $s->bind_param('ii', $ratingId, $userId);
            }
        );
        $stat->close();
        $con->commit();
        $con->close();

        return $this->getProductById($ratingId) === null;
    }



    public function getUser(int $id): ?\Application\Entities\User {
        $user = null;
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'SELECT id, uname, pwdhash FROM users WHERE id = ?',
            function($s) use ($id) {
                $s->bind_param('i', $id);
            }
        );
        $stat->bind_result($id, $userName, $passwordHash);
        if ($stat->fetch()) {
            $user = new \Application\Entities\User($id, $userName, $passwordHash);
        }
        $stat->close();
        $con->close();
        return $user;
    }

    public function getUserForUserName(string $userName): ?\Application\Entities\User {
        $user = null;
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'SELECT id, uname, pwdhash FROM users WHERE uname = ?',
            function($s) use ($userName) {
                $s->bind_param('s', $userName);
            }
        );
        $stat->bind_result($id, $userName, $passwordHash);
        if ($stat->fetch()) {
            $user = new \Application\Entities\User($id, $userName, $passwordHash);
        }
        $stat->close();
        $con->close();
        return $user;
    }

    public function createUser(string $userName, string $password): ?\Application\Entities\User {
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'INSERT INTO users (uname, pwdhash)
                    VALUES (?, ?)',
            function ($s) use ($userName, $password) {
                $s->bind_param('ss', $userName, password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]));
            }
        );
        $userId = $stat->insert_id;
        $stat->close();
        $con->commit();
        $con->close();

        return $this->getUser($userId);
    }



    public function getManufacturerByName(string $mname): ?\Application\Entities\Manufacturer {
        $manufacturer = null;
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'SELECT id, name FROM manufacturers WHERE name = ?',
            function($s) use ($mname) {
                $s->bind_param('s', $mname);
            }
        );
        $stat->bind_result($id, $name);
        if ($stat->fetch()) {
            $manufacturer = new \Application\Entities\Manufacturer($id, $name);
        }
        $stat->close();
        $con->close();
        return $manufacturer;
    }

    public function getManufacturerById(int $id): ?\Application\Entities\Manufacturer {
        $manufacturer = null;
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'SELECT id, name FROM manufacturers WHERE id = ?',
            function($s) use ($id) {
                $s->bind_param('i', $id);
            }
        );
        $stat->bind_result($id, $name);
        if ($stat->fetch()) {
            $manufacturer = new \Application\Entities\Manufacturer($id, $name);
        }
        $stat->close();
        $con->close();
        return $manufacturer;
    }

    public function createNewManufacturer(string $mname): ?\Application\Entities\Manufacturer {
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'INSERT INTO manufacturers (name)
                    VALUES (?)',
            function ($s) use ($mname) {
                $s->bind_param('s', $mname);
            }
        );
        $manId = $stat->insert_id;
        $stat->close();
        $con->commit();
        $con->close();

        return $this->getManufacturerById($manId);
    }
}