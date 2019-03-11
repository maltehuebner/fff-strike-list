<?php declare(strict_types=1);

namespace App\Model;

class StrikeEvent
{
    /** @var string $cityName */
    protected $cityName;

    /** @var \DateTime $dateTime */
    protected $dateTime;

    /** @var string $location */
    protected $location;

    public function __construct(string $cityName, \DateTime $dateTime, string $location)
    {
        $this->cityName = $cityName;
        $this->dateTime = $dateTime;
        $this->location = $location;
    }
}