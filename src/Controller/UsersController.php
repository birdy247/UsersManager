<?php

namespace UsersManager\Controller;

use Cake\Event\Event;
use UsersManager\Controller\AppController;
use Cake\Network\Session;

/**
 * Users Controller
 *
 * @property \UsersManager\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);
        $this->Auth->deny();
    }

    public function isAuthorized($user)
    {
        return parent::isAuthorized($user);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function admin()
    {
        //$this->viewBuilder()->layout('admin');
        $this->paginate = [
            'contain' => ['Roles']
        ];
        $this->set('users', $this->paginate($this->Users));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        //$this->viewBuilder()->layout('admin');
        $user = $this->Users->get($id, [
            'contain' => ['Roles']
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        //$this->viewBuilder()->layout('admin');
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);

            if ($save = $this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'admin']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $this->set(compact('user', 'roles'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        //$this->viewBuilder()->layout('admin');
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'admin']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $roles = $this->Users->Roles->find('list', ['limit' => 200]);
        $this->set(compact('user', 'roles'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
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

        //$this->viewBuilder()->layout('login');
        //If a user is already logged in, redirect them to their profile.
        if ($this->Auth->user()) {
            $this->redirect($this->referer());
        }

        $session = new Session();

        if ($this->request->is('post')) {

            if (isset($this->request->data['referred'])) {
                $session->write('Redirect.login', $this->referer());
            }

            $user = $this->Auth->identify();
            if ($user) {

                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }

            $this->Flash->error('Invalid username or password, try again');
        }
    }

    /**
     * Logout Method
     * @return type
     */
    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

}
