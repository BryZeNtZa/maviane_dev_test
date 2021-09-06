<?php

namespace Maviance\Spatter\Clients;

/**
 * Model class representing a client.
 *
 * @author     Maviance, PLC.
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */
class BaseClient implements BaseClientInterface 
{
    /**
     * Client id
     *
     * @var int $id
     */
	private $id;

    /**
     * Client name
     *
     * @var string $name
     */
	private $name;

	/**
     * Amount of the balance
     *
     * @var float $balance
     */
	private $balance;
	
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
     * @return string
     */
    public function getName(): string {
		return $this->name;
	}

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void {
		$this->name = $name;
	}

    /**
     * @return float
     */
    public function getBalance(): float {
		return $this->balance;
	}
	
    /**
     * @param float $balance
     * @return float
     */
    public function setBalance(float $balance): void {
		$this->balance = $balance;
	}
}
