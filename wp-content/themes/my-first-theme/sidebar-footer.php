<?php
if ( ! is_active_sidebar( 'footer-sidebar' ) ) {
    return;
}
?>
<footer-sidebar class="footer-widget-area">
    <?php dynamic_sidebar( 'footer-sidebar' ); ?>
</footer-sidebar>