<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Test\Infra\Doctrine;

$em = (new Doctrine())->getEM();
    $db = $em->getConnection();

$helperSet = new HelperSet(
    [
        'db' => new ConnectionHelper($db),
        'question' => new QuestionHelper(),
    ]
);

return $helperSet;
