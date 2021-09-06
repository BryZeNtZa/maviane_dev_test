<?php

namespace Maviance\Spatter\Services\Balance\Model;

use Maviance\Spatter\Clients\BaseClient;

/**
 * Interface for the Balance model.
 *
 * @author     Maviance, PLC.
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */
interface BalanceInterface {

	/**
	 * Get balance record id
	 *
	 * @return int
	 */
    public function getId(): int;
	/**
	 * Set record balance id
	 * 
	 * @param int $id
	 * @return void
	 */
    public function setId(int $id): void;

	/**
	 * Get the client who owns the balance
	 *
	 * @return BaseClient
	 */
	public function getClient(): BaseClient;
	/**
	 * Set the client who owns the balance
	 * 
	 * @param BaseClient $client
	 * @return void
	 */
    public function setClient(BaseClient $client): void;

	/**
	 * Get the amount of the balance
	 * 
	 * @return float
	 */
	public function getAmount(): float;
	/**
	 * Set the amount of the balance
	 * 
	 * @param float $amount
	 * @return void
	 */
    public function setAmount(float $amount): void;

	/**
	 * Set the status of the last operation on the balance
	 *
	 * @return bool
	 */
	public function getSuccessful(): bool;
	/**
	 * Set the status of the last operation on the balance
	 * 
	 * @param boolean $successful
	 * @return void
	 */
    public function setSuccessful(bool $successful): void;

	/**
	 * Error message in case of operation failure
	 *
	 * @return string
	 */
	public function getError(): string;
	/**
	 * Error message in case of operation failure
	 * 
	 * @param string $error
	 * @return void
	 */
    public function setError(string $error): void;

}
