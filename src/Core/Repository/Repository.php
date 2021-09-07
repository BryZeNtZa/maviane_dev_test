<?php

namespace Maviance\Core\Repository;

use Maviance\Config\Database;
use PDO;

 
 /**
 * The generic repository class
 *
 * This class performs database objects retrieving/persistence 
 *
 * @author     Maviance, PLC.
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */
class Repository {

    /**
     * It represents a PDO instance
     *
     * @var PDO object
     */
    static $db = null;

    /**
     * The name of the table in the database that the model binds
     *
     * @var string tablename
     */
    private $tablename;

    public function __construct(string $tablename) {

        if (static::$db === null) {
            
            $conn_string = 'mysql:host=' . Database::DB_HOST . ';dbname=' . Database::DB_NAME . ';charset=utf8';
            $db = new \PDO($conn_string, Database::DB_USER, Database::DB_PASSWORD);

            // Throw an Exception when an error occurs
            $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            static::$db = $db;
        }

        $this->tablename = $tablename;
    }

    /**
     * Method for fetching records from database based on criteria.
     *
     *
     * @return array
     * @access  public
     * @since   Method available since Release 1.0.0
     */
    public function find(array $filter): iterable {

		$fields = isset($filter['fields']) ? $filter['fields'] : '*';
		$query =  'SELECT ' . $fields . ' FROM ' . $this->tablename;
		$query .= $this->filter($filter);

		$stm = $this->DB()->prepare($query) or throw new Exception($this->DB()->errorInfo());
		$stm->execute();

		return $stm->fetchAll();
	}

    /**
     * Method for getting table all records from database.
     *
     *
     * @return array
     * @access  public
     * @since   Method available since Release 1.0.0
     */
	public function getAll(): iterable {
		return $this->find( array('fields' => '*') );
	}

    /**
     * Method for getting record based on its id.
     *
     * @param int $id
     * @return array
     * @access  public
     * @since   Method available since Release 1.0.0
     */
	public function getById(int $id): array {
		return $this->fetch( array('id' => $id) );
	}

    /**
     * Method for counting table rows
     *
     * @return  int
     * @access  public
     * @since   Method available since Release 1.0.0
     */
	public function count(): int {
		$filter = array('fields' => 'COUNT(*) AS count');
		return (int) $this->find($filter)[0]['count'];
	}

    /**
     * Method for getting one record from database based on filter.
     *
     *
     * @return array
     * @access  public
     * @since   Method available since Release 1.0.0
     */
    public function fetch(array $filter): array {
		$records = $this->find($filter);
		return count($records)!=0 ? $records[0] : null;
	}

    /**
     * Method inserting data in db
     * 
     * This method makes it easy to insert data into the database 
     * in a quick and easy way. The data set is retrieved from 
     * the model object as an associative array
     *
     * @return integer The last insert ID
     * @access  public
     * @since   Method available since Release 1.0.0
     */
    public function insert(array $data): int {

        if($this->tablename == null){
            throw new Exception('No model provided !');
        }
        if($data == null){
            throw new Exception('No data provided !');
        }

		$fields	= '';
		$values	= '';
		foreach($data as $field=>$value) {

			if($field==='id') continue;

			$fields .= $fields == '' ? ', ' . $field : $field;

			$type = gettype($value);

			switch($type) {
				case 'integer':
				case 'double':
				case 'float':
					$values .= $values == '' ? ', ' . $value : $value;
				break;
				case 'string':
					$value = $this->protectString($value);
					$values .= $values == '' ? ', "' . $value . '"' : '"' . $value . '"';
				break;
				case 'boolean':
					$v = $value===true ? 1 : 0;
					$values .= $values == '' ? ', ' . $v : $v;
				break;
				default:
					throw new UnhandledDatatypeException($this->tablename, $field, $type);
				break;
			}	
		}

		$query = 'INSERT INTO ' . $this->tablename . ' (' . $fields . ') VALUES(' . $values . ')';
		$stm = $this->DB()->prepare($query) or throw new Exception($this->DB()->errorInfo());
		$stm->execute();
		
		return $this->DB()->lastInsertId();
    }
	
