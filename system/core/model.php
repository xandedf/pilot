<?php
/**
 * Classe padrão de model
 *
 * @package     Pilot
 * @copyright   Copyright (c) 2013 Discover (http://www.discover.com.br) 
 * @license     Commercial
 * @author      Alexandre Gomes <alexandre.gomes@discover.com.br>
 */

class Pilot_Core_Model extends Pilot_Lib {
    
    private $mySqlConnection = '';
    
    protected $table_name = null;
    
    protected $result = null;
    
    protected $affected_result = null;

    protected $filter = null;
    
    protected $values = array();
    
    protected $columns = array();

    protected $limit = null;
    
    protected $offset = null;
    
    protected $sql = null;
    
    protected $join = null;
    
    protected $id = null;

    public function __construct($table_name = null) {
        if (!is_null($table_name)) {
            $this->table_name = $table_name;
        }
    }

    public function getTableName() {
        return $this->table_name;
    }
    
    public function setTableName($value) {
        $this->table_name = $value;
        
        return $this;
    }
    
    private function connect_db() {
        
        $this->mySqlConnection = mysql_connect(DB_HOST,DB_USER,DB_PASS);
        
        mysql_select_db(DB_SCHEMA, $this->mySqlConnection);
        
        mysql_query("SET NAMES 'utf8'");
        mysql_query('SET character_set_connection=utf8');
        mysql_query('SET character_set_client=utf8');
        mysql_query('SET character_set_results=utf8');
        
        if (!$this->mySqlConnection) {
            throw new Exception(self::___(self::MSG_ERRO_CONNECTION, mysql_error()), self::MSG_ERRO_CONNECTION_COD);
        }
        
        return $this;
        
    }
    
    protected function getColumns() {
        if (count($this->columns) > 0) {
            $columns = "";
            foreach ($this->columns as $value) {
                $columns .= "{$value},";
            }
            return substr($columns,0,-1);
        }
        
        return false;
    }

    public function setSelectColumn($value) {
        $this->columns[] = $value;
        return $this;
    }
    
    public function setValue($key, $value) {
        $this->values[$key] = $value;
        return $this;
    }
    
    public function getValue($key) {
        $result = $this->getArrayResult();
        return $result[$key];
    }
    
    public function resetValues() {
        $this->values = array();
    }

    public function getResult() {
        return $this->result;
    }
    
    public function getArrayResult() {
        $result = array();        
        if ($this->result) {
            mysql_data_seek($this->result, 0);
            while ($row = mysql_fetch_array($this->result)) {
                $mount = array();
                foreach ($row as $key => $value) {
                    if (!is_int($key)) {
                        $mount[$key] = $value;
                    }
                }
                $result[] = $mount;
            }
            
            if (count($result) == 1) {
                return $result[0];
            }
            
            return $result;
        }
        
        return false;
    }

    public function getCountResult() {
        if ($this->result) {
            return mysql_num_rows($this->result);
        }
        
        return 0;
    }
    
    public function getAffectedResult() {
        if ($this->affected_result) {
            return $this->affected_result;
        }
        
        return 0;
    }
    
    public function getReturnId() {
        if ($this->id) {
            return $this->id;
        }
        
        return false;
    }


    public function execQuery($sql, $update = false) {

        try {
            $this->connect_db();
            $this->result = mysql_query($sql, $this->mySqlConnection) or die(mysql_error(). " " . $sql);
            if ($update) {
                $this->affected_result = mysql_affected_rows($this->mySqlConnection);
                $this->id = mysql_insert_id();
            }
           // mysql_close($this->mySqlConnection);
            
            return $this;
            
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }

    }
    
    public function mountQuery() {
        
        $this->sql = "";
        
        if (count($this->columns) > 0) {
            $columns = $this->getColumns();
        } else {
            $columns = '*';
        }
        
        $this->sql = "SELECT {$columns} FROM {$this->table_name}";
        
        if (!empty($this->join)) {
            $this->sql .= $this->join;
        }
        
        if (!empty($this->filter)) {
            $this->sql .= $this->filter; 
        }
        
        if (!is_null($this->limit)) {
            $this->sql .= $this->limit;
        }

        if (!is_null($this->offset)) {
            $this->sql .= $this->offset;
        }
        
        return $this;
        
    }


