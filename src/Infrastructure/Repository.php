<?php

namespace Infrastructure;

class Repository
    implements
    \Application\Interfaces\ProductRepository,
    \Application\Interfaces\UserRepository,
    \Application\Interfaces\RatingsRepository
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

    public function getBooksForFilter(string $filter): array {
        $filter = "%$filter%";
        $books = [];

        $con = $this->getConnection();
        $stat = $this->executeStatement(
            $con,
            "SELECT id, title, author, price FROM books WHERE title LIKE ?",
            function($s) use ($filter) {
                $s->bind_param('s', $filter);
            });
        $stat->bind_result($id, $title, $author, $price);
        while($stat->fetch()) {
            $books[] = new \Application\Entities\Book($id, $title, $author, $price);
        }
        $stat->close();
        $con->close();

        return $books;
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
        $user = null;
        $con = $this->getConnection();
        $stat = $this->executeStatement($con,
            'INSERT INTO `users` (`uname`, `pwdhash`)
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
}