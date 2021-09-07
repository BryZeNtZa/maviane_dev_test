<?php

namespace Maviance\Spatter\Services\Balance\Repository;

use Maviance\Spatter\Services\Balance\Model\Balance;
use Maviance\Core\Repository\Repository;
use Maviance\Spatter\Clients\Repository\BaseClientRepository;

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
     * @var Repository $repository
     */
	private $repository;
	
    /**
     * Client Repository
     *
     * @var BaseClientRepository $clientRepository
     */
	private $clientRepository;

	public function __construct() {
		$this->repository = new Repository('balance');
		$this->clientRepository = new BaseClientRepository();
	}
	/**
	 * @return BalanceModel
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
		$record = $this->repository->fetch($criteria);

		if($record === null) return $balance;

		$balance->setId( intval($record['id']) );
		$balance->setClient($client);
		$balance->setAmount( floatval($record['amount']) );

		return $balance;
	}
}
