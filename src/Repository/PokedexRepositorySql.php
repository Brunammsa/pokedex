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
        $query = "SELECT pokemon.name, type_pokemon.name FROM pokemon 
            INNER JOIN pokemon_type_pokemon ON pokemon.id = pokemon_type_pokemon.pokemon_id
            INNER JOIN type_pokemon ON type_pokemon.id = pokemon_type_pokemon.type_id;";
            
        $statement = $this->connection->prepare($query);
        $lista = $statement->fetchAll();

        $listaDePokemons = [];

        foreach ($lista as $pokemon) {
            $listaDePokemons[] = $pokemon;
        }

        return $listaDePokemons;
    }

    public function buscaPorTypeId(int $id): ?array
    {
        $queryTypesId =  "SELECT type_id FROM pokemon_type_pokemon where pokemon_id=:id;";

        $statement = $this->connection->prepare($queryTypesId);
        $statement->bindParam(':id', $id);

        $sucess = $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        $typeList = [];

        foreach ($getList as $type) {
            $typeList[] = $type["type_id"];
        }
        //ta pegando o os id, agora preciso fazer um for p por no prox query


        $queryType = "SELECT name FROM type_pokemon where id=3;";


        if (is_array($getList)) {
            $type = $getList[0]['name'];
        } else {
            $type = null;
        }

        return $type;
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