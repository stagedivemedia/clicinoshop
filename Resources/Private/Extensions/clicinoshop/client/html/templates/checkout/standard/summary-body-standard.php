<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2020
 */

$enc = $this->encoder();


$basketTarget = $this->config( 'client/html/basket/standard/url/target' );
$basketCntl = $this->config( 'client/html/basket/standard/url/controller', 'basket' );
$basketAction = $this->config( 'client/html/basket/standard/url/action', 'index' );
$basketConfig = $this->config( 'client/html/basket/standard/url/config', [] );

$coTarget = $this->config( 'client/html/checkout/standard/url/target' );
$coCntl = $this->config( 'client/html/checkout/standard/url/controller', 'checkout' );
$coAction = $this->config( 'client/html/checkout/standard/url/action', 'index' );
$coConfig = $this->config( 'client/html/checkout/standard/url/config', [] );

$checkoutAddressUrl = $this->url( $coTarget, $coCntl, $coAction, array( 'c_step' => 'address' ), [], $coConfig );
$checkoutDeliveryUrl = $this->url( $coTarget, $coCntl, $coAction, array( 'c_step' => 'delivery' ), [], $coConfig );
$checkoutPaymentUrl = $this->url( $coTarget, $coCntl, $coAction, array( 'c_step' => 'payment' ), [], $coConfig );
$basketUrl = $this->url( $basketTarget, $basketCntl, $basketAction, [], [], $basketConfig );


