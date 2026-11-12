<?php
/* @var $this \yii\web\View */

use common\helpers\FontIcon;
use common\models\Dossier;
use common\models\Institution;
use kartik\select2\Select2;
use tws\helpers\Url;
use yii\helpers\Html;
use common\widgets\ActiveForm;

$institutii = [
	'CurteadeApelBUCURESTI',
	'TribunalulBUCURESTI',
	'JudecatoriaSECTORUL4BUCURESTI',
	'TribunalulTIMIS',
	'CurteadeApelBACAU',
	'CurteadeApelCLUJ',
	'CurteadeApelORADEA',
	'CurteadeApelCONSTANTA',
	'CurteadeApelSUCEAVA',
	'TribunalulBOTOSANI',
	'CurteadeApelPLOIESTI',
	'CurteadeApelTARGUMURES',
	'CurteadeApelGALATI',
	'CurteadeApelIASI',
	'CurteadeApelPITESTI',
	'CurteadeApelCRAIOVA',
	'JudecatoriaARAD',
	'CurteadeApelALBAIULIA',
	'CurteadeApelTIMISOARA',
	'TribunalulBRASOV',
	'TribunalulDOLJ',
	'CurteadeApelBRASOV',
	'CurteaMilitaradeApelBUCURESTI',
	'TribunalulSATUMARE',
	'TribunalulSALAJ',
	'TribunalulSIBIU',
	'TribunalulSUCEAVA',
	'TribunalulTELEORMAN',
	'TribunalulTULCEA',
	'TribunalulVASLUI',
	'TribunalulVALCEA',
	'TribunalulVRANCEA',
	'TribunalulMilitarBUCURESTI',
	'TribunalulILFOV',
	'JudecatoriaBUFTEA',
	'TribunalulGORJ',
	'TribunalulHARGHITA',
	'TribunalulHUNEDOARA',
	'TribunalulIALOMITA',
	'TribunalulIASI',
	'TribunalulMARAMURES',
	'TribunalulMEHEDINTI',
	'TribunalulMURES',
	'TribunalulNEAMT',
	'TribunalulOLT',
	'TribunalulPRAHOVA',
	'TribunalulALBA',
	'TribunalulARAD',
	'TribunalulARGES',
	'TribunalulBACAU',
	'TribunalulBIHOR',
	'TribunalulBISTRITANASAUD',
	'TribunalulBRAILA',
	'TribunalulBUZAU',
	'TribunalulCARASSEVERIN',
	'TribunalulCALARASI',
	'TribunalulCLUJ',
	'TribunalulCONSTANTA',
	'TribunalulCOVASNA',
	'TribunalulDAMBOVITA',
	'TribunalulGALATI',
	'TribunalulGIURGIU',
	'JudecatoriaADJUD',
	'JudecatoriaAGNITA',
	'JudecatoriaAIUD',
	'JudecatoriaALBAIULIA',
	'JudecatoriaALESD',
	'JudecatoriaBABADAG',
	'JudecatoriaBACAU',
	'JudecatoriaBAIADEARAMA',
	'JudecatoriaBAIAMARE',
	'JudecatoriaBAILESTI',
	'JudecatoriaBALS',
	'JudecatoriaBALCESTI',
	'JudecatoriaBECLEAN',
	'JudecatoriaBEIUS',
	'JudecatoriaBICAZ',
	'JudecatoriaBARLAD',
	'JudecatoriaBISTRITA',
	'JudecatoriaBLAJ',
	'JudecatoriaBOLINTINVALE',
	'JudecatoriaBOTOSANI',
	'JudecatoriaBOZOVICI',
	'JudecatoriaBRAD',
	'JudecatoriaBRAILA',
	'JudecatoriaBRASOV',
	'JudecatoriaBREZOI',
	'JudecatoriaBUHUSI',
	'JudecatoriaBUZAU',
	'JudecatoriaCALAFAT',
	'JudecatoriaCALARASI',
	'JudecatoriaCAMPENI',
	'JudecatoriaCAMPINA',
	'JudecatoriaCAMPULUNG',
	'JudecatoriaCAMPULUNGMOLDOVENESC',
	'JudecatoriaCARACAL',
	'JudecatoriaCARANSEBES',
	'JudecatoriaCHISINEUCRIS',
	'JudecatoriaCLUJNAPOCA',
	'JudecatoriaCONSTANTA',
	'JudecatoriaCORABIA',
	'JudecatoriaCOSTESTI',
	'JudecatoriaCRAIOVA',
	'JudecatoriaCURTEADEARGES',
	'JudecatoriaDarabani',
	'JudecatoriaCAREI',
	'JudecatoriaDEJ',
	'JudecatoriaDETA',
	'JudecatoriaDEVA',
	'JudecatoriaDOROHOI',
	'JudecatoriaDRAGASANI',
	'JudecatoriaDRAGOMIRESTI',
	'JudecatoriaDROBETATURNUSEVERIN',
	'JudecatoriaFAGARAS',
	'JudecatoriaFALTICENI',
	'JudecatoriaFAUREI',
	'JudecatoriaFETESTI',
	'JudecatoriaFILIASI',
	'JudecatoriaFOCSANI',
	'JudecatoriaGAESTI',
	'JudecatoriaGALATI',
	'JudecatoriaGHEORGHENI',
	'JudecatoriaGHERLA',
	'JudecatoriaGIURGIU',
	'JudecatoriaGURAHUMORULUI',
	'JudecatoriaGURAHONT',
	'JudecatoriaHARLAU',
	'JudecatoriaHATEG',
	'JudecatoriaHOREZU',
	'JudecatoriaHUEDIN',
	'JudecatoriaHUNEDOARA',
	'JudecatoriaHUSI',
	'JudecatoriaIASI',
	'JudecatoriaINEU',
	'JudecatoriaINSURATEI',
	'JudecatoriaINTORSURABUZAULUI',
	'JudecatoriaLEHLIUGARA',
	'JudecatoriaLIPOVA',
	'JudecatoriaLUDUS',
	'JudecatoriaLUGOJ',
	'JudecatoriaMACIN',
	'JudecatoriaMANGALIA',
	'JudecatoriaMARGHITA',
	'JudecatoriaMEDGIDIA',
	'JudecatoriaMEDIAS',
	'JudecatoriaMIERCUREACIUC',
	'JudecatoriaMIZIL',
	'JudecatoriaMOINESTI',
	'JudecatoriaMOLDOVANOUA',
	'JudecatoriaMORENI',
	'JudecatoriaMOTRU',
	'JudecatoriaMURGENI',
	'JudecatoriaNASAUD',
	'JudecatoriaNEGRESTIOAS',
	'JudecatoriaNOVACI',
	'JudecatoriaODORHEIULSECUIESC',
	'JudecatoriaOLTENITA',
	'JudecatoriaONESTI',
	'JudecatoriaORADEA',
	'JudecatoriaORASTIE',
	'JudecatoriaORAVITA',
	'JudecatoriaORSOVA',
	'JudecatoriaPANCIU',
	'JudecatoriaPATARLAGELE',
	'JudecatoriaPETROSANI',
	'JudecatoriaPIATRANEAMT',
	'JudecatoriaPITESTI',
	'JudecatoriaPLOIESTI',
	'JudecatoriaPOGOANELE',
	'JudecatoriaPUCIOASA',
	'JudecatoriaRACARI',
	'JudecatoriaRADAUTI',
	'JudecatoriaRADUCANENI',
	'JudecatoriaRAMNICUSARAT',
	'JudecatoriaRAMNICUVALCEA',
	'JudecatoriaREGHIN',
	'JudecatoriaRESITA',
	'JudecatoriaROMAN',
	'JudecatoriaROSIORIDEVEDE',
	'JudecatoriaRUPEA',
	'JudecatoriaSALISTE',
	'JudecatoriaSANNICOLAULMARE',
	'JudecatoriaSATUMARE',
	'JudecatoriaSAVENI',
	'JudecatoriaSEBES',
	'JudecatoriaSECTORUL1BUCURESTI',
	'JudecatoriaSECTORUL2BUCURESTI',
	'JudecatoriaSECTORUL3BUCURESTI',
	'JudecatoriaSECTORUL5BUCURESTI',
	'JudecatoriaSECTORUL6BUCURESTI',
	'JudecatoriaSEGARCEA',
	'JudecatoriaSFANTUGHEORGHE',
	'JudecatoriaSIBIU',
	'JudecatoriaSIGHETUMARMATIEI',
	'JudecatoriaSIGHISOARA',
	'JudecatoriaSIMLEULSILVANIEI',
	'JudecatoriaSINAIA',
	'JudecatoriaSLATINA',
	'JudecatoriaSLOBOZIA',
	'JudecatoriaSTREHAIA',
	'JudecatoriaSUCEAVA',
	'JudecatoriaTARGOVISTE',
	'JudecatoriaTARGUBUJOR',
	'JudecatoriaTARGUCARBUNESTI',
	'JudecatoriaTARGUJIU',
	'JudecatoriaTARGULAPUS',
	'JudecatoriaTARGUMURES',
	'JudecatoriaTARGUNEAMT',
	'JudecatoriaTARGUSECUIESC',
	'JudecatoriaTARNAVENI',
	'JudecatoriaTECUCI',
	'JudecatoriaTIMISOARA',
	'JudecatoriaTOPLITA',
	'JudecatoriaTULCEA',
	'JudecatoriaTURDA',
	'JudecatoriaTURNUMAGURELE',
	'JudecatoriaURZICENI',
	'JudecatoriaVALENIIDEMUNTE',
	'JudecatoriaVANJUMARE',
	'JudecatoriaVASLUI',
	'JudecatoriaVATRADORNEI',
	'JudecatoriaVIDELE',
	'JudecatoriaVISEUDESUS',
	'JudecatoriaZALAU',
	'JudecatoriaZARNESTI',
	'JudecatoriaZIMNICEA',
	'TribunalulMilitarIASI',
	'JudecatoriaALEXANDRIA',
	'TribunalulMilitarTIMISOARA',
	'TribunalulMilitarCLUJNAPOCA',
	'TribunalulMilitarTeritorialBUCURESTI',
	'JudecatoriaAVRIG',
	'JudecatoriaTOPOLOVENI',
	'JudecatoriaPODUTURCULUI',
	'JudecatoriaFAGET',
	'JudecatoriaSALONTA',
	'JudecatoriaLIESTI',
	'JudecatoriaHARSOVA',
	'JudecatoriaSOMCUTAMARE',
	'JudecatoriaPASCANI',
	'TribunalulComercialARGES',
	'TribunalulComercialCLUJ',
	'TribunalulComercialMURES',
	'TribunalulpentruminoriSifamilieBRASOV',
	'JudecatoriaCORNETU',
	'JudecatoriaJIBOU',
];

