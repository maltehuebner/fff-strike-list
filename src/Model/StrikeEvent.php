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

    /**
     * @var array $links
     * @JMS\Expose
     * @JMS\Type("array<App\Model\StrikeLink>")
     */
    protected $links = [];

    public function __construct(string $cityName, \DateTime $dateTime, string $location, float $latitude = null, float $longitude = null)
    {
        $this->cityName = $cityName;
        $this->dateTime = $dateTime;
        $this->location = $location;

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public function getCityName(): string
    {
        return $this->cityName;
    }

    public function setDateTime(\DateTime $dateTime = null): StrikeEvent
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->dateTime;
    }

    public function setLocation(string $location = null): StrikeEvent
    {
        $this->location = $location;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLatitude(float $latitude = null): StrikeEvent
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLongitude(float $longitude = null): StrikeEvent
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLinks(array $links): StrikeEvent
    {
        $this->links = $links;

        return $this;
    }

    public function getLinks(): array
    {
        return $this->links;
    }
}