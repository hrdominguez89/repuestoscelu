<?php

namespace App\Entity;

use App\Repository\CountriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountriesRepository::class)]
class Countries
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 100)]
    private $name;

    #[ORM\Column(type: "string", length: 3, nullable: true)]
    private $iso3;

    #[ORM\Column(type: "string", length: 3, nullable: true)]
    private $numeric_code;

    #[ORM\Column(type: "string", length: 2, nullable: true)]
    private $iso2;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $phonecode;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $capital;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $currency;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $currency_name;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $currency_symbol;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $tld;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $native;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $region;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $subregion;

    #[ORM\Column(type: "text", nullable: true)]
    private $timezones;

    #[ORM\Column(type: "text", nullable: true)]
    private $translations;

    #[ORM\Column(type: "decimal", precision: 10, scale: 8, nullable: true)]
    private $latitude;

    #[ORM\Column(type: "decimal", precision: 11, scale: 8, nullable: true)]
    private $longitude;

    #[ORM\Column(type: "string", length: 191, nullable: true)]
    private $emoji;

    #[ORM\Column(type: "string", length: 191, nullable: true)]
    private $emojiU;

    #[ORM\Column(type: "datetime", nullable: false)]
    private $created_at;

    #[ORM\Column(type: "datetime", nullable: true)]
    private $updated_at;

    #[ORM\Column(type: "smallint", nullable: true)]
    private $flag;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $wikiDataId;

    #[ORM\OneToMany(targetEntity: States::class, mappedBy: "country")]
    private $states;

    #[ORM\OneToMany(targetEntity: Cities::class, mappedBy: "country")]
    private $cities;

    #[ORM\ManyToOne(targetEntity: RegionType::class, inversedBy: "countries")]
    private $region_type;

    #[ORM\ManyToOne(targetEntity: SubregionType::class, inversedBy: "countries")]
    private $subregion_type;

    #[ORM\Column(type: "boolean", nullable: true)]
    private $visible;

    public function __construct()
    {
        $this->states = new ArrayCollection();
        $this->cities = new ArrayCollection();
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIso3(): ?string
    {
        return $this->iso3;
    }

    public function setIso3(?string $iso3): self
    {
        $this->iso3 = $iso3;

        return $this;
    }

    public function getNumericCode(): ?string
    {
        return $this->numeric_code;
    }

    public function setNumericCode(?string $numeric_code): self
    {
        $this->numeric_code = $numeric_code;

        return $this;
    }

    public function getIso2(): ?string
    {
        return $this->iso2;
    }

    public function setIso2(?string $iso2): self
    {
        $this->iso2 = $iso2;

        return $this;
    }

    public function getPhonecode(): ?string
    {
        return $this->phonecode;
    }

    public function setPhonecode(?string $phonecode): self
    {
        $this->phonecode = $phonecode;

        return $this;
    }

    public function getCapital(): ?string
    {
        return $this->capital;
    }

    public function setCapital(?string $capital): self
    {
        $this->capital = $capital;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCurrencyName(): ?string
    {
        return $this->currency_name;
    }

    public function setCurrencyName(?string $currency_name): self
    {
        $this->currency_name = $currency_name;

        return $this;
    }

    public function getCurrencySymbol(): ?string
    {
        return $this->currency_symbol;
    }

    public function setCurrencySymbol(?string $currency_symbol): self
    {
        $this->currency_symbol = $currency_symbol;

        return $this;
    }

    public function getTld(): ?string
    {
        return $this->tld;
    }

    public function setTld(?string $tld): self
    {
        $this->tld = $tld;

        return $this;
    }

    public function getNative(): ?string
    {
        return $this->native;
    }

    public function setNative(?string $native): self
    {
        $this->native = $native;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getSubregion(): ?string
    {
        return $this->subregion;
    }

    public function setSubregion(?string $subregion): self
    {
        $this->subregion = $subregion;

        return $this;
    }

    public function getTimezones(): ?string
    {
        return $this->timezones;
    }

    public function setTimezones(?string $timezones): self
    {
        $this->timezones = $timezones;

        return $this;
    }

    public function getTranslations(): ?string
    {
        return $this->translations;
    }

    public function setTranslations(?string $translations): self
    {
        $this->translations = $translations;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getEmoji(): ?string
    {
        return $this->emoji;
    }

    public function setEmoji(?string $emoji): self
    {
        $this->emoji = $emoji;

        return $this;
    }

    public function getEmojiU(): ?string
    {
        return $this->emojiU;
    }

    public function setEmojiU(?string $emojiU): self
    {
        $this->emojiU = $emojiU;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getFlag(): ?int
    {
        return $this->flag;
    }

    public function setFlag(?int $flag): self
    {
        $this->flag = $flag;

        return $this;
    }

    public function getWikiDataId(): ?string
    {
        return $this->wikiDataId;
    }

    public function setWikiDataId(?string $wikiDataId): self
    {
        $this->wikiDataId = $wikiDataId;

        return $this;
    }

    public function getStates(): Collection
    {
        return $this->states;
    }

    public function addState(States $state): self
    {
        if (!$this->states->contains($state)) {
            $this->states[] = $state;
            $state->setCountry($this);
        }

        return $this;
    }

    public function removeState(States $state): self
    {
        if ($this->states->removeElement($state)) {
            // set the owning side to null (unless already changed)
            if ($state->getCountry() === $this) {
                $state->setCountry(null);
            }
        }

        return $this;
    }

    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(Cities $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities[] = $city;
            $city->setCountry($this);
        }

        return $this;
    }

    public function removeCity(Cities $city): self
    {
        if ($this->cities->removeElement($city)) {
            // set the owning side to null (unless already changed)
            if ($city->getCountry() === $this) {
                $city->setCountry(null);
            }
        }

        return $this;
    }

    public function getRegionType(): ?RegionType
    {
        return $this->region_type;
    }

    public function setRegionType(?RegionType $region_type): self
    {
        $this->region_type = $region_type;

        return $this;
    }

    public function getSubregionType(): ?SubregionType
    {
        return $this->subregion_type;
    }

    public function setSubregionType(?SubregionType $subregion_type): self
    {
        $this->subregion_type = $subregion_type;

        return $this;
    }

    public function getVisible(): ?bool
    {
        return $this->visible;
    }

    public function setVisible(?bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }
}
