<?php declare(strict_types=1);

namespace App\Model;

class StrikeLink
{
    /** @var string $url */
    protected $url;

    /** @var string $linkType */
    protected $linkType;

    public function __construct(string $url, string $linkType = null)
    {
        $this->url = $url;
        $this->linkType = $linkType;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getLinkType(): ?string
    {
        return $this->linkType;
    }
}