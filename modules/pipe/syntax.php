<? namespace Titanium\Modules\Pipe; defined('PATH') or die;

class Syntax {
  // create an insert query
  public static function insert($table, array $data) {
    $query = "INSERT INTO `{$table}`";
    $keys = []; $values = [];
    foreach ($data as $k => $v) {
      $keys[] = "`{$k}`";
      $values[] = "'{$v}'";
    }
    $query .= ' (' . implode(', ', $keys) . ') VALUES (' . implode(', ', $values) . ')';
    return $query;
  }

  // create an update query. where array [key, equation (=/~), value]
  public static function update($table, array $data, $where = '') {
    $query = "UPDATE `{$table}`";

    $sets = [];
    foreach ($data as $k => $v) {
      $sets[] = "`{$k}` = '{$v}'";
    }

    $query .= ' SET ' . implode(', ', $sets);

    /**
     * WHERE
     */
    if (! empty($where)) {
      if (is_string($where)) {
        $query .= " WHERE {$where}";
      } else {
        $wheres = [];

        foreach ($where as $q) {
          list($key, $equation, $value) = $q;

          $equation = str_replace('~', 'LIKE', $equation);
          $wheres[] = "`{$key}` {$equation} '{$value}'";
        }

        $query .= ' WHERE ' . implode(' AND ', $wheres);
      }
    }

    return $query;
  }

  // create a select all query
  public static function selectAll($table, $keys = [], $orderby = '') {
    $query = 'SELECT';

    if (count($keys) > 0) {
      $kys = [];
      foreach ($keys as $k) {
        $kys[] = "`{$k}`";
      }
      $query .= ' (' . implode(', ', $kys) . ')';
    } else {
      $query .= ' *';
    }

    $query .= " FROM `{$table}`";

    if (! empty($orderby)) {
      $query .= " ORDER BY {$orderby}";
    }
    return $query;
  }

  // create a select where query. where array [key, equation (=/~), value]
  public static function selectWhere($table, $search, $keys = [], $orderby = '') {
    $query = 'SELECT';

    if (count($keys) > 0) {
      $kys = [];
      foreach ($keys as $k) {
        $kys[] = "`{$k}`";
      }
      $query .= ' (' . implode(', ', $kys) . ')';
    } else {
      $query .= ' *';
    }

    $query .= " FROM `{$table}`";

    /**
     * WHERE
     */
    if (is_string($search)) {
      $query .= " WHERE {$search}";
    } else {
      $wheres = [];

      foreach ($search as $q) {
        list($key, $equation, $value) = $q;

        $equation = str_replace('~', 'LIKE', $equation);
        $wheres[] = "`{$key}` {$equation} '{$value}'";
      }

      $query .= ' WHERE ' . implode(' AND ', $wheres);
    }

    if (! empty($orderby)) {
      $query .= " ORDER BY {$orderby}";
    }
    return $query;
  }

  // create a delete query.
  public static function deleteWhere($table, $where = '') {
    $query = "DELETE FROM `{$table}`";

    if (! empty($where)) {
      if (is_string($where)) {
        $query .= " WHERE {$where}";
      } else {
        $wheres = [];

        foreach ($where as $q) {
          list($key, $equation, $value) = $q;

          $equation = str_replace('~', 'LIKE', $equation);
          $wheres[] = "`{$key}` {$equation} '{$value}'";
        }

        $query .= ' WHERE ' . implode(' AND ', $wheres);
      }
    }

    return $query;
  }
}
