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
        if ($with === array_values($with)) {
            // array
            foreach ((array)$with as $withField) {
                if (!array_key_exists($withField, $model->data[$model->alias])) {
                    continue;
                }
                if(Validation::notEmpty($model->data[$model->alias][$withField])) {
                    return Validation::notEmpty($value);
                }
            }
        } else {
            // hash
            foreach ((array)$with as $withField => $pattern) {
                if (!array_key_exists($withField, $model->data[$model->alias])) {
                    continue;
                }
                if(preg_match($pattern, $model->data[$model->alias][$withField])) {
                    return Validation::notEmpty($value);
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
        if (empty($without)) {
            return Validation::notEmpty($value);
        }
        if ($without === array_values($without)) {
            // array
            foreach ((array)$without as $withoutField) {
                if (!array_key_exists($withoutField, $model->data[$model->alias])) {
                    continue;
                }
                if(Validation::notEmpty($model->data[$model->alias][$withoutField])) {
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
        return Validation::notEmpty($value);
    }

    /**
     * uniqueEachOther
     * jpn: $fieldと$fieldsに指定されたフィールドの値がお互いにユニークかどうか
     *
     */
    public function uniqueEachOther(Model $model, $field, $fields = array()){
        if (empty($fields)) {
            return false;
        }
        $key = key($field);
        $values = array();
        $values[] = array_shift($field);
        (array)$fields[] = $key;
        foreach ((array)$fields as $k => $f) {
            if (!array_key_exists($f, $model->data[$model->alias])) {
                return false;
            }
            $v = $model->data[$model->alias][$f];
            if (!Validation::notEmpty($v)) {
                unset($fields[$k]);
                continue;
            }
            $values[] = $v;
        }
        return (count(array_unique($fields)) === count(array_unique($values)));
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
     * notInListFromConfigure
     * jpn: Configure::write()で設定されているarray()からnotInListを生成
     *
     */
    public function notInListFromConfigure(Model $model, $field, $listname){
        $value = array_shift($field);
        $list = Configure::read($listname);
        if ($list !== array_values($list)) {
            // jpn: selectのoptionsにそのまま設置するような連想配列を想定
            $list = array_keys($list);
        }
        foreach ($list as $k => $v) {
            $list[$k] = (string)$v;
        }
        return !Validation::inList($value, $list);
    }

    /**
     * formatNumeric
     * jpn: AdditionalValidationPatternsBehavior用にemptyのときはtrue
     *
     */
    public function formatNumeric(Model $model, $field){
        $value = array_shift($field);
        if (!Validation::notEmpty($value)) {
            return true;
        }
        return Validation::numeric($value);
    }

    /**
     * formatAlphaNumber
     * jpn: AdditionalValidationPatternsBehavior用にemptyのときはtrue
     *
     */
    public function formatAlphaNumber(Model $model, $field){
        $value = array_shift($field);
        if (!Validation::notEmpty($value)) {
            return true;
        }
        return Validation::alphaNumber($value);
    }

    /**
     * formatDate
     * jpn: AdditionalValidationPatternsBehavior用にemptyのときはtrue
     *
     */
    public function formatDate(Model $model, $field){
        $value = array_shift($field);
        if (!Validation::notEmpty($value)) {
            return true;
        }
        return Validation::date($value);
    }

    /**
     * formatJson
     * jpn: json形式の文字列かどうか
     *
     */
    public function formatJson(Model $model, $field){
        $value = array_shift($field);
        if (!Validation::notEmpty($value)) {
            return true;
        }
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
     * checkSeparateDate
     * jpn: フィールドが分離している日付設定にcheckdateをかける
     *
     */
    public function checkSeparateDate(Model $model, $field, $dateFields){
        if (empty($dateFields)) {
            return false;
        }
        if (array_keys($dateFields) != array('year', 'month', 'day')) {
            return false;
        }
        if (!array_key_exists($dateFields['year'], $model->data[$model->alias])) {
            return false;
        }
        if (!array_key_exists($dateFields['month'], $model->data[$model->alias])) {
            return false;
        }
        if (!array_key_exists($dateFields['day'], $model->data[$model->alias])) {
            return false;
        }
        return checkdate(
            $model->data[$model->alias][$dateFields['month']],
            $model->data[$model->alias][$dateFields['day']],
            $model->data[$model->alias][$dateFields['year']]
        );
    }

    /**
     * cutField
     * jpn: 指定のフィールドの値を保存されないよう無視するために消す
     *
     */
    public function cutField(Model $model, $field){
        $key = key($field);
        if(empty($key)) {
            return true;
        }
        if (array_key_exists($key, $model->data[$model->alias])) {
            unset($model->data[$model->alias][$key]);
        }
        return true;
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
