<?php

declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// Import model
use Maviance\Spatter\Clients\BaseClient;
use Maviance\Spatter\Services\Balance\Model\Balance;

// Import repositories
use Maviance\Spatter\Clients\Repository\BaseClientRepository;
use Maviance\Spatter\Services\Balance\Repository\BalanceRepository;

// Import managers/services
use Maviance\Spatter\Services\Balance\BalanceManager;

$clientRepository = new BaseClientRepository();
$balanceRepository = new BalanceRepository();

$client = $clientRepository->create();
$balance = $balanceRepository->create();

echo '<center style="font-size: 36px; margin-top: 15%">Maviance Test for Senior Developper: <strong>Brice NTSA</strong></center>';
