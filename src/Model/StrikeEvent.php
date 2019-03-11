<?php declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class StrikeEvent
{
    /**
     * @var string $cityName
     * @JMS\Expose
     */
    protected $cityName;

    /**
     * @var \DateTime $dateTime
     * @JMS\Expose
     */
    protected $dateTime;

    /**
     * @var string $location
     * @JMS\Expose
     */
    protected $location;

    public function __construct(string $cityName, \DateTime $dateTime, string $location)
    {
        $this->cityName = $cityName;
        $this->dateTime = $dateTime;
        $this->location = $location;
    }
}