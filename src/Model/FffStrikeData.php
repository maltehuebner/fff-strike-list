<?php declare(strict_types=1);

namespace App\Model;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 */
class FffStrikeData
{
    /**
     * @var string $country
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $country;

    /**
     * @var \DateTime $town
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $town;

    /**
     * @var string $location
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $location;

    /**
     * @var string $time
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $time;

    /**
     * @var string $date
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $date;

    /**
     * @var string $recurrence
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $recurrence;

    /**
     * @var string $url
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $url;

    /**
     * @var float $lat
     * @JMS\Expose
     * @JMS\Type("float")
     */
    protected $lat;

    /**
     * @var float $lon
     * @JMS\Expose
     * @JMS\Type("float")
     */
    protected $lon;

    /**
     * @var string $contactName
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $contactName;

    /**
     * @var string $contactEmail
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $contactEmail;

    /**
     * @var string $contactPhone
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $contactPhone;

    /**
     * @var string $notes
     * @JMS\Expose
     * @JMS\Type("string")
     */
    protected $notes;
}
