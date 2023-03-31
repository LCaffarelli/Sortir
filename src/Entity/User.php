<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['pseudo'], message: 'There is already an account with this pseudo')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message:"Le pseudo ne peut pas être nul")]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $pseudo = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;
    #[Assert\NotBlank(message: "Le nom ne peut pas être nul")]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Assert\NotBlank(message: "Le prenom ne peut pas être nul")]
    #[ORM\Column(length: 255)]
    private ?string $prenom = null;
    #[Assert\NotBlank(message: "Le numéro de téléphone ne peut pas être nul")]
    #[Assert\Length(min:10, minMessage: "numéro incorrect" )]
    #[ORM\Column(length: 10)]
    private ?string $telephone = null;

    #[Assert\Email(message: "Email non valide")]
    #[ORM\Column(length: 255,unique: true)]
    private ?string $email = null;

    #[Assert\Regex(pattern:'/^.+\.(jpg|jpeg|png|gif|bmp)$/', message:"Le fichier saisi n'est pas une image")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Site $site = null;

    #[ORM\ManyToMany(targetEntity: Sortie::class, mappedBy: 'users')]
    private Collection $sorties;

    #[ORM\OneToMany(mappedBy: 'organisateur', targetEntity: Sortie::class)]
    private Collection $mesEvenements;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
        $this->mesEvenements = new ArrayCollection();
    }

    #[ORM\Column]
    private ?bool $actif = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

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

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
    public function getSite(): ?Site
    {
        return $this->site;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties->add($sorty);
            $sorty->addParticipant($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            $sorty->removeParticipant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getMesEvenements(): Collection
    {
        return $this->mesEvenements;
    }

    public function addMesEvenement(Sortie $mesEvenement): self
    {
        if (!$this->mesEvenements->contains($mesEvenement)) {
            $this->mesEvenements->add($mesEvenement);
            $mesEvenement->setOrganisateur($this);
        }

        return $this;
    }

    public function removeMesEvenement(Sortie $mesEvenement): self
    {
        if ($this->mesEvenements->removeElement($mesEvenement)) {
            // set the owning side to null (unless already changed)
            if ($mesEvenement->getOrganisateur() === $this) {
                $mesEvenement->setOrganisateur(null);
            }
        }

        return $this;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }
    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }
}
