<?php

namespace Maviance\Spatter\Services\Balance\Exceptions;

class BalanceUpdateException extends Exception
{
	public function __construct(string $errorMessage)
	{
		parent::__construct(sprintf('Wrong balance exception : %s', $errorMessage));
	}
}