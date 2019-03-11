<?php declare(strict_types=1);

namespace App\Source;

interface SourceInterface
{
    public function getStrikeList(): array;

    public function enrichStrikeList(array $strikeList): array;
}