<?php

namespace backend\modules\setting\modules\language\controllers;

use backend\controllers\MainController;
use backend\modules\setting\modules\language\assets\LanguageTranslateAsset;
use backend\modules\setting\modules\language\models\LanguageSearch;
use backend\modules\setting\modules\language\models\LanguageTranslationSearch;
use common\models\Language;
use common\models\LanguageSource;
use common\models\LanguageTranslate;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class LanguageController extends MainController
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'rules' => [
					[
						'allow' => true,
						'actions' => ['index', 'dt-languages'],
						'roles' => ['viewLanguageSetting'],
					],
					[
						'allow' => true,
						'actions' => ['translate', 'dt-language-translations'],
						'roles' => ['translateIntoLanguageSetting'],
					],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'dt-languages' => LanguageSearch::class,
			'dt-language-translations' => LanguageTranslationSearch::class,
		];
	}

	/**
	 * Lists all Language models.
	 *
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionIndex()
	{
		if (Yii::$app->request->isAjax && Yii::$app->request->post('dt')) {
			return $this->updateDtColumn();
		}

		return $this->render('index');
	}

	/**
	 * Lists all LanguageSource models.
	 *
	 * @param string $id
	 * @return mixed
	 */
	public function actionTranslate($id)
	{
		LanguageTranslateAsset::register($this->view);

		if (Yii::$app->request->isAjax && Yii::$app->request->post('dt')) {
			return $this->saveTranslation($id);
		}

		return $this->render('translate', [
			'categories' => ArrayHelper::map(LanguageSource::find()->all(), 'category', 'category'),
		]);
	}

	/**
	 * Finds the Language model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 * @return Language the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Language::findOne($id)) !== null) {
			return $model;
		}

		throw new NotFoundHttpException(Yii::t('common', 'The requested page does not exist.'));
	}

	/**
	 * Updates DataTable column.
	 *
	 * @return \yii\web\Response
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function updateDtColumn()
	{
		$params = Yii::$app->request->post();
		$result = false;

		$model = $this->findModel($params['key']);

		Yii::$app->eventLog
			->setData([
				'operation' => (Yii::$app->eventLog)::ACTION_UPDATE,
			])
			->beginRecord($model);

		if ($model->hasAttribute($params['attribute'])) {
			$model->{$params['attribute']} = $params['value'];

			if ($result = $model->save(true, [$params['attribute']])) {
				Yii::$app->eventLog->endRecord();
			}
		}

		Yii::$app->trigger('invalidate.cache', new \tws\caching\CacheEvent(['key' => 'findAllLanguages']));

		return $this->asJson([
			'success' => (bool) $result,
			'message' => $result ?
				Yii::t('common', 'Record has been updated.') :
				Yii::t('common', 'Cannot update the record.'),
		]);
	}

	/**
	 * Updates translation column.
	 *
	 * @param string $language_id
	 * @return \yii\web\Response
	 */
	protected function saveTranslation($language_id)
	{
		$params = Yii::$app->request->post();

		$model = LanguageTranslate::findOne([
			'id' => $params['key'],
			'language' => $language_id,
		]);
		if (!$model) {
			$model = new LanguageTranslate();
			$model->id = $params['key'];
			$model->language = $language_id;
		}

		$result = false;
		if ($model->hasAttribute($params['attribute'])) {
			$model->{$params['attribute']} = $params['value'];
			$result = $model->save();
			Yii::$app->trigger('invalidate.cache');
		}

		return $this->asJson([
			'success' => (bool) $result,
			'message' => $result ?
				Yii::t('common', 'Record has been updated.') :
				Yii::t('common', 'Cannot update the record.'),
		]);
	}
}
