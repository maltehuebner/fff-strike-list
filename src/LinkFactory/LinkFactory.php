<?php declare(strict_types=1);

namespace App\LinkFactory;

use App\Model\FffStrikeData;
use App\Model\StrikeLink;

class LinkFactory implements LinkFactoryInterface
{
    public function grepLinks(FffStrikeData $fffStrikeData): array
    {
        $resultList = [];

        preg_match_all('/href=(?:"|\')([^\']*)(?:"|\')/', $fffStrikeData->getUrl(), $matches);

        if (2 !== count($matches)) {
            return [];
        }

        foreach ($matches[1] as $url) {
            $resultList[] = new StrikeLink($url);
        }

        return $resultList;
    }
}