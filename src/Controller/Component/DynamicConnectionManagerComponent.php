<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\Database\Connection;
use Cake\Database\Exception\MissingConnectionException;
use PDOException;

class DynamicConnectionManagerComponent extends Component
{
    /**
     * Obtiene o crea una conexión dinámica basada en un datasource base y una base de datos destino.
     *
     * @param string $baseDatasource El nombre del datasource base (ej: 'default', 'intranet_azul_qa', etc).
     * @param string $databaseName El nombre de la base de datos a la que se conectará.
     * @return \Cake\Database\Connection
     */
    public function getConnection(string $baseDatasource, string $databaseName): Connection
    {
        $config = ConnectionManager::getConfig($baseDatasource);
        $dynamicName = $baseDatasource . '_' . $databaseName;

        if (!ConnectionManager::getConfig($dynamicName)) {
            $config['database'] = $databaseName;
            ConnectionManager::setConfig($dynamicName, $config);
        }

        return ConnectionManager::get($dynamicName);
    }

    /**
     * Intenta obtener una conexión válida. Si no es posible, retorna null.
     *
     * @param string $baseDatasource
     * @param string $databaseName
     * @return \Cake\Database\Connection|null
     */
    public function getSafeConnection(string $baseDatasource, string $databaseName): ?Connection
    {
        try {
            $connection = $this->getConnection($baseDatasource, $databaseName);
            $connection->execute('SELECT 1');
            return $connection;
        } catch (MissingConnectionException | PDOException $e) {
            $this->log("Error de conexión a la base de datos '{$databaseName}': " . $e->getMessage(), 'error');
            return null;
        } catch (\Exception $e) {
            $this->log("Error inesperado al intentar conectar con '{$databaseName}': " . $e->getMessage(), 'error');
            return null;
        }
    }

    /**
     * Lista todas las bases de datos existentes en una conexión dada.
     *
     * @param \Cake\Database\Connection $connection
     * @return array
     */
    public function listDatabasesFromConnection(Connection $connection): array
    {
        $driver = $connection->getDriver();
        $sql = '';

        // Solo implementado para MySQL/MariaDB
        if ($driver instanceof \Cake\Database\Driver\Mysql) {
            $sql = 'SHOW DATABASES';
        } else {
            throw new \RuntimeException('Este método solo está implementado para MySQL/MariaDB.');
        }

        $statement = $connection->execute($sql);
        $databases = [];
        foreach ($statement->fetchAll('assoc') as $row) {
            $databases[] = array_values($row)[0];
        }
        return $databases;
    }

    /**
     * Lista las tablas existentes en una conexión dada.
     *
     * @param \Cake\Database\Connection $connection
     * @return array
     */
    public function listTablesFromConnection(Connection $connection): array
    {
        return $connection->getSchemaCollection()->listTables();
    }
}