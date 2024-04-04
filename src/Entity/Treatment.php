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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Flavioski\Module\SalusPerAquam\Repository\TreatmentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Treatment
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id_treatment", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=128, nullable=false)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Flavioski\Module\SalusPerAquam\Entity\TreatmentLang", cascade={"persist", "remove"}, mappedBy="treatment")
     */
    private $treatmentLangs;

    /**
     * @ORM\OneToMany(targetEntity="Flavioski\Module\SalusPerAquam\Entity\TreatmentRate", cascade={"persist", "remove"}, mappedBy="treatment")
     */
    private $treatmentRates;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=128, nullable=false)
     */
    private $code;

    /**
     * @var bool
     *
     * @ORM\Column(name="bookable", type="boolean")
     */
    private $bookable;

    /**
     * @var bool
     *
     * @ORM\Column(name="single_sale", type="boolean")
     */
    private $singleSale;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer", options={"unsigned"=true}, nullable=true)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="package_type", type="integer", options={"unsigned"=true}, nullable=true)
     */
    private $packageType;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2, nullable=false, options={"default":"0.00"})
     */
    private $price;

    /**
     * @var bool
     *
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
        $this->setBookable(false);
        $this->setSingleSale(true);
        $this->setDateAdd(new \DateTime());
        $this->setDateUpd(new \DateTime());
        $this->setActive(true);
        $this->setDeleted(false);
        $this->treatmentLangs = new ArrayCollection();
        $this->treatmentRates = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection
     */
    public function getTreatmentLangs()
    {
        return $this->treatmentLangs;
    }

    /**
     * @return ArrayCollection
     */
    public function getTreatmentRates()
    {
        return $this->treatmentRates;
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
     * @return Treatment
     */
    public function setProductId(int $productId): Treatment
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
     * @return Treatment
     */
    public function setProductAttributeId(?int $productAttributeId): self
    {
        $this->productAttributeId = $productAttributeId;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Treatment
     */
    public function setName(string $name): Treatment
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Treatment
     */
    public function setCode(string $code): Treatment
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Is bookable.
     *
     * @return bool
     */
    public function isBookable(): bool
    {
        return $this->bookable;
    }

    /**
     * Set bookale.
     *
     * @param bool $bookable
     *
     * @return Treatment
     */
    public function setBookable(bool $bookable): Treatment
    {
        $this->bookable = $bookable;

        return $this;
    }

    /**
     * Is single sale.
     *
     * @return bool
     */
    public function isSingleSale(): bool
    {
        return $this->singleSale;
    }

    /**
     * Set single sale.
     *
     * @param bool $singleSale
     *
     * @return Treatment
     */
    public function setSingleSale(bool $singleSale): Treatment
    {
        $this->singleSale = $singleSale;

        return $this;
    }

    /**
     * @return int
     */
    public function getType(): ?int
    {
        return (int) $this->type;
    }

    /**
     * @param int|null $type
     *
     * @return Treatment
     */
    public function setType(?int $type): Treatment
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getPackageType(): ?int
    {
        return (int) $this->packageType;
    }

    /**
     * @param int|null $packageType
     *
     * @return Treatment
     */
    public function setPackageType(?int $packageType): Treatment
    {
        $this->packageType = $packageType;

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
     * @return Treatment
     */
    public function setPrice(float $price): Treatment
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Set active.
     *
     * @param bool $active
     *
     * @return Treatment
     */
    public function setActive(bool $active): Treatment
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
     * @return Treatment
     */
    public function setDeleted(bool $deleted): Treatment
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @param int $langId
     *
     * @return TreatmentLang|null
     */
    public function getTreatmentLangByLangId(int $langId)
    {
        foreach ($this->treatmentLangs as $treatmentLang) {
            if ($langId === $treatmentLang->getLang()->getId()) {
                return $treatmentLang;
            }
        }

        return null;
    }

    /**
     * @param TreatmentLang $treatmentLang
     *
     * @return $this
     */
    public function addTreatmentLang(TreatmentLang $treatmentLang)
    {
        $treatmentLang->setTreatment($this);
        $this->treatmentLangs->add($treatmentLang);

        return $this;
    }

    /**
     * @param int $rateId
     *
     * @return TreatmentRate|null
     */
    public function getTreatmentRateByRateId(int $rateId)
    {
        foreach ($this->treatmentRates as $treatmentRate) {
            if ($rateId === $treatmentRate->getId()) {
                return $treatmentRate;
            }
        }

        return null;
    }

    /**
     * @param TreatmentRate $treatmentRate
     *
     * @return $this
     */
    public function addTreatmentRate(TreatmentRate $treatmentRate)
    {
        $treatmentRate->setTreatment($this);
        $this->treatmentRates->add($treatmentRate);

        return $this;
    }

    /**
     * @return string
     */
    public function getTreatmentContent()
    {
        if ($this->treatmentLangs->count() <= 0) {
            return '';
        }

        $treatmentLang = $this->treatmentLangs->first();

        return $treatmentLang->getContent();
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
            'id_treatment' => $this->getId(),
            'id_product' => $this->getProductId(),
            'id_product_attribute' => $this->getProductAttributeId(),
            'name' => $this->getName(),
            'code' => $this->getCode(),
            'treatment_rates' => $this->getTreatmentRates()->toArray(),
            'bookable' => $this->isBookable(),
            'single_sale' => $this->isSingleSale(),
            'type' => $this->getType(),
            'package_type' => $this->getPackageType(),
            'price' => $this->getPrice(),
            'active' => $this->isActive(),
            'deleted' => $this->isDeleted(),
            'date_add' => $this->dateAdd->format(\DateTime::ATOM),
            'date_upd' => $this->dateUpd->format(\DateTime::ATOM),
        ];
    }
}
