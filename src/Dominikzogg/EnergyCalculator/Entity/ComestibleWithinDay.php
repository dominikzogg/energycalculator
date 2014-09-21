<?php

namespace Dominikzogg\EnergyCalculator\Entity;

use Doctrine\ORM\Mapping as ORM;
use Saxulum\Accessor\Accessors\Get;
use Saxulum\Accessor\AccessorTrait;
use Saxulum\Accessor\Prop;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="comestible_within_day")
 * @method int getId()
 * @method Day getDay()
 * @method Comestible getComestible()
 * @method float getAmount()
 */
class ComestibleWithinDay
{
    use AccessorTrait;
    use AttributeTrait;

    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Day
     * @ORM\ManyToOne(targetEntity="Day", inversedBy="comestiblesWithinDay")
     * @ORM\JoinColumn(name="day_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $day;

    /**
     * @var Comestible
     * @ORM\ManyToOne(targetEntity="Comestible")
     * @ORM\JoinColumn(name="comestible_id", referencedColumnName="id")
     * @Assert\NotNull(message="day.comestibleWithinDay.comestible.notnull")
     */
    protected $comestible;

    /**
     * @var float
     * @ORM\Column(name="amount", type="decimal", precision=10, scale=4, nullable=false)
     * @Assert\NotNull(message="day.comestibleWithinDay.amount.notnull")
     * @Assert\GreaterThanOrEqual(value=0, message="day.comestibleWithinDay.amount.greaterthanorequal")
     */
    protected $amount = 0;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getComestible()->getName();
    }

    protected function initializeProperties()
    {
        $this->prop((new Prop('id'))->method(Get::PREFIX));
        $this->prop((new Prop('day'))->method(Get::PREFIX));
        $this->prop((new Prop('comestible'))->method(Get::PREFIX));
        $this->prop((new Prop('amount'))->method(Get::PREFIX));
    }

    /**
     * @param Day $day
     * @param bool $stopPropagation
     * @return $this
     */
    public function setDay(Day $day = null, $stopPropagation = false)
    {
        if(!$stopPropagation) {
            if(!is_null($this->day)) {
                $this->day->removeComestibleWithinDay($this, true);
            }
            if(!is_null($day)) {
                $day->addComestibleWithinDay($this, true);
            }
        }
        $this->day = $day;
        return $this;
    }

    /**
     * @param Comestible $comestible
     * @return $this
     */
    public function setComestible(Comestible $comestible)
    {
        $this->comestible = $comestible;
        $this->resetValues();
        return $this;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        $this->resetValues();
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->getComestible()->getName();
    }

    /**
     * @return float
     */
    public function getCalorie()
    {
        if($this->calorie === null) {
            $this->calorie = $this->getComestible()->getCalorie() * $this->getAmount() / 100;
        }

        return $this->calorie;
    }

    /**
     * @return float
     */
    public function getProtein()
    {
        if($this->protein === null) {
            $this->protein = $this->getComestible()->getProtein() * $this->getAmount() / 100;
        }

        return $this->protein;
    }

    /**
     * @return float
     */
    public function getCarbohydrate()
    {
        if($this->carbohydrate === null) {
            $this->carbohydrate = $this->getComestible()->getCarbohydrate() * $this->getAmount() / 100;
        }

        return $this->carbohydrate;
    }

    /**
     * @return float
     */
    public function getFat()
    {
        if($this->fat === null) {
            $this->fat = $this->getComestible()->getFat() * $this->getAmount() / 100;
        }

        return $this->fat;
    }
}
