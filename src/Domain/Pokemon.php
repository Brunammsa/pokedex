<?php

namespace Bruna\Pokedex\Domain;

class Pokemon
{
    public function __construct(public string $namePokemon)
    {
    }

    public function getNamePokemon(): string
    {
        return $this->namePokemon;
    }
}