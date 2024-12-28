<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Authentication\Authenticator\ResultInterface;
use Authentication\Controller\Component\AuthenticationComponent;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['login', 'add', 'verify2fa']);

        $user = $this->Authentication->getIdentity();
        if ($user && !empty($user->google2fa_secret) && $this->request->getParam('action') !== 'verify2fa') {
            if (!$this->request->getSession()->read('Auth.2fa_verified')) {
                return $this->redirect(['action' => 'verify2fa']);
            }
        }
    }

    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Google2fa'); // Asegúrate de cargar el componente Google2fa
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $user = $this->Authentication->getIdentity();
            if (!empty($user->google2fa_secret)) {
                return $this->redirect(['action' => 'verify2fa']);
            }else{
                return $this->redirect(['action' => 'enable2fa']);
            }
            $target = $this->Authentication->getLoginRedirect() ?? '/';
            return $this->redirect($target);
        }

        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error(__('Invalid username or password'));
        }
    }

    public function logout()
    {
        $result = $this->Authentication->getResult();
        if ($result->isValid()) {
            $this->Authentication->logout();
            //return $this->redirect('/');
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }
    }

    public function enable2fa()
    {
        $identity = $this->Authentication->getIdentity();
        $user = $this->Users->get($identity->getIdentifier()); // Obtener la entidad de usuario

        $secretKey = $this->Google2fa->generateSecretKey();
        $qrCodeUrl = $this->Google2fa->getQRCodeUrl('YourCompany', $user->email, $secretKey);

        // Guarda el secretKey en la base de datos asociado al usuario
        $user->google2fa_secret = $secretKey;
        if ($this->Users->save($user)) {
            $this->Flash->success(__('2FA habilitado correctamente.'));
        } else {
            $this->Flash->error(__('No se pudo habilitar el 2FA. Por favor, inténtelo de nuevo.'));
        }

        $this->set(compact('qrCodeUrl'));
    }

    public function verify2fa()
    {
        if ($this->request->is('post')) {
            $user = $this->Authentication->getIdentity();
            $oneTimePassword = $this->request->getData('otp');
            $secretKey = $user->google2fa_secret;

            if ($this->Google2fa->verifyKey($secretKey, $oneTimePassword)) {
                // 2FA verificado correctamente
                $this->request->getSession()->write('Auth.2fa_verified', true);
                $this->Flash->success('2FA verificado correctamente.');
                $target = $this->Authentication->getLoginRedirect() ?? '/';
                return $this->redirect($target);
            } else {
                // 2FA fallido
                $this->Flash->error('Código 2FA incorrecto.');
            }
        }
        $this->set(compact('user'));
    }
}