<?php

class YavPost extends CakeTestModel {

    public $name = 'YavPost';
    public $actsAs = array(
        'Cakeplus.AddValidationRule',
        'Yav.AdditionalValidationRules'
    );

    public $validate = array();
}

class AdditionalValidationRulesTest extends CakeTestCase {

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
     * test_notEmptyWith
     *
     */
    public function test_notEmptyWith(){
        $this->YavPost->validate['not_empty_with1'] = array(
            'notEmptyWith' => array(
                'rule' => array('notEmptyWith', array('not_empty_with2', 'not_empty_with3'))
            ));
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
                'not_empty_with3' => '空でない',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_notEmptyWithHash
     *
     */
    public function test_notEmptyWithHash(){
        $this->YavPost->validate['not_empty_with1'] = array(
            'notEmptyWith' => array(
                'rule' => array('notEmptyWith', array('not_empty_with3' => '/^[0-9]+$/'))
            ));
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
                'not_empty_with3' => 'abcdefg',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
                'not_empty_with3' => '123456',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );
    }

   /**
     * test_notEmptyWithout
     *
     */
    public function test_notEmptyWithout(){
        $this->YavPost->validate['not_empty_with1'] = array(
            'notEmptyWithout' => array(
                'rule' => array('notEmptyWithout', array('not_empty_with2', 'not_empty_with3'))
            ));
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
                'not_empty_with3' => '空でない',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_notEmptyWithoutHash
     *
     */
    public function test_notEmptyWithoutHash(){
        $this->YavPost->validate['not_empty_with1'] = array(
            'notEmptyWithoutout' => array(
                'rule' => array('notEmptyWithout', array('not_empty_with3' => '/^[0-9]+$/'))
            ));
        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
                'not_empty_with3' => 'abcdef',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );

        $data = array(
            'YavPost' => array(
                'title' => 'タイトル',
                'not_empty_with1' => '',
                'not_empty_with3' => '123456',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('not_empty_with1' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_uniqueEachOther
     *
     */
    public function test_uniqueEachOther(){
        $this->YavPost->validate['value1'] = array(
            'uniqueEachOther' => array(
                'rule' => array('uniqueEachOther', array('value1', 'value2', 'value3'))
            ));
        $data = array(
            'YavPost' => array(
                'value1'  =>  'foo',
                'value2'  =>  'bar',
                'value3'  =>  'baz',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('value1' , $this->YavPost->validationErrors ) );

        $data = array(
            'YavPost' => array(
                'value1'  =>  'foo',
                'value2'  =>  'bar',
                'value3'  =>  '',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('value1' , $this->YavPost->validationErrors ) );

        $data = array(
            'YavPost' => array(
                'value1'  =>  'foo',
                'value2'  =>  'bar',
                'value3'  =>  'bar',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('value1' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_notInList
     *
     * jpn: 指定された値が入っていたらfalse
     */
    public function test_notInList(){
        $this->YavPost->validate['body'] = array(
            'notInList' => array(
                'rule' => array('notInList', array('hoge', 'fuga'))
            ));
        $data = array(
            'YavPost' => array(
                'body'  =>  'hoge',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('body' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_inListRegex
     * description
     *
     * @param
     */
    public function test_inListRegex(){
        $this->YavPost->validate['body'] = array(
            'inListRegex' => array(
                'rule' => array('inListRegex', array('/ho.e/', '/fuga/'))
            ));
        $data = array(
            'YavPost' => array(
                'body'  =>  'hoke',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('body' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_hiraganaOnly
     *
     * jpn: hiraganaOnlyだと全角スペースを許さない
     */
    public function test_hiraganaOnly(){
        $this->YavPost->validate['body'] = array(
            'hiraganaOnly' => array(
                'rule' => array('hiraganaOnly')
            ));
        $data = array(
            'YavPost' => array(
                'body'  =>  'ひらがな　と　すぺーす',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('body' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_hiraganaAndSpace
     *
     * jpn: hiraganaAndSpaceだと全角スペースを許す
     */
    public function test_hiraganaAndSpace(){
        $this->YavPost->validate['body'] = array(
            'hiraganaAndSpace' => array(
                'rule' => array('hiraganaAndSpace')
            ));
        $data = array(
            'YavPost' => array(
                'body'  =>  'ひらがな　と　すぺーす',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('body' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_katakanaOnly
     *
     * jpn: katakanaOnlyだと全角スペースを許さない
     */
    public function test_katakanaOnly(){
        $this->YavPost->validate['body'] = array(
            'katakanaOnly' => array(
                'rule' => array('katakanaOnly')
            ));
        $data = array(
            'YavPost' => array(
                'body'  =>  'カタカナ　ト　スペース',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('body' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_katakanaAndSpace
     *
     * jpn: katakanaAndSpaceだと全角スペースを許す
     */
    public function test_katakanaAndSpace(){
        $this->YavPost->validate['body'] = array(
            'katakanaAndSpace' => array(
                'rule' => array('katakanaAndSpace')
            ));
        $data = array(
            'YavPost' => array(
                'body'  =>  'カタカナ　ト　スペース',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('body' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_compareWith
     *
     */
    public function test_compareWith(){
        $this->YavPost->validate['value1'] = array(
            'compareWith' => array(
                'rule' => array('compareWith', 'value2', 'eq')
            ));
        $data = array(
            'YavPost' => array(
                'value1'  =>  '1',
                'value2'  =>  '1',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('value1' , $this->YavPost->validationErrors ) );

        $this->YavPost->validate['value1'] = array(
            'compareWith' => array(
                'rule' => array('compareWith', 'value2', 'lt')
            ));
        $data = array(
            'YavPost' => array(
                'value1'  =>  '1',
                'value2'  =>  '2',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('value1' , $this->YavPost->validationErrors ) );

        $this->YavPost->validate['value1'] = array(
            'compareWith' => array(
                'rule' => array('compareWith', 'value2', 'gt')
            ));
        $data = array(
            'YavPost' => array(
                'value1'  =>  '1',
                'value2'  =>  '0',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('value1' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_checkSeparateDate
     *
      */
    public function test_checkSeparateDate(){
        $this->YavPost->validate['nen'] = array(
            'birthday' => array(
                'rule' => array('checkSeparateDate', array('year' => 'nen', 'month' => 'tsuki', 'day' => 'hi'))
            ));
        $data = array(
            'YavPost' => array(
                'nen'  =>  '1980',
                'tsuki'  =>  '2',
                'hi'  =>  '31',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertTrue( array_key_exists('nen' , $this->YavPost->validationErrors ) );

        $data = array(
            'YavPost' => array(
                'nen'  =>  '1980',
                'tsuki'  =>  '4',
                'hi'  =>  '4',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('nen' , $this->YavPost->validationErrors ) );
    }

    /**
     * test_cutField
     *
     * jpn: 指定のフィールドの値を保存されないよう無視するために消す
     */
    public function test_cutField(){
        $this->YavPost->validate['title'] = array(
            'cut' => array(
                'rule' => array('cutField')
            ));
        $data = array(
            'YavPost' => array(
                'title' => 'hoge',
                'body'  =>  'hoge',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->assertTrue( array_key_exists('title' , $this->YavPost->data['YavPost'] ) );
        $this->YavPost->validates();
        $this->assertFalse( array_key_exists('title' , $this->YavPost->data['YavPost'] ) );
    }

}
