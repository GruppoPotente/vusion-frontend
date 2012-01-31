<?php
/* Programs Test cases generated on: 2012-01-24 15:39:09 : 1327408749*/
App::uses('ProgramsController', 'Controller');

/**
 * TestProgramsController *
 */
class TestProgramsController extends ProgramsController {
/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect action
 *
 * @param mixed $url
 * @param mixed $status
 * @param boolean $exit
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

/**
 * ProgramsController Test Case
 *
 */
class ProgramsControllerTestCase extends ControllerTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.program');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$this->Programs = new TestProgramsController();
		$this->Programs->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Programs);

		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
		$this->testAction("/programs/index");
		$this->assertEquals(2, count($this->vars['programs']));
		//debug(array('toto' => 'tata'));
		//throw new Exception(serialize($result));
		//debug($result, true);
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
		$this->testAction("/programs/view/1");
		//print_r($result);
		$this->assertEquals(array(
				'Program' => array(
					'id' => 1,
					'name' => 'm4h',
					'country' => 'uganda',
					'created' => '2012-01-24 15:29:24',
					'modified' => '2012-01-24 15:29:24'
				)
			), 
			$this->vars['program']
			);
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
		 $data = array(
		 	 'Program' => array(
		 	 	 //'id' => '3',
		 	 	 'name' => 'Newprogram',
		 	 	 'country' => 'Somewhere',
		 	 	 )
		 	 );
    		 $this->testAction('/programs/add', array('data' => $data, 'method' => 'post'));
	}

/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {

	}

/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {

	}

}
