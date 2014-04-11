<?php

class YavPost extends CakeTestModel {

    public $name = 'YavPost';
    public $actsAs = array(
        'Cakeplus.AddValidationRule',
        'Yav.ForceValidationManage'
    );

    public $validate = array();
}

class ForceValidationManageTest extends CakeTestCase {

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
     * test_ForceValidationManage
     *
     * jpn: $validateを設定していないフィールドの値は強制的に無視する
     */
    public function test_ForceValidationManage(){
        $this->YavPost->validate['title'] = array(
            'notempty' => array(
                'rule' => array('notEmpty')
            ));
        $data = array(
            'YavPost' => array(
                'title' => 'hoge',
                'body'  =>  'hoge',
            ),
        );
        $this->assertIdentical( $this->YavPost->create( $data ) , $data);
        $this->assertTrue( array_key_exists('body' , $this->YavPost->data['YavPost'] ) );
        $this->YavPost->save();
        $id = $this->YavPost->getLastInsertID();
        $result = $this->YavPost->findById($id);
        $this->assertTrue( empty($this->YavPost->data['YavPost']['body'] ) );
    }

}
