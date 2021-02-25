<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2020
 */

$enc = $this->encoder();

$basketTarget = $this->config( 'client/html/basket/standard/url/target' );
$basketController = $this->config( 'client/html/basket/standard/url/controller', 'basket' );
$basketAction = $this->config( 'client/html/basket/standard/url/action', 'index' );
$basketConfig = $this->config( 'client/html/basket/standard/url/config', [] );

$checkoutTarget = $this->config( 'client/html/checkout/standard/url/target' );
$checkoutController = $this->config( 'client/html/checkout/standard/url/controller', 'checkout' );
$checkoutAction = $this->config( 'client/html/checkout/standard/url/action', 'index' );
$checkoutConfig = $this->config( 'client/html/checkout/standard/url/config', [] );

$optTarget = $this->config( 'client/jsonapi/url/target' );
$optCntl = $this->config( 'client/jsonapi/url/controller', 'jsonapi' );
$optAction = $this->config( 'client/jsonapi/url/action', 'options' );
$optConfig = $this->config( 'client/jsonapi/url/config', [] );


?>
<section class="aimeos checkout-standard" data-jsonurl="<?= $enc->attr( $this->url( $optTarget, $optCntl, $optAction, [], [], $optConfig ) ); ?>">

	<nav>
		<div class="steps">

			<div class="step active basket">
				<a href="<?= $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, [], [], $basketConfig ) ); ?>">
					<?= $enc->html( $this->translate( 'client', 'Basket' ), $enc::TRUST ); ?>
				</a>
			</div>

                  <?php foreach( $this->get( 'standardStepsBefore', [] ) as $name ) : ?>
                        <div class="checketiy check-green"></div>
				<div class="step active <?= $name ?>">
					<a href="<?= $enc->attr( $this->url( $checkoutTarget, $checkoutController, $checkoutAction, ['c_step' => $name], [], $checkoutConfig ) ); ?>">
						<?= $enc->html( $this->translate( 'client', $name ) ); ?>
					</a>
				</div>
			<?php endforeach; ?>

                  <?php if( $this->get( 'standardStepActive', false ) ) : ?>
                        <div class="checketiy check-grey"></div>
				<div class="step current <?= $this->get( 'standardStepActive', false ) ?>">
					<?= $enc->html( $this->translate( 'client', $this->get( 'standardStepActive', false ) ) ); ?>
				</div>
			<?php endif ?>

                  <?php foreach( $this->get( 'standardStepsAfter', [] ) as $name ) : ?>
				<div class="step <?= $name ?>">
					<?= $enc->html( $this->translate( 'client', $name ) ); ?>
                        </div>
                        
			<?php endforeach; ?>
                        
		</div>
	</nav>


	<?php if( isset( $this->standardErrorList ) ) : ?>
		<ul class="error-list">
			<?php foreach( (array) $this->standardErrorList as $errmsg ) : ?>
				<li class="error-item"><?= $enc->html( $errmsg ); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

<div class="row">
      <div class="col-md-8">
            <form method="<?= $enc->attr( $this->get( 'standardMethod', 'POST' ) ); ?>" action="<?= $enc->attr( $this->get( 'standardUrlNext' ) ); ?>">
                  <?= $this->csrf()->formfield(); ?>
                  <?= $this->get( 'standardBody' ); ?>
            </form>
      </div>
      <div class="small-summary col-md-4">
            <div class="basket">
                        <?= $this->partial(
                              /** client/html/basket/standard/summary/detail
                               * Location of the detail partial template for the basket standard component
                               *
                               * To configure an alternative template for the detail partial, you
                               * have to configure its path relative to the template directory
                               * (usually client/html/templates/). It's then used to display the
                               * product detail block in the basket standard component.
                               *
                               * @param string Relative path to the detail partial
                               * @since 2017.01
                               * @category Developer
                               */
                              $this->config( 'client/html/basket/standard/summary/detail', 'common/summary/detail-small' ),
                              array(
                                    'summaryEnableModify' => true,
                                    'summaryBasket' => $this->standardBasket,
                                    'summaryTaxRates' => $this->get( 'standardTaxRates', [] ),
                                    'summaryNamedTaxes' => $this->get( 'standardNamedTaxes', [] ),
                                    'summaryErrorCodes' => $this->get( 'standardErrorCodes', [] ),
                                    'summaryCostsPayment' => $this->get( 'standardCostsPayment', 0 ),
                                    'summaryCostsDelivery' => $this->get( 'standardCostsDelivery', 0 ),
                              )
                        ); ?>
                  </div>
            </div>
      </div>
</section>
