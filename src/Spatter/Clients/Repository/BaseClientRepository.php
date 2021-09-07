<?php

namespace Maviance\Spatter\Clients\Repository;

use Maviance\Core\Repository\Repository;
use Maviance\Spatter\Clients\BaseClient;

/**
 * Default Repository Implementation for the Base client model.
 *
 * @author     Maviance, PLC.
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */
class BaseClientRepository extends Repository implements BaseClientRepositoryInterface {

	public function __construct() {
		parent::__construct('client');
	}

	/**
	 * @return BaseClient
	 */
    public function create(): BaseClient {
		return new BaseClient();
	}

	/**
	 * @param BaseClient $balance
	 * @return void
	 */
    public function save(BaseClient $balance): void {
		$this->persist($this->getData($balance));
	}
	
	/**
	 * @return BaseClient
	 */
    public function get(int $id): BaseClient {
		$client = $this->create();
		$record = $this->getById($id);
		
		if($record === null) return $client;

		$client->setId( intval($record['id']) );
		$client->setName($record['name']);
		$client->setBalance( floatval($record['balance']) );

		return $client;
	}

	/**
	 * @param BaseClient $client
	 * @return array
	 */
	private function getData(BaseClient $client): array {
		return array(
			'id' => null,
			'name' => $balance->getName(),
			'balance' => $balance->getBalance(),
		);
	}
}
