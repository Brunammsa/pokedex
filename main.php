<?php

require_once "vendor/autoload.php";

use Bruna\Pokedex\Persistence\ConnectionCreator;
use Bruna\Pokedex\Repository\PokedexRepositorySql;
use Bruna\Pokedex\Domain\Pokemon;
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

    $listaDePokemons = $repository->listaDePokemon();

    foreach ($listaDePokemons as $pokemon) {
        echo $pokemon . PHP_EOL;
    }
}


function mostrarPokemon(): void
{
    $connectionPdo = ConnectionCreator::createConnection();
    $repository = new PokedexRepositorySql($connectionPdo);

    $inputzVei = new InputNumber("Qual o ID do nome do pokemon? ");
    $pokemonId = $inputzVei->ask();

    try {
        $pokemonRelacionado = $repository->pokemonRelacionado(intval($pokemonId));

        if (is_null($pokemonRelacionado)) {
            throw new InvalidArgumentException("Não existe Pokemon com este ID" . PHP_EOL);
        } 

        $nomePokemon = $repository->getNomePokemon(intval($pokemonId));

        echo "$nomePokemon possui os tipos: $pokemonRelacionado" . PHP_EOL;


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
                $repository->armazenaPokemonEType($namePokemonWId, $typePokemonWId);
                echo "o Pokemon $namePokemon com o tipo $typePokemon foi inserido com sucesso" . PHP_EOL;
            } else {
                echo "O pokemon $namePokemon já está relacionado ao tipo $typePokemon" . PHP_EOL;

            }

            $validation = readline("Se deseja por mais um type no pokemon $namePokemon, aperte ENTER ou digite SAIR para finalizar. ");
            if ($validation == 'sair') {
                return;
            }
        } catch (\Throwable) {
            echo "Algo deu errado, tente novamente" . PHP_EOL;
            return;
        }
    }
}


main();