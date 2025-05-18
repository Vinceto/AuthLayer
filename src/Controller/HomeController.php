<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Home Controller
 *
 */
class HomeController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('DynamicConnectionManager');
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $databases = [];
        $tables = [];

        $connection = $this->DynamicConnectionManager->getSafeConnection('intranet_azul_qa', 'IDIEM_KERNEL');

        if ($connection !== null) {
            $databases = $this->DynamicConnectionManager->listDatabasesFromConnection($connection);
            $tables = $this->DynamicConnectionManager->listTablesFromConnection($connection);
        } else {
            $this->Flash->error('La base de datos "IDIEM_KERNEL" no está disponible. Asegúrate de tener activa la VPN.');
        }

        $this->set(compact('databases', 'tables'));
    }

}
