<?php
/*
Template Name: Star Network Speak Page
*/
?>
<?php
/**
 * @author Maxim Bogdanov <sin666m4a1fox@gmail.com>
 * @copyright maxim 12/11/14 | 11:55 AM
 */

$url = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID()));

?>

<?php get_header('star_network'); ?>
    <div class="star-main-content-wrapper" style="background: url('<?php echo $url; ?>') no-repeat; min-height: 600px;">
        <div id="content" class="row widecolumn extra-pages">
            <div class="star-network-content">
                <?php if (have_posts()) : ?>
                    <?php while (have_posts()) : the_post(); ?>
                        <div class="star-network-additional-page-title">
                            <?php echo get_the_title(); ?>
                        </div>

                        <div class="star-network-additional-page-sublime">
                            <?php echo get_the_excerpt(); ?>
                        </div>

                        <?php if (get_the_content() !== ''): ?>
                            <div class="star-network-additional-page-content">
                                <?php the_content(); ?>
                            </div>
                        <?php endif; ?>


                        <div class="form-wrapper">
                            <div id="waitMe"></div>
                            <form id="newsletter-form" method="post">
                                <div class="email-input-wrapper">
                                    <label for="email"></label>
                                    <input type="email" name="email" required="" aria-required="true" id="email"
                                           placeholder="Email Address"
                                           class="news-letter-email-input"
                                           value=""/>
                                </div>
                                <div class="zip-input-wrapper">
                                    <label for="zip"></label>
                                    <input type="text" name="zip" id="zip" placeholder="ZIP code"
                                           class="news-letter-zip-input"
                                           value=""/>
                                </div>
                                <div class="checkboxes">
                                    <div class="checkboxes-title">
                                        Choose from a selection of our targeted newsletters!
                                    </div>
                                    <div>
                                        <label for="eating-drinking-networking-events">
                                            <input type="checkbox" name="cat[eating_drinking]"
                                                   id="eating-drinking-networking-events"
                                                   value="Eating & Drinking"/>
                                            <span class="custom checkbox"></span>
                                            Eating & Drinking

                                        </label>
                                    </div>
                                    <div>
                                        <label for="kids-education">
                                            <input type="checkbox" name="cat[kids_education]" id="kids-education"
                                                   value="Kids & Education"/>
                                            <span class="custom checkbox"></span>
                                            Kids & Education

                                        </label>
                                    </div>
                                    <div>
                                        <label for="business-news-networking-events">
                                            <input type="checkbox" name="cat[business_news_networking_events]"
                                                   id="business-news-networking-events"
                                                   value="Business News & Networking Events"/>
                                            <span class="custom checkbox"></span>
                                            Business News & Networking Events

                                        </label>
                                    </div>
                                    <div>
                                        <label for="real-estate">
                                            <input type="checkbox" name="cat[real_estate]" id="real-estate"
                                                   value="Real Estate"/>
                                            <span class="custom checkbox"></span>
                                            Real Estate

                                        </label>
                                    </div>
                                    <div>
                                        <label for="special-deals-discounts">
                                            <input type="checkbox" name="cat[special_deals_discounts]" id="special-deals-discounts"
                                                   value="Special Deals & Discounts"/>
                                            <span class="custom checkbox"></span>
                                            Special Deals & Discounts

                                        </label>
                                    </div>
                                </div>

                                <div class="data captcha">
                                    <input type="text" id="defaultReal" name="defaultReal" placeholder="Enter Access Code"
                                           required="" aria-required="true" maxlength="6"/>
                                </div>

                                <div class="submit-news-letter-btn submit">
                                    <input type="submit" value="submit" name="submit" class="news-letter-email-submit"/>
                                </div>
                            </form>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>

        </div>

        <div id="content-bottom-bg"></div>
    </div>
<?php get_footer('star_network'); ?>