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

class BalanceManagerTest extends TestCase
{
	/*
	 * @var BaseClientRepository
	 */
	private $clientRepository;
	
	/*
	 * @var BalanceRepository
	 */
	private $balanceRepository;
	
	/*
	 * Test if the balance of client is the same in the balance table
	 */
    public function testSynchronized()
    {
		$this->reInitBD();

		$client1 = $this->clientRepository->get(1);
		$client1Balance = $this->balanceRepository->get(1);

		$this->assertNotNull($client1Balance);
		$this->assertEquals($client1->getBalance(), $client1Balance->getClient()->getBalance());
    }    

	/*
	 * Test the refresh method
	 */	
	public function testRefreshBalance()
    {
		$this->reInitBD();

		$client1 = $this->clientRepository->get(1);
		
		$balanceManager = new BalanceManager($this->balanceRepository, $client1);

		$client1Balance = $balanceManager->refresh();
		$this->assertEquals($client1->getBalance(), $client1Balance->getClient()->getBalance());
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
