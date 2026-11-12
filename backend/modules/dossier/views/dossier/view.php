<?php

/* @var $this yii\web\View */
/* @var $model common\models\Dossier */

use common\models\Dossier;
use common\models\EventLog;
use common\widgets\datatable\DataTable;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use tws\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\DetailView;

$this->title = Yii::t('common', 'View {item}', ['item' => Yii::t('common', 'Dossier')]);
$this->params['breadcrumbs'] = [
	[
		'label' => Yii::t('common', 'Dossiers'),
		'url' => ['index'],
	],
	Yii::t('common', 'View'),
];
$this->params['actions'] = [
	[
		'visible' => Yii::$app->user->can('viewDossier'),
		'tag' => 'a',
		'url' => ['index'],
		'icon' => 'fa fa-list',
		'options' => [
			'class' => 'btn btn-sm btn-default',
			'title' => Yii::t('common', 'List'),
			'data' => [
				'toggle' => 'tooltip',
			],
		],
	],
	[
		'visible' => Yii::$app->user->can('deleteDossier'),
		'tag' => 'a',
		'url' => ['delete', 'id' => $model->id],
		'icon' => 'fa fa-trash',
		'options' => [
			'class' => 'btn btn-sm btn-danger',
			'title' => Yii::t('common', 'Delete'),
			'data' => [
				'toggle' => 'tooltip',
				'confirm' => Yii::t('common', 'Are you sure you want to perform this operation?'),
				'method' => 'POST',
			],
		],
	],
];

$showEventLogs = isset($showEventLogs) ? $showEventLogs : Yii::$app->eventLog->enabled;
?>

<?php if (Yii::$app->request->isAjax): ?>
<div class="modal-dialog modal-lg">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<div class="modal-title"><?= $this->title ?></div>
		</div>
		<div class="modal-body">
