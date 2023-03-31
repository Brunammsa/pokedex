<?php

namespace Bruna\Pokedex\Domain;

class TypePokemon
{
    public function __construct(public string $nameType)
    {

    }

    public function getType(): string
    {
        return $this->nameType;
    }
}