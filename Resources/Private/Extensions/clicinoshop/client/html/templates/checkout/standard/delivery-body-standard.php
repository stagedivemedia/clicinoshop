<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2015-2020
 */

$enc = $this->encoder();


?>
<?php $this->block()->start( 'checkout/standard/delivery' ); ?>
<section class="checkout-standard-delivery">

	<h4><?= $enc->html( $this->translate( 'client', 'delivery' ), $enc::TRUST ); ?></h4>
	<p class="note"><?= $enc->html( $this->translate( 'client', 'Please choose your delivery method' ), $enc::TRUST ); ?></p>

	<?php foreach( $this->get( 'deliveryServices', [] ) as $id => $service ) : ?>
		<div id="c_delivery-<?= $enc->attr( $id ); ?>" class="item item-service row">

			<div class="custom-delivery">
				<label class="description" for="c_deliveryoption-<?= $enc->attr( $id ); ?>">
                              <div class="service-topline">
                                    <div class="option-left-wrapper">
                                          <input class="option" type="radio"
                                                id="c_deliveryoption-<?= $enc->attr( $id ); ?>"
                                                name="<?= $enc->attr( $this->formparam( ['c_deliveryoption'] ) ); ?>"
                                                value="<?= $enc->attr( $id ); ?>"
                                                <?= $id != $this->get( 'deliveryOption' ) ?: 'checked="checked"' ?>
                                          />
                                          <div class="icons">
                                                <?php foreach( $service->getRefItems( 'media', 'icon', 'default' ) as $mediaItem ) : ?>
                                                      <?= $this->partial(
                                                            $this->config( 'client/html/common/partials/media', 'common/partials/media-standard' ),
                                                            array( 'item' => $mediaItem, 'boxAttributes' => array( 'class' => 'icon' ) )
                                                      ); ?>
                                                <?php endforeach; ?>
                                          </div>
                                    </div>
                                    
                                    

                                    <?php if( $price = $service->price ) : ?>
                                          <?php if( $price->getValue() > 0 ) : ?>
                                                <span class="price-value">
                                                      <?= $enc->html( sprintf( /// Service fee value (%1$s) and shipping cost value (%2$s) with currency (%3$s)
                                                            $this->translate( 'client', '%1$s%3$s + %2$s%3$s' ),
                                                            $this->number( $price->getValue(), $price->getPrecision() ),
                                                            $this->number( $price->getCosts() > 0 ? $price->getCosts() : 0, $price->getPrecision() ),
                                                            $this->translate( 'currency', $price->getCurrencyId() )
                                                      ) ); ?>
                                                </span>
                                          <?php else : ?>
                                                
                                                <span class="price-value">
                                                      <strong>Versandkosten:</strong>
                                                      <?= $enc->html( sprintf(
                                                            /// Price format with price value (%1$s) and currency (%2$s)
                                                            $this->translate( 'client/code', 'price:default', null, 0, false ) ?: $this->translate( 'client', '%1$s %2$s' ),
                                                            $this->number( $price->getCosts() > 0 ? $price->getCosts() : 0, $price->getPrecision() ),
                                                            $this->translate( 'currency', $price->getCurrencyId() )
                                                      ) ); ?>
                                                </span>
                                          <?php endif; ?>

                                    <?php endif; ?>
                              </div>
                              <?php foreach( $service->getRefItems( 'text', null, 'default' ) as $textItem ) : ?>
                                    <?php if( ( $type = $textItem->getType() ) !== 'name' ) : ?>
                                          <p class="<?= $enc->attr( $type ); ?>"><?= $enc->html( $textItem->getContent(), $enc::TRUST ); ?></p>
                                    <?php endif; ?>
                              <?php endforeach; ?>
				</label>
			</div>
		</div>

	<?php endforeach; ?>


	<div class="button-group">
		<a class="btn btn-default btn-lg btn-back" href="<?= $enc->attr( $this->get( 'standardUrlBack' ) ); ?>">
			<?= $enc->html( $this->translate( 'client', 'Previous' ), $enc::TRUST ); ?>
		</a>
		<button class="btn btn-primary btn-lg btn-action">
			<?= $enc->html( $this->translate( 'client', 'Next' ), $enc::TRUST ); ?>
		</button>
	</div>

</section>
<?php $this->block()->stop(); ?>
<?= $this->block()->get( 'checkout/standard/delivery' ); ?>
