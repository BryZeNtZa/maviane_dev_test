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

class BalanceRepositoryOtherTest extends TestCase
{
    public function testRetrieve()
    {
		$this->reInitBD();

		$clientRepository = new BaseClientRepository();
		$balanceRepository = new BalanceRepository();

		// check we have exactly 2 clients in our dataset
		$this->assertEquals(2, $clientRepository->count());

		// check we have exactly 1 balance entry in our dataset
		$this->assertEquals(1, $balanceRepository->count());

		// get client 1
		$client1 = $clientRepository->get(1);
		$this->assertNotNull($client1);
		$this->assertEquals('Test user', $client1->getName());
		$this->assertEquals(1000, $client1->getBalance());

		// get client 1 balance
		$client1Balance = $balanceRepository->get(1);
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

		$clientRepository = new BaseClientRepository();
		$balanceRepository = new BalanceRepository();

		// check we have exactly 1 balance entry in our dataset
		$this->assertEquals(1, $balanceRepository->count());

		// balance to be created
		$balance = $balanceRepository->create();

		// client owning the balance
		$client2 = $clientRepository->get(2);
		$this->assertNotNull($client2);
		$this->assertEquals('Second user', $client2->getName());
		$this->assertEquals(50000, $client2->getBalance());

		$balance->setClient($client2);

		// perform the insertion
		$balanceRepository->save($balance);

		// check if the client's balance entry has been created and is OK
		$this->assertEquals(2, $balanceRepository->count());
		$this->assertEquals($client2->getBalance(), $balance->getAmount());
    }
	
	/*
	 * Update client 1 balance
	 */
    public function testUpdate()
    {
		$this->reInitBD();

		$balanceRepository = new BalanceRepository();
		$this->assertEquals(1, $balanceRepository->count());

		// balance to be updated
		$balance = $balanceRepository->get(1);
		$this->assertEquals(1000, $balance->getAmount());

		$balance->setAmount(2000);

		// perform the update
		$balanceRepository->save($balance);

		// re-get the balance and check if it has been updated
		$balance = $balanceRepository->get(1);
		$this->assertEquals(2000, $balance->getAmount());
    }
	
	/*
	 * Reinitialize database as the tests goes on
	 */
	private function reInitBD() {
		// TODO
	}
}
