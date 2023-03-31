<?php

namespace Bruna\Pokedex\Domain;

class Pokemon
{
    public function __construct(
        public string $namePokemon,
        public int $typeId,
        public int $speciesId,
        public string $gender
    )
    {

    }

    public function getNamePokemon(): string
    {
        return $this->namePokemon;
    }

    public function getType(): int
    {
        return $this->typeId;
    }

    public function getSpecies(): int
    {
        return $this->speciesId;
    }

    public function getGender(): string
    {
        return $this->gender;
    }
}