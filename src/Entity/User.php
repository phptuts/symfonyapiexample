<?php

namespace App\Entity;

use App\Model\ViewInterface;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email", errorPath="email")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements ViewInterface
{
    use TimeTrait;

    /**
     *
     * @ORM\Id
     * @ORM\GeneratedValue("AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @Assert\Type(type="bool")
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity=Setting::class, mappedBy="user", orphanRemoval=true, cascade={"persist", "merge", "remove"})
     */
    private $settings;

    public function __construct()
    {
        $this->settings = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function view(): array
    {
        return [
            'email' => $this->getEmail(),
            'name' => $this->getName(),
            'is_active' => $this->getIsActive(),
            'settings' => array_map(function (Setting $setting) {
                return $setting->view();
            }, $this->getSettings()->toArray()),
            'created' => $this->getCreatedAt()->getTimestamp(),
            'updated' => $this->getUpdatedAt()->getTimestamp(),
            'id' => $this->getId()
        ];
    }

    /**
     * @return Collection|Setting[]
     */
    public function getSettings(): Collection
    {
        return $this->settings;
    }

    public function addSetting(Setting $setting): self
    {
        if (!$this->settings->contains($setting)) {
            $this->settings[] = $setting;
            $setting->setUser($this);
        }

        return $this;
    }

    public function removeSetting(Setting $setting): self
    {
        if ($this->settings->removeElement($setting)) {
            // set the owning side to null (unless already changed)
            if ($setting->getUser() === $this) {
                $setting->setUser(null);
            }
        }

        return $this;
    }


}
