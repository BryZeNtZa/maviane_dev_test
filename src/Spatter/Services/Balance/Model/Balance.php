<?php

namespace Maviance\Spatter\Services\Balance\Model;

use Maviance\Spatter\Clients\BaseClient;

/**
 * Model class representing a Balance.
 *
 * @author     Maviance, PLC.
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */
class Balance implements BalanceInterface {

    /**
     * Balance record id
     *
     * @var int $id
     */
	private $id;
	
    /**
     * Client
     *
     * @var BaseClient $client
     */
	private $client;

    /**
     * Amount of the balance
     *
     * @var float $amount
     */
	private $amount;

    /**
     * State of the last operation
     *
     * @var boolean $successful
     */
	private $successful;

    /**
     * Error message of the last operation failure
     *
     * @var bool $error
     */
	private $error;
	
    /**
     * @return int
     */
    public function getId(): int {
		return $this->id;
	}
    /**
     * @param int $id
     * @return void
     */
    public function setId(int $id): void {
		$this->id = $id;
	}

    /**
     * @return BaseClient
     */
    public function getClient(): BaseClient {
		return $this->client;
	}
    /**
     * @param BaseClient $client
     * @return void
     */
    public function setClient(BaseClient $client): void {
		$this->client = $client;
	}

	/**
	 * @return float
	 */
    public function getAmount(): float {
		return $this->amount;
	}
	/**
	 * @param float $amount
	 * @return void
	 */
    public function setAmount(float $amount): void {
		$this->amount = $amount;
	}

	/**
	 * @return bool
	 */
    public function getSuccessful(): bool {
		return $this->successful;
	}
	/**
	 * @param boolean $successful
	 * @return void
	 */
    public function setSuccessful(bool $successful): void {
		$this->successful = $successful;
	}

	/**
	 * @return void
	 */
    public function getError(): string {
		return $this->error;
	}
	/**
	 * @param string $error
	 * @return void
	 */
    public function setError(string $error): void {
		$this->error = $error;
	}
}
