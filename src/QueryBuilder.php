<?php

  class QueryBuilder {

    private $_connection;
    private $_current_sql = [];

    public function __construct (PDO $connection) {
      $this->_connection = $connection;
    }

    public function __get (string $table_name): QueryBuilder {
      $this->_current_sql = [
        "conditions" => [],
        "bound_params" => []
      ];
      $this->_current_sql["table"] = $table_name;
      return $this;
    }

    /**
     * Step 1:
     *  Following the documentation hints below:
     *  - Create a new method called "find_by_superhero_name"
     *  -- data type will be stdClass (generic object)
     *  - Give it a parameter called "superhero_name"
     *  -- data type will be a string
     * 
     * Step 2:
     *  The method should find a superhero by the name
     *  specified and return a corresponding row
     * 
     * @example
     *  $query_builder
     *    ->superhero
     *    ->find_by_superhero_name("Incredible Hulk");
     * 
     * @method stdClass find_by_superhero_name()
     * @param string $superhero_name
     * @return stdClass 
     */
    // --- replace this line with your method ---
    public function find_by_superhero_name(string $superhero_name): stdClass { // Declaring function with superhero_name parameter and stdClass return type.
      $this->_current_sql["built_statement"] = "SELECT * FROM {$this->_current_sql["table"]}"; // Creating select query
      $this->where("name", "=", $superhero_name, ":find_one_row"); // Adding where clause in select query with $superhero_name parameter

      $result = $this
        ->_build_where_clause() // Converting array conditions to string and saving in built_statement index
        ->_prepare_and_execute() // Preparing query and binding value :find_one_row to $superhero_name then executing the query
        ->fetch(); // Getting corresponding row result
      
      return $result ? $result : new stdClass(); // Checking if row result is not empty then returning fetched result else returning empty generic object 
    }
    public function all (): array {
      $this->_current_sql["built_statement"] = "SELECT * FROM {$this->_current_sql["table"]}";
      return $this
        ->_build_where_clause()
        ->_prepare_and_execute()
        ->fetchAll();
    }

    public function find ($id): stdClass {
      $this->_current_sql["built_statement"] = "SELECT * FROM {$this->_current_sql["table"]}";
      $this->where("id", "=", $id, ":find_one_pk");

      $result = $this
        ->_build_where_clause()
        ->_prepare_and_execute()
        ->fetch();
      
      return $result ? $result : new stdClass();
    }

    public function where (string $field, string $operator, $value, $bound_param = null): QueryBuilder {
      $bound_param = $bound_param ?? ":{$field}";
      $this->_current_sql["bound_params"][$bound_param] = $value;
      $this->_current_sql["conditions"][]= "{$field} {$operator} {$bound_param}";

      return $this;
    }

    private function _prepare_and_execute (): PDOStatement {
      $stmt = $this
        ->_connection
        ->prepare($this->_current_sql["built_statement"]);
      
      foreach ($this->_current_sql["bound_params"] as $bound_param => $value) {
        $stmt->bindValue($bound_param, $value);
      }

      $stmt->execute();
      return $stmt;
    }

    private function _build_where_clause (): QueryBuilder {
      if (count($this->_current_sql["conditions"]) > 0) {
        $this->_current_sql["built_statement"] .= " WHERE ";
        $this->_current_sql["built_statement"] .= implode(" AND ", $this->_current_sql["conditions"]);
      }

      return $this;
    }

  }