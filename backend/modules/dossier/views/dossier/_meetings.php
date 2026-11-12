<?php

/* @var $this yii\web\View */
/* @var $form common\widgets\ActiveForm */
/* @var $model common\models\Dossier */

use common\helpers\DateHelper;
use common\models\ExchangeRate;
use common\models\Dossier;

?>
<?php $sedinteDosar = @unserialize($model->meetings);?>

<h3>Ședințe</h3>
<hr>
<?php if ($sedinteDosar): ?>
<table>
    <tbody>
<?php foreach ($sedinteDosar['DosarSedinta'] as $sedintaDosar): ?>
    <tr>
        <td style="font-weight: bold; text-align: left;">
            <a href="#"><?= $sedintaDosar['data'] ?></a>
        </td>
    </tr>
    <br>
    <tr>
        <td style="text-align: left;">Ora estimata: <?= $sedintaDosar['ora'] ?></td>
    </tr>
    <tr>
        <td style="text-align: left;">Complet: <?= $sedintaDosar['complet'] ?></td>
    </tr>
    <tr>
        <td style="text-align: left;">Tip solutie: <?= $sedintaDosar['solutie'] ?></td>
    </tr>
    <tr>
        <td style="text-align: left;">Solutia pe scurt: <?= $sedintaDosar['solutieSumar'] ?></td>
    </tr>
    <tr>
        <td style="text-align: left;">Document: <?= $sedintaDosar['documentSedinta'] . ' ' . $sedintaDosar['numarDocument'] . ' ' .$sedintaDosar['dataPronuntare'] ?></td>
    </tr>
<?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
<p></p>
