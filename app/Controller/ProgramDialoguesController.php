<?php
App::uses('AppController', 'Controller');
App::uses('Dialogue', 'Model');
App::uses('Program', 'Model');
App::uses('Request', 'Model');
App::uses('ProgramSetting', 'Model');
App::uses('Participant', 'Model');
App::uses('VumiRabbitMQ', 'Lib');


class ProgramDialoguesController extends AppController
{
    var $components = array('RequestHandler', 'Acl');


    public function beforeFilter()
    {
        parent::beforeFilter();
        //$this->Auth->allow('*');
        $this->RequestHandler->accepts('json');
        $this->RequestHandler->addInputType('json', array('json_decode'));
    }


    function constructClasses()
    {
        parent::constructClasses();
        $options              = array('database' => ($this->Session->read($this->params['program']."_db")));
        $this->Dialogue       = new Dialogue($options);
        $this->ProgramSetting = new ProgramSetting($options);
        $this->Participant    = new Participant($options);
        $this->Request        = new Request($options);
        $this->_instanciateVumiRabbitMQ();
    }

    protected function _instanciateVumiRabbitMQ(){
        $this->VumiRabbitMQ = new VumiRabbitMQ(Configure::read('vusion.rabbitmq'));
    }


    public function index()
    {
        $this->set('dialogues', $this->Dialogue->getActiveAndDraft());
    }


    public function save()
    {
       if ($this->request->is('post')) {
            if ($this->Dialogue->saveDialogue($this->request->data)) {
                $this->set(
                    'result', 
                    array(
                        'status'=>'ok',
                        'dialogue-obj-id' => $this->Dialogue->id,
                        'message' => __('Dialogue saved as draft.')
                        )
                    );
            } else {
                $this->set(
                    'result', 
                    array(
                        'status'=>'fail',
                        'message' => $this->Dialogue->validationErrors['dialogue'],
                        )
                    );
            }
        }
    }

    public function edit()
    {
        $id = $this->params['id'];
        
        if (!isset($id))
            return;

        $this->Dialogue->id = $id;

        if (!$this->Dialogue->exists()) {
            $this->Session->setFlash(__("Dialogue doesn't exist."), 
                'default',
                array('class' => "message failure")
                );
            return;
        }

        $dialogue = $this->Dialogue->read(null, $id);
        if ($dialogue['Dialogue']['activated'] == 2) {
            $currentDialogue = $this->Dialogue->getActiveDialogue($dialogue['Dialogue']['dialogue-id']);
            $link = Router::url(array(
                'program'=> $this->params['program'],
                'controller'=>'programDialogues', 
                'action' => 'edit',
                'id' => $currentDialogue['Dialogue']['_id'].''));
           $this->Session->setFlash(
                "<a href='".$link."'>".__("This is an old version of this dialogue. Click here to get the current version.")."</a>", 
                'default',
                array('class' => "message failure"));
        }

        $this->set('dialogue', $dialogue);
    }

    public function activate()
    {
        $programUrl = $this->params['program'];
        $dialogueId = $this->params['id'];

        if ($this->_hasAllProgramSettings()) {
            $savedDialogue = $this->Dialogue->makeActive($dialogueId);
            if ($savedDialogue) {
                if ($savedDialogue['Dialogue']['auto-enrollment'] == 'all')
                    $this->Participant->autoEnrollDialogue($savedDialogue['Dialogue']['dialogue-id']);
                $this->_notifyUpdateBackendWorker($programUrl, $savedDialogue['Dialogue']['dialogue-id']);
                $this->Session->setFlash(__('Dialogue activated.'), 
                'default',
                array('class' => "message success")
                );
            } else
                $this->Session->setFlash(__('Dialogue unknown reload the page and try again.'), 
                'default',
                array('class' => "message failure")
                );
        } else 
            $this->Session->setFlash(__('Please set the program settings then try again.'), 
                'default',
                array('class' => "message failure")
                );
    
        
        $this->redirect(array('program'=>$programUrl, 'action' => 'index'));
    }


