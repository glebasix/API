<?php

namespace App\Entity;

//use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
//use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\BtcCoursesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use MonterHealth\ApiFilterBundle\Annotation\ApiFilter;
use MonterHealth\ApiFilterBundle\Filter\BooleanFilter;
use MonterHealth\ApiFilterBundle\Filter\DateFilter;
use MonterHealth\ApiFilterBundle\Filter\OrderFilter;
use MonterHealth\ApiFilterBundle\Filter\RangeFilter;
use MonterHealth\ApiFilterBundle\Filter\SearchFilter;
use Doctrine\Common\Collections\Collection;
//use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\GeneratedValue;

#[ORM\Entity(repositoryClass: BtcCoursesRepository::class)]
#[ApiResource]
class BtcCourses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $currency = null;

    #[ORM\Column]
    private ?float $course = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dataTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCourse(): ?float
    {
        return $this->course;
    }

    public function setCourse(float $course): self
    {
        $this->course = $course;

        return $this;
    }

    public function getDataTime(): ?\DateTimeInterface
    {
        return $this->dataTime;
    }

    public function setDataTime(\DateTimeInterface $dataTime): self
    {
        $this->dataTime = $dataTime;

        return $this;
    }
}