?>
<style>
	td, th {
		padding: 5px;
	}
</style>
<div class="container" style="width:100%;">
<?php
	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		if ($data == "") {
			$data = null;
		}
		return $data;
	}
	if (Yii::$app->request->isPost) {
		if (empty(Yii::$app->request->post("numarDosar")) and empty(Yii::$app->request->post("obiectDosar")) and empty(Yii::$app->request->post("numeParte"))) {
			$mesajEroare = "Este necesara completarea a cel putin unuia dintre campurile: 'Nr. unic Dosar', 'Obiect Dosar' sau 'Nume Parte'.";
			Yii::$app->session->setFlash('error', $mesajEroare);
		} else {
			$numarDosar = test_input(Yii::$app->request->post("numarDosar"));
			$obiectDosar = test_input(Yii::$app->request->post("obiectDosar"));
			$numeParte = test_input(Yii::$app->request->post("numeParte"));
		}
		$numeParte2 = "" . $numeParte;
		$institutie = test_input(Yii::$app->request->post("institutie"));
		$dataStart = test_input(Yii::$app->request->post("dataStart"));
		$dataStop = test_input(Yii::$app->request->post("dataStop"));
		$dataUltimaModificareStart = test_input(Yii::$app->request->post("dataUltimaModificareStart"));
		$dataUltimaModificareStop = test_input(Yii::$app->request->post("dataUltimaModificareStop"));
	}
