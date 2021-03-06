<?php

namespace Maviance\Tests;

// Import models
use Maviance\Spatter\Clients\BaseClient;
use Maviance\Spatter\Services\Balance\Model\Balance;

// Import repositories
use Maviance\Spatter\Clients\Repository\BaseClientRepository;
use Maviance\Spatter\Clients\Repository\BaseClientRepositoryOther;
use Maviance\Spatter\Services\Balance\Repository\BalanceRepository;

// Import managers/services
use Maviance\Spatter\Services\Balance\BalanceManager;

use PHPUnit\Framework\TestCase;

class BalanceRepositoryTest extends TestCase
{
	/*
	 * @var BaseClientRepository
	 */
	private $clientRepository;
	
	/*
	 * @var BalanceRepository
	 */
	private $balanceRepository;

    public function testRetrieve()
    {
		$this->reInitBD();

		// check we have exactly 2 clients in our dataset
		$this->assertEquals(2, $this->clientRepository->count());

		// check we have exactly 1 balance entry in our dataset
		$this->assertEquals(1, $this->balanceRepository->count());

		// get client 1
		$client1 = $clientRepository->get(1);
		$this->assertNotNull($client1);
		$this->assertEquals('Test user', $client1->getName());
		$this->assertEquals(1000, $client1->getBalance());

		// get client 1 balance
		$client1Balance = $this->balanceRepository->get(1);
		$this->assertNotNull($client1Balance);
		$this->assertEquals($client1->getName(), $client1Balance->getClient()->getName());

		// get client 2
		$client2 = $clientRepository->get(2);
		$this->assertNotNull($client2);
		$this->assertEquals('Second user', $client2->getName());
		$this->assertEquals(35000000, $client2->getBalance());
    }
	
	/*
	 * Create client 2 balance entry
	 */
    public function testInsert()
    {
		$this->reInitBD();

		// check we have exactly 1 balance entry in our dataset
		$this->assertEquals(1, $this->balanceRepository->count());

		// balance to be created
		$balance = $this->balanceRepository->create();

		// client owning the balance
		$client2 = $clientRepository->get(2);
		$this->assertNotNull($client2);
		$this->assertEquals('Second user', $client2->getName());
		$this->assertEquals(50000, $client2->getBalance());

		$balance->setClient($client2);

		// perform the insertion
		$this->balanceRepository->save($balance);

		// check if the client's balance entry has been created and is OK
		$this->assertEquals(2, $this->balanceRepository->count());
		$this->assertEquals($client2->getBalance(), $balance->getAmount());
    }
	
	/*
	 * Update client 1 balance
	 */
    public function testUpdate()
    {
		$this->reInitBD();

		$this->assertEquals(1, $this->balanceRepository->count());

		// balance to be updated
		$balance = $this->balanceRepository->get(1);
		$this->assertEquals(1000, $balance->getAmount());

		$balance->setAmount(2000);

		// perform the update
		$this->balanceRepository->save($balance);

		// re-get the balance and check if it has been updated
		$balance = $this->balanceRepository->get(1);
		$this->assertEquals(2000, $balance->getAmount());
    }

	/*
	 * Setup the tests
	 */
	protected function setUp() {
		$this->clientRepository = new BaseClientRepository();
		$this->balanceRepository = new BalanceRepository();
	}

	/*
	 * Reinitialize database as the tests goes on
	 */
	private function reInitBD() {
		// TODO
	}

}
