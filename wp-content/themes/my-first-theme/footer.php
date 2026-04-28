<?php 
$footer_text = apply_filters('my_theme_footer_text', '© 2026 我的网站. All rights reserved.');
?>
<p class="site-footer">
    <?php echo esc_html($footer_text); ?>
</p>
<?php wp_footer(); ?>
</body>
</html>