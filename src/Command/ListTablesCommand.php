<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;

class ListTablesCommand extends Command
{
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $connectionName = $args->getArgument('connection') ?? 'default';

        $io->out("Listando tablas para la conexión: $connectionName");

        try {
            $connection = ConnectionManager::get($connectionName);
            $schemaCollection = $connection->getSchemaCollection();
            $tables = $schemaCollection->listTables();

            foreach ($tables as $table) {
                $io->out(" - $table");
            }
        } catch (\Exception $e) {
            $io->err("Error: " . $e->getMessage());
        }
    }

    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        return $parser
            ->addArgument('connection', [
                'help' => 'Nombre de la conexión a base de datos (por defecto: default)',
                'required' => false,
            ]);
    }
}

// bin/cake list_tables intranet_azul_qa