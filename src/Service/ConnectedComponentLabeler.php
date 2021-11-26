<?php

namespace App\Service;

use App\Model\Matrix;
use Symfony\Component\Console\Style\SymfonyStyle;

interface ConnectedComponentLabeler
{
    public function getGroupsNr(Matrix $matrix, ?SymfonyStyle $logger=null): int;
}
