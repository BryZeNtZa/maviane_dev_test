<?php

namespace Maviance\Spatter\Services\Balance\Repository;

use Maviance\Core\Repository\Repository;
use Maviance\Spatter\Services\Balance\Model\BalanceModel;

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

	public function __construct() {
		parent::__construct('balance');
	}

	/**
	 * @return BalanceModel
	 */
    public function create(): BalanceModel {
		return new BalanceModel();
	}

	/**
	 * @param BalanceModel $balance
	 * @return void
	 */
    public function save(BalanceModel $balance): void {
		$this->persist($this->getData($balance));
	}

	/**
	 * @param BaseClient $client
	 * @return Balance
	 */
	public function findBalance(BaseClient $client): Balance {

		$balance = $this->create();

		$criteria = array('client_id' => $client->getId());
		$record = $this->get($criteria);

		if($record === null) return $balance;

		$balance->setId( intval($record['id']) );
		$balance->setClient($client);
		$balance->setAmount( floatval($record['amount']) );

		return $balance;
	}

	/**
	 * @param BalanceModel $balance
	 * @return array
	 */
	private function getData(BalanceModel $balance): array {
		return array(
			'id' => null,
			'client_id' => $balance->getClient()->getId(),
			'amount' => $balance->getAmount(),
			'successful' => $balance->getSuccessful(),
			'error' => $balance->getError(),
		);
	}
}
