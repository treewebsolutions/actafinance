<?php

/* @var $this yii\web\View */
/* @var $form common\widgets\ActiveForm */
/* @var $model common\models\Dossier */

use common\helpers\DateHelper;
use common\models\ExchangeRate;
use common\models\Dossier;

?>
<?php $caiAtacDosar = @unserialize($model->attack_ways);?>
<h3>Căi de atac</h3>
<hr>
<?php if($caiAtacDosar): ?>
<table style="width: 100%">
    <tbody>
    <tr>
        <th style="width: 25%; text-align: left;">Dată declarare</th>
        <th style="width: 50%; text-align: left;">Parte declarantă</th>
        <th style="width: 25%; text-align: left;">Cale de atac</th>
    </tr>
    <?php foreach($caiAtacDosar['DosarCaleAtac'] as $caleAtacDosar): ?>
    <tr>
        <td style="width: 25%; text-align: left;"> <?= $caleAtacDosar['dataDeclarare'] ?> </td>
        <td style="width: 50%; text-align: left;"> <?= $caleAtacDosar['parteDeclaratoare'] ?> </td>
        <td style="width: 25%; text-align: left;"> <?= $caleAtacDosar['tipCaleAtac'] ?> </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<p></p>