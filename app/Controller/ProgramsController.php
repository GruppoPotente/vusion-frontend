<?php

App::uses('AppController', 'Controller');
App::uses('ProgramSetting', 'Model');
App::uses('Participant', 'Model');
App::uses('Schedule', 'Model');
App::uses('History', 'Model');
App::uses('UnmatchableReply', 'Model');
App::uses('VumiRabbitMQ', 'Lib');

/**
 * Programs Controller
 *
 * @property Program $Program
 */
class ProgramsController extends AppController
{

    var $components = array('RequestHandler');
    public $helpers = array('Time', 'Js' => array('Jquery'));    
    var $uses = array('Program', 'Group');
    var $paginate = array(
        'limit' => 10,
        'order' => array(
            'Program.created' => 'desc'
            )
        );

    function constructClasses()
    {
        parent::constructClasses();

        $this->VumiRabbitMQ = new VumiRabbitMQ();
    }


    /**
    * index method
    *
    * @return void
    */
    public function index() 
    {
        $this->Program->recursive = -1;
        if ($this->Group->hasSpecificProgramAccess($this->Session->read('Auth.User.group_id'))) {
           $this->paginate = array(
                'authorized',
                'specific_program_access' => 'true',
                'user_id' => $this->Session->read('Auth.User.id'),
                );
        }
        $programs      =  $this->paginate();
        $isProgramEdit = $this->Acl->check(array(
                'User' => array(
                    'id' => $this->Session->read('Auth.User.id')
                ),
            ), 'controllers/Programs/edit');
        foreach($programs as &$program) {
            $database = $program['Program']['database'];
            $tempProgramSetting = new ProgramSetting(array('database' => $database));
            $shortcode = $tempProgramSetting->find('programSetting', array('key'=>'shortcode'));
            if (isset($shortcode[0]['ProgramSetting']['value'])) {
                $program['Program']['shortcode'] = $shortcode[0]['ProgramSetting']['value'];
            } 
            $tempParticipant = new Participant(array('database' => $database));
            $program['Program']['participant-count'] = $tempParticipant->find('count'); 
            $tempHistory = new History(array('database' => $database));
            $program['Program']['history-count'] = $tempHistory->find('count');
            $tempSchedule = new Schedule(array('database' => $database));
            $program['Program']['schedule-count'] = $tempSchedule->find('count');  
        }
        $tempUnmatchableReply = new UnmatchableReply(array('database'=>'vusion'));
        $this->set('unmatchableReplies', $tempUnmatchableReply->find('all'));
        $this->set(compact('programs', 'isProgramEdit'));
    }


    /**
    * view method
    *
    * @param string $id
    * @return void
    */
    public function view($id = null)
    {
        $this->Program->id = $id;
        if (!$this->Program->exists()) {
            throw new NotFoundException(__('Invalid program.'));
        }
        $this->set('program', $this->Program->read(null, $id));
    }


    /**
    * add method
    *
    * @return void
    */
    public function add()
    {
        if ($this->request->is('post')) {
            $this->Program->create();
            if ($this->Program->save($this->request->data)) {
                $this->Session->setFlash(__('The program has been saved.'),
                    'default',
                    array('class'=>'message success')
                );
                $this->_startBackendWorker(
                    $this->request->data['Program']['url'],
                    $this->request->data['Program']['database']
                    );
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The program could not be saved. Please, try again.'), 
                'default',
                array('class' => "message failure")
                );
            }
        }
    }


    /** 
    * function redirection to allow mocking in the testcases
    */
    protected function _startBackendWorker($workerName, $databaseName)
    {
        $this->VumiRabbitMQ->sendMessageToCreateWorker($workerName,$databaseName);    	 
    }


    /**
    * edit method
    *
    * @param string $id
    * @return void
    */
    public function edit($id = null)
    {
        $this->Program->id = $id;
        if (!$this->Program->exists()) {
            throw new NotFoundException(__('Invalid program.'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Program->save($this->request->data)) {
                $this->Session->setFlash(__('The program has been saved.'),
                    'default',
                    array('class'=>'message success')
                );
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The program could not be saved. Please, try again.'), 
                'default',
                array('class' => "message failure")
                );
            }
        } else {
            $this->request->data = $this->Program->read(null, $id);
        }
    }

    //TODO: Ask for delete confirmation
    public function delete($id = null)
    {
        //if ($this->request->is('post')) {
            $this->Program->id = $id;
            if (!$this->Program->exists()) {
                throw new NotFoundException(__('Invalid program.'));
            }
            if ($this->Program->deleteProgram()) {
                $this->Session->setFlash(__('Program deleted.'),
                    'default',
                    array('class'=>'message success')
                    );
                $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Program was not deleted.'), 
                'default',
                array('class' => "message failure")
                );
            $this->redirect(array('action' => 'index'));
        //}
    }


}
