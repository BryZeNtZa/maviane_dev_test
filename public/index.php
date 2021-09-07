<?php

declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

// Import models
use Maviance\Spatter\Clients\BaseClient;
use Maviance\Spatter\Services\Balance\Model\Balance;

// Import repositories
use Maviance\Spatter\Clients\Repository\BaseClientRepository;
use Maviance\Spatter\Services\Balance\Repository\BalanceRepository;

// Import managers/services
use Maviance\Spatter\Services\Balance\BalanceManager;

$clientRepository = new BaseClientRepository();
$balanceRepository = new BalanceRepository();

$client0 = $clientRepository->create();
$balance = $balanceRepository->create();
$client1 = $clientRepository->get(1);
$client2 = $clientRepository->get(2);

echo 'CLIENT N° 1';
echo '<br />';
echo 'Client name : '.$client1->getName();
echo '<br />';
echo 'Client balance : '.$client1->getBalance();
echo '<br />------------------------<br />';
echo 'CLIENT N° 1';
echo '<br />';
echo 'Client name : '.$client2->getName();
echo '<br />';
echo 'Client balance : '.$client2->getBalance();
echo '<center style="font-size: 36px; margin-top: 8%">Maviance Test for Senior Developper: <strong>Brice NTSA</strong></center>';
