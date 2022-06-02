<?php

namespace App\Entity;

use App\Entity\Category;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecipeRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
// #[ORM\HasLifecycleCallbacks]

class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le champs nom est requis.')]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    // #[Assert\NotBlank(message: 'Le champs image est requis.')]
    private $image;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Le champs ingrédients est requis.')]
    private $ingredient;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Le champs préparation est requis.')]
    private $preparation;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le champs type est requis.')]
    private $type;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le champs difficulté est requis.')]
    private $difficulty;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le champs duré est requis.')]
    private $duration;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $portion;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $vegetarian;

    #[ORM\Column(type: 'text', nullable: true)]
    private $presentation;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getIngredient(): ?string
    {
        return $this->ingredient;
    }

    public function setIngredient(string $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getPreparation(): ?string
    {
        return $this->preparation;
    }

    public function setPreparation(string $preparation): self
    {
        $this->preparation = $preparation;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDifficulty(): ?string
    {
        return $this->difficulty;
    }

    public function setDifficulty(string $difficulty): self
    {
        $this->difficulty = $difficulty;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPortion(): ?string
    {
        return $this->portion;
    }

    public function setPortion(?string $portion): self
    {
        $this->portion = $portion;

        return $this;
    }

    public function getVegetarian(): ?bool
    {
        return $this->vegetarian;
    }

    public function setVegetarian(?bool $vegetarian): self
    {
        $this->vegetarian = $vegetarian;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }
}
