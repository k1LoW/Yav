<?php
/**
 * ForceValidationManageBehavior
 *
 * jpn: $model->validateを設定していないフィールドの値は強制的に無視する
 */
class ForceValidationManageBehavior extends ModelBehavior {

    /**
     * beforeValidate
     *
     */
    public function beforeValidate(Model $model, $options = array()) {
        foreach ($model->data[$model->alias] as $fieldName => $value) {
            if (!array_key_exists($fieldName, $model->validate)) {
                unset($model->data[$model->alias][$fieldName]);
            }
        }
        return true;
    }
}