    /**
     * Method for update data in db
     * 
     * This method makes it easy to update a previously persisted object
     * in a quick and easy way. The data set is retrieved from 
     * the model object as an associative array
     *
     * @return integer The affected rows
     * @access  public
     * @since   Method available since Release 1.0.0
     */
    public function update(array $data, array $criteria=null): int {

        if($this->tablename == null){
            throw new Exception('No model provided !');
        }
        if($data == null){
            throw new Exception('No data provided !');
        }

		$query = 'UPDATE ' . $this->tablename . ' SET ';
		
		foreach($data as $field=>$value){

			if($field==='id') continue;

			$type = gettype($value);

			switch($type) {
				case 'integer':
				case 'double':
				case 'float':
					$query .= $field.' = '.$value.', ';
				break;
				case 'string':
					$value = $this->protectString($value);
					$values .= $values == '' ? ', "' . $value . '"' : '"' . $value . '"';
				break;
				case 'boolean':
					$v = $value===true ? 1 : 0;
					$query .= $field.' = '.$v.', ';
				break;
				default:
					throw new UnhandledDatatypeException($this->tablename, $field, $type);
				break;
			}
		}
		
		if( !empty($data['id']) ) {
			$query .= $this->predicate( array('id' => $data['id']) );
		} else {
			if( isset($criteria) ) $query .= $this->predicate($criteria);
		}

		return $this->DB()->exec( $query ) or throw new Exception($this->DB()->errorInfo());
    }

    /**
     * Save model object (Insert/Update)
     * 
     * @return  void
     * @access  public
     * @since   Method available since Release 1.0.0
     */
	public function persist($data): void {
		if($data['id'] === 0 || $data['id'] === null) {
			$this->insert($data);
		}
		else {
			$this->update($data);
		}
	}

    /**
     * Method for deleting data in db
     * 
     * This method makes it easy to delete a previously persisted object
     * based on some criteria
     *
     * @param   array $criteria
     * @return  integer The affected rows
     * @access  public
     * @since   Method available since Release 1.0.0
     */
	public function delete(array $criteria): int {
		$query = 'DELETE FROM '. $this->tablename . $this->predicate($criteria);
		return $this->DB()->exec($query) or throw new Exception($this->DB()->errorInfo());
	}

    /**
     * Method for building query WHERE predicate
     * based on an array of criteria
     * 
     * @param   array $criteria
     * @return  string WHERE part of the query
     * @access  private
     * @since   Method available since Release 1.0.0
     */
	private function predicate(array $criteria): string {

		if( count($criteria) === 0) return '';

		$where = '';
		foreach($criteria as $field=>$value){
			$type = gettype($value);
			switch($type) {
				case 'integer':
				case 'double':
				case 'float':
					$where .= ($where === '') 
					? $field . ' = ' . $value 
					: ' AND ' . $field . ' = ' . $value;
				break;
				case 'string':
					$value = $this->protectString($value);
					$where .= ($where === '') 
					? $field . ' = "' . $value .'"' 
					: ' AND ' . $field . ' = "' . $value . '"';
				break;
				case 'boolean':
					$v = $value===true ? 1 : 0;
					$where .= ($where === '') 
					? $field . ' = ' . $v 
					: ' AND ' . $field . ' = ' . $v;
				break;
				default:
					throw new UnhandledDatatypeException($this->tablename, $field, $type);
				break;
			}
		}

		return ' WHERE '.$where;
	}

    /**
     * Method for building filter part of the SELECT query
     * 
     * @param   array $filter
     * @return  string filter part of the SELECT query
     * @access  private
     * @since   Method available since Release 1.0.0
     */
	private function filter(array $filter): string {
		$filter = '';
		$query .= isset($filter['where']) ? ' WHERE '.$filter['where'] : '';
		$query .= isset($filter['group']) ? ' GROUP BY '.$filter['group'] : '';
		$query .= isset($filter['order']) ? ' ORDER BY '.$filter['order'] : '';
		$query .= isset($filter['limit']) ? ' LIMIT '.$filter['limit'] : '';

		return $filter;
	}

    /**
     * The method return a PDO database connection.
     *
     * @return  PDO object
     * @access  public
     * @since   Method available since Release 1.0.0
     */
    protected function DB(): PDO {

        return static::$db;
    }

    /**
     * Utility method to prevent SQL injection and quoting errors.
     *
     * @return  string
     * @access  public
     * @since   Method available since Release 1.0.0
     */	
	private function protectString(string $str) {
		return substr($this->DB()->quote($str), 1, -1);
	}

}
