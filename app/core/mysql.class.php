<?php /**/

class MySQL extends PDO
{

  public function __construct() {

    parent::__construct('mysql:dbname='.NOMBRE_BASE_DATOS.';host='.HOST_BASE_DATOS,USUARIO_BASE_DATOS,CLAVE_BASE_DATOS);

    try {

      parent::setAttribute(parent::ATTR_ERRMODE, parent::ERRMODE_EXCEPTION);

      parent::setAttribute(parent::ATTR_DEFAULT_FETCH_MODE, parent::FETCH_ASSOC);

      parent::exec("SET CHARACTER SET utf8");

    } catch (PDOException $e) {

        echo 'Error BD: ' . $e->getMessage();

      }

    }


    public function executeQuery($query, $params=NULL){

      try{

        $stmt = parent::prepare($query);

        if($stmt->execute($params)){

          return $stmt->fetchAll();

        }else{

          return array();

        }

      } catch (PDOException $e) {

        echo 'Error BD: ' . $e->getMessage();

      }

    }

    public function updateData($tabla, $values, $where){
      if(count($values)>0 and count($where)>0){
          $query = 'UPDATE ' . $tabla . ' SET ';
          $params = array();
          $coma = '';
          foreach($values as $key => $val){
              $query .= $coma.$key .' = :set_' . $key;
              $params[':set_' . $key] = $val;
              $coma = ',';
          }
          $query .= ' WHERE ';
          $and = '';
          foreach($where as $key => $val){
              if($val==='NOT NULL'){
                  $query .= $and.' '.$key.' IS NOT NULL';
              }else{
                  (is_null($val))? $query .= $and.$key .' <=> :whe_' . $key :  $query .= $and.$key .' = :whe_' . $key;
                  $params[':whe_' . $key] = $val;
              }
              $and = ' AND ';
          }
          $stmt = parent::prepare($query);
          $stmt->execute($params);
          if($stmt->rowCount()>0){
              return TRUE;
          }else{

              return FALSE;
          }
      }else{
          return FALSE;
      }
    }
    public function updateDataAll($tabla, $values, $where=array()){
      if(count($values)>0 and count($where)>0){
          $query = 'UPDATE ' . $tabla . ' SET ';
          $params = array();
          $coma = '';
          foreach($values as $key => $val){
              $query .= $coma.$key .' = :set_' . $key;
              $params[':set_' . $key] = $val;
              $coma = ',';
          }
          $query .= ' WHERE ';
          $and = '';
          foreach($where as $key => $val){
              if($val[1]==='NOT NULL'){
                  $query .= $and.' '.$key.' IS NOT NULL';
              }else if($val[1]==='IN'){
                $query .= $and.' '.$val[0].' IN ('.$val[2].')';
              }else{
                  (is_null($val[1]))? $query .= $and.$key .' <=> :whe_' . $key :  $query .= $and.$val[0] .' = :whe_' . $key;
                  $params[':whe_' . $key] = $val[1];
              }
              $and = ' AND ';
          }
          $stmt = parent::prepare($query);
          $stmt->execute($params);
          if($stmt->rowCount()>0){
              return TRUE;
          }else{
              return FALSE;
          }
      }else{
          return FALSE;
      }
    }
    public function selectData($tabla, $where=array(), $orderBy=''){
      $query = 'SELECT * FROM ' . $tabla;
      if(count($where)>0){
          $query .= ' WHERE ';
          $and = '';
          $params = array();
          foreach($where as $key => $val){
              if($val==='NOT NULL'){
                  $query .= $and.' '.$key.' IS NOT NULL';
              }else{
                  (is_null($val))? $query .= $and.$key .' <=> :' . $key :  $query .= $and.$key .' = :' . $key;
                  $params[':' . $key] = $val;
              }
              $and = ' AND ';
          }
      }
      if($orderBy!=''){ $query .= ' ORDER BY '.$orderBy; }
      $stmt = parent::prepare($query);
      $stmt->execute($params);
      return $stmt->fetchAll();
    }
    public function selectDataAll($tabla, $where=array(), $orderBy=''){
      $query = 'SELECT * FROM ' . $tabla;
      if(count($where)>0){
          $query .= ' WHERE ';
          $and = '';
          $params = array();
          foreach($where as $key => $val){
              if($val[1]==='NOT NULL'){
                  $query .= $and.' '.$key.' IS NOT NULL';
              }else if($val[1]==='BETWEEN'){
                $query .= $and.' '.$val[0].' BETWEEN "'.$val[2].'" AND "'.$val[3].'" ';
              }else{
                  (is_null($val[1]))? $query .= $and.$key .' <=> :' . $key :  $query .= $and.$val[0] .' = :' . $key;
                  $params[':' . $key] = $val[1];
              }
              $and = ' AND ';
          }
      }
      if($orderBy!=''){ $query .= ' ORDER BY '.$orderBy; }
      $stmt = parent::prepare($query);
      $stmt->execute($params);
      return $stmt->fetchAll();
    }
    public function insertData($tabla, $values) {

        if(count($values)>0){
            $query = 'INSERT INTO '.$tabla;
            $queryKeys = '';
            $queryValues = '';
            $params = array();
            $coma = '';
            foreach($values as $key => $val){
                $queryKeys .= $coma.$key;
                $queryValues .= $coma.':'.$key;
                $params[':'.$key] = $val;
                $coma = ',';
            }
            $query .= '('.$queryKeys.') VALUES ('.$queryValues.')';
            $stmt = parent::prepare($query);
            $stmt->execute($params);
            if($stmt->rowCount()>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
    public function insertDataMasivo($tabla,$columns, $values=array()) {
        if(count($values)>0){
            $query = 'INSERT INTO '.$tabla;
            $query .= '('.implode(',',$columns).') VALUES '.implode(',',$values).'';
            $stmt = parent::prepare($query);
            $stmt->execute();
            if($stmt->rowCount()>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
    public function selectRowData($tabla, $fields, $where=array()){
        if(count($where)>0){
            $query = 'SELECT '.$fields.' FROM ' . $tabla;
            $query .= ' WHERE ';
            $and = '';
            $params = array();
            foreach($where as $key => $val){
                if($val==='NOT NULL'){
                    $query .= $and.' '.$key.' IS NOT NULL';
                }else{
                    (is_null($val))? $query .= $and.$key .' <=> :' . $key :  $query .= $and.$key .' = :' . $key;
                    $params[':' . $key] = $val;
                }
                $and = ' AND ';
            }
            $stmt = parent::prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();
        }else{
            return FALSE;
        }
    }
    public function selectRowDataAll($tabla, $fields, $where=array(), $groupBy=array(), $orderBy=array()){
        if(!empty($tabla)){
            if (!empty($fields)) {
              $field = array();
              foreach ($fields as $key => $value) {
                $field[] = $value;
              }
              $field = empty($field)?'*':implode(',',$field);
            }
            $query = 'SELECT '.$field.' FROM ' . $tabla;
            $params = array();
            if (!empty($where)) {
              $query .= ' WHERE ';
              $and = '';
              foreach($where as $key => $val){
                  if($val[1]==='NOT NULL'){
                      $query .= $and.' '.$key.' IS NOT NULL';
                  }else if($val[1]==='BETWEEN'){
                    $query .= $and.' '.$val[0].' BETWEEN "'.$val[2].'" AND "'.$val[3].'" ';
                  }else if($val[1]==='>='){
                    $query .= $and.' '.$val[0].' >= "'.$val[2].'"';
                  }else if($val[1]==='<='){
                    $query .= $and.' '.$val[0].' <= "'.$val[2].'"';
                  }else if($val[1]==='OR'){
                    $query .= $and.' '.$val[0].' = "'.$val[2].'" OR '.$val[0].' = "'.$val[3].'"';
                  }else{
                      (is_null($val[1]))? $query .= $and.$key .' <=> :' . $key :  $query .= $and.$val[0] .' = :' . $key;
                      $params[':' . $key] = $val[1];
                  }
                  $and = ' AND ';
              }
            }
            if (!empty($groupBy)) {
              $grp = array();
              foreach ($groupBy as $key => $value) {
                $grp[]= $value;
              }
              $groupBy=" group by ".implode(',',$grp);
            }else {
              $groupBy = '';
            }
            if (!empty($orderBy)) {
              $grp = array();
              foreach ($orderBy as $key => $value) {
                $grp[]= $value;
              }
              $orderBy=" order by ".implode(',',$grp);
            }else {
              $orderBy = '';
            }
            $stmt = parent::prepare($query.$groupBy.$orderBy);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }else{
            return FALSE;
        }
    }
    public function selectRowDataAllSQLSERVER($tabla, $fields, $where=array(), $groupBy=array()){
        if(!empty($tabla)){
            if (!empty($fields)) {
              $field = array();
              foreach ($fields as $key => $value) {
                $field[] = $value;
              }
              $field = empty($field)?'*':implode(',',$field);
            }
            $query = 'SELECT '.$field.' FROM ' . $tabla;
            $params = array();
            if (!empty($where)) {
              $query .= ' WHERE ';
              $and = '';
              foreach($where as $key => $val){
                  if($val==='NOT NULL'){
                      $query .= $and.' '.$key.' IS NOT NULL';
                  }else{
                      (is_null($val))? $query .= $and.$key .' <=> :' . $key :  $query .= $and.$key .' = :' . $key;
                      $params[':' . $key] = $val;
                  }
                  $and = ' AND ';
              }
            }

            if (!empty($groupBy)) {
              $grp = array();
              foreach ($groupBy as $key => $value) {
                $grp[]= $value;
              }
              $groupBy=" group by ".implode(',',$grp);
            }else {
              $groupBy = '';
            }

            $stmt = parent::prepare($query.$groupBy);
            $stmt->execute($params);
            return $stmt->fetchAll();
        }else{
            return FALSE;
        }
    }
    public function deleteDataNoWhere($tabla) {
            $query = 'DELETE FROM ' . $tabla;
            $params = array();
            $stmt = parent::prepare($query);
            $stmt->execute($params);
            if($stmt->rowCount()>0){
                return TRUE;
            }else{
                return FALSE;
            }

    }
    public function deleteDataMasivo($tabla, $where) {
        if(count($where)>0){
            $query = 'DELETE FROM ' . $tabla;
            $query .= ' WHERE ';
            $and = '';
            $params = array();
            foreach($where as $key => $val){
                if($val[1]==='NOT NULL'){
                    $query .= $and.' '.$key.' IS NOT NULL';
                }else if($val[1]==='IN'){
                    $query .= $and.' '.$val[0].' IN ('.$val[2].')';
                }else{
                    (is_null($val[1]))? $query .= $and.$key .' <=> :whe_' . $key :  $query .= $and.$val[0] .' = :whe_' . $key;
                  $params[':whe_' . $key] = $val[1];
                }
                $and = ' AND ';
            }
            $stmt = parent::prepare($query);
            $stmt->execute($params);
            if($stmt->rowCount()>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
    public function deleteData($tabla, $where) {
        if(count($where)>0){
            $query = 'DELETE FROM ' . $tabla;
            $query .= ' WHERE ';
            $and = '';
            $params = array();
            foreach($where as $key => $val){
                if($val==='NOT NULL'){
                    $query .= $and.' '.$key.' IS NOT NULL';
                }else{
                    (is_null($val))? $query .= $and.$key .' <=> :' . $key :  $query .= $and.$key .' = :' . $key;
                    $params[':' . $key] = $val;
                }
                $and = ' AND ';
                $params[':' . $key] = $val;
            }
            $stmt = parent::prepare($query);
            $stmt->execute($params);
            if($stmt->rowCount()>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
    public function deleteDataAll($tabla, $where) {
        if(count($where)>0){
            $query = 'DELETE FROM ' . $tabla;
            $query .= ' WHERE ';
            $and = '';
            $params = array();
            foreach($where as $key => $val){
                if($val[1]==='NOT NULL'){
                    $query .= $and.' '.$key.' IS NOT NULL';
                }else{
                    (is_null($val[1]))? $query .= $and.$key .' <=> :' . $key :  $query .= $and.$val[0] .' = :' . $key;
                    $params[':' . $key] = $val[1];
                }
                $and = ' AND ';
                $params[':' . $key] = $val[1];
            }
            $stmt = parent::prepare($query);
            $stmt->execute($params);
            if($stmt->rowCount()>0){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }

    public function lastId($name = null) {
        return parent::lastInsertId($name);
    }

    public function getLastId($table = null) {
        $query  = "SELECT id FROM ".  $table ." ORDER BY id DESC LIMIT 1";

        $stmt = parent::prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
        return $stmt;
    }
}