    public function validateKeyword()
    {
        $shortCode = $this->ProgramSetting->find('getProgramSetting', array('key'=>'shortcode'));
        if (!$shortCode) {
            $this->set('result', array(
                    'status'=>'fail', 
                    'message' => __('Program shortcode has not been defined, please go to program settings.')
                    ));
            return;
        }

        $keywordToValidate = $this->request->data['keyword'];
        $dialogueId        = $this->request->data['dialogue-id'];
 
        /**Is the keyword used by another dialogue of the same program*/
        $dialogueUsingKeyword = $this->Dialogue->getActiveDialogueUseKeyword($keywordToValidate);
        if ($dialogueUsingKeyword && 
            $dialogueUsingKeyword['Dialogue']['dialogue-id'] != $dialogueId) {
            $this->set(
                'result', array(
                    'status'=>'fail', 
                    'message'=> __("'%s' already used in dialogue '%s' of the same program.", $keywordToValidate, $dialogueUsingKeyword['Dialogue']['name'])
                    )
                );
            return;
            }

        /**Is the keyword used by request in the same program*/
        $foundKeyword = $this->Request->find('keyword', array('keywords'=> $keywordToValidate));
        if ($foundKeyword) {
            $this->set(
                'result', array(
                    'status'=>'fail', 
                    'message'=> __("'%s' already used by a request of the same program.", $foundKeyword)
                    )
                );
            return;
        }

        /**Is the keyword used by another program*/
        $programs = $this->Program->find(
            'all', 
            array('conditions'=> 
            array('Program.url !='=> $this->params['program'])
            )
        );
        foreach ($programs as $program) {
            $programSettingModel = new ProgramSetting(array('database'=>$program['Program']['database']));
            if ($programSettingModel->find('hasProgramSetting', array('key'=>'shortcode', 'value'=> $shortCode))) {
                $dialogueModel = new Dialogue(array('database'=>$program['Program']['database']));
                $foundKeyword = $dialogueModel->useKeyword($keywordToValidate);
                if ($foundKeyword) {
                    $this->set(
                        'result', array(
                            'status'=>'fail', 
                            'message'=>__("'%s' already used by a dialogue of program '%s'.", $foundKeyword, $program['Program']['name'])
                            )
                        );
                    return;
                }
                $requestModel = new Request(array('database'=>$program['Program']['database']));
                $foundKeyword = $requestModel->find('keyword', array('keywords'=> $keywordToValidate));
                if ($foundKeyword) {
                    $this->set(
                        'result', array(
                            'status'=>'fail', 
                            'message'=> __("'%s' already used by a request of program '%s'.", $foundKeyword, $program['Program']['name'])
                            )
                        );
                    return;
                }
            }
        }
        $this->set('result', array('status'=>'ok'));

    }    


    public function testSendAllMessages()
    {
        $programUrl = $this->params['program'];
        if (isset($this->params['id'])) {
            $objectId = $this->params['id'];
            $this->set(compact('objectId'));
        }
 
        if ($this->request->is('post')) {
            $phoneNumber = $this->request->data['SendAllMessages']['phone-number'];
            $dialogueId  = $this->request->data['SendAllMessages']['dialogue-obj-id'];
            $result      = $this->_notifySendAllMessagesBackendWorker($programUrl, $phoneNumber, $dialogueId);
            $this->Session->setFlash(
                __('Message(s) being sent, should arrive shortly...'), 
                'default',
                array('class' => "message success")
                );
        }
        $dialogues = $this->Dialogue->getActiveAndDraft();    
        $this->set(compact('dialogues'));         
    }


    protected function _notifySendAllMessagesBackendWorker($workerName, $phone, $scriptId)
    {
        return $this->VumiRabbitMQ->sendMessageToSendAllMessages($workerName, $phone, $scriptId);
    }


    protected function _notifyUpdateBackendWorker($workerName, $dialogueId)
    {
        return $this->VumiRabbitMQ->sendMessageToUpdateSchedule($workerName, 'dialogue', $dialogueId);
    }


    protected function _notifyUpdateRegisteredKeywords($workerName)
    {
        return $this->VumiRabbitMQ->sendMessageToUpdateRegisteredKeywords($workerName);
    }

    
    protected function _hasAllProgramSettings()
    {
        $shortCode = $this->ProgramSetting->find('getProgramSetting', array('key'=>'shortcode'));
        $timezone = $this->ProgramSetting->find('getProgramSetting', array('key'=>'timezone'));        
        if ($shortCode and $timezone) {
            return true;
        }
        return false;
    }


    public function delete()
    {
         $programUrl = $this->params['program'];
         $dialogueId = $this->params['id'];
         if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
         }
         if ($this->Dialogue->deleteDialogue($dialogueId)) {
             $result = $this->_notifyUpdateRegisteredKeywords($programUrl);
             $this->Session->setFlash(
                 __('Dialogue deleted.'),
                 'default',
                 array('class'=>'message success')
                 );
             $this->redirect(array(
                 'program' => $programUrl,
                 'action' => 'index'
                 ));
         }  
         $this->Session->setFlash(
             __('Delete dialogue failed.'), 
             'default',
             array('class' => "message failure")
             );
         $this->redirect(
             array(
                 'program' => $programUrl,
                 'action' => 'index'
                 )
             );
    }


}
