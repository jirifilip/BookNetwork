<?php

class QueryBuilder {

    private $query = array(
        "statement" => "",
        "columns" => [],
        "table" => "",
        "values" => [],
        "where" => [],
        "whereParams" => [],
        "groupBy" => "",
        "join" => ""
    );

    function __construct($table) {
        $this->query["table"] = $table;
    }

    # statements
    public function select() {
        $this->query['statement'] = "SELECT";
        return $this;
    }
    public function insert() {
        $this->query['statement'] = "INSERT";
        return $this;
    }
    public function update() {
        $this->query['statement'] = "UPDATE";
        return $this;
    }
    public function delete() {
        $this->query['statement'] = "DELETE";
        return $this;
    }
    public function drop() {
        $this->query['statement'] = "DROP TABLE";
        return $this;
    }

    # columns
    public function column(array $names) {
        $this->query['columns'] = $names;
        return $this;
    }

    # values
    public function values(array $values) {
        $cond = $this->query['statement'] == "INSERT INTO" || $this->query['statement'] == "UPDATE";
        if (!$cond) return;

        $this->query["values"] = $values;
        return $this;
    }

    # where
    public function where($column, $condition, $val) {
        $whereClause = "$column $condition ?";
        array_push($this->query["where"], $whereClause);
        array_push($this->query["whereParams"], $val);
        return $this; 
    }

    # get
    public function get() {
        extract($this->query);

        if (empty($columns)) {
            $columns = "* ";
            $columns .= $statement == "SELECT" || $statement == "DELETE" ? "FROM" : "";
            $columns .= $statement == "INSERT" ? "INTO" : "";
        }
        
        // $finalValStr = "";
        // if (!empty($values)) {
        //     $finalValStr = "(";
        //     foreach ($values as $index => $val) {
        //         $finalValStr .= "$val" . $index == count($values) - 1? " ," : "";
        //     }
        // }

        $finalWhereClauses = "";
        if (!empty($where)) {
            $finalWhereClauses = "WHERE ";
            foreach ($where as $index => $val) {
                $finalWhereClauses .= "$val";
                if ($index < count($where) - 1) {
                    $finalWhereClauses .= " AND ";
                }
            }
        }

        $query = "$statement $columns $table";
        $query .= " $finalWhereClauses";

        $values = $whereParams;

        $return = [
            "statement" => $query,
            "values" => $values
        ];

        return $return;
    }

    # query db
    public function apply() {
        $query = $this->get();
      
        $result = Db::queryAll($query['statement'], $query['values']);

        if (count($result) == 1) {
            return array_shift($result);
        }
        else {
            return $result;
        }
    }

}