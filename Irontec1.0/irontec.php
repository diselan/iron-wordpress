<?php
/**
 * Plugin Name: Irontec Demo
 * Plugin URI: https://diselan.com
 * Description: Este plugin es de prueba para Irontec y solo es un saludo.
 * Version: 1.0.0
 * Author: Jose A. Garrido
 * Author URI: https://diselan.com
 * Text Domain: irontec-demo
 * Domain Path: /languages/
 */

function get_text() {
	/** These are the lyrics to Hello Dolly */
	$texto = "Hola Irontec
	El plugin está en marcha.
	Espero que sea suficiente
	como demo 1.";

	// Here we split it into lines
	$texto = explode( "\n", $texto );

	// And then randomly choose a line
	return wptexturize( $texto[ mt_rand( 0, count( $texto ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later
function texto_irontec() {
	$chosen = get_text();
	echo "<p id='irontec'>$chosen</p>";
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'admin_notices', 'hello_dolly' );

// We need some CSS to position the paragraph
function irontec_css() {
	// This makes sure that the positioning is also good for right-to-left languages
	$x = is_rtl() ? 'left' : 'right';

	echo "
	<style type='text/css'>
	#irontec{
		float: $x;
		padding-$x: 15px;
		padding-top: 5px;
		margin: 0;
		font-size: 11px;
	}
	</style>
	";
}

add_action( 'admin_head', 'irontec_css' );

?>
