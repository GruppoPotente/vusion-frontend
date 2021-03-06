<?php
App::uses('Request', 'Model');
App::uses('MongodbSource', 'Mongodb.MongodbSource');


class RequestTestCase extends CakeTestCase
{
 
   
    public function setUp()
    {
        parent::setUp();

        $connections = ConnectionManager::enumConnectionObjects();

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

    public function testGetKeywords()
    {
        $request['Request'] = array(
            'keyword' => 'key request, keyword, otherkeyword request'
            );
        $this->Request->create();
        $this->Request->save($request);

        $request['Request'] = array(
            'keyword' => 'k'
            );
        $this->Request->create();
        $this->Request->save($request);
        
        $this->assertEqual(
            array('key', 'keyword', 'otherkeyword', 'k'),
            $this->Request->getKeywords());
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
    
    
    public function testValidateKeyword_Fail_With_Invalid_Conditions()
    {
        $request['Request'] = array(
            'keyword' => 'free'
            );
        $this->Request->create();
        $savedRequest = $this->Request->save($request);
        
        $matchingKeywordRequest = $this->Request->find('keyphrase', array('keywords'=>'free',
            'excludeRequest'=>'')); // 'excludeRequest'=>'' is an invalid condition //
        $this->assertEqual('free', $matchingKeywordRequest);
    }


    public function testSave_validateKeyword_ok()
    {
        $request['Request'] = array(
            'keyword' => 'key request, keyword, otherkeyword request'
            );
        $this->Request->create();
        $savedRequest = $this->Request->save($request);
        $this->assertTrue(isset($savedRequest));
    }

    public function testSave_validateKeyword_fail()
    {
        $request['Request'] = array(
            'keyword' => 'key request, keyw?ord, otherkeyword request'
            );
        $this->Request->create();
        $savedRequest = $this->Request->save($request);
        $this->assertFalse($savedRequest);
        $this->assertEquals(
            'This keyword format is not valid.',
            $this->Request->validationErrors['keyword'][0]);
    }

}
