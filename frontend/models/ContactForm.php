<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class ContactForm extends Model
{
	/**
	 * @var string The contact name.
	 */
	public $name;

	/**
	 * @var string The email address.
	 */
	public $email;

	/**
	 * @var string The phone number.
	 */
	public $phone;

	/**
	 * @var string The subject of the email.
	 */
	public $subject;

	/**
	 * @var string The message of the email.
	 */
	public $message;

	/**
	 * @var string The honeypot field.
	 */
	public $verifyCode;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'email', 'phone', 'subject', 'message'], 'required'],
			['email', 'email'],
			['verifyCode', 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'name' => Yii::t('label', 'Name'),
			'email' => Yii::t('label', 'Email'),
			'phone' => Yii::t('label', 'Phone'),
			'subject' => Yii::t('label', 'Subject'),
			'message' => Yii::t('label', 'Message'),
		];
	}

	/**
	 * Sends an email to the specified email address using the information collected by this model.
	 *
	 * @return bool whether the email was sent.
	 */
	public function sendEmail()
	{
		if (!empty($this->verifyCode)) {
			return false;
		}
		return Yii::$app->mailer
			->compose(
				['html' => 'contact-html', 'text' => 'contact-text'],
				['model' => $this]
			)
			->setTo(Yii::$app->settings->get('email', 'contact'))
			->setReplyTo([$this->email => $this->name])
			->setSubject($this->subject)
			->send();
	}
}
