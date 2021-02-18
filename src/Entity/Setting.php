<?php

namespace App\Entity;

use App\Model\ViewInterface;
use App\Repository\SettingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SettingRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Setting implements ViewInterface
{

    use TimeTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"post"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Groups({"post"})
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="settings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;


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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }


    public function view(): array
    {
        return [
            'created' => $this->getCreatedAt()->getTimestamp(),
            'updated' => $this->getUpdatedAt()->getTimestamp(),
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'id' => $this->getId()
        ];
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }


}
