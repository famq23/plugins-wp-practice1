<?php
/**
* Plugin Name: Ejemplo de plugin-testing
* Plugin URI: https://www.plugin-testing.co
* Description: Este plugin modifica los títulos de las entradas.
* Version: 0.1.0
* Author: Francisco Alonso
* Author URI: https://www.franciscomesa.co
* Requires at least: 4.0
* Tested up to: 5.8
*
* Text Domain: plugin-testing
* Domain path: /languages/
*/
defined("ABSPATH") or die("¡ No puede !");

add_filter( 'the_title', 'pluginTesting_cambiar_titulo', 10, 2 );
/*
function pluginTesting_cambiar_titulo( $title, $id ) {
  $title = ' [Exclusiva] ' . $title;
  return $title;
}
*/
function pluginTesting_cambiar_titulo( $title, $id ) {
  $texto = get_post_meta( $id, '_pluginTesting_extension_titulo', true );
  if ( ! empty( $texto ) ) {
    $title = $title . ' '. $texto;
  }
  return $title;
}

//add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );

add_action( 'add_meta_boxes_post', 'pluginTesting_add_meta_boxes' );
function pluginTesting_add_meta_boxes() {
  add_meta_box(
    'pluginTesting-extension-titulo',
    'Extensión del Título',
    'pluginTesting_print_extension_titulo_meta_box'
  );
}

add_action( 'save_post', 'pluginTesting_save_extension_titulo' );
function pluginTesting_save_extension_titulo( $post_id ) {
  // Si se está guardando de forma automática, no hacemos nada.
  if ( defined( DOING_AUTOSAVE ) && DOING_AUTOSAVE ) {
    return;
 }
 // Si nuestro campo de texto no está disponible, no hacemos nada.
 if ( ! isset( $_REQUEST['pluginTesting-extension-titulo'] ) ) {
   return;
 }
 // Ahora sí, coger el valor del campo de texto y limpiarlo por seguridad.
 $texto = trim( sanitize_text_field( $_REQUEST['pluginTesting-extension-titulo'] ) );
 // Guardarlo en el campo personalizado "_pluginTesting_extension_titulo"
 update_post_meta( $post_id, '_pluginTesting_extension_titulo', $texto );
}

function pluginTesting_print_extension_titulo_meta_box( $post ) {
  $post_id = $post->ID;
  $val = get_post_meta( $post_id, '_pluginTesting_extension_titulo', true ); ?>
  <label for= "pluginTesting-extension-titulo">Texto:</label>
  <input name="pluginTesting-extension-titulo" type="text" value="<?php echo esc_attr( $val ); ?>" />
<?php
}

