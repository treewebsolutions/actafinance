<?php
/* @var $this \yii\web\View */

use yii\helpers\Html;
use tws\helpers\Url;
?>
<div class="footer">
	<div class="footer-wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
					<a href="<?= Url::to(['/site/index']) ?>">
						<div class="footer-logo mb30 " style="max-width: 160px;">
							<img src="<?= Url::to('@uploads/' . Yii::$app->settings->get('appLogo')) ?: Url::to('@web/img/logo.png') ?>" alt="<?= Yii::$app->name ?>">
						</div>
					</a>
					<?php if ($appDescription = Yii::$app->settings->get('appDescription', 'general')): ?>
						<p class="app-description"><?= $appDescription[Yii::$app->language] ?></p>
					<?php endif; ?>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					<div class="footer-widget mb30">
						<h3 class="footer-title"><?= Yii::t('frontend', 'Contact Us') ?></h3>
						<div class="contact-info">
							<?php
								$contact = Yii::$app->settings->getCategory('contact');
								$address = implode(', ', array_filter([
									$contact['streetName'],
									$contact['streetNumber'],
									$contact['locality'],
									$contact['zipCode'],
									$contact['county'],
									$contact['country'] ? \common\models\Country::findAllCountries()[$contact['country']]->translation->name : null,
								]));
							?>
							<ul class="listnone">
								<?php if ($address): ?>
									<li><i class="icon icon-primary icon-placeholder"></i>
										<?php if ($contact['latitude'] && $contact['longitude']): ?>
											<a class="link-underline" href="//www.google.com/maps/place/<?= $contact['latitude'] ?>,<?= $contact['longitude'] ?>" target="_blank"><?= $address ?></a>
										<?php else: ?>
											<?= $address ?>
										<?php endif; ?>
									</li>
								<?php endif; ?>
								<?php if ($contact['mobilePhone'] || $contact['fixedPhone']): ?>
									<li><i class="icon icon-primary icon-phone-call"></i>
										<?= implode(', ', array_filter([
											$contact['mobilePhone'] ? Html::a($contact['mobilePhone'], "tel:{$contact['mobilePhone']}", ['class' => 'link-underline']) : null,
											$contact['fixedPhone'] ? Html::a($contact['fixedPhone'], "tel:{$contact['fixedPhone']}", ['class' => 'link-underline']) : null,
										])) ?>
									</li>
								<?php endif; ?>
								<?php if ($contact['email']): ?>
									<li><i class=" icon icon-primary icon-envelope"></i>
										<a class="link-underline" href="mailto:<?= $contact['email'] ?>"><?= $contact['email'] ?></a>
									</li>
								<?php endif; ?>
							</ul>
						</div>
						<?php if ($contact['schedule'][Yii::$app->language]): ?>
							<div class="time-schedule">
								<?= nl2br($contact['schedule'][Yii::$app->language]) ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
					<?php if ($navMenu = \common\models\Menu::findDefaultFooterMenu()): ?>
					<div class="footer-widget mb30">
						<h3 class="footer-title"><?= Yii::t('frontend', 'Quick Links') ?></h3>
						<?= \common\widgets\sitemenu\SiteNav::widget([
							'options' => [
								'class' => 'listnone',
							],
							'activateParents' => true,
							'encodeLabels' => false,
							'items' => $navMenu->getNestedItems(),
						]) ?>
					</div>
					<?php endif; ?>
				</div>
				<div class="col-lg-3">
					<div class="footer-social">
						<h3 class="footer-title"><?= Yii::t('frontend', 'Follow Us') ?></h3>
						<?php $socialNetwork = Yii::$app->settings->getCategory('socialNetwork'); ?>
						<ul class="listnone">
							<?php if ($socialNetwork['facebookPage']): ?>
								<li>
									<a href="<?= $socialNetwork['facebookPage'] ?>"><i class="fa fa-facebook-square"></i> Facebook</a>
								</li>
							<?php endif; ?>
							<?php if ($socialNetwork['instagramPage']): ?>
								<li>
									<a href="<?= $socialNetwork['instagramPage'] ?>"><i class="fa fa-instagram"> </i>Instagram</a>
								</li>
							<?php endif; ?>
							<?php if ($socialNetwork['twitterPage']): ?>
								<li>
									<a href="<?= $socialNetwork['twitterPage'] ?>"><i class="fa fa-twitter-square"></i> Twitter</a>
								</li>
							<?php endif; ?>
							<?php if ($socialNetwork['linkedinPage']): ?>
								<li>
									<a href="<?= $socialNetwork['linkedinPage'] ?>"><i class="fa fa-linkedin-square"></i> Linked In</a>
								</li>
							<?php endif; ?>
							<?php if ($socialNetwork['pinterestPage']): ?>
								<li>
									<a href="<?= $socialNetwork['pinterestPage'] ?>"><i class="fa fa-pinterest-square"></i> Pinterest</a>
								</li>
							<?php endif; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="tiny-footer">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<?= Yii::t('common', '&copy; {0} {1}. All rights reserved.', [Yii::$app->name, date('Y')]) ?>
				<a class="dev-by-tws link-underline" href="//www.treewebsolutions.com" target="_blank" title="Tree Web Solutions"><?= Yii::t('common', 'Developed by {0}.', 'TWS') ?></a>
			</div>
		</div>
	</div>
</div>
<a class="btn btn-default btn-jump-top skip-link" href="#page-header" title="Salt la început">
	<span class="sr-only">Salt la început</span>
	<span class="fa fa-angle-up"></span>
</a>
<?php
$this->registerJs('
	$("#navigation").menumaker({
		image: \'<div class="logo" style="max-width: 160px;"><img src="' . (Url::to('@uploads/' . Yii::$app->settings->get('appLogo')) ?: Url::to('@web/img/logo.png')) . '" alt="' . Yii::$app->name .'"></div>\',
		format: "multitoggle"
	});
');
?>
