<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use mysql_xdevapi\Exception;

/**
 * Resources Controller
 *
 * @property \App\Model\Table\ResourcesTable $Resources
 * @method \App\Model\Entity\Resource[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ResourcesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $tag = $this->request->getQuery('tag');
        $titleKey = $this->request->getQuery('titleKey');
        $bodyKey = $this->request->getQuery('bodyKey');
        if($tag){
            $keys = explode(" ", $tag);
            $filters = array();
            foreach ($keys as $k){
                $filters[] = array('tags like'=>'%'.$k.'%');
            }
            $query = $this->Resources->find()
                ->where(['And'=>$filters]);
        }
        elseif ($titleKey){
            $query = $this->Resources->find()->where(['title like'=>'%'.$titleKey.'%']);
        }
        elseif ($bodyKey){
            $query = $this->Resources->find()->where(['body like'=>'%'.$bodyKey.'%']);
        }
        else{
            $query = $this->Resources;
        }
        $this->paginate = [
            'contain' => ['Users'],
        ];
        $resources = $this->paginate($query);

        $this->set('username',$this->Auth->user('name'));
        $this->set(compact('resources'));
    }

    /**
     * View method
     *
     * @param string|null $id Resource id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $resource = $this->Resources->get($id, [
            'contain' => ['Users'],
        ]);

        $this->set('username',$this->Auth->user('name'));
        $this->set(compact('resource'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $resource = $this->Resources->newEmptyEntity();
        try{
            if ($this->request->is('post')) {
                $resource = $this->Resources->patchEntity($resource, $this->request->getData());

                if(!$resource->getErrors){
                    $image = $this->request->getData('image_file');
                    $name = $image->getClientFilename();

                    if(!is_dir(WWW_ROOT.'img'.DS.'user-img'))
                        mkdir(WWW_ROOT.'img'.DS.'user-img',0775);

                    $targetPath = WWW_ROOT.'img'.DS.'user-img'.DS.$name;
                    if($name){
                        if(file_exists($targetPath)){
                            $base = pathinfo($name, PATHINFO_FILENAME);
                            $extension = pathinfo($name, PATHINFO_EXTENSION);
                            $name = $base.'(1)'.'.'.$extension;
                        }
                        $targetPath = WWW_ROOT.'img'.DS.'user-img'.DS.$name;
                        $image->moveTo($targetPath);
                    }

                    $resource->image = 'user-img/'.$name;
                }

                if ($this->Resources->save($resource)) {
                    $this->Flash->success(__('The resource has been saved.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('The resource could not be saved. Please, try again.'));
            }
        }catch (\PDOException $e){
            $this->Flash->error(__('The resource could not be saved. Please, try again.'));
        }
        $users = $this->Resources->Users->find('list', ['limit' => 200])->all();
        $this->set('userid',$this->Auth->user('id'));
        $this->set(compact('resource', 'users'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Resource id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $username = $this->Resources->Users->get($this->Resources->get($id)->get('user_id'))->get('name');
        if ($this->Auth->user('name') == $username) {
            $resource = $this->Resources->get($id, [
                'contain' => [],
            ]);
            try {
                if ($this->request->is(['patch', 'post', 'put'])) {
                    $resource = $this->Resources->patchEntity($resource, $this->request->getData());

                    if(!$resource->getErrors){
                        $image = $this->request->getData('image_file');
                        $name = $image->getClientFilename();
                        if($name){
                            if(!is_dir(WWW_ROOT.'img'.DS.'user-img'))
                                mkdir(WWW_ROOT.'img'.DS.'user-img',0775);

                            $imgpath = WWW_ROOT.'img'.DS.$resource->image;
                            if(file_exists($imgpath)){
                                unlink($imgpath);
                            }

                            $targetPath = WWW_ROOT.'img'.DS.'user-img'.DS.$name;
                            if(file_exists($targetPath)){
                                $base = pathinfo($name, PATHINFO_FILENAME);
                                $extension = pathinfo($name, PATHINFO_EXTENSION);
                                $name = $base.'(1)'.'.'.$extension;
                            }
                            $targetPath = WWW_ROOT.'img'.DS.'user-img'.DS.$name;
                            $image->moveTo($targetPath);

                            $resource->image = 'user-img/'.$name;
                        }
                    }

                    if ($this->Resources->save($resource)) {
                        $this->Flash->success(__('The resource has been saved.'));

                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('The resource could not be saved. Please, try again.'));
                }
            }catch (\PDOException $e){
                $this->Flash->error(__('The resource could not be saved. Please, try again.'));
            }
            $users = $this->Resources->Users->find('list', ['limit' => 200])->all();
            $this->set('username', $this->Auth->user('name'));
            $this->set(compact('resource', 'users'));
        }else{
            $this->Flash->error(__('You do not own this resource.'));
            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Resource id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $username = $this->Resources->Users->get($this->Resources->get($id)->get('user_id'))->get('name');
        if ($this->Auth->user('name') == $username) {
            $this->request->allowMethod(['post', 'delete']);
            $resource = $this->Resources->get($id);

            $imgpath = WWW_ROOT.'img'.DS.$resource->image;

            if ($this->Resources->delete($resource)) {
                if(file_exists($imgpath)){
                    unlink($imgpath);
                }
                $this->Flash->success(__('The resource has been deleted.'));
            } else {
                $this->Flash->error(__('The resource could not be deleted. Please, try again.'));
            }
        }else{
            $this->Flash->error(__('You do not own this resource.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function generateUrl($id = null): ?\Cake\Http\Response
    {
        $username = $this->Resources->Users->get($this->Resources->get($id)->get('user_id'))->get('name');
        if ($this->Auth->user('name') == $username) {
            $this->Flash->info(__('URL to this resource: http://localhost/PAIDocs/resources/view/'.$id));
        }else{
            $this->Flash->error(__('You do not own this resource.'));
        }
        return $this->redirect(['action' => 'view', $id]);
    }

    /**
     * Guest pages
     */
    public function beforeFilter(EventInterface $event)
    {
        $this->Auth->allow(['view']);
    }
}