<?php endif; ?>

			<div class="table-responsive">
				<?= DetailView::widget([
					'model' => $model,
					'options' => [
						'class' => 'table table-striped table-bordered detail-view detail-view-fixed',
					],
					'attributes' => [
                        [
                            'format' => 'html',
                            'label' => Yii::t('label', 'Sent At'),
                            'value' => function (Dossier $model) {
                                return $model->sent_at ?: '&mdash;';
                            },
                        ],
						[
							'format' => 'html',
							'label' => Yii::t('label', 'Number'),
							'value' => function (Dossier $model) {
								return $model->number ?: '&mdash;';
							},
						],
						[
							'format' => 'html',
							'label' => Yii::t('label', 'Old Number'),
							'value' => function (Dossier $model) {
								return $model->old_number ?: '&mdash;';
							},
						],
						[
							'format' => 'raw',
							'label' => Yii::t('label', 'Procedural Stage'),
							'value' => function (Dossier $model) {
								return $model->procedural_stage ?: '&mdash;';
							},
						],
						[
							'format' => 'html',
							'label' => Yii::t('label', 'Object'),
							'value' => function (Dossier $model) {
								return $model->object ?: '&mdash;';
							},
						],
                        [
                            'format' => 'html',
                            'label' => Yii::t('label', 'Institution'),
                            'value' => function (Dossier $model) {
                                return $model->institution ?: '&mdash;';
                            },
                        ],
                        [
                            'format' => 'html',
                            'label' => Yii::t('label', 'Department'),
                            'value' => function (Dossier $model) {
                                return $model->department ?: '&mdash;';
                            },
                        ],
                        [
                            'format' => 'html',
                            'label' => Yii::t('label', 'Category'),
                            'value' => function (Dossier $model) {
                                return $model->category ?: '&mdash;';
                            },
                        ],
                        [
                            'format' => 'html',
                            'label' => Yii::t('label', 'Registration Date'),
                            'value' => function (Dossier $model) {
                                return $model->registration_date ? Yii::$app->formatter->asDatetime($model->registration_date) : '&mdash;';
                            },
                        ],
                        [
                            'format' => 'html',
                            'label' => Yii::t('label', 'Modification Date'),
                            'value' => function (Dossier $model) {
                                return $model->modification_date ? Yii::$app->formatter->asDatetime($model->modification_date) : '&mdash;';
                            },
                        ],
                        [
                            'format' => 'html',
                            'label' => Yii::t('label', 'Parts'),
                            'value' => function (Dossier $model) {
                                $partiDosar = @unserialize($model->parts);
                                if ($partiDosar) {
                                    $content[] = Html::tag('div', implode('', [
                                        Html::tag('div', 'Nume', ['class' => 'th', 'style' => 'width: 75%']),
                                        Html::tag('div', 'Calitate Parte', ['class' => 'th', 'style' => 'width: 25%']),
                                    ]), ['class' => 'tr']);

                                    foreach ($partiDosar['DosarParte'] as $item) {
                                        $content[] = Html::tag('div', implode('', [
                                            Html::tag('div', $item['nume'], ['class' => 'td', 'style' => 'width: 75%']),
                                            Html::tag('div', $item['calitateParte'], ['class' => 'td', 'style' => 'width: 25%']),
                                        ]), ['class' => 'tr']);
                                    }
                                    return Html::tag('div', implode('', $content), ['class' => 'table table-bordered table-nested']);
                                }
                                return Html::tag('div', '&mdash;', ['class' => 'p8']);
                            },
                        ],
                        [
                            'format' => 'html',
                            'label' => Yii::t('label', 'Meetings'),
                            'value' => function (Dossier $model) {
                                $sedinteDosar = @unserialize($model->meetings);
                                if ($sedinteDosar) {
                                    foreach ($sedinteDosar['DosarSedinta'] as $item) {
                                        $content[] = Html::tag('div', implode('', [
                                            Html::tag('div', 'Data' . ': ' . $item['data'], ['class' => 'tr', 'style' => 'text-align: left']),
                                            Html::tag('div', 'Ora' . ': ' . $item['ora'], ['class' => 'tr', 'style' => 'text-align: left']),
                                            Html::tag('div', 'Complet' . ': ' . $item['complet'], ['class' => 'tr', 'style' => 'text-align: left']),
                                            Html::tag('div', 'Tip solutie' . ': ' . $item['solutie'], ['class' => 'tr', 'style' => 'text-align: left']),
                                            Html::tag('div', 'Solutia pe scurt' . ': ' . $item['solutieSumar'], ['class' => 'tr', 'style' => 'text-align: left']),
                                            Html::tag('div', 'Document' . ': ' . $item['documentSedinta'] . ' ' . $item['numarDocument'] . ' ' . $item['dataPronuntare'] , ['class' => 'tr', 'style' => 'text-align: left']),
                                            Html::tag('br'),
                                        ]), ['class' => 'tr']);
                                    }
                                    return Html::tag('div', implode('', $content), ['class' => 'table table-bordered table-nested']);
                                }
                                return Html::tag('div', '&mdash;', ['class' => 'p8']);
                            },
                        ],
                        [
                            'format' => 'html',
                            'label' => Yii::t('label', 'Attack Ways'),
                            'value' => function (Dossier $model) {
                                $caiAtacDosar = @unserialize($model->attack_ways);
                                if ($caiAtacDosar) {
                                    $content[] = Html::tag('div', implode('', [
                                        Html::tag('div', 'Dată declarare', ['class' => 'th', 'style' => 'width: 25%']),
                                        Html::tag('div', 'Parte declarantă', ['class' => 'th', 'style' => 'width: 50%']),
                                        Html::tag('div', 'Cale de atac', ['class' => 'th', 'style' => 'width: 25%']),
                                    ]), ['class' => 'tr']);

                                    foreach ($caiAtacDosar['DosarCaleAtac'] as $item) {
                                        $content[] = Html::tag('div', implode('', [
                                            Html::tag('div', $item['dataDeclarare'], ['class' => 'td', 'style' => 'width: 25%']),
                                            Html::tag('div', $item['parteDeclaratoare'], ['class' => 'td', 'style' => 'width: 50%']),
                                            Html::tag('div', $item['tipCaleAtac'], ['class' => 'td', 'style' => 'width: 25%']),
                                        ]), ['class' => 'tr']);
                                    }
                                    return Html::tag('div', implode('', $content), ['class' => 'table table-bordered table-nested']);
                                }
                                return Html::tag('div', '&mdash;', ['class' => 'p8']);
                            },
                        ],
						[
							'format' => 'html',
							'label' => Yii::t('label', 'Created By'),
							'value' => function (Dossier $model) {
								return $model->creator ? Html::a($model->creator->getFullName(), ['/user-manager/user/view', 'id' => $model->creator->id]) : '&mdash;';
							},
						],
						[
							'format' => 'html',
							'label' => Yii::t('label', 'Created At'),
							'value' => function (Dossier $model) {
								return $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : '&mdash;';
							},
						],
						[
							'format' => 'html',
							'label' => Yii::t('label', 'Updated By'),
							'value' => function (Dossier $model) {
								return $model->updater ? Html::a($model->updater->getFullName(), ['/user-manager/user/view', 'id' => $model->updater->id]) : '&mdash;';
							},
						],
						[
							'format' => 'html',
							'label' => Yii::t('label', 'Updated At'),
							'value' => function (Dossier $model) {
								return $model->updated_at ? Yii::$app->formatter->asDatetime($model->updated_at) : '&mdash;';
							},
						],
						[
							'format' => 'html',
							'label' => Yii::t('label', 'Status'),
							'value' => function (Dossier $model) {
								$status = Dossier::getStatusLabels()[$model->status];
								return Html::tag('span', $status['label'], ['class' => 'label label-' . $status['color']]);
							},
						],
					],
				]) ?>
			</div>

