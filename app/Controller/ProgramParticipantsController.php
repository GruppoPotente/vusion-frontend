<?php
App::uses('AppController','Controller');
App::uses('Participant','Model');
App::uses('History', 'Model');
App::uses('ProgramSetting', 'Model');


class ProgramParticipantsController extends AppController
{

    public $uses = array('Participant', 'History');
    
    function constructClasses() 
    {
        parent::constructClasses();

        $options = array('database' => ($this->Session->read($this->params['program']."_db")));

        $this->Participant    = new Participant($options);
        $this->History        = new History($options);
        $this->ProgramSetting = new ProgramSetting($options);
    }


    function beforeFilter() 
    {
        parent::beforeFilter();
        //$this->Auth->allow('*');
    }

    
    public function index() 
    {
        $programTimezone = $this->ProgramSetting->find('programSetting', array('key' => 'timezone'));
    	$this->set(compact('programTimezone'));
    	
        $participants = $this->paginate();
        $this->set(compact('participants'));        
    }

    
    public function add() 
    {
        $programTimezone = $this->ProgramSetting->find('programSetting', array('key' => 'timezone'));
    	$this->set(compact('programTimezone'));
    	
        $programUrl = $this->params['program'];
 
        if ($this->request->is('post')) {
            $this->Participant->create();
            if ($this->Participant->save($this->request->data)) {
                $this->Session->setFlash(__('The participant has been saved.'),
                    'default',
                    array('class'=>'good-message')
                );
                $this->redirect(array(
                    'program' => $programUrl,  
                    'controller' => 'programParticipants',
                    'action' => 'index'
                    ));
            } else {
                $this->Session->setFlash(__('The participant could not be saved.'));
            }
        }        
    }

    
    public function edit() 
    {
        $programTimezone = $this->ProgramSetting->find('programSetting', array('key' => 'timezone'));
    	$this->set(compact('programTimezone'));
    	
        $programUrl = $this->params['program'];
        $id         = $this->params['id'];
        
        $this->Participant->id = $id;
        if (!$this->Participant->exists()) {
            throw new NotFoundException(__('Invalid participant'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Participant->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'),
                    'default',
                    array('class'=>'good-message')
                );
                $this->redirect(array('program' => $programUrl, 'controller'=>'participants', 'action' => 'index'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Participant->read(null, $id);
        } 
    }

    
    public function delete() 
    {
        $programUrl = $this->params['program'];
        $id         = $this->params['id'];
        
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Participant->id = $id;
        if (!$this->Participant->exists()) {
            throw new NotFoundException(__('Invalid participant:') . $id);
        }
        if ($this->Participant->delete()) {
            $this->Session->setFlash(__('Participant deleted'),
                'default',
                array('class'=>'good-message')
            );
            $this->redirect(array('program' => $programUrl,
                'action' => 'index'
                ));
        }
        $this->Session->setFlash(__('Participant was not deleted'));
        $this->redirect(array('program' => $programUrl,
                'action' => 'index'
                ));
    }

    
    public function view() 
    {
        $programTimezone = $this->ProgramSetting->find('programSetting', array('key' => 'timezone'));
    	$this->set(compact('programTimezone'));
    	
        $id = $this->params['id'];

        $this->Participant->id = $id;
        if (!$this->Participant->exists()) {
            throw new NotFoundException(__('Invalid participant'));
        }
        $participant = $this->Participant->read(null, $id);
        $histories   = $this->History->find('participant', array(
                'phone' => $participant['Participant']['phone']
            ));
        $this->set(compact(
            'participant',
            'histories'));
    }

    
    public function import()
    {
        $programTimezone = $this->ProgramSetting->find('programSetting', array('key' => 'timezone'));
    	$this->set(compact('programTimezone'));
    	
        require_once 'excel_reader2.php';
        //$data = new Spreadsheet_Excel_Reader("example.xls");

        $programName = $this->Session->read($this->params['program'].'_name');
        $programUrl  = $this->params['program'];

        if ($this->request->is('post')) {
            if (!$this->request->data['Import']['file']['error']) {
                $fileName = $this->request->data['Import']['file']["name"];
                $ext      = end(explode('.', $fileName));

                if (!($ext == 'csv') and !($ext == 'xls') and !($ext == 'xlsx')) {
                    $this->Session->setFlash('This file format is not supported');
                    return;
                }

                $filePath = WWW_ROOT . "files/" . $programUrl; 

                if (!file_exists(WWW_ROOT . "files/".$programUrl)) {
                    //echo 'create folder: ' . WWW_ROOT . "files/".$programUrl;
                    mkdir($filePath);
                    chmod($filePath, 0777);
                }
                                
                /** in case the file has already been created, 
                * the chmod function should not be called.
                */
                $wasFileAlreadyThere = false;
                if(file_exists($filePath . DS . $fileName)) {
                    $wasFileAlreadyThere = true;
                }

                copy($this->request->data['Import']['file']['tmp_name'],
                    $filePath . DS . $fileName);
                
                if(!$wasFileAlreadyThere) {
                    chmod($filePath . DS . $fileName, 0777);
                }
                
                if ($ext == 'csv') {
 
                    $entries = $this->processCsv($filePath, $fileName);

                } else if ($ext == 'xls' || $ext == 'xlsx') {

                    $entries = $this->processXls($filePath, $fileName);

                }
            }
        } 
        $this->set(compact('entries'));
    }

    
    private function processCsv($filePath, $fileName)
    {
        $importedParticipants = fopen($filePath . DS . $fileName,"r");
        $entries              = array();
        $participant          = array();
        $count = 0;
        while (!feof($importedParticipants)) {                    
            $entries[] = fgets($importedParticipants);
            if ($count > 0 && $entries[$count]) {
                $this->Participant->create();
                $entries[$count]      = str_replace("\n", "", $entries[$count]);
                $explodeLine          = explode(",", $entries[$count]);
                $participant['phone'] = $explodeLine[0];
                $participant['phone'] = $this->checkPhoneNumber($participant['phone']);
                $participant['name']  = $explodeLine[1];
                //print_r($participant);
                if ($this->Participant->save($participant)) {
                    $entries[$count] .= " insert ok"; 
                } else {
                    $entries[$count] .= " duplicated phone line ".($count+1);
                }
                
            }
            $count++; 
        }
        return $entries;
    }

    
    private function processXls($filePath, $fileName)
    {
        $data = new Spreadsheet_Excel_Reader($filePath . DS . $fileName);
        for ( $i = 2; $i <= $data->rowcount($sheet_index=0); $i++) {
            $participant['phone'] = $data->val($i,'A');
            $participant['phone'] = $this->checkPhoneNumber($participant['phone']);
            $participant['name']  = $data->val($i,'B');
            $this->Participant->create();
            //for view report
            $entries[$i] = $participant['phone'] . ','.$participant['name'];
            if ($this->Participant->save($participant)) {
                $entries[$i] .= " insert ok"; 
            } else {
                $entries[$i] .= " duplicated phone line ".$i;
            }
        }
        return $entries;
    }
    
   
    public function checkPhoneNumber($phoneNumber) 
    {
        $newPhoneNumber = preg_replace("/[^0-9]/", "", $phoneNumber);
        $newPhoneNumber = preg_replace("/^0/", "", $newPhoneNumber);
        return $newPhoneNumber;
    }
    
    
}
