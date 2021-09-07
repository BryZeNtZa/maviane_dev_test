<?php

namespace Maviance\Spatter\Services\Balance;

use Maviance\Spatter\Services\Balance\Exceptions\BalanceUpdateException;
use Maviance\Spatter\Services\Balance\Model\BalanceInterface;
use Maviance\Spatter\Services\Balance\Repository\BalanceRepositoryInterface;
use Maviance\Spatter\Clients\BaseClientInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class BalanceManager
{

    /**
     *
     * @var BaseClientInterface
     */
    protected $client;

    /**
     *
     * @var BalanceRepositoryInterface
     */
    protected $erm;

    /**
     * @var Monolog/Logger
     */
    protected $logger;

    /**
     *
     * @param BalanceRepository $erm
     * @param BaseClientInterface $client
     */
    public function __construct(BalanceRepositoryInterface $erm, BaseClientInterface $client)
    {
        $this->client = $client;
        $this->erm = $erm;
        $this->logger = new Logger(get_class());
        // $this->logger->pushHandler(new StreamHandler(__DIR__ . '/logs/app.log', Logger::DEBUG));
    }

    /**
     * Method for synchronizing the account balance of a given client 
	 * With his balance in the balance table
     *
     * @return BalanceInterface
     */
    public function refresh(): BalanceInterface
    {

        try {
	    // We'll find existing client balance and update it
            // Or we'll create a new balance record for the client
            $balance = $this->erm->findBalance($this->client);

            $balance->setClient($this->client);
            $balance->setAmount($this->client->getBalance());

            $this->erm->save($balance);

            $balance->setSuccessful(true);
            $this->logger->info("Success");
			
        } catch (\Throwable $e) {
			$balance = $this->erm->create();
            $balance->setSuccessful(false);
            $balance->setError(\sprintf("Class: %s - Message: %s", get_class($e), $e->getMessage()));

            $this->logger->error(\sprintf("Class: %s - Message: %s", get_class($e), $e->getMessage()));
            throw new BalanceUpdateException(sprintf("Could not update balance from provider: %s", $e->getMessage()));
        }

        return $balance;
    }

}