<?php if (Yii::$app->request->isAjax): ?>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('common', 'Close') ?></button>
		</div>
	</div>
</div>
<?php endif; ?>


<?php if ((!isset($showEventLogs) || $showEventLogs === true) && !Yii::$app->request->isAjax && Yii::$app->user->can('viewEventLog')): ?>
	<div class="portlet box blue-hoki mt-15">
		<div class="portlet-title">
			<div class="caption"><?= Yii::t('common', 'Event Logs') ?></div>
		</div>
		<div class="portlet-body">
			<?= DataTable::widget([
				'id' => 'dt-logs',
				'options' => [
					'class' => 'table table-bordered table-hover',
				],
				'showColumnFilters' => true,
				'clientOptions' => [
					'deferRender' => true,
					'processing' => true,
					'serverSide' => true,
					'ajax' => [
						'url' => Url::to(['/eventlog-manager/event-log/dt-event-logs']),
						'method' => 'POST',
						'data' => new JsExpression('function (data) {
							data.model = ' . json_encode(\common\models\Dossier::class) . '; 
							data.model_key = "' . $model->id . '";
						}'),
						'reloadInterval' => 5 * 60000,
					],
					'order' => [
						[3, 'desc'],
					],
					'pageLength' => Yii::$app->settings->get('itemsPerPage'),
					'lengthMenu' => [
						'autoCreate' => true,
						'displayAll' => Yii::t('common', 'All'),
					],
					'autoWidth' => false,
					'responsive' => true,
					'columns' => [
						[
							'class' => 'common\widgets\datatable\ActionColumn',
							'title' => Yii::t('common', 'Action'),
							'buttons' => [
								'event-log' => [
									'visible' => Yii::$app->user->can('viewEventLog'),
									'url' => ['/eventlog-manager/event-log/view', 'id' => new JsExpression('id')],
									'content' => '<span class="fa fa-eye"></span>',
									'options' => [
										'class' => 'action-view btn btn-xs btn-info',
										'title' => Yii::t('common', 'View'),
										'data' => [
											'toggle' => 'tooltip',
											'popup-action' => '',
										],
									],
								],
							],
						],
						[
							'data' => 'user',
							'title' => Yii::t('label', 'User'),
							'filter' => ['text'],
						],
						[
							'data' => 'operation',
							'title' => Yii::t('label', 'Operation'),
							'filter' => ['select', ArrayHelper::getColumn(EventLog::getActions(), 'label')],
						],
						[
							'data' => 'date',
							'title' => Yii::t('label', 'Date'),
							'filter' => ['date', 'icu:' . Yii::$app->settings->get('dateFormat')],
						],
						[
							'data' => 'ip_address',
							'title' => Yii::t('label', 'IP Address'),
							'filter' => ['text'],
						],
					],
				],
			]) ?>
		</div>
	</div>
<?php endif; ?>
