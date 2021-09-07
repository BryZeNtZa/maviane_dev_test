<?php

namespace Maviance\Spatter\Clients\Repository;

use Maviance\Spatter\Clients\BaseClient;

/**
 * Repository interface for the Base client model
 *
 * @author     Maviance, PLC.
 * @version    Release: @package_version@
 * @since      Class available since Release 1.0.0
 */

interface BaseClientRepositoryInterface
{
	/**
	 * @return BaseClient
	 */
    public function create(): BaseClient;

	/**
	 * @param BaseClient $client
	 * @return void
	 */
    public function save(BaseClient $client): void;
	
	/**
	 * @param  int $id
	 * @return BaseClient
	 */
    public function get(int $id): BaseClient;

}
