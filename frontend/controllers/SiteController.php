<?php

namespace frontend\controllers;

use common\models\ActivateAccountForm;
use common\models\AssumeIdentityForm;
use common\models\LoginForm;
use common\models\Page;
use common\models\ResetPasswordForm;
use common\models\ResetPasswordRequestForm;
use common\models\SignupForm;
use common\models\User;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use tws\helpers\Url;
use tws\widgets\sitemap\Sitemap;
use Yii;
use frontend\models\ContactForm;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends MainController
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::class,
				'only' => ['logout'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
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
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex()
	{
		$this->layout = 'home';

		return $this->render('index');
	}

	/**
	 * Logs in a user.
	 *
	 * @return mixed
	 */
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			Yii::$app->session->setFlash('info', Yii::t('common', 'You are already authenticated.'));
			return $this->goHome();
		}

		$model = new LoginForm();

		if ($model->load(Yii::$app->request->post())) {
			if ($model->login()) {
				return $this->redirect('/admin');
			}
			Yii::$app->session->setFlash('error', Yii::t('common', 'The provided credentials are invalid.'));
		}

		return $this->render('login', [
			'model' => $model,
		]);
	}

	/**
	 * Login as user.
	 *
	 * @return mixed
	 */
	public function actionAssumeIdentity($id)
	{
		if (!Yii::$app->user->isGuest || empty($id)) {
			return $this->goHome();
		}

		$model = new AssumeIdentityForm();
		$model->id = Yii::$app->security->unmaskToken($id);

		if ($model->login()) {
			return $this->goBack(['/site/index']);
		} else {
			$message = Yii::t('common', 'The provided credentials are invalid.');
			if ($user = User::findOne(['id' => Yii::$app->security->unmaskToken($id), 'deleted' => User::NO])) {
				if ($user->status == User::STATUS_INACTIVE && Yii::$app->settings->get('userAccountActivation') == User::ACCOUNT_ACTIVATION_AUTOMATIC) {
					$message = Yii::t('common', 'Your account is inactive. Please make sure you have activated your account by clicking the link in the activation email you received.');
				}
			}
			Yii::$app->session->setFlash('error', $message);
		}

		return $this->render('assume-identity', [
			'model' => $model,
		]);
	}

	/**
	 * Logs out the current user.
	 *
	 * @return mixed
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Activates user account.
	 *
	 * @param string|int|null $token
	 * @return mixed
	 */
	public function actionActivate($token = null)
	{
		if (!Yii::$app->user->isGuest) {
			Yii::$app->session->setFlash('info', Yii::t('common', 'Your account is already active.'));
			return $this->goHome();
		}

		$model = new ActivateAccountForm();
		$model->token = $token;

		if (($model->load(Yii::$app->request->post()) || !empty($token)) && $model->validate()) {
			if ($model->activate()) {
				Yii::$app->trigger('user.afterAccountActivation', new \yii\base\Event(['sender' => $model->getUser()]));
				Yii::$app->session->setFlash('success', Yii::t('common', 'Your account has been activated.'));
				if (Yii::$app->getUser()->login($model->getUser(), (int) Yii::$app->settings->get('userLoginDuration'))) {
					return $this->redirect(['/account/default/index']);
				}
				return $this->redirect(['/site/login']);
			}
			Yii::$app->session->setFlash('error', Yii::t('common', 'Cannot activate the account due to invalid data provided.'));
		}

		return $this->render('activate', [
			'model' => $model,
		]);
	}

	/**
	 * Resets password.
	 *
	 * @param null|string $token
	 * @return mixed
	 */
	public function actionResetPassword($token = null)
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$resetPasswordRequestModel = new ResetPasswordRequestForm();
		$resetPasswordModel = new ResetPasswordForm();
		$resetPasswordModel->token = $token;
		$resetPasswordModel->setScenario(ResetPasswordForm::SCENARIO_TOKEN);

		if ($resetPasswordRequestModel->load(Yii::$app->request->post()) && $resetPasswordRequestModel->validate() && $resetPasswordRequestModel->sendRequest()) {
			Yii::$app->session->setFlash('success', Yii::t('common', 'You have requested a password reset. Please check the message that was sent to {0}.', [$resetPasswordRequestModel->username]));
			return $this->redirect(['reset-password']);
		} else {
			$resetPasswordModel->load(Yii::$app->request->post());
			// Switch to enter password scenario if the provided token is valid
			if (!empty($resetPasswordModel->token) && $resetPasswordModel->getUser()) {
				$resetPasswordModel->setScenario(ResetPasswordForm::SCENARIO_PASSWORD);
				if ($resetPasswordModel->token != $token) {
					return $this->redirect(['reset-password', 'token' => $resetPasswordModel->token]);
				}
			}
			if ($resetPasswordModel->load(Yii::$app->request->post()) && $resetPasswordModel->validate() && $resetPasswordModel->resetPassword()) {
				Yii::$app->session->setFlash('success', Yii::t('common', 'New password saved.'));
				if ($user = $resetPasswordModel->getUser()) {
					$user = is_array($user) ? reset($user) : $user;
					if (Yii::$app->getUser()->login($user, (int) Yii::$app->settings->get('userLoginDuration'))) {
						return $this->goHome();
					}
				}
			}
		}

		return $this->render('reset-password', [
			'resetPasswordRequestModel' => $resetPasswordRequestModel,
			'resetPasswordModel' => $resetPasswordModel,
		]);
	}
}
