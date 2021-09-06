<?php

namespace Maviance\Spatter\Services\Balance\Repository;

use Maviance\Spatter\Services\Balance\Model\Balance;

/**
 * Repository interface for the Balance model
 *
 * @author     Maviance, PLC.
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */

interface BalanceRepositoryInterface
{
	/**
	 * @return Balance
	 */
    public function create(): Balance;

	/**
	 * @param Balance $balance
	 * @return void
	 */
    public function save(Balance $balance): void;

	/**
	 * @param BaseClient $client
	 * @return Balance
	 */
    public function findBalance(BaseClient $client): Balance;
}
