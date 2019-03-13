<?php declare(strict_types=1);

namespace App\LinkFactory;

use App\Model\FffStrikeData;

interface LinkFactoryInterface
{
    public function grepLinks(FffStrikeData $fffStrikeData): array;
}