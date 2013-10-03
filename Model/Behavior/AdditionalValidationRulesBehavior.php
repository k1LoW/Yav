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
    public function setUp(Model $model, $config = array()){
    }

    /**
     * notEmptyWith
     * jpn: $withに指定されたフィールドに1つでも値が入っていたらnotEmptyを発動
     *      もしくは$withに指定されたフィールドが1つでもそれぞれ指定するパターンにマッチしていたらnotEmpty発動
     *
     */
    public function notEmptyWith(Model $model, $field, $with = array()){
        if (empty($with)) {
            return true;
        }
        $key = key($field);
        $value = array_shift($field);
        $v = new Validation();
        if ($with === array_values($with)) {
            // array
            foreach ((array)$with as $withField) {
                if (!array_key_exists($withField, $model->data[$model->alias])) {
                    continue;
                }
                if($v->notEmpty($model->data[$model->alias][$withField])) {
                    return $v->notEmpty($value);
                }
            }
        } else {
            // hash
            foreach ((array)$with as $withField => $pattern) {
                if (!array_key_exists($withField, $model->data[$model->alias])) {
                    continue;
                }
                if(preg_match($pattern, $model->data[$model->alias][$withField])) {
                    return $v->notEmpty($value);
                }
            }
        }
        return true;
    }

    /**
     * notEmptyWithout
     * jpn: $withoutに指定されたフィールドに1つも値が入っていなかったらnotEmptyを発動
     *      もしくは$withoutに指定されたフィールドが1つでもそれぞれ指定するパターンにマッチしていなかったらnotEmpty発動
     *
     */
    public function notEmptyWithout(Model $model, $field, $without = array()){
        $key = key($field);
        $value = array_shift($field);
        $v = new Validation();
        if (empty($without)) {
            return $v->notEmpty($value);
        }
        if ($without === array_values($without)) {
            // array
            foreach ((array)$without as $withoutField) {
                if (!array_key_exists($withoutField, $model->data[$model->alias])) {
                    continue;
                }
                if($v->notEmpty($model->data[$model->alias][$withoutField])) {
                    return true;
                }
            }
        } else {
            // hash
            foreach ((array)$without as $withoutField => $pattern) {
                if (!array_key_exists($withoutField, $model->data[$model->alias])) {
                    continue;
                }
                if(preg_match($pattern, $model->data[$model->alias][$withoutField])) {
                    return true;
                }
            }
        }
        return $v->notEmpty($value);
    }

    /**
     * isUniqueTogether
     * jpn: $fieldsに指定されたフィールドの値も含めて$fieldの値がユニークかどうかをチェックする
     *
     */
    public function isUniqueTogether(Model $model, $field, $fields = array()){
        if (empty($fields)) {
            return false;
        }
        $key = key($field);
        $value = array_shift($field);
        $conditions = array(
            "{$model->alias}.{$key}" => $value,
        );
        foreach ((array)$fields as $f) {
            if (!array_key_exists($f, $model->data[$model->alias])) {
                return false;
            }
            $v = $model->data[$model->alias][$f];
            $conditions["{$model->alias}.{$f}"] = $v;
        }
        return !$model->find('count', array('conditions' => $conditions, 'recursive' => -1));
    }

    /**
     * hiraganaAndSpace
     * jpn: 全角ひらがなと全角スペースのみ
     *
     */
    public function hiraganaAndSpace(Model $model, $field){
        $key = key($field);
        $value = array_shift($field);
        $field = array($key => str_replace('　','', $value));
        return $model->hiraganaOnly($field);
    }

    /**
     * katakanaAndSpace
     * jpn: 全角カタカナと全角スペースのみ
     *
     */
    public function katakanaAndSpace(Model $model, $field){
        $key = key($field);
        $value = array_shift($field);
        $field = array($key => str_replace('　','', $value));
        return $model->katakanaOnly($field);
    }

    /**
     * recordExists
     * jpn: belongsToなどで指定しているModelのレコード側が存在していること
     *
     * @param $arg
     */
    public function recordExists(Model $model, $field, $belongsToModelName){
        $key = key($field);
        $value = array_shift($field);
        if (is_array($belongsToModelName)) {
            $belongsToModelName = Inflector::classify(preg_replace('/_id$/', '', $key));
        }
        $belongsToModel = ClassRegistry::init($belongsToModelName);
        return $belongsToModel->exists($value);
    }

    /**
     * parentModelExists
     *
     */
    public function parentModelExists(Model $model, $field, $belongsToModelName){
        return $this->recordExists($model, $field, $belongsToModelName);
    }

    /**
     * notInList
     * jpn: $listに指定されている値を保持していたらfalse
     *
     */
    public function notInList(Model $model, $field, $list){
        $value = array_shift($field);
        return !Validation::inList($value, $list);
    }

    /**
     * inListRegex
     * jpn: $listRegexに指定されている正規表現にマッチしたらtrue
     *
     */
    public function inListRegex(Model $model, $field, $listRegex){
        $value = array_shift($field);
        foreach ($listRegex as $regex) {
            if (preg_match($regex, $value)) {
                return true;
            }
        }
        return false;
    }

    /**
     * notInListRegex
     * jpn: $listRegexに指定されている正規表現にマッチしたらfalse
     *
     */
    public function notInListRegex(Model $model, $field, $listRegex){
        return !$this->inListRegex($model, $field, $listRegex);
    }

    /**
     * inListFromConfigure
     * jpn: Configure::write()で設定されているarray()からinListを生成
     *
     */
    public function inListFromConfigure(Model $model, $field, $listname){
        $value = array_shift($field);
        $list = Configure::read($listname);
        if ($list !== array_values($list)) {
            // jpn: selectのoptionsにそのまま設置するような連想配列を想定
            $list = array_keys($list);
        }
        foreach ($list as $k => $v) {
            $list[$k] = (string)$v;
        }
        return Validation::inList($value, $list);
    }

    /**
     * formatJson
     * jpn: json形式の文字列かどうか
     *
     */
    public function formatJson(Model $model, $field){
        $value = array_shift($field);
        $result = json_decode($value);
        if ($result === null) {
            return false;
        }
        return true;
    }

    /**
     * equalToField
     * jpn: 登録されているデータを編集するにあたって指定フィールド$currentDataFieldと同じ値かどうが(今のパスワードなどに使用)
     *
     */
    public function equalToField(Model $model, $field, $currentDataField){
        $value = array_shift($field);
        if (empty($model->data[$model->alias][$model->primaryKey])) {
            return false;
        }
        $result = $model->find('count', array(
                'conditions' => array(
                    "{$model->alias}.{$model->primaryKey}" => $model->data[$model->alias][$model->primaryKey],
                    "{$model->alias}.{$currentDataField}" => $value,
                ),
            ));
        return ($result === 1);
    }

    /**
     * formatFuzzyEmail
     * jpn: 日本のキャリアの微妙なメールアドレスも通す
     *
     */
    public function formatFuzzyEmail(Model $model, $field){
        $value = array_shift($field);
        return preg_match('/^[-+.\w]+@[-a-z0-9]+(\.[-a-z0-9]+)*\.[a-z]{2,6}$/i', $value);
    }

    /**
     * compareWith
     * jpn: $fieldと$withFieldを$operatorで比較する
     *
     */
    public function compareWith(Model $model, $field, $withField, $operator){
        if (!in_array($operator, array('eq', 'lt', 'gt', 'le', 'ge'))) {
            return false;
        }
        if (!isset($model->data[$model->alias][$withField])) {
            return false;
        }
        $value = array_shift($field);
        $withValue = $model->data[$model->alias][$withField];
        switch($operator) {
            case 'eq':
                return ($value == $withValue);
                break;
            case 'lt':
                return ($value < $withValue);
                break;
            case 'gt':
                return ($value > $withValue);
                break;
            case 'le':
                return ($value <= $withValue);
                break;
            case 'ge':
                return ($value >= $withValue);
                break;
        }
        return false;
    }

    /**
     * allAllow
     * jpn: validation_patternでrequiredを作成するために使用
     *
     */
    public function allAllow(Model $model, $field){
        return true;
    }

    /**
     * allDeny
     *
     */
    public function allDeny(Model $model, $field){
        return false;
    }
}