<?php

/* @var $this yii\web\View */

use common\models\Dossier;
use common\widgets\datatable\DataTable;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use tws\helpers\Url;
use yii\web\JsExpression;

$this->title = Yii::t('common', 'Dossiers');
$this->params['breadcrumbs'] = [
	$this->title,
];

if ($showTrash = Yii::$app->settings->get('enableSoftDelete')) {
	$showTrash = Yii::$app->request->get('deleted') == Dossier::YES;
	$this->params['breadcrumbs'][] = [
		'template' => '<li class="page-links">{link}</li>',
		'label' => implode('', [
			Html::a(Yii::t('common', 'Current'), ['index'], ['class' => $showTrash ? '' : 'active']),
			Html::a(Yii::t('common', 'Trash'), ['index', 'deleted' => Dossier::YES], ['class' => $showTrash ? 'active' : '']),
		]),
	];
}

$this->params['actions'] = [
	[
		'visible' => Yii::$app->user->can('restoreDossier') && $showTrash,
		'tag' => 'button',
		'icon' => 'fa fa-undo',
		'options' => [
			'class' => 'btn btn-sm btn-success hidden',
			'title' => Yii::t('common', 'Restore'),
			'data' => [
				'toggle' => 'tooltip',
				'dt-bulk-operation' => 'restore',
				'dt-confirm' => Yii::t('common', 'Are you sure you want to perform this operation?'),
				'dt-url' => Url::to(['restore']),
				'dt-table' => '#dt-dossiers',
			],
		],
	],
	[
		'visible' => Yii::$app->user->can('deleteDossier'),
		'tag' => 'button',
		'icon' => 'fa fa-trash',
		'options' => [
			'class' => 'btn btn-sm btn-danger hidden',
			'title' => $showTrash ? Yii::t('common', 'Delete Permanently') : Yii::t('common', 'Delete'),
			'data' => [
				'toggle' => 'tooltip',
				'dt-bulk-operation' => $showTrash ? 'delete-permanently' : 'delete',
				'dt-confirm' => Yii::t('common', 'Are you sure you want to perform this operation?'),
				'dt-url' => Url::to(['delete']),
				'dt-table' => '#dt-dossiers',
			],
		],
	],
];
?>
<div class="dt-scroll-x">
<?= DataTable::widget([
	'id' => 'dt-dossiers',
	'options' => [
		'class' => 'table table-bordered table-hover',
	],
	'showColumnFilters' => true,
	'clientOptions' => [
		'deferRender' => true,
		'processing' => true,
		'serverSide' => true,
		'ajax' => [
			'url' => Url::to(['dt-dossiers']),
			'method' => 'POST',
			'data' => new JsExpression('function (data) {
				data.deleted = ' . json_encode($showTrash ? Dossier::YES : null) . ';
			}'),
		],
		'order' => [
			[8, 'desc'],
			[9, 'desc'],
		],
		'pageLength' => Yii::$app->settings->get('itemsPerPage'),
		'lengthMenu' => [
			'autoCreate' => true,
			'displayAll' => Yii::t('common', 'All'),
		],
		'autoWidth' => false,
		'responsive' => false,
//        'scrollX' => true,
//		'rowGroup' => [
//			'dataSrc' => 'variant'
//		],
		'columns' => [
			[
				'class' => 'common\widgets\datatable\CheckboxColumn',
			],
			[
				'class' => 'common\widgets\datatable\ActionColumn',
				'data' => 'action',
				'title' => Yii::t('common', 'Action'),
			],
			[
				'data' => 'number',
				'title' => Yii::t('label', 'Number'),
				'filter' => ['text'],
			],
			[
				'data' => 'object',
				'title' => Yii::t('label', 'Object'),
				'filter' => ['text'],
			],
			[
				'data' => 'procedural_stage',
				'title' => Yii::t('label', 'Procedural Stage'),
				'filter' => ['text'],
			],
			[
				'data' => 'institution',
				'title' => Yii::t('label', 'Institution'),
				'filter' => ['text'],
			],
			[
				'data' => 'department',
				'title' => Yii::t('label', 'Department'),
				'filter' => ['text'],
			],
			[
				'data' => 'category',
				'title' => Yii::t('label', 'Category'),
				'filter' => ['text'],
			],
			[
				'data' => 'registration_date',
				'title' => Yii::t('label', 'Registration Date'),
				'filter' => ['date', 'icu:' . Yii::$app->settings->get('dateFormat')],
			],
			[
				'data' => 'modification_date',
				'title' => Yii::t('label', 'Modification Date'),
				'filter' => ['date', 'icu:' . Yii::$app->settings->get('dateFormat')],
			],
			[
				'data' => 'sent_at',
				'title' => Yii::t('label', 'Sent At'),
				'filter' => ['date', 'icu:' . Yii::$app->settings->get('dateFormat')],
			],
			[
				'data' => 'created_at',
				'title' => Yii::t('label', 'Created At'),
				'filter' => ['date', 'icu:' . Yii::$app->settings->get('dateFormat')],
			],
			[
				'data' => 'status',
				'title' => Yii::t('label', 'Status'),
				'className' => 'col-autowidth',
				'filter' => ['select', ArrayHelper::getColumn(Dossier::getStatusLabels(), 'label')],
			],
		],
	],
]) ?>
</div>
