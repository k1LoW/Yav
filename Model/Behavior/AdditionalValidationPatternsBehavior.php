<?php
/**
 * AdditionalValidationPatternsBehavior
 *
 * jpn: Cakeplus用のカスタムバリデーションパターン
 */
class AdditionalValidationPatternsBehavior extends ModelBehavior {

    public $validationPatterns = array(
                                       'required' => array(
                                                           'required' => array(
                                                                               'rule' => '/.*/',
                                                                               'required' => true,
                                                                               'last' => true,
                                                                               ),
                                                            ),
                                       'notempty' => array(
                                                           'notEmpty' => array('rule' => array('notEmpty'),
                                                                               'required' => false,
                                                                               'last' => true
                                                                               ),
                                                           ),
                                       // jpn: 数値チェック用
                                       'numeric' => array(
                                                           'numeric' => array(
                                                                              'rule' => '/^[0-9]+$/',
                                                                              'last' => true,
                                                                              ),
                                                            ),

                                       // jpn:
                                       'zenkaku_only' => array(
                                                              'zenkakuOnly' => array(
                                                                                 'rule' => array('zenkakuOnly'),
                                                                                 'allowEmpty' => true,
                                                                                 'last' => true,
                                                                                 ),
                                                              ),
                                       'katakana_only' => array(
                                                              'katakanaOnly' => array(
                                                                                 'rule' => array('zenkakuOnly'),
                                                                                 'allowEmpty' => true,
                                                                                 'last' => true,
                                                                                 ),
                                                              ),
                                       'katakana_and_space' => array(
                                                              'katakanaAndSpace' => array(
                                                                                 'rule' => array('katakanaAndSpace'),
                                                                                 'allowEmpty' => true,
                                                                                 'last' => true,
                                                                                 ),
                                                              ),
                                       );

    /**
     * setUp
     *
     */
    public function setUp(Model $model){
        $this->mergeValidationPatterns($model);
    }

    /**
     * mergeValidationPatterns
     *
     */
    private function mergeValidationPatterns(Model $model){
        $model->validation_patterns = Hash::merge($this->validationPatterns, $model->validation_patterns);
    }

}