<?php

/* @var $this yii\web\View */
/* @var $form common\widgets\ActiveForm */
/* @var $model common\models\Dossier */

use common\helpers\DateHelper;
use common\models\Dossier;

?>
<?php $partiDosar = @unserialize($model->parts);?>
<h3>Părți</h3>
<hr>
<?php if ($partiDosar): ?>
<table style="width: 100%;">
    <tbody>
        <tr>
            <th style="width: 75%; text-align: left;">Nume</th>
            <th style="width: 25%; text-align: left;">Calitate Parte</th>
        </tr>
        <?php foreach ($partiDosar['DosarParte'] as $parteDosar): ?>
        <tr>
            <td style="width: 75%; text-align: left;"><?= $parteDosar['nume'] ?></td>
            <td style="width: 25%; text-align: left;"><?= $parteDosar['calitateParte'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<p></p>