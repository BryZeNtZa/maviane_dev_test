<?php

namespace Maviance\Core\Model\Exception;

use Exception;

class UnhandledDatatypeException extends Exception
{
	public function __construct(string $tablename, string $field, string $type)
	{
		parent::__construct( sprintf('Unknown Datatype «%s» of the attribute «%s» on model class «%s»', $tablename, $field, $type) );
	}
}