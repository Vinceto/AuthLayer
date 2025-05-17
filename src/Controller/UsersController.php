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
        $currentAction = $this->request->getParam('action');

        $excludedActions = ['login', 'logout', 'verify2fa'];

        if (
            $user &&
            !empty($user->google2fa_secret) &&
            !in_array($currentAction, $excludedActions)
        ) {
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
        $user = $this->Users->get($identity->getIdentifier());

        // Si no tiene aún clave secreta, la generamos y guardamos
        if (empty($user->google2fa_secret)) {
            $secretKey = $this->Google2fa->generateSecretKey();
            $user->google2fa_secret = $secretKey;
            $this->Users->save($user);
        } else {
            $secretKey = $user->google2fa_secret;
        }

        $qrImage = $this->Google2fa->getQRCodeImage('AuthLayerApp', $user->email, $secretKey);

        // Si recibimos POST, es para verificar el código OTP ingresado por el usuario
        if ($this->request->is('post')) {
            $otp = $this->request->getData('otp');
            if ($this->Google2fa->verifyKey($secretKey, $otp)) {
                $this->Flash->success('2FA ha sido activado correctamente.');
                // Marcar 2FA como verificado en sesión (o en BD si prefieres)
                $this->request->getSession()->write('Auth.2fa_verified', true);
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('Código 2FA incorrecto. Por favor, intenta de nuevo.');
            }
        }

        $this->set(compact('qrImage', 'secretKey'));
    }


    public function verify2fa()
    {
        $user = $this->Authentication->getIdentity();
        $this->set(compact('user'));

        if ($this->request->is('post')) {
            $oneTimePassword = $this->request->getData('otp');
            $secretKey = $user->google2fa_secret;

            if ($this->Google2fa->verifyKey($secretKey, $oneTimePassword)) {
                $this->request->getSession()->write('Auth.2fa_verified', true);
                $this->Flash->success('2FA verificado correctamente.');

                $target = $this->Authentication->getLoginRedirect() ?? '/';
                return $this->redirect($target);
            } else {
                $this->Flash->error('Código 2FA incorrecto.');
            }
        }
    }    
}