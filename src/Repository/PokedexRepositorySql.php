<?php

namespace Bruna\Pokedex\Repository;

use Bruna\Pokedex\Domain\Pokemon;
use Bruna\Pokedex\Domain\TypePokemon;

use PDO;

class PokedexRepositorySql
{
    public function __construct(private PDO $connection)
    {
    }


    public function armazenaPokemonEType($pokemon, $typePok): void
    {
        $query = "INSERT INTO pokemon_type_pokemon (pokemon_id, type_id) VALUES (:name, :type);";

        $statement = $this->connection->prepare($query);
        $statement->execute([
            ':name'=>$pokemon,
            ':type'=>$typePok
        ]);
    }


    public function armazenaPokemon(Pokemon $pokemon): int
    {
        $query = "INSERT INTO pokemon (name) VALUE (:name)";

        $statement = $this->connection->prepare($query);
        $statement->execute([':name'=>$pokemon->getNamePokemon()]);

        $lastId = $this->connection->lastInsertId();

        return $lastId;
    }

    public function armazenaType(TypePokemon $type): int
    {

        $query = "INSERT INTO type_pokemon (name) VALUE (:type);";

        $statement = $this->connection->prepare($query);
        $statement->execute([":type"=>$type->getType()]);
        $lastId = $this->connection->lastInsertId();

        return $lastId;
    }


    public function conferePokemon(int $id): bool
    {
        $query = "SELECT * from pokemon WHERE id=:id";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id);

        $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        return is_array($getList) && count($getList) > 0 && $getList[0] != 0;
    }


    public function conferePokemonType(int $pokemon, int $type): bool
    {
        $query = "SELECT * from pokemon_type_pokemon WHERE pokemon_id=:idPokemon and type_id=:idType;";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':idPokemon', $pokemon);
        $statement->bindParam(':idType', $type);

        $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        return is_array($getList) && count($getList) > 0 && $getList[0] != 0;
    }


    public function pokemonRelacionado(int $id): string
    {
        $query = "select name from type_pokemon as tp 
        JOIN pokemon_type_pokemon as ptp on ptp.type_id=tp.id where ptp.pokemon_id=:id;";

        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        $tipos = array_column($getList, 'name');

        $nomeTipos = implode(', ', $tipos);
        return $nomeTipos;
    }


    public function getNomePokemon(int $id): string
    {   
        $query = "SELECT name FROM pokemon WHERE id=:id;";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':id', $id);
        $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $getList[0]['name'];
    }


    public function listaDePokemon(): array
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

    public function buscaType(string $name): ?int
    {
        $queryTypesId = "SELECT id FROM type_pokemon WHERE name=:name;";
        $statement = $this->connection->prepare($queryTypesId);
        $statement->bindParam(':name', $name);

        $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($getList) && count($getList) > 0 && $getList[0] != 0) {
            return $getList[0]['id'];
        } elseif(is_array($getList) && count($getList) == 0) {
            return null;
        }
    }


    public function buscaPokemon(string $name): ?int
    {
        $query = "SELECT id from pokemon WHERE name=:name;";
        $statement = $this->connection->prepare($query);
        $statement->bindParam(':name', $name);

        $statement->execute();
        $getList = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (is_array($getList) && count($getList) > 0 && $getList[0] != 0) {
            return $getList[0]['id'];
        } else {
            return null;
        }
    }
}