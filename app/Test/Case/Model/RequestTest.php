<?php
App::uses('Request', 'Model');
App::uses('MongodbSource', 'Mongodb.MongodbSource');


class RequestTestCase extends CakeTestCase
{

    protected $_config = array(
        'datasource' => 'Mongodb.MongodbSource',
        'host' => 'localhost',
        'login' => '',
        'password' => '',
        'database' => 'test',
        'port' => 27017,
        'prefix' => '',
        'persistent' => true,
        );

    
    public function setUp()
    {
        parent::setUp();

        $connections = ConnectionManager::enumConnectionObjects();
        
        if (!empty($connections['test']['classname']) && $connections['test']['classname'] === 'mongodbSource'){
            $config = new DATABASE_CONFIG();
            $this->_config = $config->test;
        }
        
        ConnectionManager::create('mongo_test', $this->_config);
        $this->Mongo = new MongodbSource($this->_config);

        $option        = array('database'=>'test');
        $this->Request = new Request($option);

        $this->Request->setDataSource('mongo_test');
        $this->Request->deleteAll(true, false);
    }


    public function tearDown()
    {
        $this->Request->deleteAll(true, false);
        unset($this->Request);
        parent::tearDown();
    }

    
    public function testFindKeyword()
    {
        $request['Request'] = array(
            'keyword' => 'key request, keyword, otherkeyword request'
            );
        $this->Request->create();
        $this->Request->save($request);
        $matchingKeywordRequest = $this->Request->find('keyword', array('keywords'=>'keyword'));
        $this->assertEqual('keyword', $matchingKeywordRequest);

        $matchingKeywordRequest = $this->Request->find('keyword', array('keywords'=>'keywo'));
        $this->assertEqual(null, $matchingKeywordRequest);

        $matchingKeywordRequest = $this->Request->find('keyword', array('keywords'=>'keywor, keyword'));
        $this->assertEqual('keyword', $matchingKeywordRequest);
        
        $matchingKeywordRequest = $this->Request->find('keyword', array('keywords'=>'kEy'));
        $this->assertEqual('kEy', $matchingKeywordRequest);
        
        $matchingKeywordRequest = $this->Request->find('keyword', array('keywords'=>'request'));
        $this->assertEqual(null, $matchingKeywordRequest);

        $request['Request'] = array(
            'keyword' => 'key'
            );
        $this->Request->create();
        $this->Request->save($request);
        $matchingKeywordRequest = $this->Request->find('keyword', array('keywords'=>'key'));
        $this->assertEqual('key', $matchingKeywordRequest);
        
    }


    public function testFindKeyphrase()
    {
        $request['Request'] = array(
            'keyword' => 'key request, keyword, otherkeyword request'
            );
        $this->Request->create();
        $savedRequest = $this->Request->save($request);

        $otherRequest['Request'] = array(
            'keyword' => 'something else'
            );
        $this->Request->create();
        $this->Request->save($otherRequest);

        $matchingKeywordRequest = $this->Request->find('keyphrase', array('keywords'=>'keyword'));
        $this->assertEqual('keyword', $matchingKeywordRequest);

        $matchingKeywordRequest = $this->Request->find('keyphrase', array('keywords'=>'keywo'));
        $this->assertEqual(null, $matchingKeywordRequest);

        $matchingKeywordRequest = $this->Request->find('keyphrase', array('keywords'=>'keywor, keyword'));
        $this->assertEqual('keyword', $matchingKeywordRequest);
        
        $matchingKeywordRequest = $this->Request->find('keyphrase', array('keywords'=>'kEy'));
        $this->assertEqual(null, $matchingKeywordRequest);
        
        $matchingKeywordRequest = $this->Request->find('keyphrase', array('keywords'=>'kEy request'));
        $this->assertEqual('kEy request', $matchingKeywordRequest);

        $matchingKeywordRequest = $this->Request->find(
            'keyphrase', 
            array(
                'keywords'=>'kEy request',
                'excludeRequest' => $savedRequest['Request']['_id']
                )
            );
        $this->assertEqual(null, $matchingKeywordRequest);

        $matchingKeywordRequest = $this->Request->find('keyphrase', array('keywords'=>'request'));
        $this->assertEqual(null, $matchingKeywordRequest);

    }


}
