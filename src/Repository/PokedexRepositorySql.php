<?php

namespace Bruna\Pokedex\Repository;

use Bruna\Pokedex\Domain\Pokemon;
use Bruna\Pokedex\Domain\Species;
use Bruna\Pokedex\Domain\TypePokemon;

use PDO;

class PokedexRepositorySql
{
    public function __construct(private PDO $connection)
    {
    }


    public function armazenaPokemonEType(Pokemon $pokemon, TypePokemon $typePok): void
    {
        $query = "INSERT INTO pokemon_type_pokemon (pokemon_id, type_id) VALUES (:name, :type);";

        $statement = $this->connection->prepare($query);
        $sucess = $statement->execute([
            ':name'=>$pokemon->getNamePokemon(),
            ':type'=>$typePok->getType()
        ]);
    }


    public function armazenaPokemon(Pokemon $pokemon): int
    {
        $query = "INSERT INTO pokemon (name) VALUE (:name)";

        $statement = $this->connection->prepare($query);
        $sucess = $statement->execute([':name'=>$pokemon->getNamePokemon()]);

        $lastId = $this->connection->lastInsertId();

        return $lastId;
    }

    public function armazenaType(TypePokemon $type): int
    {
        $query = "INSERT INTO type_pokemon (name_type) VALUE (:type);";

        $statement = $this->connection->prepare($query);
        $sucess = $statement->execute([":type"=>$type->getType()]);

        $lastId = $this->connection->lastInsertId();

        return $lastId;
    }

    public function armazenaSpecie(Species $specie): void
    {
        $query = "INSERT INTO species (name) VALUE (:specie);";

        $statement = $this->connection->prepare($query);
        $sucess = $statement->execute([":specie"=>$specie->getSpecie()]);
    }

    public function listaDePokemonESeusTipos(): array
    {
        $query = "SELECT * from pokemon;";
            
        $statement = $this->connection->query($query);
        $lista = $statement->fetchAll();

        $listaDePokemons = [];

        foreach ($lista as $pokemon) {
            $listaDePokemons[] = "ID: " . $pokemon[0] . " - Pokemon: " . $pokemon[1];
        }
        return $listaDePokemons;
    }

    public function buscaPorTypeId(string $name): ?int
    {
        $queryTypesId = "SELECT id FROM pokemon where name=:name;";
        $statement = $this->connection->prepare($queryTypesId);
        $statement->bindParam(':name', $name);

        $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($getList)) {
            $idType = $getList[0]['id'];
        } else {
            $idType = null;
        }

    }

    public function buscaPorSpecieId(int $specie): ?int
    {
        $query = "SELECT id FROM species WHERE name=:specie;";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(':specie', $specie);

        $sucess = $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($getList)) {
            $idSpecie = $getList[0]['id'];
        } else {
            $idSpecie = null;
        }

        return $idSpecie;
    }

    public function buscaPokemon(int $id): ?string
    {
        $query = "SELECT name from pokemon WHERE id=:id;";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id);

        $sucess = $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($getList) && count($getList) > 0 && $getList[0] != 0) {
            $pokemon = $getList[0]['name'];
        } else {
            $pokemon = null;
        }

        return $pokemon;
    }
}