?>
<h3>Căutare în portal.just.ro</h3>
<table border='0' cellpadding='1' cellspacing='0' style='white-space: nowrap'>
	<?php $form = ActiveForm::begin([
		'id' => '',
		'options' => [
			'novalidate' => true,
			'class' => '',
		],
		'validateOnType' => true,
		'validateOnBlur' => false,
	]); ?>
		<tr><td align="right">Nr. unic Dosar: </td><td><input type="text" name="numarDosar" size="50" value="<?php echo $numarDosar;?>"></td></tr>
		<tr><td align="right">Obiect Dosar: </td><td><input type="text" name="obiectDosar" size="50" value="<?php echo $obiectDosar;?>"></td></tr>
		<tr><td align="right">Nume Parte: </td><td><input type="text" name="numeParte" size="50" value="<?php echo $numeParte;?>"></td></tr>
		<tr><td align="right">Institutie: </td>
			<td>
				<?= Select2::widget([
						'name' => 'institutie',
						'data' => array_combine($institutii, $institutii),
						'value' => $institutie,
						'options' => ['placeholder' => ''],
						'pluginOptions' => [
							'allowClear' => true
						],
					]);
				?>
			</td>
		</tr>
		<tr><td align="right">Data inregistrarii: </td><td><input type="date" name="dataStart" size="10" value="<?php echo $dataStart;?>"> - <input type="date" name="dataStop" size="10" value="<?php echo $dataStop;?>"></td></tr>
		<tr><td align="right">Data ultimei modificari: </td><td><input type="date" name="dataUltimaModificareStart" size="10" value="<?php echo $dataUltimaModificareStart;?>"> - <input type="date" name="dataUltimaModificareStop" size="10" va lue="<?php echo $dataUltimaModificareStop;?>"></td></tr>
		<tr><td>&nbsp;</td><td><input type="submit" name="submit" value="Cautare Dosare"></td></tr>
	<?php ActiveForm::end(); ?>
