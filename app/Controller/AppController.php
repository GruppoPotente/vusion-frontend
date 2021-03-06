<?php
App::uses('Controller', 'Controller');
App::uses('ProgramSetting', 'Model');
App::uses('UnattachedMessage', 'Model');
App::uses('Dialogue', 'Model');
App::uses('Request', 'Model');

class AppController extends Controller
{

    var $uses = array('Program', 'Group');

    public $components = array(
        'Session',
        'Auth' => array(
            'loginAction' => array(
                'controller' => 'users',
                'action' => 'login',
                
                ),
            'loginRedirect' => array(
                'controller' => 'programs',
                'action' => 'index'
                ),
            'logoutRedirect' => array(
                'controller' => 'users',
                'action' => 'login'
                ),
            //'authError' => 'Authentication Failed',
            'authenticate' => array(
                'Form' => array(
                    //'field' => array('username' => 'username'),
                    'fields' => array('username' => 'email')
                    ),
                'Basic' => array(
                    'fields' => array('username' => 'email')
                    )
                ),
            'authorize' => array(
                'Actions' => array('actionPath' => 'controllers')
                )
            ),
        'Acl',
        'Cookie');

    public $helpers = array('Html', 'Form', 'Session', 'Js', 'Time', 'AclLink', 'Text');


    function beforeFilter()
    {    
        //In case of a Json request, no need to set up the variables
        if ($this->params['ext']=='json' or $this->params['ext']=='csv')
            return;

        $programUrl = $this->params['program'];
        $programName = $this->Session->read($this->params['program'].'_name');
        $programTimezone = $this->Session->read($this->params['program'].'_timezone');
        $databaseName = $this->Session->read($this->params['program'].'_db');
        if ($this->Session->read('Auth.User.id')) {
            $isAdmin = $this->Acl->check(
                array(
                    'User' => array(
                        'id' => $this->Session->read('Auth.User.id')
                        )
                    ),
                'controllers/Admin');
        }
        if (isset($programUrl)) {            
            $unattachedMessageModel = new UnattachedMessage(array('database' => $databaseName));
            $unattachedMessages = $unattachedMessageModel->find('future');
            if (isset($unattachedMessages))
                $programUnattachedMessages = $unattachedMessages;
            else
                $programUnattachedMessages = null;
            
            $dialogueModel = new Dialogue(array('database' => $databaseName));
            $dialogues = $dialogueModel->getActiveAndDraft();
            $requestModel = new Request(array('database' => $databaseName));
            $requests = $requestModel->find('all');

            $redis = new Redis();
            $redis->connect('127.0.0.1');
            
            $hasProgramLogs = $this->_hasProgramLogs($redis,$programUrl);
            if ($this->_hasProgramLogs($redis,$programUrl))
                $programLogsUpdates = $this->_processProgramLogs($redis,$programUrl);
            
            $this->set(compact('programUnattachedMessages', 'dialogues', 'hasProgramLogs', 'programLogsUpdates', 'requests'));
        }
        $this->set(compact('programUrl', 'programName', 'programTimezone', 'isAdmin'));
    }


    function constructClasses()
    {
        parent::constructClasses();

        //Verify the access of user to this program
        if (!empty($this->params['program'])) {
            $this->Program->recursive = -1;
            
            $data = $this->Program->find('authorized', array(
                'specific_program_access' => $this->Group->hasSpecificProgramAccess(
                    $this->Session->read('Auth.User.group_id')),
                'user_id' => $this->Session->read('Auth.User.id'),
                'program_url' => $this->params['program']
                ));
            if (count($data)==0) {
               throw new NotFoundException('Could not find this page.');
            } else {
            	$database_name = $data[0]['Program']['database'];
                $this->Session->write($this->params['program'] . '_name', $data[0]['Program']['name']);
                $this->Session->write($this->params['program'] . '_db', $database_name); 
                $programSettingModel = new ProgramSetting(array('database' => $database_name));
                $programTimezone = $programSettingModel->find('programSetting', array('key' => 'timezone'));
                if (isset($programTimezone[0]['ProgramSetting']['value']))
                    $this->Session->write($this->params['program'].'_timezone', $programTimezone[0]['ProgramSetting']['value']);
                else 
                    $this->Session->write($this->params['program'].'_timezone', null);
            }
        }
    }
    
    protected function _hasProgramLogs($redis,$program)
    {
        if (count($redis->zRange($program.':logs', -5, -1, true)) > 0)
            return true;
        return false;
    }
    
    
    protected function _processProgramLogs($redis,$program)
    {
        if ($this->_hasProgramLogs($redis,$program)) {
            $programLogs = array();
        
            $logs = $redis->zRange($program.':logs', -5, -1, true);
            foreach ($logs as $key => $value) {
                $programLogs[] = $key;
            }
            return array_reverse($programLogs);
        }
        return array();    	    	    
    }


}
