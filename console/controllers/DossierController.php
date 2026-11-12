<?php
namespace console\controllers;

use common\helpers\DateHelper;
use common\models\Dossier;
use common\models\Filter;
use common\models\Institution;
use common\models\Template;
use SoapClient;
use tws\helpers\Url;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\ArrayHelper;

class DossierController extends Controller
{
	/**
	 * @var \DateTime The current date and time instance used for this process operations.
	 */
	public static $currentDate;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		self::$currentDate = new \DateTime();
	}

	/**
	 * Get dossiers from portal.just.ro.
	 *
	 * @return bool|int
	 */
	public function actionRun()
	{
		try {
			$client = new SoapClient('http://portalquery.just.ro/query.asmx?wsdl', ['trace' => false, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS]);
			$count = 0;
			$dossier = Dossier::find()
				->andWhere([
					'status' => Dossier::STATUS_ACTIVE,
					'deleted' => Dossier::NO,
				])
			->andWhere([
				'IS NOT', 'sent_at', null
			])
			->orderBy([
				'sent_at' => SORT_DESC,
			])
			->one();

			$startDate = $dossier && $dossier->sent_at ? DateHelper::formatAsDate($dossier->sent_at) : self::$currentDate->format('Y-m-d');

			$filters = Filter::find()
				->andWhere([
					'status' => Filter::STATUS_ACTIVE,
					'deleted' => Filter::NO,
				])
				->all();
			if (!empty($filters)) {
				$institutions = [];
				foreach ($filters as $filter) {
					if ($filter->type == Filter::TYPE_INSTITUTION) {
						$institution = Institution::find()
							->andWhere([
								'status' => Dossier::STATUS_ACTIVE,
								'deleted' => Dossier::NO,
							])
							->andWhere([
								'LIKE', 'name', $filter->filter
							])
							->all();
						$institutions = array_merge($institutions, ArrayHelper::getColumn($institution, 'name'));
					}
				}
				foreach ($filters as $filter) {
					if ($filter->type == Filter::TYPE_OBJECT) {
						$params = [
							'obiectDosar' => $filter->filter,
							'dataStart' => $startDate,
							'dataUltimaModificareStart' => $startDate,
						];
						$response = $client->__soapCall('CautareDosare', array($params));
						if (!empty($response->CautareDosareResult->Dosar)) {
							foreach ($response->CautareDosareResult->Dosar as $result) {
								if (empty($institutions) || in_array($result->institutie, $institutions)) {
									$model = Dossier::findOne([
										'number' => $result->numar,
									]);
									if (!$model) {
										$model = new Dossier();
									}
									if ($model->registration_date == DateHelper::formatAsDateTime($result->data) && $model->modification_date == DateHelper::formatAsDateTime($result->dataModificare)) {
										continue;
									}
									$model->number = $result->numar;
									$model->old_number = $result->numarVechi ?? null;
									$model->procedural_stage = $result->stadiuProcesual;
									$model->object = $result->obiect;
									$model->institution = $result->institutie;
									$model->department = $result->departament;
									$model->category = $result->categorieCaz;
									$model->registration_date = DateHelper::formatAsDateTime($result->data);
									$model->modification_date = DateHelper::formatAsDateTime($result->dataModificare);
									$model->parts = !empty($result->parti) ? serialize(json_decode(json_encode($result->parti), true)) : null;
									$model->meetings = !empty($result->sedinte) ? serialize(json_decode(json_encode($result->sedinte), true)) : null;
									$model->attack_ways = !empty($result->caiAtac) ? serialize(json_decode(json_encode($result->caiAtac), true)) : null;
									$model->status = 1;
									$model->save();
									$count += 1;
								}
							}
						}
					}
				}
			}

			$this->stdout("[" . date("Y-m-d H:i:s") . "] Processed {$count} record(s).");
			return ExitCode::OK;
		} catch (\Exception $e) {
			$this->stderr($e->getMessage());
			return ExitCode::UNSPECIFIED_ERROR;
		} catch (\Throwable $e) {
			$this->stderr($e->getMessage());
			return ExitCode::UNSPECIFIED_ERROR;
		}
	}


	/**
	 * Get emails with dossiers
	 *
	 * @return bool|int
	 */
	public function actionSendEmail()
	{
		try {
			$template = Template::findDefaultByTypeAndVariant(Template::TYPE_EMAIL, Template::EMAIL_VARIANT_REPORT_DOSSIER);
			if (!$template || !($templateTranslation = $template->getTranslation())) {
				return false;
			}

			$dossiers = Dossier::find()
				->andWhere([
					'status' => Dossier::STATUS_ACTIVE,
					'deleted' => Dossier::NO,
					'sent_at' => null,
				])
				->limit(10);
			$count = 0;

			foreach ($dossiers->all() as $dossier) {
				$shortCodeValues = $dossier->getShortCodeValues(true);
				$mail = Yii::$app->getMailer()
					->compose()
					->setTo(Yii::$app->settings->get('targetEmail'))
					->setSubject(strtr($templateTranslation->subject, $shortCodeValues))
					->setHtmlBody(strtr($templateTranslation->content, $shortCodeValues));

				if ($mail->send()) {
					$dossier->sent_at = (new \DateTime)->format('Y-m-d H:i:s');
					$dossier->save();
				}
				$count++;
			}

			$this->stdout("[" . date("Y-m-d H:i:s") . "] Processed {$count} record(s).");
			return ExitCode::OK;
		} catch (\Exception $e) {
			$this->stderr($e->getMessage());
			return ExitCode::UNSPECIFIED_ERROR;
		} catch (\Throwable $e) {
			$this->stderr($e->getMessage());
			return ExitCode::UNSPECIFIED_ERROR;
		}
	}
}
