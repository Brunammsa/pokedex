<?php

require_once "vendor/autoload.php";

use Bruna\Pokedex\Persistence\ConnectionCreator;
use Bruna\Pokedex\Repository\PokedexRepositorySql;
use Bruna\Pokedex\Domain\Pokemon;
use Bruna\Pokedex\Domain\Species;
use Bruna\Pokedex\Domain\TypePokemon;
use Brunammsa\Inputzvei\InputNumber;
use Brunammsa\Inputzvei\InputText;


function main(): void
{
    echo "Olá mestre Pokemon!\n" . PHP_EOL;
    menu();
}

function menu(): void
{
    $options = null;

    while ($options == null || !$options !== 0) {
        echo "Digite:\n1 para listar pokemons\n2 para mostrar pokemon\n3 para adicionar pokemon\n0 para finalizar\n";
        $options = readline();

        if ($options == 1) {
            listarPokemons();
        } elseif ($options == 2) {
            mostrarPokemon();
        } elseif ($options == 3) {
            adicionarPokemon();
        } elseif ($options == 0) {
            exit();
        } else {
            echo "Opção inválida" . PHP_EOL;
        }
    }
}


function listarPokemons(): void
{
    $connectionPdo = ConnectionCreator::createConnection();
    $repository = new PokedexRepositorySql($connectionPdo);

    $listaDePokemons = $repository->listaDePokemonESeusTipos();

    foreach ($listaDePokemons as $pokemon) {
        echo $pokemon . PHP_EOL;
    }
}


function mostrarPokemon(): void
{
    $connectionPdo = ConnectionCreator::createConnection();
    $repository = new PokedexRepositorySql($connectionPdo);

    $inputzVei = new InputNumber("Qual o ID do pokemon? ");
    $pokemonId = $inputzVei->ask();

    try {
        if (!($pokemon = $repository->buscaPokemon($pokemonId)) || is_null($pokemon = $repository->buscaPokemon($pokemonId))) {
            throw new InvalidArgumentException("Não existe Pokemon com este ID" . PHP_EOL);  
        }
        if (!($type = $repository->buscaPorTypeId($pokemonId)) || is_null($type = $repository->buscaPorTypeId($pokemonId))) {
            throw new InvalidArgumentException("O pokemon $pokemon existe, mas ainda não tem tipo relacionado" . PHP_EOL);
        }

        if (count($type) == 2) {
            echo "Pokemon: $pokemon - Tipo: $type[0] / $type[1]" . PHP_EOL;
        } elseif (count($type) == 1) {
            echo "Pokemon: $pokemon - Tipo: $type[0]" . PHP_EOL;
        }
    } catch (InvalidArgumentException $exception) {
        echo $exception->getMessage();
    }
}

function adicionarPokemon(): void
{
    $connectionPdo = ConnectionCreator::createConnection();
    $repository = new PokedexRepositorySql($connectionPdo);

    $inputzVei = new InputText("Qual o nome do pokemon? ");
    $namePokemon = $inputzVei->ask();

    $validation = null;

    while ($validation == null || !$validation == 'sair') {
        $inputzVei = new InputText("Qual o type do pokemon? ");
        $typePokemon = $inputzVei->ask();

        try {
            $namePokemonWId = $repository->buscaPokemon($namePokemon);
            if (is_null($namePokemonWId)) {
                $name = new Pokemon($namePokemon);
                $namePokemonWId = $repository->armazenaPokemon($name);
            }

            $typePokemonWId = $repository->buscaType($typePokemon);
            if (is_null($typePokemonWId)) {
                $type = new TypePokemon($typePokemon);
                $typePokemonWId = $repository->armazenaType($type);
            }

            $confere = $repository->conferePokemonType($namePokemonWId, $typePokemonWId);

            if (!$confere) {
                $armazenaRelacionado = $repository->armazenaPokemonEType($namePokemonWId, $typePokemonWId);
                echo "o Pokemon $namePokemon com o tipo $typePokemonfoi inserido com sucesso" . PHP_EOL;
            } else {
                echo "O pokemon $namePokemon já está relacionado ao tipo $typePokemon" . PHP_EOL;

            }

            $validation = readline("Se deseja por mais um type no pokemon $namePokemon, aperte ENTER ou digite SAIR para finalizar. ");
            if ($validation == 'sair') {
                return;
            }
        } catch (\Throwable $th) {
            echo "Algo deu errado, tente novamente" . PHP_EOL;
            return;
        }
    }
}


main();