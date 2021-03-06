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
                'rule' => array('allAllow'),
                'required' => true,
                'last' => true,
            ),
        ),
        'notempty' => array(
            'notEmpty' => array(
                'rule' => array('notEmpty'),
                'required' => false,
                'last' => true
            ),
        ),
        'empty' => array(
            'empty' => array(
                'rule' => array('allDeny'),
                'required' => false,
                'allowEmpty' => true, // required
                'last' => true
            ),
        ),
        'ignore' => array(
            'empty' => array(
                'rule' => array('allDeny'),
                'required' => false,
                'last' => true
            ),
        ),
        'cut' => array(
            'cutField' => array(
                'rule' => array('cutField'),
                'required' => false,
                'last' => true
            ),
        ),
        'checkbox_check' => array(
            'checkboxCheck' => array(
                'rule' => '/^[^0]$/',
                'required' => false,
                'last' => true
            ),
        ),
        'date' => array(
            'date' => array(
                'rule' => array('formatDate'),
                'required' => false,
                'last' => true
            ),
        ),
        'alpha_numeric' => array(
            'alphaNumeric' => array(
                'rule' => array('formatAlphaNumeric'),
                'required' => false,
                'last' => true,
            ),
        ),
        // jpn: 数値チェック用
        'numeric' => array(
            'numeric' => array(
                'rule' => array('formatNumeric'),
                'required' => false,
                'last' => true,
            ),
        ),
        'unique' => array(
            'isUnique' => array(
                'rule' => array('isUnique'),
                'allowEmpty' => true,
                'required' => false,
                'last' => true,
            )
        ),
        'fuzzy_email' => array(
            'email' => array(
                'rule' => array('formatFuzzyEmail'),
                'allowEmpty' => true,
                'last' => true,
            )
        ),
        'email_confirm' => array(
            'compare2fieldsEmail' => array(
                'rule' => array('compare2fields', 'email'),
                'allowEmpty' => true,
                'last' => true,
            )
        ),
        'password_confirm' => array(
            'compare2fieldsPassword' => array(
                'rule' => array('compare2fields', 'password'),
                'allowEmpty' => true,
                'last' => true,
            )
        ),
        'password_current' => array(
            'passwordCurrent' => array(
                'rule' => array('equalToField', 'password'),
                'allowEmpty' => true,
                'last' => true,
            )
        ),
        'json' => array(
            'formatJson' => array(
                'rule' => array('formatJson'),
                'required' => false,
                'last' => true,
            )
        ),
        // jpn:
        'zenkaku_only' => array(
            'zenkakuOnly' => array(
                'rule' => array('zenkakuOnly'),
                'allowEmpty' => true,
                'last' => true,
            ),
        ),
        'hiragana_only' => array(
            'hiraganaOnly' => array(
                'rule' => array('hiraganaOnly'),
                'allowEmpty' => true,
                'last' => true,
            ),
        ),
        'hiragana_and_space' => array(
            'hiraganaAndSpace' => array(
                'rule' => array('hiraganaAndSpace'),
                'allowEmpty' => true,
                'last' => true,
            ),
        ),
        'katakana_only' => array(
            'katakanaOnly' => array(
                'rule' => array('katakanaOnly'),
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
    public function setUp(Model $model, $config = array()){
        $this->mergeValidationPatterns($model);
    }

    /**
     * getValidationMessages
     *
     */
    public function getValidationMessages(){
        return array(
            'required'               => __d('yav', 'Validation Error: required'),
            'notEmpty'               => __d('yav', 'Validation Error: notEmpty'),
            'empty'                  => __d('yav', 'Validation Error: empty'),
            'checkboxCheck'          => __d('yav', 'Validation Error: checkboxCheck'),
            'numeric'                => __d('yav', 'Validation Error: numeric'),
            'isUnique'               => __d('yav', 'Validation Error: isUnique'),
            'date'                   => __d('yav', 'Validation Error: date'),
            'email'                  => __d('yav', 'Validation Error: email'),
            'compare2fieldsEmail'    => __d('yav', 'Validation Error: compare2fieldsEmail'),
            'compare2fieldsPassword' => __d('yav', 'Validation Error: compare2fieldsPassword'),
            'passwordCurrent'        => __d('yav', 'Validation Error: passwordCurrent'),
            'zenkakuOnly'            => __d('yav', 'Validation Error: zenkakuOnly'),
            'katakanaOnly'           => __d('yav', 'Validation Error: katakanaOnly'),
            'katakanaAndSpace'       => __d('yav', 'Validation Error: katakanaAndSpace'),
            'notEmptyFile'           => __d('yav', 'Validation Error: notEmptyFile'),
            'checkExtension'         => __d('yav', 'Validation Error: checkExtension'),
            'checkFileSize'          => __d('yav', 'Validation Error: checkFileSize'),
            'checkboxCheck'          => __d('yav', 'Validation Error: checkboxCheck'),
        );
    }

    /**
     * mergeValidationPatterns
     *
     */
    private function mergeValidationPatterns(Model $model){
        if (empty($model->validation_patterns)) {
            $model->validation_patterns = array();
        }
        foreach ($this->validationPatterns as $key => $patterns) {
            if (empty($model->validation_patterns[$key])) {
                $model->validation_patterns[$key] = $this->validationPatterns[$key];
            }
        }
    }

}
