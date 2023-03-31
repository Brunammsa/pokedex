<?php

namespace Bruna\Pokedex\Domain;

class Species
{
    public function __construct(public string $name)
    {

    }

    public function getSpecie(): string
    {
        return $this->name;
    }
}