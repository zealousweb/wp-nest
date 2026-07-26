<?php
/**
 * The template for displaying site footer.
 *
 * @package WPNest
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}
?>
	<footer class="self-end w-full h-auto py-12 leading-normal text-center bg-primary">
		<!-- Footer content goes here -->
	</footer>

</div>

<?php wp_footer(); ?>

</body>
</html>
