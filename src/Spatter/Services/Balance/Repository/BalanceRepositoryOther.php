<?php

namespace Maviance\Spatter\Services\Balance\Repository;

use Maviance\Core\Repository\Repository;
use Maviance\Spatter\Services\Balance\Model\Balance;
use Maviance\Spatter\Clients\BaseClient;
use Maviance\Spatter\Clients\Repository\BaseClientRepository;

/**
 * Other RepositoryInterface implementation for the Balance Model
 * It directly extends the generic Repository class
 * This has the advantage of providing more abstraction and code cleaness
 *
 * @author     Maviance, PLC.
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */
interface BalanceRepositoryOther extends Repository implements BalanceRepositoryInterface {

    /**
     * Client Repository
     *
     * @var BaseClientRepository $clientRepository
     */
	private $clientRepository;

	public function __construct() {
		parent::__construct('balance');
		$this->clientRepository = new BaseClientRepository();
	}

	/**
	 * @return Balance
	 */
    public function create(): Balance {
		$balance = new Balance();
		$balance->setClient( $this->clientRepository->create() );
		return $balance;
	}

	/**
	 * @param Balance $balance
	 * @return void
	 */
    public function save(Balance $balance): void {
		$this->persist($this->getData($balance));
	}

	/**
	 * @return Balance
	 */
    public function get(int $id): Balance {

		$balance = $this->create();

		$record = $this->getById($id);

		if($record === null) return $balance;
		
		$balance->setId( intval($record['id']) );
		$client = $this->clientRepository->get(intval($record['client_id']));
		$balance->setClient($client);
		$balance->setAmount( floatval($record['amount']) );

		return $balance;
	}

	/**
	 * @param BaseClient $client
	 * @return Balance
	 */
	public function findBalance(BaseClient $client): Balance {

		$balance = $this->create();

		$criteria = array('client_id' => $client->getId());
		$record = $this->fetch($criteria);

		if($record === null) return $balance;

		$balance->setId( intval($record['id']) );
		$balance->setClient($client);
		$balance->setAmount( floatval($record['amount']) );

		return $balance;
	}

	/**
	 * @param Balance $balance
	 * @return array
	 */
	private function getData(Balance $balance): array {
		return array(
			'id' => null,
			'client_id' => $balance->getClient()->getId(),
			'amount' => $balance->getAmount(),
			'successful' => $balance->getSuccessful(),
			'error' => $balance->getError(),
		);
	}
}
