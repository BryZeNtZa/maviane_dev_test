<?php

namespace Maviance\Spatter\Services\Balance;

use Maviance\Spatter\Services\Balance\Exceptions\BalanceUpdateException;
use Maviance\Spatter\Services\Balance\Model\BalanceInterface;
use Maviance\Spatter\Services\Balance\Repository\BalanceRepositoryInterface;
use Maviance\Spatter\Clients\BaseClientInterface;
use Monolog\Logger;

class Manager
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
        $this->logger = new Monolog / Logger();
    }

    public function refresh(): BalanceInterface
    {
        $balance = $this->erm->create();

        try {
            $balance_amount = $this->client->getBalance();
            $balance->setAmount($balance_amount);
            $balance->setSuccessful(true);
            $this->logger->info("Success");
        } catch (\Throwable $e) {
            $balance->setSuccessful(false);
            $balance->setError(\sprintf("Class: %s - Message: %s", get_class($e), $e->getMessage()));
            $this->logger->error(\sprintf("Class: %s - Message: %s", get_class($e), $e->getMessage()));
            throw new BalanceUpdateException(sprintf("Could not retrieve updated balance from provider: %s", $e->getMessage()));
        } finally {
            $this->erm->save($balance);
        }
        return $balance;
    }

    public function getBalance(): float
    {
        return $this->erm->getBalance();
    }
}
