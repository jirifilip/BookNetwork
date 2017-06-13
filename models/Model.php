<?php

abstract class Model {

    protected $name;
    protected $id;
    protected $idColumnName;
    protected $whereClause;
    
    private $hasOne = [];
    private $hasMany = [];
    private $oneToN = [];

    public function where($column, $predicate, $value) {
        if (empty($this->whereClause)) {
            $this->instantiateWhereTemplate();
        }
        $this->whereClause->where($column, $predicate, $value);
        return $this;
    }

    public function all() {
        $builder = new QueryBuilder($this->name);

        $res = $builder
                ->select()
                ->apply();

        if (@!is_array($res[0])) {
            $res = [$res];
        }

        return $res;
    }

    // public function allWithDepencencies() {
    //     $res = $this->where("$this->idColumnName", ">=", "0")->apply();

    //     return $res;
    // }

    public function apply($withoutDependencies = true, $asArray = true) {
        $res = $this->whereClause->apply();
        $this->emptyClauses();

        if (!$this->dependenciesEmpty() && !$withoutDependencies) {
            $this->id = $res[$this->idColumnName];
            
            $dependencies = $this->bringOthers();
            foreach ($dependencies as $key => $value) {
                $res[$key] = $value;
            }
            
        }

        return $res;
    }

    private function instantiateWhereTemplate() {
        $this->whereClause = new QueryBuilder($this->name);
        $this->whereClause->select();
    }

    private function emptyClauses() {
        $this->whereClause = "";
    }

    private function dependenciesEmpty() {
        return empty($this->hasOne) && empty($this->hasMany);
    }

    protected function hasOne($tableName, $primaryKey) {
        array_push($this->hasOne, [
            "table" => $tableName,
            "primaryKey" => $primaryKey
        ]);
    }

    protected function hasMany($tableName, $primaryKeyJoinTable, $resultKeyJoinTable) {
        array_push($this->hasMany, [
            "table" => $tableName,
            "primaryKey" => $primaryKey,
            "resultKey" => $resultKeyJoinTable
        ]);
    }

    protected function mToN($name, $joinTable, $joinTableId, $secondTable, $secondTableId, $firstTableJoinName, $orderBy) {
        array_push($this->hasMany, [
            "name" => $name,
            "joinTable" => $joinTable,
            "joinTableId" => $joinTableId,
            "secondTable" => $secondTable,
            "secondTableId" => $secondTableId,
            "firstTableJoinName" => $firstTableJoinName,
            "orderBy" => $orderBy
        ]);
    }

    protected function bringOthers() {
        $results = @$this->bring($this->hasOne);

        $many = $this->bringMany($this->hasMany);

        foreach ($many as $key => $val) {
            $results[$key] = $val;
        }

        return $results;
    }

    private function bringMany($array) {
        $result = [];

        foreach ($array as $record) {
            $name = $record['name'];

            $orderBy = $record['orderBy'];

            $primaryKey = $this->idColumnName;

            $joinTable = $record['joinTable'];
            $joinTableId = $record["joinTableId"];

            $secondTable = $record["secondTable"];
            $secondTableId = $record["secondTableId"];

            $firstTableJoinName = $record['firstTableJoinName'];

            $res = Db::queryAll("
                SELECT $secondTable.* FROM $joinTable
                INNER JOIN $secondTable ON ($joinTable.$joinTableId = $secondTable.$secondTableId)
                WHERE $joinTable.$firstTableJoinName = ?
                ORDER BY $orderBy;
            ", [
                $this->id
            ]);

            $result[$name] = $res;
        } 

        return $result;
    }

    private function bring($array) {
        $result = [];

        foreach ($array as $record) {
            $builder = new QueryBuilder($record['table']);
            $result[$record['table']] = $builder
                                            ->select()
                                            ->where($record['primaryKey'], "=", $this->id)
                                            ->apply();
        }

        return $result;
    }



    public function insert(array $params) {

        $question_marks = array_reduce($params, function($acc, $curr) {
            return $acc .= " ?,";
        });

        $question_marks = substr($question_marks, 0, strlen($question_marks) - 1);
        $question_marks = "($question_marks)";
        
        $keys = array_keys($params);
        
        $columns = array_reduce($keys, function($acc, $curr) {
            return $acc .= " $curr,";
        });

        $columns = substr($columns, 0, strlen($columns) - 1);
        $columns = "($columns)";

        $values = array_values($params);

        Db::updateDelete("
            INSERT INTO $this->name $columns VALUES $question_marks
        ", $values);
    }

    public function update(array $where, array $params) {

        $question_marks = array_reduce($params, function($acc, $curr) {
            return $acc .= " ?,";
        });

        $question_marks = array_fill(0, count($params), "?");
        $columns = array_keys($params);
        
        $values = array_values($params);
        array_push($values, $where[1]);
        $combined = array_combine($columns, $question_marks);
        
        $acc = "SET ";
        foreach ($combined as $column => $qMark) {
            $acc .= "$column=$qMark, ";
        }

        $acc = substr($acc, 0, strlen($acc) - 2);

        Db::updateDelete("
            UPDATE $this->name
            $acc
            WHERE $where[0]=?
        ", $values);

    }

    public function delete(array $where) {
        if (is_array($where[0])) {

            $predicateString = "WHERE ";
            $first = array_shift($where);

            $predicateString .= "$first[0]=$first[1] ";
            $values = [ $first[1] ];

            foreach ($where as $predicate) {
                $predicateString .= "AND $predicate[0]=$predicate[1]";
                array_push($values, $predicate[1]);
            }

            Db::updateDelete("
                DELETE FROM $this->name
                $predicateString
            ", $values);

        } else {
            Db::updateDelete("
                DELETE FROM $this->name
                WHERE $where[0]=?
            ", [$where[1]]);
        }

    }


}