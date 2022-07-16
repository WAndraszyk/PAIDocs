<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\EventInterface;
use Cake\ORM\TableRegistry;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set('username',$this->Auth->user('name'));
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
        $user = $this->Users->get($id, [
            'contain' => ['Resources'],
        ]);

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
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'logout']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
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

        $this->logout();
    }

    /**
     * Login
     */
    public function login(){
        if($this->request->is('post')){
            $user = $this->Auth->identify();
            if($user){
                $this->Auth->setUser($user);
                return $this->redirect(['controller' => 'resources']);
            }
            // Bad login
            $this->Flash->error('Incorrect login!');
        }
    }

    /**
     * Logout
     */
    public function logout(): ?\Cake\Http\Response
    {
        $this->Flash->success('You are logged out!');
        return $this->redirect($this->Auth->logout());
    }

    /**
     * Register
     */
    public function register(){
        $user = $this->Users->newEmptyEntity();
        if($this->request->is('post')){
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if($this->Users->save($user)){
                $this->Flash->success('You have been registered successfully and can now log in');
                return $this->redirect(['action' => 'login']);
            }else{
                $this->Flash->error('You haven\'t been able to register!');
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize',['user']);
    }

    /**
     * Guest pages
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Auth->allow(['register', 'forgot']);
        $this->Auth->deny(['index']);
    }

    /**
     * Forgot password
     */
    public function forgot(){
        if($this->request->is('post')){
            $hasher = new DefaultPasswordHasher;

            $myname = $this->request->getData('name');
            $myanswer = $this->request->getData('answer');
            $newpass = $this->request->getData('password');

            $query = $this->Users->find()->where(array('name'=>$myname));
            $user = $query->first();
            $answer = $user->get('answer');
            if($hasher->check($myanswer,$answer)){
                $user->password = $newpass;
                if($this->Users->save($user)){
                    $this->Flash->success('Your password has been successfully changed');
                    return $this->redirect(['action' => 'login']);
                }else{
                    $this->Flash->error('Your password could not be changed');
                }
            }else{
                $this->Flash->error('Wrong answer!');
            }
        }
    }
}
