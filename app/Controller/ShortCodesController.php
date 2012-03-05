<?php

App::uses('AppController', 'Controller');
App::uses('ShortCode', 'Model');

class ShortCodesController extends AppController
{

    var $helpers = array('Js' => array('Jquery'));
	//var $uses = array('Program', 'Group');
    
    public function beforeFilter()
    {
        parent::beforeFilter();
    }


    public function constructClasses()
    {
        parent::constructClasses();
        
        $options = array(
            'database' => 'shortcodes'
            );
        $this->ShortCode = new ShortCode($options);
    }
    
    
    public function index()
    {
        $shortcodes = $this->paginate();
        $this->set(compact('shortcodes'));
        //print_r($shortcodes);
    }
    
    
    public function add()
    {
        if ($this->request->is('post')) {
	    $this->ShortCode->create();
	    if($this->ShortCode->save($this->request->data)) {
	        $this->Session->setFlash(__('The shortcode has been saved.'));
	        $this->redirect(array(
                    'controller' => 'shortCodes',
                    'action' => 'add'
                    ));
	    } else {
	        $this->Session->setFlash(__('The shortcode could not be saved.'));
	    }
        }
    }
    
    
    public function edit()
    {
        $shortcode = $this->params['shortCode'];
        $id         = $this->params['id'];
        
        $this->ShortCode->id = $id;
        if (!$this->ShortCode->exists()) {
            throw new NotFoundException(__('Invalid shortcode') . $id);
        }
        if ($this->request->is('post') || $this->request->is('put')) {
	    if ($this->ShortCode->save($this->request->data)) {
	        $this->Session->setFlash(__('The shortcode has been saved'));
	        $this->redirect(array('controller' => 'shortCodes',
	 	    'action' => 'index'
		    ));
	    } else {
                $this->Session->setFlash(__('The shortcode could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->ShortCode->read(null, $id);
        }
    	    
    }
    
    public function delete()
    {
        $shortcode = $this->params['shortCode'];
        $id         = $this->params['id'];
        
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->ShortCode->id = $id;
        if (!$this->ShortCode->exists()) {
            throw new NotFoundException(__('Invalid shortcode') . $id);
        }
        if ($this->ShortCode->delete()) {
            $this->Session->setFlash(__('ShortCode deleted'));
            $this->redirect(array('controller' => 'shortCodes',
                'action' => 'index'
                ));
        }
        $this->Session->setFlash(__('ShortCode was not deleted'));
        $this->redirect(array('controller' => 'shortCodes',
                'action' => 'index'
                ));
    	    
    }
    
    
}
