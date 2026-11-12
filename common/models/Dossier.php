<?php

namespace common\models;

use DateTime;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%dossier}}".
 *
 * @property int $id
 * @property string $number
 * @property string $old_number
 * @property string $procedural_stage
 * @property string $object
 * @property string $institution
 * @property string $department
 * @property string $category
 * @property string $registration_date
 * @property string $modification_date
 * @property string $parts
 * @property string $meetings
 * @property string $attack_ways
 * @property string $quotes_through_advertising
 * @property string $sent_at
 * @property int $created_by
 * @property int $updated_by
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 * @property int $deleted
 *
 *
 * @property User $creator
 * @property User $updater
 */
class Dossier extends CommonActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%dossier}}';
    }

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => (new DateTime)->format('Y-m-d H:i:s'),
            ],
            [
                'class' => BlameableBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['registration_date', 'modification_date', 'sent_at', 'created_at', 'updated_at'], 'safe'],
            [['parts', 'meetings', 'attack_ways', 'quotes_through_advertising'], 'string'],
            [['created_by', 'updated_by', 'status', 'deleted'], 'integer'],
            [['status'], 'required'],
            [['number', 'old_number', 'procedural_stage', 'object', 'institution', 'department', 'category'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('label', 'ID'),
            'number' => Yii::t('label', 'Number'),
            'old_number' => Yii::t('label', 'Old Number'),
            'procedural_stage' => Yii::t('label', 'Procedural Stage'),
            'object' => Yii::t('label', 'Object'),
            'institution' => Yii::t('label', 'Institution'),
            'department' => Yii::t('label', 'Department'),
            'category' => Yii::t('label', 'Category'),
            'registration_date' => Yii::t('label', 'Registration Date'),
            'modification_date' => Yii::t('label', 'Modification Date'),
            'parts' => Yii::t('label', 'Parts'),
            'meetings' => Yii::t('label', 'Meetings'),
            'attack_ways' => Yii::t('label', 'Attack Ways'),
            'quotes_through_advertising' => Yii::t('label', 'Quotes Through Advertising'),
            'sent_at' => Yii::t('label', 'Sent At'),
            'created_by' => Yii::t('label', 'Created By'),
            'updated_by' => Yii::t('label', 'Updated By'),
            'created_at' => Yii::t('label', 'Created At'),
            'updated_at' => Yii::t('label', 'Updated At'),
            'status' => Yii::t('label', 'Status'),
            'deleted' => Yii::t('label', 'Deleted'),
        ];
    }

    public function getCreator()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery|CommonActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * Gets the values for the model available short codes.
     *
     * @param bool $html
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function getShortCodeValues($html = false)
    {
		$regex = '/(?#! splitCamelCase Rev:20140412)
						# Split camelCase "words". Two global alternatives. Either g1of2:
						  (?<=[a-z])      # Position is after a lowercase,
						  (?=[A-Z])       # and before an uppercase letter.
						| (?<=[A-Z])      # Or g2of2; Position is after uppercase,
						  (?=[A-Z][a-z])  # and before upper-then-lower case.
						/x';
		$split = preg_split($regex, $this->institution);
		$institution = implode(' ', $split);
    	$shortCodes = [
            '{{SENT_AT}}' => $this->sent_at,
            '{{NUMBER}}' => $this->number,
            '{{OLD_NUMBER}}' => $this->old_number,
            '{{PROCEDURAL_STAGE}}' => $this->procedural_stage,
            '{{OBJECT}}' => $this->object,
            '{{INSTITUTION}}' => $institution,
            '{{DEPARTMENT}}' => $this->department,
            '{{CATEGORY}}' => $this->category,
            '{{REGISTRATION_DATE}}' => $this->registration_date,
            '{{MODIFICATION_DATE}}' => $this->modification_date,
        ];
		$shortCodes['{{PARTS}}'] = Yii::$app->controller->renderPartial('@backend/modules/dossier/views/dossier/_parts', [
			'model' => $this,
		]);
		$shortCodes['{{MEETINGS}}'] = Yii::$app->controller->renderPartial('@backend/modules/dossier/views/dossier/_meetings', [
			'model' => $this,
		]);
		$shortCodes['{{ATTACK_WAYS}}'] = Yii::$app->controller->renderPartial('@backend/modules/dossier/views/dossier/_attack-ways', [
			'model' => $this,
		]);
		$shortCodes['{{QUOTES_THROUGH_ADVERTISING}}'] = Yii::$app->controller->renderPartial('@backend/modules/dossier/views/dossier/_quotes_through_advertising', [
			'model' => $this,
		]);

        return $shortCodes;
    }

}
