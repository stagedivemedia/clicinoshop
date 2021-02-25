<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2012
 * @copyright Aimeos (aimeos.org), 2015-2020
 */

/* Available data:
 * - detailProductItem : Product item incl. referenced items
 */


$enc = $this->encoder();

$optTarget = $this->config( 'client/jsonapi/url/target' );
$optCntl = $this->config( 'client/jsonapi/url/controller', 'jsonapi' );
$optAction = $this->config( 'client/jsonapi/url/action', 'options' );
$optConfig = $this->config( 'client/jsonapi/url/config', [] );

$basketTarget = $this->config( 'client/html/basket/standard/url/target' );
$basketController = $this->config( 'client/html/basket/standard/url/controller', 'basket' );
$basketAction = $this->config( 'client/html/basket/standard/url/action', 'index' );
$basketConfig = $this->config( 'client/html/basket/standard/url/config', [] );
$basketSite = $this->config( 'client/html/basket/standard/url/site' );


/** client/html/basket/require-stock
 * Customers can order products only if there are enough products in stock
 *
 * Checks that the requested product quantity is in stock before
 * the customer can add them to his basket and order them. If there
 * are not enough products available, the customer will get a notice.
 *
 * @param boolean True if products must be in stock, false if products can be sold without stock
 * @since 2014.03
 * @category Developer
 * @category User
 */
$reqstock = (int) $this->config( 'client/html/basket/require-stock', true );


?>

