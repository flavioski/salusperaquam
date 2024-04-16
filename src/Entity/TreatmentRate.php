<?php
/**
 * Salus per Aquam
 * Copyright since 2021 Flavio Pellizzer and Contributors
 * <Flavio Pellizzer> Property
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to flappio.pelliccia@gmail.com so we can send you a copy immediately.
 *
 * @author    Flavio Pellizzer
 * @copyright Since 2021 Flavio Pellizzer
 * @license   https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace Flavioski\Module\SalusPerAquam\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flavioski\Module\SalusPerAquam\Repository\TreatmentRateRepository")
 * @ORM\HasLifecycleCallbacks
 */
class TreatmentRate
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_treatment_rate", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Treatment
     * @ORM\ManyToOne(targetEntity="Flavioski\Module\SalusPerAquam\Entity\Treatment", inversedBy="treatmentRates", cascade={"persist"})
     * @ORM\JoinColumn(name="id_treatment", referencedColumnName="id_treatment", nullable=false)
     */
    private $treatment;

    /**
     * @var int
     *
     * @ORM\Column(name="id_product", type="integer", options={"unsigned"=true}, nullable=false)
     */
    private $productId;

    /**
     * @var int
     *
     * @ORM\Column(name="id_product_attribute", type="integer", options={"unsigned"=true}, nullable=true)
     */
    private $productAttributeId;

    /**
     * @var \DateTime
     * @ORM\Column(name="from_date", type="datetime", nullable=false)
     */
    private $fromDate;

    /**
     * @var \DateTime
     * @ORM\Column(name="to_date", type="datetime", nullable=false)
     */
    private $toDate;

    /**
     * @var string
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    /**
     * @var string
     * @ORM\Column(name="weekdays", type="string", nullable=true)
     */
    private $weekdays;

    /**
     * @var string
     * @ORM\Column(name="weekend", type="string", nullable=true)
     */
    private $weekend;

    /**
     * @var string
     * @ORM\Column(name="internal_id", type="string", nullable=true)
     */
    private $internalId;

    /**
     * @var string
     * @ORM\Column(name="internal_id_rate", type="string", nullable=true)
     */
    private $internalIdRate;

    /**
     * @var float
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false, options={"default":"0.00"})
     */
    private $price;

    /**
     * @var bool
     * @ORM\Column(name="installment_payment_plan", type="boolean")
     */
    private $installmentPaymentPlan;

    /**
     * @var bool
     * @ORM\Column(name="discount", type="boolean")
     */
    private $discount;

    /**
     * @var bool
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean")
     */
    private $deleted = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_add", type="datetime")
     */
    private $dateAdd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_upd", type="datetime")
     */
    private $dateUpd;

    public function __construct()
    {
        $this->setInstallmentPaymentPlan(false);
        $this->setDiscount(false);
        $this->setDateAdd(new \DateTime());
        $this->setDateUpd(new \DateTime());
        $this->setActive(true);
        $this->setDeleted(false);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Treatment
     */
    public function getTreatment()
    {
        return $this->treatment;
    }

    /**
     * @param Treatment $treatment
     *
     * @return $this
     */
    public function setTreatment(Treatment $treatment)
    {
        $this->treatment = $treatment;

        return $this;
    }

    /**
     * @return int
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * @param int $productId
     *
     * @return TreatmentRate
     */
    public function setProductId(int $productId): TreatmentRate
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return int
     */
    public function getProductAttributeId(): ?int
    {
        return $this->productAttributeId;
    }

    /**
     * @param int|null $productAttributeId
     *
     * @return TreatmentRate
     */
    public function setProductAttributeId(?int $productAttributeId): self
    {
        $this->productAttributeId = $productAttributeId;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getFromDate(): DateTime
    {
        return $this->fromDate;
    }

    /**
     * @param DateTime $fromDate
     *
     * @return TreatmentRate
     */
    public function setFromDate(DateTime $fromDate): TreatmentRate
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getToDate(): DateTime
    {
        return $this->toDate;
    }

    /**
     * @param DateTime $toDate
     *
     * @return TreatmentRate
     */
    public function setToDate(DateTime $toDate): TreatmentRate
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return TreatmentRate
     */
    public function setDescription(?string $description): TreatmentRate
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeekdays(): ?string
    {
        return $this->weekdays;
    }

    /**
     * @param string|null $weekdays
     *
     * @return TreatmentRate
     */
    public function setWeekdays(?string $weekdays): TreatmentRate
    {
        $this->weekdays = $weekdays;

        return $this;
    }

    /**
     * @return string
     */
    public function getWeekend(): ?string
    {
        return $this->weekend;
    }

    /**
     * @param string|null $weekend
     *
     * @return TreatmentRate
     */
    public function setWeekend(?string $weekend): TreatmentRate
    {
        $this->weekend = $weekend;

        return $this;
    }

    /**
     * @return string
     */
    public function getInternalId(): ?string
    {
        return (string) $this->internalId;
    }

    /**
     * @param string|null $internalId
     *
     * @return TreatmentRate
     */
    public function setInternalId(?string $internalId): TreatmentRate
    {
        $this->internalId = $internalId;

        return $this;
    }

    /**
     * @return string
     */
    public function getInternalIdRate(): ?string
    {
        return (string) $this->internalIdRate;
    }

    /**
     * @param string|null $internalIdRate
     *
     * @return TreatmentRate
     */
    public function setInternalIdRate(?string $internalIdRate): TreatmentRate
    {
        $this->internalIdRate = $internalIdRate;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return (float) $this->price;
    }

    /**
     * @param float $price
     *
     * @return TreatmentRate
     */
    public function setPrice(float $price): TreatmentRate
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInstallmentPaymentPlan(): bool
    {
        return $this->installmentPaymentPlan;
    }

    /**
     * @param bool $installmentPaymentPlan
     *
     * @return TreatmentRate
     */
    public function setInstallmentPaymentPlan(bool $installmentPaymentPlan): TreatmentRate
    {
        $this->installmentPaymentPlan = $installmentPaymentPlan;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDiscount(): bool
    {
        return $this->discount;
    }

    /**
     * @param bool $discount
     *
     * @return TreatmentRate
     */
    public function setDiscount(bool $discount): TreatmentRate
    {
        $this->discount = $discount;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return TreatmentRate
     */
    public function setActive(bool $active): TreatmentRate
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Is deleted.
     *
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Set deleted.
     *
     * @param bool $deleted
     *
     * @return TreatmentRate
     */
    public function setDeleted(bool $deleted): TreatmentRate
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get date add
     *
     * @return \DateTime
     */
    public function getDateAdd(): DateTime
    {
        return $this->dateAdd;
    }

    /**
     * Date is stored in UTC timezone
     *
     * @param DateTime $dateAdd
     *
     * @return $this
     */
    public function setDateAdd(DateTime $dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get date upd
     *
     * @return DateTime
     */
    public function getDateUpd(): DateTime
    {
        return $this->dateUpd;
    }

    /**
     * Date is stored in UTC timezone
     *
     * @param DateTime $dateUpd
     *
     * @return $this
     */
    public function setDateUpd(DateTime $dateUpd)
    {
        $this->dateUpd = $dateUpd;

        return $this;
    }

    /**
     * Now we tell doctrine that before we persist or update we call the updatedTimestamps() function.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $this->setDateUpd(new DateTime());

        if ($this->getDateAdd() == null) {
            $this->setDateAdd(new DateTime());
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'id_treatment_rate' => $this->getId(),
            'id_treatment' => $this->getTreatment()->getId(),
            'id_product' => $this->getProductId(),
            'id_product_attribute' => $this->getProductAttributeId(),
            'from_date' => $this->getFromDate(),
            'to_date' => $this->getToDate(),
            'description' => $this->getDescription(),
            'weekdays' => $this->getWeekdays(),
            'weekend' => $this->getWeekend(),
            'internal_id' => $this->getInternalId(),
            'internal_id_rate' => $this->getInternalIdRate(),
            'price' => $this->getPrice(),
            'installment_payment_plan' => $this->isInstallmentPaymentPlan(),
            'discount' => $this->isDiscount(),
            'active' => $this->isActive(),
            'deleted' => $this->isDeleted(),
            'date_add' => $this->dateAdd->format(\DateTime::ATOM),
            'date_upd' => $this->dateUpd->format(\DateTime::ATOM),
        ];
    }
}
