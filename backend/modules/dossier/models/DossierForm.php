<?php

namespace backend\modules\dossier\models;

use common\models\Dossier;
use Yii;
use yii\base\InvalidCallException;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class DossierForm extends Dossier
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();
		$this->status = static::STATUS_ACTIVE;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return ArrayHelper::merge(parent::rules(), [

		]);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		return Model::scenarios();
	}

	/**
	 * @inheritdoc
	 */
	public function afterFind()
	{
		parent::afterFind();
	}

//	/**
//	 * Saves the Template translations.
//	 *
//	 * @return bool
//	 */
//	protected function saveTemplateTranslations()
//	{
//		try {
//			foreach (\common\models\Language::findAllLanguages() as $language) {
//				$templateTranslation = TemplateTranslation::findOne([
//					'template_id' => $this->id,
//					'language_id' => $language->language_id,
//				]);
//				if (!$templateTranslation) {
//					$templateTranslation = new TemplateTranslation();
//					$templateTranslation->template_id = $this->id;
//					$templateTranslation->language_id = $language->language_id;
//				}
//				$templateTranslation->name = static::ensureTranslationValue($this->name, $language->language_id);
//				$templateTranslation->subject = $this->subject[$language->language_id];
//				$templateTranslation->content = $this->content[$language->language_id];
//
//				$this->link('templateTranslations', $templateTranslation);
//			}
//			return true;
//		} catch (InvalidCallException $e) {
//			return false;
//		}
//	}

	/**
	 * @inheritdoc
	 */
	public function save($runValidation = true, $attributeNames = null)
	{
		$dbTransaction = static::getDb()->beginTransaction();
		try {
			if (!parent::save($runValidation, $attributeNames)) {
				throw new \Exception();
			}
//			if (!$this->saveTemplateTranslations()) {
//				throw new \Exception();
//			}
			$dbTransaction->commit();
			return $this;
		} catch(\Exception $e) {
			$dbTransaction->rollBack();
			return false;
		}
	}
}
