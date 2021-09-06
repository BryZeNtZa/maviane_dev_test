<?php

namespace Maviance\Spatter\Clients;

/**
 * Interface for the Base Client.
 *
 * @author     Maviance, PLC.
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */
interface BaseClientInterface
{

	/**
	 * Get client record id
	 *
	 * @return int
	 */
    public function getId(): int;
	/**
	 * Set client record id
	 * 
	 * @param int $id
	 * @return void
	 */
    public function setId(int $id): void;

	/**
	 * Get the client name
	 *
	 * @return string
	 */

    public function getName(): string;
	/**
	 * Set the client name
	 * 
	 * @param string $name
	 * @return void
	 */
    public function setName(string $name): void;
	/**
	 * Get the client balance
	 *
	 * @return float
	 */

    public function getBalance(): float;
	/**
	 * Set the client balance
	 * 
	 * @param float $balance
	 * @return void
	 */
    public function setBalance(float $balance): void;

}
