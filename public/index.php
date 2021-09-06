<?php

declare(strict_types=1);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

use Maviance\Spatter\Services\Balance\BalanceManager;
use Maviance\Spatter\Services\Balance\Repository\BalanceRepository;
use Maviance\Spatter\Clients\BaseClient;

$balanceRepository = new BalanceRepository();
$baseClient = new BaseClient();
$balanceManager = new BalanceManager($balanceRepository, $baseClient);

echo '<center style="font-size: 36px; margin-top: 15%">Maviance Test for Senior Developper: <strong>Brice NTSA</strong></center>';
