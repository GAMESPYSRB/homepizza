<?php
// The 404 Page template file.
get_header(); ?>
    <div id="content">
    <div id="lafka_page_title" class="lafka_title_holder">
        <div class="inner fixed">
            <div class="lafka-title-text-container">
                <?php lafka_breadcrumb() ?>
                <h1 class="heading-title"><?php esc_html_e( 'Strana nije pronađena', 'lafka' ) ?></h1>
            </div>
        </div>
    </div>
    <div class="inner">
        <div id="main" class="fixed box box-common">
            <div class="content_holder">
                <p><?php esc_html_e( 'Ništa nije pronađeno na ovoj lokaciji. Probajte da pretražite.', 'lafka' ); ?></p>
				<?php get_search_form(); ?>
            </div>
        </div>
    </div>
<?php
get_footer();
