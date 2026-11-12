<?php

namespace backend\modules\nomenclature\models;

use common\models\Template;
use common\models\TemplateTranslation;
use Yii;
use yii\base\InvalidCallException;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class EmailTemplateForm extends Template
{
	/**
	 * @var array The multilingual name of the Template.
	 */
	public $name;

	/**
	 * @var array The multilingual subject of the Template.
	 */
	public $subject;

	/**
	 * @var array The multilingual content of the Template.
	 */
	public $content;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = static::TYPE_EMAIL;
		$this->status = static::STATUS_ACTIVE;
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return ArrayHelper::merge(parent::rules(), [
			[['variant'], 'required'],
			[['name'], 'required', 'when' => function ($model) {
				return empty($model->name[Yii::$app->language]);
			}, 'whenClient' => 'function (attribute, value) {
        return attribute.$form.find("[name*=\"[name][' . Yii::$app->language . ']\"]").val() == "";
    	}'],
			[['subject'], 'required', 'when' => function ($model) {
				return empty($model->name[Yii::$app->language]);
			}, 'whenClient' => 'function (attribute, value) {
        return attribute.$form.find("[name*=\"[subject][' . Yii::$app->language . ']\"]").val() == "";
    	}'],
			[['content'], 'required', 'when' => function ($model) {
				return empty($model->name[Yii::$app->language]);
			}, 'whenClient' => 'function (attribute, value) {
        return attribute.$form.find("[name*=\"[content][' . Yii::$app->language . ']\"]").val() == "";
    	}'],
			[['name', 'subject', 'content'], 'each', 'rule' => ['trim']],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
			'name' => Yii::t('label', 'Name'),
			'subject' => Yii::t('label', 'Subject'),
			'content' => Yii::t('label', 'Content'),
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

		$this->name = ArrayHelper::map($this->templateTranslations, 'language_id', 'name');
		$this->subject = ArrayHelper::map($this->templateTranslations, 'language_id', 'subject');
		$this->content = ArrayHelper::map($this->templateTranslations, 'language_id', 'content');
	}

	/**
	 * Saves the Template translations.
	 *
	 * @return bool
	 */
	protected function saveTemplateTranslations()
	{
		try {
			foreach (\common\models\Language::findAllLanguages() as $language) {
				$templateTranslation = TemplateTranslation::findOne([
					'template_id' => $this->id,
					'language_id' => $language->language_id,
				]);
				if (!$templateTranslation) {
					$templateTranslation = new TemplateTranslation();
					$templateTranslation->template_id = $this->id;
					$templateTranslation->language_id = $language->language_id;
				}
				$templateTranslation->name = static::ensureTranslationValue($this->name, $language->language_id);
				$templateTranslation->subject = $this->subject[$language->language_id];
				$templateTranslation->content = $this->content[$language->language_id];

				$this->link('templateTranslations', $templateTranslation);
			}
			return true;
		} catch (InvalidCallException $e) {
			return false;
		}
	}

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
			if (!$this->saveTemplateTranslations()) {
				throw new \Exception();
			}
			$dbTransaction->commit();
			return $this;
		} catch(\Exception $e) {
			$dbTransaction->rollBack();
			return false;
		}
	}

	/**
	 * Gets the short code items.
	 *
	 * @param null|int $variant
	 * @return array
	 */
	public static function getShortCodeItems($variant = null)
	{
		$shortCodeItems = [
			'{{APP_NAME}}' => Yii::t('label', 'App Name'),
			'{{APP_URL}}' => Yii::t('label', 'App URL'),
			'{{APP_LOGO_URL}}' => Yii::t('label', 'App Logo URL'),
			'{{APP_LOGO_ALT_URL}}' => Yii::t('label', 'App Logo Alternative URL'),
		];

		switch ($variant) {
			case static::EMAIL_VARIANT_PASSWORD_RESET:
				$shortCodeItems = array_merge($shortCodeItems, [
					'{{NUMBER}}' => Yii::t('label', 'Number'),
					'{{OLD_NUMBER}}' => Yii::t('label', 'Old Number'),
					'{{PROCEDURAL_STAGE}}' => Yii::t('label', 'Procedural Stage'),
					'{{OBJECT}}' => Yii::t('label', 'Object'),
					'{{INSTITUTION}}' => Yii::t('label', 'Institution'),
					'{{DEPARTMENT}}' => Yii::t('label', 'Department'),
					'{{CATEGORY}}' => Yii::t('label', 'Category'),
					'{{REGISTRATION_DATE}}' => Yii::t('label', 'Registration Date'),
					'{{MODIFICATION_DATE}}' => Yii::t('label', 'Modification Date'),
					'{{PARTS}}' => Yii::t('label', 'Parts'),
					'{{MEETINGS}}' => Yii::t('label', 'Meetings'),
					'{{ATTACK_WAYS}}' => Yii::t('label', 'Attack Ways'),
					'{{QUOTES_THROUGH_ADVERTISING}}' => Yii::t('label', 'Quotes Through Advertising'),
					'{{SENT_AT}}' => Yii::t('label', 'Sent At'),
				]);
				break;
            case static::EMAIL_VARIANT_REPORT_DOSSIER:
                $shortCodeItems = array_merge($shortCodeItems, [
                    '{{NUMBER}}' => Yii::t('label', 'Number'),
                    '{{OLD_NUMBER}}' => Yii::t('label', 'Old Number'),
                    '{{PROCEDURAL_STAGE}}' => Yii::t('label', 'Procedural Stage'),
                    '{{OBJECT}}' => Yii::t('label', 'Object'),
                    '{{INSTITUTION}}' => Yii::t('label', 'Institution'),
                    '{{DEPARTMENT}}' => Yii::t('label', 'Department'),
                    '{{CATEGORY}}' => Yii::t('label', 'Category'),
                    '{{REGISTRATION_DATE}}' => Yii::t('label', 'Registration Date'),
                    '{{MODIFICATION_DATE}}' => Yii::t('label', 'Modification Date'),
                    '{{PARTS}}' => Yii::t('label', 'Parts'),
                    '{{MEETINGS}}' => Yii::t('label', 'Meetings'),
                    '{{ATTACK_WAYS}}' => Yii::t('label', 'Attack Ways'),
                    '{{QUOTES_THROUGH_ADVERTISING}}' => Yii::t('label', 'Quotes Through Advertising'),
                    '{{SENT_AT}}' => Yii::t('label', 'Sent At'),
                ]);
                break;
			default:
				break;
		}

		return $shortCodeItems;
	}
}
