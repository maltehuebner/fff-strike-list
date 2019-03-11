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

    /**
     * @var float $latitude
     * @JMS\Expose
     */
    protected $latitude;

    /**
     * @var float $longitude
     * @JMS\Expose
     */
    protected $longitude;

    public function __construct(string $cityName, \DateTime $dateTime, string $location)
    {
        $this->cityName = $cityName;
        $this->dateTime = $dateTime;
        $this->location = $location;
    }

    public function getCityName(): string
    {
        return $this->cityName;
    }

    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLatitude(float $latitude): StrikeEvent
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function setLongitude(float $longitude): StrikeEvent
    {
        $this->longitude = $longitude;

        return $this;
    }
}