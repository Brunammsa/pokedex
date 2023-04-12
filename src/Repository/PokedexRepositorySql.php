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

    public function armazenaPokemon(Pokemon $pokemon): void
    {
        $query = "INSERT INTO pokemon (name) VALUE (:name)";

        $statement = $this->connection->prepare($query);
        $sucess = $statement->execute([':name'=>$pokemon->getNamePokemon()]);
    }

    public function armazenaType(TypePokemon $type): void
    {
        $query = "INSERT INTO type_pokemon (name_type) VALUE (:type);";

        $statement = $this->connection->prepare($query);
        $sucess = $statement->execute([":type"=>$type->getType()]);
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

    public function buscaPorTypeId(int $id): ?array
    {
        $queryTypesId = "SELECT type_id FROM pokemon_type_pokemon where pokemon_id=:id;";
        $statement = $this->connection->prepare($queryTypesId);
        $statement->bindParam(':id', $id);
        $statement->execute();
        
        $typeIdsList = [];
        
        while ($type = $statement->fetch(PDO::FETCH_ASSOC)) {
            $idType = $type["type_id"];

            $queryDoType = "SELECT name FROM type_pokemon where id=:idType;";
            $statement2 = $this->connection->prepare($queryDoType);
            $statement2->bindParam(':idType', $idType);
            $statement2->execute();

            $result = $statement2->fetch(PDO::FETCH_ASSOC);
            $typeName = $result['name'];
            $typeIdsList[] = $typeName;
        }
        return $typeIdsList;
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

        if (is_array($getList)) {
            $pokemon = $getList[0]['name'];
        } else {
            $pokemon = null;
        }

        return $pokemon;
    }
}