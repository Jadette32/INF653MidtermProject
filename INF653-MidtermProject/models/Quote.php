<?php	
  class Quotes{
    private $conn;
    private $table = 'quotes';

    public $id;
    public $quote;
    public $author;
    public $category;
    public $author_id;
    public $category_id;

    public function __construct($db) {
      $this->conn = $db;
    }


    // Read all quotes
    public function display_quotes() {
        $query = 'SELECT
                    quotes.id,
                    quotes.quote,
                    authors.author,
                    categories.category
                FROM
                    ' . $this->table . '
                INNER JOIN
                    authors
                ON
                    quotes.author_id = authors.id
                INNER JOIN
                    categories
                ON
                    quotes.category_id = categories.id
                ORDER BY
                    quotes.id';

        // Prepare the query statement
        $stmt = $this->conn->prepare($query);

        // Execute query
        $stmt->execute();

        $quotes_arr = array(); // Initialize array to store quotes

        // Fetch quotes
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'author' => $author,
                'category' => $category
            );

            array_push($quotes_arr, $quote_item);
        }

        // Check if less than 25 quotes were retrieved
        if (count($quotes_arr) < 25) {
            // Retrieve additional quotes to meet the minimum requirement
            $additional_quotes = $this->fetch_additional_quotes(25 - count($quotes_arr));
            $quotes_arr = array_merge($quotes_arr, $additional_quotes);
        }

        return $quotes_arr; // Return array of quotes
    }

    // Fetch additional quotes to meet the minimum requirement
    private function fetch_additional_quotes($count) {
        $query = 'SELECT
                    quotes.id,
                    quotes.quote,
                    authors.author,
                    categories.category
                FROM
                    ' . $this->table . '
                INNER JOIN
                    authors
                ON
                    quotes.author_id = authors.id
                INNER JOIN
                    categories
                ON
                    quotes.category_id = categories.id
                ORDER BY
                    quotes.id
                LIMIT :count'; // Limit to the specified count of quotes

        // Prepare the query statement
        $stmt = $this->conn->prepare($query);

        // Bind parameter
        $stmt->bindParam(':count', $count, PDO::PARAM_INT);

        // Execute query
        $stmt->execute();

        $additional_quotes = array(); // Initialize array to store additional quotes

        // Fetch additional quotes
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quote_item = array(
                'id' => $id,
                'quote' => $quote,
                'author' => $author,
                'category' => $category
            );

            array_push($additional_quotes, $quote_item);
        }

        return $additional_quotes;
    }


    // Read single quote

    public function read_single() {
        $query = 'SELECT
                    quotes.id,
                    quotes.quote,
                    authors.author,
                    categories.category
                FROM
                    ' . $this->table . '
                INNER JOIN
                    authors
                ON
                    quotes.author_id = authors.id
                INNER JOIN
                    categories
                ON
                    quotes.category_id = categories.id
                WHERE';

        $conditions = array();
        $params = array();

        if (isset($_GET['id'])) {
            $conditions[] = 'quotes.id = :id';
            $params[':id'] = $_GET['id'];
        }

        if (isset($_GET['author_id'])) {
            $conditions[] = 'quotes.author_id = :author_id';
            $params[':author_id'] = $_GET['author_id'];
        }

        if (isset($_GET['category_id'])) {
            $conditions[] = 'quotes.category_id = :category_id';
            $params[':category_id'] = $_GET['category_id'];
        }

        if (empty($conditions)) {
            // No conditions specified, return empty array
            return array();
        }

        $query .= ' ' . implode(' AND ', $conditions);

        $stmt = $this->conn->prepare($query);

        foreach ($params as $param => $value) {
            $stmt->bindParam($param, $value);
        }

        $stmt->execute();

        $quotes = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $quotes[] = array(
                'id' => $id,
                'quote' => $quote,
                'author' => $author,
                'category' => $category
            );
        }

        return $quotes;
    }


    // Create author

    public function create() {
      $query = 'INSERT INTO ' .
        $this->table . '(quote, author_id, category_id)
      VALUES(
         :quote, :author_id, :category_id)';

      $stmt = $this->conn->prepare($query);
      $this->quote = htmlspecialchars(strip_tags($this->quote));
      $this->author_id = htmlspecialchars(strip_tags($this->author_id));
      $this->category_id = htmlspecialchars(strip_tags($this->category_id));
      $stmt->bindParam(':quote', $this->quote);
      $stmt->bindParam(':author_id', $this->author_id);
      $stmt->bindParam(':category_id', $this->category_id);

      if ($stmt->execute()) {
        return true;
      }

      printf("Error: %s.\n", $stmt->error);
      return false;

      if ($categories->category != null) {
        $category_arr = array(
          'id' => $categories->id,
          'category' => $categories->category
        );

        echo json_encode($category_arr);
      } else {
        echo json_encode(
          array('message' => 'category_id Not Found')
        );
      }
    }


    // Update author
    public function update() {
        // Check if author_id or category_id is not found
        if (empty($this->author_id)) {
            return json_encode(array('message' => 'author_id Not Found'));
        }
        if (empty($this->category_id)) {
            return json_encode(array('message' => 'category_id Not Found'));
        }

        // Proceed with update query
        $query = 'UPDATE ' .
            $this->table . '
        SET
            quote = :quote,
            author_id = :author_id,
            category_id = :category_id
        WHERE
            id = :id';

        $stmt = $this->conn->prepare($query);
        $this->quote = htmlspecialchars(strip_tags($this->quote));
        $this->author_id = htmlspecialchars(strip_tags($this->author_id));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':quote', $this->quote);
        $stmt->bindParam(':author_id', $this->author_id);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }

        printf("Error: %s.\n", $stmt->error);
        return false;

        echo $query;
    }

    // Delete author
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute() && $stmt->rowCount() > 0) { // Check if at least one row was affected
            return json_encode(array('id' => $this->id)); // Return a single JSON object with the id field
        }

        printf("Error: %s.\n", $stmt->error);
        return json_encode(array('message' => 'No Quotes Found')); // If no quotes found, return appropriate message
    }
  }
?>