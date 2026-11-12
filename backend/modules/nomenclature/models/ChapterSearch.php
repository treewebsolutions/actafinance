<?php

namespace backend\modules\nomenclature\models;

use common\helpers\DateHelper;
use common\helpers\FontIcon;
use common\models\Chapter;
use common\widgets\datatable\DataTableAction;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ChapterSearch extends DataTableAction
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->query = Chapter::find()
            ->alias('c')
            ->select([
                'c.*',
            ])
            ->joinWith([
                'chapterTranslations ct' => function (ActiveQuery $query) {
                    return $query->andOnCondition(['ct.language_id' => Yii::$app->language]);
                },
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
                'c.deleted' => isset($this->requestParams['deleted']) ? $this->requestParams['deleted'] : Chapter::NO,
            ]);
    }

    /**
     * @inheritdoc
     */
    public function formatData(ActiveQuery $query, $columns)
    {
        return ArrayHelper::toArray($query->all(), [
            Chapter::class => [
                'id',
                'sort_order',
                'actions' => function (Chapter $model) {
                    $actions = [];

                    if ($this->requestParams['deleted'] == Chapter::YES) {
                        if (Yii::$app->user->can('restoreChapter')) {
                            $actions[] = Html::a('<span class="fa fa-undo"></span>', ['restore', 'id' => $model->id], [
                                'class' => 'action-view btn btn-xs btn-success',
                                'title' => Yii::t('common', 'Restore'),
                                'data' => [
                                    'toggle' => 'tooltip',
                                    'dt-operation' => 'restore',
                                    'dt-confirm' => Yii::t('common', 'Are you sure you want to perform this operation?'),
                                ],
                            ]);
                        }
                        if (Yii::$app->user->can('deleteChapter')) {
                            $actions[] = Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => $model->id], [
                                'class' => 'action-delete btn btn-xs btn-danger',
                                'title' => Yii::t('common', 'Delete Permanently'),
                                'data' => [
                                    'toggle' => 'tooltip',
                                    'dt-operation' => 'delete-permanently',
                                    'dt-confirm' => Yii::t('common', 'Are you sure you want to perform this operation?'),
                                ],
                            ]);
                        }
                    } else {
                        if (Yii::$app->user->can('viewChapter')) {
                            $actions[] = Html::a('<span class="fa fa-eye"></span>', ['view', 'id' => $model->id], [
                                'class' => 'action-view btn btn-xs btn-info',
                                'title' => Yii::t('common', 'View'),
                                'data' => [
                                    'toggle' => 'tooltip',
                                ],
                            ]);
                        }
                        if (Yii::$app->user->can('updateChapter')) {
                            $actions[] = Html::a('<span class="fa fa-edit"></span>', ['update', 'id' => $model->id], [
                                'class' => 'action-update btn btn-xs btn-primary',
                                'title' => Yii::t('common', 'Update'),
                                'data' => [
                                    'toggle' => 'tooltip',
                                ],
                            ]);
                        }
                        if (Yii::$app->user->can('deleteChapter')) {
                            $actions[] = Html::a('<span class="fa fa-trash"></span>', ['delete', 'id' => $model->id], [
                                'class' => 'action-delete btn btn-xs btn-danger',
                                'title' => Yii::t('common', 'Delete'),
                                'data' => [
                                    'toggle' => 'tooltip',
                                    'dt-operation' => 'delete',
                                    'dt-confirm' => Yii::t('common', 'Are you sure you want to perform this operation?'),
                                ],
                            ]);
                        }
                    }

                    $actions = array_map(function ($actionsChunk) {
                        return Html::tag('div', implode('', $actionsChunk));
                    }, array_chunk($actions, 3));

                    return implode('', $actions);
                },
                'image' => function (Chapter $model) {
                    if ($model->image) {
                        $imgTag = Html::img($model->imageUrl, [
                            'class' => 'img-responsive',
                            'alt' => $model->translation->title,
                        ]);
                        return Html::a($imgTag, $model->imageUrl, [
                            'title' => Yii::t('common', 'Open Gallery'),
                            'data' => [
                                'toggle' => 'tooltip',
                                'fancybox' => 'chapters',
                                'caption' => $model->translation->title,
                            ],
                        ]);
                    }
                    return '&mdash;';
                },
                'icon' => function (Chapter $model) {
                    return $model->icon ? FontIcon::render($model->icon, ['class' => 'fa-lg']) : '&mdash;';
                },
                'title' => function (Chapter $model) {
                    return $model->getTreeName($glue = ' &rsaquo; ');
                },
				'rate' => function (Chapter $model) {
					return $model->rate ?: '&mdash;';
				},
                'created_by' => function (Chapter $model) {
                    return $model->creator ? $model->creator->getFullName() : '&mdash;';
                },
                'created_at' => function (Chapter $model) {
                    return $model->created_at ? Yii::$app->formatter->asDatetime($model->created_at) : '&mdash;';
                },
                'updated_at' => function (Chapter $model) {
                    return $model->updated_at ? Yii::$app->formatter->asDatetime($model->updated_at) : '&mdash;';
                },
                'status' => function (Chapter $model) {
                    $status = Chapter::getStatusLabels()[$model->status];
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
                case 'title':
                    $query->$filterOperator(['LIKE', 'ct.title', $value]);
                    break;
                case 'created_by':
                    $query->$filterOperator([
                        'OR',
                        ['LIKE', 'cr.first_name', $value],
                        ['LIKE', 'cr.middle_name', $value],
                        ['LIKE', 'cr.last_name', $value],
                    ]);
                    break;
                case 'created_at':
                    $query->$filterOperator(['LIKE', 'c.created_at', DateHelper::formatAsDate($value)]);
                    break;
                case 'updated_at':
                    $query->$filterOperator(['LIKE', 'c.updated_at', DateHelper::formatAsDate($value)]);
                    break;
                default:
                    if (array_key_exists($column['data'], $schema)) {
                        $query->$filterOperator(['LIKE', 'c.' . $column['data'], $value]);
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
                case 'title':
                    $query->addOrderBy(['ct.title' => $sort]);
                    break;
                case 'created_by':
                    $query->addOrderBy([
                        'cr.first_name' => $sort,
                        'cr.middle_name' => $sort,
                        'cr.last_name' => $sort,
                    ]);
                    break;
                default:
                    $query->addOrderBy(['c.' . $column['data'] => $sort]);
                    break;
            }
        }
        return $query;
    }
}