</table>
<?php
	if (Yii::$app->request->isPost) {
		try {
			if (!empty(Yii::$app->request->post("numarDosar")) or !empty(Yii::$app->request->post("obiectDosar")) or !empty(Yii::$app->request->post("numeParte"))) {
			$client = new SoapClient('http://portalquery.just.ro/query.asmx?wsdl', ['trace' => false, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS]);
			$params = [
				'numarDosar' => $numarDosar,
				'obiectDosar' => $obiectDosar,
				'numeParte' => $numeParte2,
				'institutie' => $institutie,
				'dataStart' => $dataStart,
				'dataStop' => $dataStop,
				'dataUltimaModificareStart' => $dataUltimaModificareStart,
				'dataUltimaModificareStop' => $dataUltimaModificareStop
			];
			$response = $client->__soapCall('CautareDosare', array($params));
//			echo "<pre>";
//			print_r($client->__getFunctions());
//			echo "<hr>";
//			print_r($client->__getTypes());
//			echo "<hr>";
//			print_r($client->__getLastRequestHeaders());
//			echo "<hr>";
//			print_r($client->__getLastRequest());
//			echo "<hr>";
//			print_r($client->__getLastResponseHeaders());
//			echo "<hr>";
//			print_r($client->__getLastResponse());
//			echo "<hr>";
//			print_r($response->CautareDosareResult->Dosar[0]);
//			echo "</pre>";
			echo "<hr>";
			echo 'Dosare gasite: ' . count($response->CautareDosareResult->Dosar);
			echo "<br><br>";
			if (!empty($response->CautareDosareResult->Dosar)) {
				// dosar
				echo "<table border='1' cellpadding='1' cellspacing='0' style='white-space: nowrap'>";
				// echo "<tr><th>numar</th><th>numarVechi</th><th>data</th><th>institutie</th><th>departament</th><th>categorieCaz</th><th>categorieCazNume</th><th>stadiuProcesual</th><th>stadiuProcesualNume</th>";
				echo "<tr><th>numar</th><th>numarVechi</th><th>data</th><th>institutie</th><th>departament</th><th>categorieCaz</th><th>stadiuProcesual</th>";
				echo "<th>obiect</th><th>dataModificare</th><th>parti</th><th>sedinte</th><th>caiAtac</th></tr>";
				foreach ($response->CautareDosareResult->Dosar as $dosar) {
					echo "<tr>";
					echo "<td>" . $dosar->numar . "</td>";
					echo "<td>" . $dosar->numarVechi . "</td>";
					echo "<td style='white-space: normal'>" . str_ireplace('T', ' ', $dosar->data) . "</td>";
					echo "<td style='white-space: normal'>" . str_ireplace('Judecatoria', 'Judecatoria<br>', $dosar->institutie) . "</td>";
					echo "<td>" . $dosar->departament . "</td>";
					echo "<td>" . $dosar->categorieCaz . "</td>";
					// echo "<td>" . $dosar->categorieCazNume . "</td>";
					echo "<td>" . $dosar->stadiuProcesual . "</td>";
					// echo "<td>" . $dosar->stadiuProcesualNume . "</td>";
					echo "<td>" . $dosar->obiect . "</td>";
					echo "<td style='white-space: normal'>" . str_ireplace('T', ' ', $dosar->dataModificare) . "</td>";
					// dosarParte
					echo "<td>";
					if (!empty($dosar->parti->DosarParte)) {
						echo "<table border='1' cellpadding='1' cellspacing='0' style='white-space: nowrap'>";
						echo "<tr><th>numar</th><th>nume</th><th>calitateParte</th></tr>";
						foreach ($dosar->parti->DosarParte as $dosarParte) {
							echo "<tr>";
							echo "<td>" . $dosar->numar . "</td>";
							echo "<td>" . $dosarParte->nume . "</td>";
							echo "<td>" . $dosarParte->calitateParte . "</td>";
							echo "</tr>";
						}
						echo "</table>";
					}
					echo "</td>";
					// dosarSedinta
					echo "<td>";
					if (!empty($dosar->sedinte->DosarSedinta)) {
						echo "<table border='1' cellpadding='1' cellspacing='0' style='white-space: nowrap'>";
						echo "<tr><th>numar</th><th>complet</th><th>data</th><th>ora</th><th>solutie</th><th>solutieSumar</th><th>dataPronuntare</th><th>documentSedinta</th><th>numarDocument</th><th>dataDocument</th></tr>";
						foreach($dosar->sedinte->DosarSedinta as $dosarSedinta) {
							echo "<tr>";
							echo "<td>" . $dosar->numar . "</td>";
							echo "<td>" . $dosarSedinta->complet . "</td>";
							echo "<td>" . $dosarSedinta->data . "</td>";
							echo "<td>" . $dosarSedinta->ora . "</td>";
							echo "<td>" . $dosarSedinta->solutie . "</td>";
							echo "<td>" . $dosarSedinta->solutieSumar . "</td>";
							echo "<td>" . $dosarSedinta->dataPronuntare . "</td>";
							echo "<td>" . $dosarSedinta->documentSedinta . "</td>";
							echo "<td>" . $dosarSedinta->numarDocument . "</td>";
							echo "<td>" . $dosarSedinta->dataDocument . "</td>";
							echo "</tr>";
						}
						echo "</table>";
					}
					echo "</td>";
					// dosarCaleAtac
					echo "<td>";
					if (!empty($dosar->caiAtac->DosarCaleAtac)) {
						echo "<table border='1' cellpadding='1' cellspacing='0' style='white-space: nowrap'>";
						echo "<tr><th>numar</th><th>dataDeclarare</th><th>parteDeclaratoare</th><th>tipCaleAtac</th></tr>";
						foreach($dosar->caiAtac->DosarCaleAtac as $dosarCaleAtac) {
							echo "<tr>";
							echo "<td>" . $dosar->numar . "</td>";
							echo "<td>" . str_ireplace('T',' ', $dosarCaleAtac->dataDeclarare) . "</td>";
							echo "<td>" . $dosarCaleAtac->parteDeclaratoare . "</td>";
							echo "<td>" . $dosarCaleAtac->tipCaleAtac . "</td>";
							echo "</tr>";
						}
						echo "</table>";
					}
					echo "</td>";
					echo "</tr>";
				}
				echo "</table>";
			}
		}
		} catch (\Exception $e) {
			Yii::$app->session->setFlash('error', $e->getMessage());
		}
	}
?>
</div>

