<?php

namespace backend\modules\nomenclature\models;

use common\models\Language;
use common\models\Chapter;
use common\models\ChapterTranslation;
use tws\helpers\FileHelper;
use tws\helpers\StringHelper;
use Yii;
use yii\base\InvalidCallException;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\web\UploadedFile;

class ChapterForm extends Chapter
{
    /**
     * @var UploadedFile The article image file.
     */
    public $imageFile;

    /**
     * @var array The multilingual title of the article chapter.
     */
    public $title = [];

    /**
     * @var array The multilingual keywords of the article chapter.
     */
    public $keywords = [];

    /**
     * @var array The multilingual description of the article chapter.
     */
    public $description = [];

    /**
     * @var array The multilingual content of the article chapter.
     */
    public $content = [];


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
            [['title'], 'required', 'when' => function () {
                return empty($this->title[Yii::$app->language]);
            }, 'whenClient' => 'function (attribute, value) {
				return attribute.$form.find("[name*=\"[title][' . Yii::$app->language . ']\"]").val() == "";
			}'],
            [['title', 'keywords', 'description', 'content'], 'each', 'rule' => ['trim']],
            [['keywords', 'description', 'content'], 'each', 'rule' => ['default', 'value' => null]],
            [['imageFile'], 'file', 'extensions' => ['jpeg', 'jpg', 'png', 'gif'], 'maxSize' => Yii::$app->settings->get('maxFileSize'), 'maxFiles' => 1, 'skipOnEmpty' => true],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'parent_id' => Yii::t('label', 'Parent Chapter'),
            'imageFile' => Yii::t('label', 'Image'),
            'title' => Yii::t('label', 'Title'),
            'keywords' => Yii::t('label', 'Keywords'),
            'description' => Yii::t('label', 'Description'),
            'content' => Yii::t('label', 'Content'),
            'rate' => Yii::t('common', 'Rate'),
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

        $this->title = ArrayHelper::map($this->chapterTranslations, 'language_id', 'title');
        $this->keywords = ArrayHelper::map($this->chapterTranslations, 'language_id', 'keywords');
        $this->description = ArrayHelper::map($this->chapterTranslations, 'language_id', 'description');
        $this->content = ArrayHelper::map($this->chapterTranslations, 'language_id', 'content');
    }

    /**
     * Saves the chapter leaf flag.
     *
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    protected function saveChapterLeaf()
    {
        try {
            $chapters  = Chapter::getTree($this->id);
            foreach ($chapters as $chapter) {
                $chapter = Chapter::findOne(['id' => $chapter->id]);
                $children = Chapter::find()
                    ->where([
                        'status' => self::STATUS_ACTIVE,
                        'deleted' => self::NO,
                        'parent_id' => $chapter->id,
                    ])
                    ->all();
                if(count($children) > 0) {
                    $chapter->leaf = 0;
                } else {
                    $chapter->leaf = 1;
                }
                if (!$chapter->save(['leaf'])) {
                    throw new \Exception();
                }
            }

            return true;
        } catch (InvalidCallException $e) {
            $this->addError('', $e->getMessage());
            return false;
        }
    }

    /**
     * Saves the files.
     *
     * @return bool
     */
    protected function saveFiles()
    {
        try {
            if (!($file = UploadedFile::getInstance($this, 'imageFile'))) {
                return true;
            }

            $dirPath = Yii::getAlias("@uploads/chapter/{$this->id}");
            $oldFilePath = "{$dirPath}/{$this->oldAttributes['image']}";
            $fileName = StringHelper::truncate(implode('_', array_filter([
                    Inflector::slug($this->translation->title),
                    Yii::$app->security->generateRandomString(8),
                ])), 255 - (mb_strlen($file->extension) + 1), '') . ".{$file->extension}";
            $filePath = "{$dirPath}/{$fileName}";

            FileHelper::createDirectory($dirPath);
            if (!$file->saveAs($filePath)) {
                throw new \Exception();
            }
            if (!$this->updateAttributes(['image' => $fileName])) {
                throw new \Exception();
            }
            if (is_file($oldFilePath) && $oldFilePath != $filePath) {
                FileHelper::unlink($oldFilePath);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Saves the translations.
     *
     * @return bool
     */
    protected function saveChapterTranslations()
    {
        try {
            foreach (Language::findAllLanguages() as $language) {
                $chapterTranslation = ChapterTranslation::findOne([
                    'chapter_id' => $this->id,
                    'language_id' => $language->language_id,
                ]);
                if (!$chapterTranslation) {
                    $chapterTranslation = new ChapterTranslation();
                    $chapterTranslation->chapter_id = $this->id;
                    $chapterTranslation->language_id = $language->language_id;
                }
                $chapterTranslation->title = static::ensureTranslationValue($this->title, $language->language_id);
                $chapterTranslation->slug = Inflector::slug($chapterTranslation->title);
                $chapterTranslation->keywords = $this->keywords[$language->language_id] ? implode(',', (array) $this->keywords[$language->language_id]) : null;
                $chapterTranslation->description = $this->description[$language->language_id];
                $chapterTranslation->content = $this->content[$language->language_id];

                $this->link('chapterTranslations', $chapterTranslation);
            }
            return true;
        } catch (InvalidCallException $e) {
            $this->addError('', $e->getMessage());
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $transaction = static::getDb()->beginTransaction();
        try {
            if (!parent::save($runValidation, $attributeNames)) {
                throw new \Exception();
            }
            if (!$this->saveChapterLeaf()) {
                throw new \Exception();
            }
            if (!$this->saveFiles()) {
                throw new \Exception();
            }
            if (!$this->saveChapterTranslations()) {
                throw new \Exception();
            }
            $transaction->commit();
            return true;
        } catch(\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}
