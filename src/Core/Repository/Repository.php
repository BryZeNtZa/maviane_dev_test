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
    public function find(array $criteria): iterable {

		$fields = isset($criteria['fields']) ? $criteria['fields'] : '*';
		$query =  'SELECT ' . $fields . ' FROM ' . $this->tablename;

		$query .= isset($criteria['where']) ? ' WHERE '.$criteria['where'] : '';
		$query .= isset($criteria['group']) ? ' GROUP BY '.$criteria['group'] : '';
		$query .= isset($criteria['order']) ? ' ORDER BY '.$criteria['order'] : '';
		$query .= isset($criteria['limit']) ? ' LIMIT '.$criteria['limit'] : '';

		$stm = $this->DB()->prepare($query) or throw new Exception($this->DB()->errorInfo());
		$stm->execute();

		return $stm->fetchAll();
	}

    /**
     * Abstract method for getting model all records from database.
     *
     *
     * @return array
     * @access  public
     * @since   Method available since Release 1.0.0
     */
	public function getAll(): iterable {
		return $this->find(array('fields'=>'*'));
	}

	public function count(): int {
		$criteria = array('fields' => 'COUNT(*) AS count');
		return $this->find($criteria)[0]['count'];
	}

    /**
     * Abstract method for getting one record from database based on criteria.
     *
     *
     * @return array
     * @access  public
     * @since   Method available since Release 1.0.0
     */
    public function get(array $criteria): array {
		$records = $this->find($criteria);
		return count($records)!=0 ? $records[0] : null;
	}

    /**
     * The insert method.
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
     * The update method.
     * 
     * This method makes it easy to updated a previously persisted object
     * in a quick and easy way. The data set is retrieved from 
     * the model object as an associative array
     *
     * @return integer The affected rows
     * @access  public
     * @since   Method available since Release 1.0.0
     */
    public function update(array $data): int {

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
		
		$query .= ' WHERE id='.$data['id']; 

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
     * Utility method to prevent SQL injection and .
     *
     * @return  PDO object
     * @access  public
     * @since   Method available since Release 1.0.0
     */	
	private function protectString(string $str) {
		return substr($this->DB()->quote($str), 1, -1);
	}

}
