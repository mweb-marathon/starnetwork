<?php
/*
Template Name: Star Network Sponsor Page
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
    <div class="star-main-content-wrapper" style="background-position: center top;background: url('<?php echo $url; ?>') no-repeat; min-height: 600px;">
        <div id="content" class="row widecolumn extra-pages">
            <div class="star-network-content-wrapper-bg">
                <div class="star-network-content middle-row">
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
                                <form id="sponsor-form" method="post">
                                    <div class="name-input-wrapper">
                                        <label for="name"></label>
                                        <input type="text" name="first_last" required="" aria-required="true" id="name"
                                               placeholder="First and Last"
                                               class="speak-name-input"
                                               value=""/>
                                    </div>

                                    <div class="title-input-wrapper">
                                        <label for="title"></label>
                                        <input type="text" name="title" required="" aria-required="true" id="title"
                                               placeholder="Title"
                                               class="speak-title-input"
                                               value=""/>
                                    </div>

                                    <div class="company-input-wrapper">
                                        <label for="company"></label>
                                        <input type="text" name="company" required="" aria-required="true" id="company"
                                               placeholder="Company"
                                               class="speak-company-input"
                                               value=""/>
                                    </div>

                                    <div class="email-input-wrapper">
                                        <label for="email"></label>
                                        <input type="email" name="email" required="" aria-required="true" id="email"
                                               placeholder="Email"
                                               class="speak-email-input"
                                               value=""/>
                                    </div>

                                    <div class="phone-input-wrapper">
                                        <label for="phone"></label>
                                        <input type="text" name="phone" required="" aria-required="true" id="phone"
                                               placeholder="Phone"
                                               class="speak-phone-input"
                                               value=""/>
                                    </div>

                                    <div class="textarea">
                                        <textarea placeholder="Events of interest" required="" aria-required="true"
                                                  name="comment" id="comment"></textarea>
                                    </div>

                                    <div class="data captcha">
                                        <input type="text" id="defaultReal" name="defaultReal"
                                               placeholder="Enter Access Code" required="" aria-required="true"
                                               maxlength="6"/>
                                    </div>

                                    <div class="submit-news-letter-btn submit">
                                        <input type="submit" value="submit" name="submit"
                                               class="news-letter-email-submit"/>
                                    </div>
                                </form>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

            </div>

        </div>

        <div id="content-bottom-bg"></div>
    </div>
<?php get_footer('star_network'); ?>