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
        //$connection = $this->DynamicConnectionManager->getConnection('default', 'otra_base_de_datos');
        
        $connection = $this->DynamicConnectionManager->getConnection('intranet_azul_qa', 'IDIEM_KERNEL');
        $databases = $this->DynamicConnectionManager->listDatabasesFromConnection($connection);
        $tables = $this->DynamicConnectionManager->listTablesFromConnection($connection);
        $this->set(compact('tables', 'databases'));
    }

}