<section class="aimeos catalog-detail" itemscope="" itemtype="http://schema.org/Product" data-jsonurl="<?= $enc->attr( $this->url( $optTarget, $optCntl, $optAction, [], [], $optConfig ) ); ?>">
	<?php if( isset( $this->detailErrorList ) ) : ?>
		<ul class="error-list">
			<?php foreach( (array) $this->detailErrorList as $errmsg ) : ?>
				<li class="error-item"><?= $enc->html( $errmsg ); ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>


      <?php if( isset( $this->detailProductItem ) ) : ?>
            

		<article class="product <?= $this->detailProductItem->getConfigValue( 'css-class' ) ?>" data-id="<?= $this->detailProductItem->getId(); ?>">

                  <div class="frame product-stage frame-halfpadding">
                        <div class="frame-container">
                              <div class="frame-inner row">
                                   
                                    <div class="col-sm-7">
                                          <?= $this->partial(
                                                /** client/html/catalog/detail/partials/image
                                                 * Relative path to the detail image partial template file
                                                 *
                                                 * Partials are templates which are reused in other templates and generate
                                                 * reoccuring blocks filled with data from the assigned values. The image
                                                 * partial creates an HTML block for the catalog detail images.
                                                 *
                                                 * @param string Relative path to the template file
                                                 * @since 2017.01
                                                 * @category Developer
                                                 */
                                                $this->config( 'client/html/catalog/detail/partials/image', 'catalog/detail/image-partial-standard' ),
                                                ['mediaItems' => $this->get( 'detailMediaItems', map() ), 'params' => $this->param()]
                                          ); ?>

                                    </div>

                                    <div class="col-sm-5">

                                          <div class="catalog-detail-basic">
                                                <div class="detail-top-row">
                                                      <h1 class="name" itemprop="name"><?= $enc->html( $this->detailProductItem->getName(), $enc::TRUST ); ?></h1>
                                                </div>
                                                
                                                <p class="code">
                                                      <span class="name"><?= $enc->html( $this->translate( 'client', 'Article no.' ), $enc::TRUST ); ?>: </span>
                                                      <span class="value" itemprop="sku"><?= $enc->html( $this->detailProductItem->getCode() ); ?></span>
                                                </p>

                                                <?php if( !( $textItems = $this->detailProductItem->getRefItems( 'text', 'long' ) )->isEmpty() ) : ?>

                                                      <div class="content description">

                                                            <?php foreach( $textItems as $textItem ) : ?>
                                                                  <p class="long item"><?= $enc->html( $textItem->getContent(), $enc::TRUST ); ?></p>
                                                            <?php endforeach; ?>

                                                      </div>

                                                <?php endif; ?>
                                                

                                                <?php if( $this->detailProductItem->getRating() > 0 ) : ?>
                                                      <div class="rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                                                            <span class="stars"><?= str_repeat( '★', (int) round( $this->detailProductItem->getRating() ) ) ?></span>
                                                            <span class="rating-value" itemprop="ratingValue"><?= $enc->html( $this->detailProductItem->getRating() ) ?></span>
                                                            <span class="ratings" itemprop="reviewCount"><?= (int) $this->detailProductItem->getRatings() ?></span>
                                                      </div>
                                                <?php endif ?>

                                                <?php foreach( $this->detailProductItem->getRefItems( 'text', 'short', 'default' ) as $textItem ) : ?>
                                                      <p class="short" itemprop="description"><?= $enc->html( $textItem->getContent(), $enc::TRUST ); ?></p>
                                                <?php endforeach; ?>

                                          </div>


                                          <div class="catalog-detail-basket" data-reqstock="<?= $reqstock; ?>" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

                                                <div class="price-list">
                                                      <div class="articleitem price price-actual"
                                                            data-prodid="<?= $enc->attr( $this->detailProductItem->getId() ); ?>"
                                                            data-prodcode="<?= $enc->attr( $this->detailProductItem->getCode() ); ?>">

                                                            <?= $this->partial(
                                                                  $this->config( 'client/html/common/partials/price', 'common/partials/price-standard' ),
                                                                  ['prices' => $this->detailProductItem->getRefItems( 'price', null, 'default' )]
                                                            ); ?>
                                                      </div>

                                                      <?php if( $this->detailProductItem->getType() === 'select' ) : ?>
                                                            <?php foreach( $this->detailProductItem->getRefItems( 'product', 'default', 'default' ) as $prodid => $product ) : ?>
                                                                  <?php if( !( $prices = $product->getRefItems( 'price', null, 'default' ) )->isEmpty() ) : ?>

                                                                        <div class="articleitem price"
                                                                              data-prodid="<?= $enc->attr( $prodid ); ?>"
                                                                              data-prodcode="<?= $enc->attr( $product->getCode() ); ?>">

                                                                              <?= $this->partial(
                                                                                    $this->config( 'client/html/common/partials/price', 'common/partials/price-standard' ),
                                                                                    ['prices' => $prices]
                                                                              ); ?>

                                                                        </div>

                                                                  <?php endif; ?>
                                                            <?php endforeach; ?>
                                                      <?php endif; ?>
                                                
                                                </div>


                                                


                                                <form method="POST" action="<?= $enc->attr( $this->url( $basketTarget, $basketController, $basketAction, ( $basketSite ? ['site' => $basketSite] : [] ), [], $basketConfig ) ); ?>">
                                                      <!-- catalog.detail.csrf -->
                                                      <?= $this->csrf()->formfield(); ?>
                                                      <!-- catalog.detail.csrf -->

                                                      <?php if( $basketSite ) : ?>
                                                            <input type="hidden" name="<?= $this->formparam( 'site' ) ?>" value="<?= $enc->attr( $basketSite ) ?>" />
                                                      <?php endif ?>

                                                      <?php if( $this->detailProductItem->getType() === 'select' ) : ?>

                                                            <div class="catalog-detail-basket-selection">

                                                                  <?= $this->partial(
                                                                        /** client/html/common/partials/selection
                                                                         * Relative path to the variant attribute partial template file
                                                                         *
                                                                         * Partials are templates which are reused in other templates and generate
                                                                         * reoccuring blocks filled with data from the assigned values. The selection
                                                                         * partial creates an HTML block for a list of variant product attributes
                                                                         * assigned to a selection product a customer must select from.
                                                                         *
                                                                         * The partial template files are usually stored in the templates/partials/ folder
                                                                         * of the core or the extensions. The configured path to the partial file must
                                                                         * be relative to the templates/ folder, e.g. "partials/selection-standard.php".
                                                                         *
                                                                         * @param string Relative path to the template file
                                                                         * @since 2015.04
                                                                         * @category Developer
                                                                         * @see client/html/common/partials/attribute
                                                                         */
                                                                        $this->config( 'client/html/common/partials/selection', 'common/partials/selection-standard' ),
                                                                        ['productItems' => $this->detailProductItem->getRefItems( 'product', 'default', 'default' )]
                                                                  ); ?>

                                                            </div>

                                                      <?php endif; ?>

                                                      <div class="catalog-detail-basket-attribute">

                                                            <?= $this->partial(
                                                                  /** client/html/common/partials/attribute
                                                                   * Relative path to the product attribute partial template file
                                                                   *
                                                                   * Partials are templates which are reused in other templates and generate
                                                                   * reoccuring blocks filled with data from the assigned values. The attribute
                                                                   * partial creates an HTML block for a list of optional product attributes a
                                                                   * customer can select from.
                                                                   *
                                                                   * The partial template files are usually stored in the templates/partials/ folder
                                                                   * of the core or the extensions. The configured path to the partial file must
                                                                   * be relative to the templates/ folder, e.g. "partials/attribute-standard.php".
                                                                   *
                                                                   * @param string Relative path to the template file
                                                                   * @since 2016.01
                                                                   * @category Developer
                                                                   * @see client/html/common/partials/selection
                                                                   */
                                                                  $this->config( 'client/html/common/partials/attribute', 'common/partials/attribute-standard' ),
                                                                  ['productItem' => $this->detailProductItem]
                                                            ); ?>

                                                      </div>


                                                      <div class="stock-list">
                                                            <div class="articleitem stock-actual"
                                                                  data-prodid="<?= $enc->attr( $this->detailProductItem->getId() ); ?>"
                                                                  data-prodcode="<?= $enc->attr( $this->detailProductItem->getCode() ); ?>">
                                                            </div>

                                                            <?php foreach( $this->detailProductItem->getRefItems( 'product', null, 'default' ) as $articleId => $articleItem ) : ?>

                                                                  <div class="articleitem"
                                                                        data-prodid="<?= $enc->attr( $articleId ); ?>"
                                                                        data-prodcode="<?= $enc->attr( $articleItem->getCode() ); ?>">
                                                                  </div>

                                                            <?php endforeach; ?>

                                                      </div>


                                                      <?php if( !$this->detailProductItem->getRefItems( 'price', 'default', 'default' )->empty() ) : ?>
                                                            <div class="addbasket">
                                                                  <div class="input-group">
                                                                        <input type="hidden" value="add" name="<?= $enc->attr( $this->formparam( 'b_action' ) ); ?>" />
                                                                        <input type="hidden"
                                                                              name="<?= $enc->attr( $this->formparam( ['b_prod', 0, 'prodid'] ) ); ?>"
                                                                              value="<?= $enc->attr( $this->detailProductItem->getId() ); ?>"
                                                                        />
                                                                        <input type="number" class="form-control input-lg" <?= !$this->detailProductItem->isAvailable() ? 'disabled' : '' ?>
                                                                              name="<?= $enc->attr( $this->formparam( ['b_prod', 0, 'quantity'] ) ); ?>"
                                                                              min="<?= $this->detailProductItem->getScale() ?>" max="2147483647"
                                                                              step="<?= $this->detailProductItem->getScale() ?>" maxlength="10"
                                                                              value="<?= $this->detailProductItem->getScale() ?>" required="required"
                                                                        />
                                                                        <button class="btn btn-primary btn-lg btn-add-to-cart" type="submit" value="" <?= !$this->detailProductItem->isAvailable() ? 'disabled' : '' ?> >
                                                                              <?= $enc->html( $this->translate( 'client', 'Add to basket' ), $enc::TRUST ); ?>
                                                                        </button>
                                                                  </div>
                                                            </div>
                                                      <?php endif ?>

                                                </form>

                                          </div>

                                    </div>
                              </div>
                        </div>
                  </div>
			

                  
			<div class="col-sm-12">

				<?php if( $this->detailProductItem->getType() === 'bundle' && !( $products = $this->detailProductItem->getRefItems( 'product', null, 'default' ) )->isEmpty() ) : ?>

					<section class="catalog-detail-bundle">
						<h4 class="header"><?= $this->translate( 'client', 'Bundled products' ); ?></h4>

						<?= $this->partial(
							$this->config( 'client/html/common/partials/products', 'common/partials/products-standard' ),
							['products' => $products, 'itemprop' => 'isRelatedTo']
						); ?>

					</section>

				<?php endif; ?>


				<div class="catalog-detail-additional">

					<?php if( !( $textItems = $this->detailProductItem->getRefItems( 'text', 'long' ) )->isEmpty() ) : ?>

						<div class="additional-box">
							<h4 class="header-description-custom"><?= $enc->html( $this->translate( 'client', 'Description' ), $enc::TRUST ); ?></h4>
							<div class="content-description-custom">

								<?php foreach( $textItems as $textItem ) : ?>
									<div class="long item"><?= $enc->html( $textItem->getContent(), $enc::TRUST ); ?></div>
								<?php endforeach; ?>

							</div>
						</div>

					<?php endif; ?>
                        </div>                             



				<?php if( !( $products = $this->detailProductItem->getRefItems( 'product', null, 'suggestion' ) )->isEmpty() ) : ?>
                              <div class="frame frame-background-light crossselling">
                                    <div class="frame-container">
                                          <div class="frame-inner">
                                                <section class="catalog-detail-suggest">
                                                      <h2 class="header-custom">Das könnte dich auch interessieren</h2>
                                                      <h3 class="subheader-custom">Ähnliche Produkte</h3>

                                                      <?= $this->partial(
                                                            $this->config( 'client/html/common/partials/products', 'common/partials/products-standard' ),
                                                            ['products' => $products, 'itemprop' => 'isRelatedTo']
                                                      ); ?>

                                                </section>
                                          </div>
                                    </div>
                                    
                              </div>
					
				<?php endif; ?>


				<?php if( !( $products = $this->detailProductItem->getRefItems( 'product', null, 'bought-together' ) )->isEmpty() ) : ?>

					<section class="catalog-detail-bought">
						<h4 class="header"><?= $this->translate( 'client', 'Other customers also bought' ); ?></h4>

						<?= $this->partial(
							$this->config( 'client/html/common/partials/products', 'common/partials/products-standard' ),
							['products' => $products, 'itemprop' => 'isRelatedTo']
						); ?>

					</section>

				<?php endif; ?>


				<?= $this->block()->get( 'catalog/detail/supplier' ); ?>

			</div>

		</article>

	<?php endif; ?>

</section>