?>
<?php $this->block()->start( 'checkout/standard/summary' ); ?>
<section class="checkout-standard-summary common-summary">
	<input type="hidden" name="<?= $enc->attr( $this->formparam( array( 'cs_order' ) ) ); ?>" value="1" />

	<h3><?= $enc->html( $this->translate( 'client', 'summary' ), $enc::TRUST ); ?></h3>
	<p class="note"><?= $enc->html( $this->translate( 'client', 'Please check your order' ), $enc::TRUST ); ?></p>


	<div class="common-summary-address row">
		<div class="item payment <?= !$this->value( $this->get( 'summaryErrorCodes', [] ), 'address/payment' ) ?: 'error' ?> col-sm-6">
			<div class="header">
				<a class="modify" href="<?= $enc->attr( $checkoutAddressUrl ); ?>">
					<?= $enc->html( $this->translate( 'client', 'Change' ), $enc::TRUST ); ?>
				</a>
				<h4><?= $enc->html( $this->translate( 'client', 'Billing address' ), $enc::TRUST ); ?></h4>
			</div>

			<div class="content">
				<?php if( $addresses = $this->standardBasket->getAddress( 'payment' ) ) : ?>
					<?= $this->partial(
						/** client/html/checkout/standard/summary/address
						 * Location of the address partial template for the checkout summary
						 *
						 * To configure an alternative template for the address partial, you
						 * have to configure its path relative to the template directory
						 * (usually client/html/templates/). It's then used to display the
						 * payment or delivery address block on the summary page during the
						 * checkout process.
						 *
						 * @param string Relative path to the address partial
						 * @since 2017.01
						 * @category Developer
						 * @see client/html/checkout/standard/summary/detail
						 * @see client/html/checkout/standard/summary/options
						 * @see client/html/checkout/standard/summary/service
						 */
						$this->config( 'client/html/checkout/standard/summary/address', 'common/summary/address-standard' ),
						['addresses' => $addresses, 'type' => 'payment']
					); ?>
				<?php endif; ?>
			</div>
		</div><!--

		--><div class="item delivery <?= !$this->value( $this->get( 'summaryErrorCodes', [] ), 'address/delivery' ) ?: 'error' ?> col-sm-6">
			<div class="header">
				<a class="modify" href="<?= $enc->attr( $checkoutAddressUrl ); ?>">
					<?= $enc->html( $this->translate( 'client', 'Change' ), $enc::TRUST ); ?>
				</a>
				<h4><?= $enc->html( $this->translate( 'client', 'Delivery address' ), $enc::TRUST ); ?></h4>
			</div>

			<div class="content">
				<?php if( $addresses = $this->standardBasket->getAddress( 'delivery' ) ) : ?>
					<?= $this->partial(
						$this->config( 'client/html/checkout/standard/summary/address', 'common/summary/address-standard' ),
						['addresses' => $addresses, 'type' => 'delivery']
					); ?>
				<?php else : ?>
					<?= $enc->html( $this->translate( 'client', 'like billing address' ), $enc::TRUST ); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>


	<div class="common-summary-service row">
		<div class="item delivery <?= !$this->value( $this->get( 'summaryErrorCodes', [] ), 'service/delivery' ) ?: 'error' ?> col-sm-6">
			<div class="header">
				<a class="modify" href="<?= $enc->attr( $checkoutDeliveryUrl ); ?>">
					<?= $enc->html( $this->translate( 'client', 'Change' ), $enc::TRUST ); ?>
				</a>
				<h4><?= $enc->html( $this->translate( 'client', 'delivery' ), $enc::TRUST ); ?></h4>
			</div>

			<div class="content">
				<?php if( $services = $this->standardBasket->getService( 'delivery' ) ) : ?>
					<?= $this->partial(
						/** client/html/checkout/standard/summary/service
						 * Location of the service partial template for the checkout summary
						 *
						 * To configure an alternative template for the service partial, you
						 * have to configure its path relative to the template directory
						 * (usually client/html/templates/). It's then used to display the
						 * payment or delivery service block on the summary page during the
						 * checkout process.
						 *
						 * @param string Relative path to the service partial
						 * @since 2017.01
						 * @category Developer
						 * @see client/html/checkout/standard/summary/address
						 * @see client/html/checkout/standard/summary/detail
						 * @see client/html/checkout/standard/summary/options
						 */
						$this->config( 'client/html/checkout/standard/summary/service', 'common/summary/service-standard' ),
						['service' => $services, 'type' => 'delivery']
					); ?>
				<?php endif; ?>
			</div>
		</div><!--

		--><div class="item payment <?= !$this->value( $this->get( 'summaryErrorCodes', [] ), 'service/payment' ) ?: 'error' ?> col-sm-6">
			<div class="header">
				<a class="modify" href="<?= $enc->attr( $checkoutPaymentUrl ); ?>">
					<?= $enc->html( $this->translate( 'client', 'Change' ), $enc::TRUST ); ?>
				</a>
				<h4><?= $enc->html( $this->translate( 'client', 'payment' ), $enc::TRUST ); ?></h4>
			</div>

			<div class="content">
				<?php if( $services = $this->standardBasket->getService( 'payment' ) ) : ?>
					<?= $this->partial(
						$this->config( 'client/html/checkout/standard/summary/service', 'common/summary/service-standard' ),
						['service' => $services, 'type' => 'payment']
					); ?>
				<?php endif; ?>
			</div>
		</div>

	</div>


	<div class="checkout-standard-summary-option row">
		<?= $this->partial(
			/** client/html/checkout/standard/summary/options
			 * Location of the options partial template for the checkout summary
			 *
			 * To configure an alternative template for the options partial, you
			 * have to configure its path relative to the template directory
			 * (usually client/html/templates/). It's then used to display the
			 * options block on the summary page during the checkout process.
			 *
			 * @param string Relative path to the options partial
			 * @since 2017.01
			 * @category Developer
			 * @see client/html/checkout/standard/summary/address
			 * @see client/html/checkout/standard/summary/detail
			 * @see client/html/checkout/standard/summary/service
			 */
			$this->config( 'client/html/checkout/standard/summary/options', 'checkout/standard/option-partial-standard' ),
			['standardBasket' => $this->standardBasket, 'errors' => $this->get( 'summaryErrorCodes', [] )]
		); ?>
	</div>


	


	<div class="button-group">
		<a class="btn btn-default btn-lg btn-back" href="<?= $enc->attr( $this->get( 'standardUrlBack' ) ); ?>">
			<?= $enc->html( $this->translate( 'client', 'Back' ), $enc::TRUST ); ?>
		</a>
		<button class="btn btn-primary btn-lg btn-action">
			<?= $enc->html( $this->translate( 'client', 'Buy now' ), $enc::TRUST ); ?>
		</button>
	</div>

</section>
<?php $this->block()->stop(); ?>
<?= $this->block()->get( 'checkout/standard/summary' ); ?>
