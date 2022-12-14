<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\Column(type: 'string')]
    private string $name = '';

    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'user')]
    private $images;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: LibraryImage::class)]
    private Collection $libraryImages;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->libraryImages = new ArrayCollection();
    }
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, LibraryImage>
     */
    public function getLibraryImages(): Collection
    {
        return $this->libraryImages;
    }

    public function addLibraryImage(LibraryImage $libraryImage): self
    {
        if (!$this->libraryImages->contains($libraryImage)) {
            $this->libraryImages->add($libraryImage);
            $libraryImage->setUser($this);
        }

        return $this;
    }

    public function removeLibraryImage(LibraryImage $libraryImage): self
    {
        if ($this->libraryImages->removeElement($libraryImage)) {
            // set the owning side to null (unless already changed)
            if ($libraryImage->getUser() === $this) {
                $libraryImage->setUser(null);
            }
        }

        return $this;
    }
}
