<?php

namespace Bruna\Pokedex\Repository;

use Bruna\Pokedex\Domain\Pokemon;
use Bruna\Pokedex\Domain\Species;
use Bruna\Pokedex\Domain\TypePokemon;

use PDO;

class PokedexRepository
{
    public function __construct(private PDO $connection)
    {
    }

    public function armazenaPokemon(Pokemon $pokemon): void
    {
        $query = "INSERT INTO pokemon (name_pokemon, type_id, species_id, gender) VALUES (
        :name,
        :type,
        :species,
        :gender    
        );";

        $statement = $this->connection->prepare($query);
        $sucecc = $statement->execute([
            ":name"=>$pokemon->getNamePokemon(),
            ":type"=>$pokemon->getType(),
            ":species"=>$pokemon->getSpecies(),
            ":gender"=>$pokemon->getGender()
        ]);
    }

    public function armazenaType(TypePokemon $type): void
    {
        $query = "INSERT INTO type_pokemon (name_type) VALUE (:type);";

        $statement = $this->connection->prepare($query);
        $sucecc = $statement->execute([":type"=>$type->getType()]);
    }

    public function armazenaSpecie(Species $specie): void
    {
        $query = "INSERT INTO species (name) VALUE (:specie);";

        $statement = $this->connection->prepare($query);
        $sucecc = $statement->execute([":specie"=>$specie->getSpecie]);
    }

    public function listaDePokemonESeusTipos(): array
    {
        $query = "SELECT p.name_pokemon, t.name_type FROM pokemon AS p JOIN type_pokemon AS t ON t.id=p.type_id;";

        $statement = $this->connection->prepare($query);
        $lista = $statement->fetchAll();

        $listaDePokemons = [];

        foreach ($lista as $pokemon) {
            $listaDePokemons[] = $pokemon;
        }

        return $listaDePokemons;
    }

    public function buscaPorTypeId($type): ?int
    {
        $query = "SELECT id FROM type_pokemon WHERE name_type=:type;";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(':type', $type);

        $sucecc = $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($getList)) {
            $idType = $getList['id'];
        } else {
            $idType = null;
        }

        return $idType;
    }

    public function buscaPorSpecieId($specie): ?int
    {
        $query = "SELECT id FROM species WHERE name=:specie;";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(':specie', $specie);

        $sucecc = $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($getList)) {
            $idSpecie = $getList['id'];
        } else {
            $idSpecie = null;
        }

        return $idSpecie;
    }
}