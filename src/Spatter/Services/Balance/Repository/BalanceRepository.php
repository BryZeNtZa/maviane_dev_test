<?php

namespace Maviance\Spatter\Services\Balance\Repository;

use Maviance\Core\Repository\Repository;
use Maviance\Spatter\Services\Balance\Model\Balance;

/**
 * Default Repository Implementation for the Balance Model.
 *
 * @author     Maviance, PLC.
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */
class BalanceRepository implements BalanceRepositoryInterface {
    /**
     * Balance Repository
     *
     * @var int $repository
     */
	private $repository;

	public function __construct() {
		$this->repository = new Repository('balance');
	}
	/**
	 * @return BalanceModel
	 */
    public function create(): Balance {
		return new Balance();
	}

	/**
	 * @param Balance $balance
	 * @return void
	 */
    public function save(Balance $balance): void {
		$data = array(
			'id' => null,
			'client_id' => $balance->getClient()->getId(),
			'amount' => $balance->getAmount(),
			'successful' => $balance->getSuccessful(),
			'error' => $balance->getError(),
		);
		$this->repository->persist($data);
	}

	/**
	 * @param BaseClient $client
	 * @return Balance
	 */
	public function findBalance(BaseClient $client): Balance {

		$balance = $this->create();

		$criteria = array('client_id' => $client->getId());
		$record = $this->repository->get($criteria);

		if($record === null) return $balance;

		$balance->setId( intval($record['id']) );
		$balance->setClient($client);
		$balance->setAmount( floatval($record['amount']) );

		return $balance;
	}
}
