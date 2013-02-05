<?php
/**
 * AdditionalValidationRulesBehavior
 *
 * jpn: 追加のバリデーションルール
 */
class AdditionalValidationRulesBehavior extends ModelBehavior {

    /**
     * setUp
     *
     * @param Model $model
     */
    public function setUp(Model $model){
    }

    /**
     * notEmptyWith
     * jpn: $withに指定されたフィールドに1つでも値が入っていたらnotEmptyを発動
     *
     */
    public function notEmptyWith(Model $model, $field, $with = array()){
        if (empty($with)) {
            return true;
        }
        $key = key($field);
        $value = array_shift($field);
        $v = new Validation();
        foreach ((array)$with as $withField) {
            if (!array_key_exists($withField, $model->data[$model->alias])) {
                continue;
            }
            if($v->notEmpty($model->data[$model->alias][$withField])) {
                return $v->notEmpty($value);
            }
        }
        return true;
    }

    /**
     * katakanaAndSpace
     * jpn: 全角カタカナと全角スペースのみ
     * @param Model $model, $field
     */
    public function katakanaAndSpace(Model $model, $field){
        $key = key($field);
        $value = array_shift($field);
        $field = array($key => str_replace('　','', $value));
        return $model->katakanaOnly($field);
    }

    /**
     * inListFromConfigure
     * jpn: Configureのarray()からinListを生成
     *
     * @param Model $model, $field
     */
    public function inListFromConfigure(Model $model, $field, $listname){
        $value = array_shift($field);
        $list = Configure::read($listname);
        return Validation::inList($value, $list);
    }
}