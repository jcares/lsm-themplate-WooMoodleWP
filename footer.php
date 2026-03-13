<?php
if ( ! function_exists( 'wp_footer' ) ) {
    exit; // Exit if accessed directly outside WordPress
}
?>
<?php include get_template_directory() . '/parts/footer.php'; ?>
<?php wp_footer(); ?>
</body>
</html>