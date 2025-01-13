<?php

namespace App\Entity;

use App\Repository\RateServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RateServiceRepository::class)]
class RateService
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $cost = null;

    /**
     * @var Collection<int, ServiceOffer>
     */
    #[ORM\OneToMany(targetEntity: ServiceOffer::class, mappedBy: 'rateService', orphanRemoval: true)]
    private Collection $serviceOffers;

    public function __construct()
    {
        $this->serviceOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCost(): ?string
    {
        return $this->cost;
    }

    public function setCost(string $cost): static
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * @return Collection<int, ServiceOffer>
     */
    public function getServiceOffers(): Collection
    {
        return $this->serviceOffers;
    }

    public function addServiceOffer(ServiceOffer $serviceOffer): static
    {
        if (!$this->serviceOffers->contains($serviceOffer)) {
            $this->serviceOffers->add($serviceOffer);
            $serviceOffer->setRateService($this);
        }

        return $this;
    }

    public function removeServiceOffer(ServiceOffer $serviceOffer): static
    {
        if ($this->serviceOffers->removeElement($serviceOffer)) {
            // set the owning side to null (unless already changed)
            if ($serviceOffer->getRateService() === $this) {
                $serviceOffer->setRateService(null);
            }
        }

        return $this;
    }
}