    public function find() {
        
        $this->mountQuery();
        
        try {
            $this->execQuery($this->sql);
            $this->cleanFilters();
            return $this;
            
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
        
    }
    
    public function mountInsertQuery() {
        
        if (!count($this->values) > 0) {
            return false;
        }
        
        $this->sql = "INSERT INTO {$this->table_name} ";
        
        $values = "";
        $columns = "";
        foreach ($this->values as $key => $value) {
            $columns .= "`{$key}`,";
            if (is_int($value)) {
                $values .= "{$value},";
            } else {
                $values .= "'{$value}',";
            }
        }
        
        $columns = substr($columns,0,-1);
        $values = substr($values,0,-1);
        
        $this->sql .= "({$columns}) VALUES ({$values}) ";
        
        return $this;
        
    }
    
    public function insert() {
        
        $this->mountInsertQuery();
        
        try {
            $this->execQuery($this->sql, true);
            $this->cleanFilters();
            return $this;
            
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
    }
    
    public function mountUpdateQuery() {
        
        if (!count($this->values) > 0) {
            return false;
        }
        
        $this->sql = "UPDATE {$this->table_name} SET ";
        
        $values = "";
        foreach ($this->values as $key => $value) {
            $values .= "{$key} = {$value},";
        }
        
        $this->sql .= substr($values,0,-1);
        
        if (!empty($this->filter)) {
            $this->sql .= $this->filter; 
        }
        
        return $this;
        
    }

    public function update() {
        
        $this->mountUpdateQuery();
        
        try {
            $this->execQuery($this->sql, true);
            $this->cleanFilters();
            return $this;
            
        } catch (Exception $exc) {
            throw new Exception($exc->getMessage());
        }
        
    }
    
    public function getSQL() {
        return $this->sql;
    }
    
    public function setFilter($column, $value, $type_filter, $quote = false, $compar = self::type_compar_and) {
        
        switch ($type_filter) {
            case self::type_conjunto:
                if (is_array($value)) {
                    $values = "";
                    foreach ($value as $temp) {
                        $values .= "{$temp},";
                    }
                    $value = substr($values,0,-1);
                }
                
                if ($quote) {
                    $value = "'{$value}'";
                }
                
                $base = "{$column} IN({$value})";
                break;

            default:
                if ($quote) {
                    $value = "'{$value}'";
                }
                $base = "{$column} {$type_filter} {$value}";
                break;
        }
        
        
        if (empty($this->filter)) {
            $this->filter = " WHERE {$base}";
            return $this;
        }
        
        $this->filter .= " {$compar} {$base}";
        
        return $this;
    }
    
    public function setLimit($value) {
        $this->limit = " LIMIT " . $value;
        return $this;
    }
    
    public function setOffset($value) {
        $this->offset = " OFFSET " . $value;
        return $this;
    }

    public function setJoin($primaryTable = null, $primaryColumnId = null, $secondTable = null, $secondAlias = '', $secondColumnId = null, $type_join = null) {
        if (is_null($primaryTable)) {
            $primaryTable = $this->getTableName();
        }
        
        if ($secondAlias <> '') {
            $this->join .= " {$type_join} {$secondTable} AS {$secondAlias} ON {$primaryTable}.{$primaryColumnId} = {$secondAlias}.{$secondColumnId}";
        } else {
            $this->join .= " {$type_join} {$secondTable}{$secondAlias} ON {$primaryTable}.{$primaryColumnId} = {$secondTable}.{$secondColumnId}";
        }
        
        return $this;
    }

    public function cleanFilters() {
        
        $this->filter = null;
        $this->limit = null;
        $this->offset = null;
        $this->join = null;
        $this->columns = array();
        
        return $this;
        
    }
    
    public function loadById($value, $columnIdName = null) {
        if (is_null($columnIdName)) {
            $columnIdName = 'id';
        }
        $this->setFilter($columnIdName, $value, self::type_igual)->find();
        return $this;
    }
    
}
