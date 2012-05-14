<?php

App::uses('AppController','Controller');
App::uses('Template','Model');

class TemplatesController extends AppController
{
    var $helpers = array('Time','Js'=>array('Jquery'));
    
    public function beforeFilter()
    {
        parent::beforeFilter();
    }
    
    
    public function constructClasses()
    {
        parent::constructClasses();
        
        if (!Configure::read("mongo_db")) {
            $options = array(
                'database' => 'vusion'
                );
        } else {
            $options = array(
                'database' => Configure::read("mongo_db")
                );
        }
        $this->Template = new Template($options);
    }
    
    
    public function index()
    {
        $templates = $this->paginate();
        $this->set(compact('templates'));
    }
    
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Template->create();
            if ($this->Template->save($this->request->data)) {
                $this->Session->setFlash(__('The template has been saved.'),
                    'default',
                    array('class'=>'message success')
                );
                $this->redirect(array(
                    'controller' => 'templates',
                    'action' => 'index'
                    ));
            } else {
                $this->Session->setFlash(__('The template could not be saved.'));
            }
        }
    }
    
    
    public function edit()
    {
        $template = $this->params['template'];
        $id        = $this->params['id'];
        
        $this->Template->id = $id;
        if (!$this->Template->exists()) {
            throw new NotFoundException(__('Invalid template') . $id);
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Template->save($this->request->data)) {
                $template = $this->request->data;
                $this->Session->setFlash(__('The template has been saved'),
                    'default',
                    array('class'=>'message success')
                );
                $this->redirect(array('controller' => 'templates',
                    'action' => 'index'
                    ));
            } else {
                $this->Session->setFlash(__('The template could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Template->read(null, $id);
        }
        return $template;    
    }
    
    
    public function delete()
    {
        $id = $this->params['id'];
        
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Template->id = $id;
        if (!$this->Template->exists()) {
            throw new NotFoundException(__('Invalid template') . $id);
        }
        if ($this->Template->delete()) {
            $this->Session->setFlash(__('Template deleted'),
                'default',
                array('class'=>'message success')
            );
            $this->redirect(array('controller' => 'templates',
                'action' => 'index'
                ));
        }
        $this->Session->setFlash(__('Template was not deleted'));
        $this->redirect(array('controller' => 'templates',
                'action' => 'index'
                ));
        
    }
    
    
}
