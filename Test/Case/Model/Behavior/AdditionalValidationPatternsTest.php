<?php

class YavPost extends CakeTestModel {

    public $name = 'YavPost';
    public $actsAs = array(
        'Cakeplus.AddValidationRule',
        'Cakeplus.ValidationPatterns',
        'Yav.AdditionalValidationRules',
        'Yav.AdditionalValidationPatterns',
    );

    public $validate = array();

    public function beforeValidate($options = array()){
        parent::beforeValidate($options);
        $this->setValidationPatterns();
    }
}

class AdditionalValidationPatternsTest extends CakeTestCase {

    public $fixtures = array('plugin.yav.yav_post');

    /**
     * setUp
     *
     */
    public function setUp(){
        $this->YavPost = ClassRegistry::init('YavPost');
    }

    /**
     * tearDown
     *
     */
    public function tearDown(){
        unset($this->YavPost);
    }

    /**
     * test_numeric
     * jpn: notemptyパターンがない場合はフォーマットチェック系パターンは通す
     *
     */
    public function test_numeric(){
        $this->YavPost->validate['not_empty_with1'] = array('numeric');
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $this->YavPost->validate['not_empty_with1'] = array('numeric', 'notempty');
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_empty
     * jpn: emptyパターン
     *
     */
    public function test_empty(){
        $this->YavPost->validate['title'] = array('empty');
        $data = array(
            'YavPost' => array(
                'title' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('title' , $this->YavPost->validationErrors ) );

        $this->YavPost->validate['title'] = array('empty');
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('title' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_alpha_numeric
     * jpn: notemptyパターンがない場合はフォーマットチェック系パターンは通す
     *
     */
    public function test_alpha_numeric(){
        $this->YavPost->validate['not_empty_with1'] = array('alpha_numeric');
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $this->YavPost->validate['not_empty_with1'] = array('alpha_numeric', 'notempty');
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_date
     * jpn: notemptyパターンがない場合はフォーマットチェック系パターンは通す
     *
     */
    public function test_date(){
        $this->YavPost->validate['not_empty_with1'] = array('date');
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $this->YavPost->validate['not_empty_with1'] = array('date', 'notempty');
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_json
     * jpn: notemptyパターンがない場合はフォーマットチェック系パターンは通す
     *
     */
    public function test_json(){
        $this->YavPost->validate['not_empty_with1'] = array('json');
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $this->YavPost->validate['not_empty_with1'] = array('json', 'notempty');
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );
    }

}
