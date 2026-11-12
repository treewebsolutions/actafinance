<?php

namespace backend\modules\dossier\models;

use common\helpers\DateHelper;
use common\models\Dossier;
use common\widgets\datatable\DataTableAction;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class DossierSearch extends DataTableAction
{
	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->query = Dossier::find()
			->alias('d')
			->select([
				'd.id',
				'd.number',
				'd.old_number',
				'd.procedural_stage',
				'd.object',
				'd.institution',
				'd.department',
				'd.category',
				'd.registration_date',
				'd.modification_date',
				'd.parts',
				'd.meetings',
				'd.attack_ways',
				'd.quotes_through_advertising',
				'd.sent_at',
				'd.created_by',
				'd.created_at',
				'd.updated_at',
				'd.status',
			])
			->joinWith([
				'creator cr' => function (ActiveQuery $query) {
					$query->select([
						'cr.id',
						'cr.first_name',
						'cr.middle_name',
						'cr.last_name',
					]);
				},
			])
			->andWhere([
				'd.deleted' => isset($this->requestParams['deleted']) ? $this->requestParams['deleted'] : Dossier::NO,
			]);
	}

	/**
	 * @inheritdoc
	 */
	public function formatData(ActiveQuery $query, $columns)
	{
		return ArrayHelper::toArray($query->all(), [
			Dossier::class => [
				'id',
				'action' => function (Dossier $model) {
					$actions = [];

						if (Yii::$app->user->can('viewDossier')) {
							$actions[] = Html::a('<span class="fa fa-eye"></span>', ['view', 'id' => $model->id], [
								'class' => 'action-view btn btn-xs btn-info',
								'title' => Yii::t('common', 'View'),
								'data' => [
									'toggle' => 'tooltip',
									'popup-action' => '',
								],
							]);
						}

					$actions = array_map(function ($actionsChunk) {
						return Html::tag('div', implode('', $actionsChunk));
					}, array_chunk($actions, 3));

					return implode('', $actions);
				},
				'number' => function (Dossier $model) {
					return $model->number ?: '&mdash;';
				},
                'old_number' => function (Dossier $model) {
                    return $model->old_number ?: '&mdash;';
                },
				'object' => function (Dossier $model) {
					return $model->object ?: '&mdash;';
				},
                'procedural_stage' => function (Dossier $model) {
                    return $model->procedural_stage ?: '&mdash;';
                },
                'institution' => function (Dossier $model) {
                    return $model->institution ?: '&mdash;';
                },
                'department' => function (Dossier $model) {
                    return $model->department ?: '&mdash;';
                },
                'category' => function (Dossier $model) {
                    return $model->category ?: '&mdash;';
                },
                'registration_date' => function (Dossier $model) {
                    return $model->registration_date ? Yii::$app->formatter->asDatetime($model->registration_date) : '&mdash;';
                },
                'modification_date' => function (Dossier $model) {
                    return $model->modification_date ? Yii::$app->formatter->asDatetime($model->modification_date) : '&mdash;';
                },
                'parts' => function (Dossier $model) {
                    return $model->parts ?: '&mdash;';
                },
                'meetings' => function (Dossier $model) {
                    return $model->meetings ?: '&mdash;';
                },
                'attack_ways' => function (Dossier $model) {
                    return $model->attack_ways ?: '&mdash;';
                },
                'quotes_through_advertising' => function (Dossier $model) {
                    return $model->quotes_through_advertising ?: '&mdash;';
                },
                'sent_at' => function (Dossier $model) {
                    return $model->sent_at ? Yii::$app->formatter->asDatetime($model->sent_at) : '&mdash;';
                },
				'created_by' => function (Dossier $model) {
					return $model->creator ? $model->creator->getFullName() : '&mdash;';
				},
				'created_at' => function (Dossier $model) {
					return $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : '&mdash;';
				},
				'updated_at' => function (Dossier $model) {
					return $model->updated_at ? Yii::$app->formatter->asDatetime($model->updated_at) : '&mdash;';
				},
				'status' => function (Dossier $model) {
					$status = Dossier::getStatusLabels()[$model->status];
					return Html::tag('span', $status['label'], ['class' => 'label label-block label-' . $status['color']]);
				},
			],
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function applyFilter(ActiveQuery $query, $columns, $search)
	{
		/** @var \yii\db\ActiveRecord $modelClass */
		$modelClass = $query->modelClass;
		$schema = $modelClass::getTableSchema()->columns;

		foreach ($columns as $column) {
			if ($column['searchable'] == 'false') {
				continue;
			}
			if (!empty($search['value'])) {
				$value = trim($search['value']);
				$filterOperator = 'orFilterWhere';
			} else {
				$value = trim($column['search']['value']);
				$filterOperator = 'andFilterWhere';
			}

			switch ($column['data']) {
				case 'created_by':
					$query->$filterOperator([
						'OR',
						['LIKE', 'cr.first_name', $value],
						['LIKE', 'cr.middle_name', $value],
						['LIKE', 'cr.last_name', $value],
					]);
					break;
				case 'registration_date':
					$query->$filterOperator(['LIKE', 'd.registration_date', DateHelper::formatAsDate($value)]);
					break;
				case 'modification_date':
					$query->$filterOperator(['LIKE', 'd.modification_date', DateHelper::formatAsDate($value)]);
					break;
				case 'created_at':
					$query->$filterOperator(['LIKE', 'd.created_at', DateHelper::formatAsDate($value)]);
					break;
				case 'sent_at':
					$query->$filterOperator(['LIKE', 'd.sent_at', DateHelper::formatAsDate($value)]);
					break;
				case 'updated_at':
					$query->$filterOperator(['LIKE', 'd.updated_at', DateHelper::formatAsDate($value)]);
					break;
				default:
					if (array_key_exists($column['data'], $schema)) {
						$query->$filterOperator(['LIKE', 'd.' . $column['data'], $value]);
					}
					break;
			}
		}
		return $query;
	}

	/**
	 * @inheritdoc
	 */
	public function applyOrder(ActiveQuery $query, $columns, $order)
	{
		foreach ($order as $key => $item) {
			$column = $columns[$item['column']];
			if (array_key_exists('orderable', $column) && $column['orderable'] === 'false') {
				continue;
			}
			$sort = mb_strtolower($item['dir']) == 'desc' ? SORT_DESC : SORT_ASC;

			switch ($column['data']) {
				case 'created_by':
					$query->addOrderBy([
						'cr.first_name' => $sort,
						'cr.middle_name' => $sort,
						'cr.last_name' => $sort,
					]);
					break;
				default:
					$query->addOrderBy(['d.' . $column['data'] => $sort]);
					break;
			}
		}
		return $query;
	}
}
