<?php
/**
 * Plugin Name: Irontec Demo2
 * Plugin URI: https://diselan.com
 * Description: Este plugin es de prueba para Irontec y solo es un saludo.
 * Version: 1.0.0
 * Author: Jose A. Garrido
 * Author URI: https://diselan.com
 * Text Domain: irontec-demo2
 */

/* Registra los Widgets propios del Theme */

// registrar la funcion que se ejecuta al activar el plugin
register_activation_hook(__FILE__, 'holamundo_activate');
 
// funcion que se ejecuta al activar el plugin
function holamundo_activate() {
    // crear la tabla del plugin e insertar dos registros
    global $wpdb;
    $table_name= $wpdb->prefix."holamundo";
 
    $sql = "CREATE TABLE $table_name (
        `id` mediumint( 9 ) NOT NULL auto_increment,
        `type` tinytext NOT NULL,
        `saludo` tinytext NOT NULL,
        PRIMARY KEY  (`id`)
        )";
    $wpdb->query($sql);
 
    $sql = "INSERT INTO $table_name (`id`, `type`, `saludo`)
        VALUES (1, 'default', 'Hola Mundo')";
    $wpdb->query($sql);
 
    $sql = "INSERT INTO $table_name (`id`, `type`, `saludo`)
        VALUES (2, 'custom', 'Hola Mundo')";
    $wpdb->query($sql);
 
    // añadir la option del plugin
    add_option('holamundo_saludo_type', 'default');
}

// registrar la funcion que se ejecuta al desactivar el plugin
register_deactivation_hook(__FILE__, 'holamundo_deactivate');
 
// funcion que se ejecuta al desactivar el plugin
function holamundo_deactivate() {
    // borrar la tabla del plugin
    global $wpdb;
    $table_name = $wpdb->prefix."holamundo";
 
    $sql = "DROP TABLE $table_name";
    $wpdb->query($sql);
 
    // borrar la option del plugin
    delete_option('holamundo_saludo_type');
}

// registrar la funcion que se ejecuta al desactivar el plugin
register_deactivation_hook(__FILE__, 'holamundo_deactivate');
 
// crear un item en el panel de administracion
add_action('admin_menu', 'holamundo_menu');
 
// crear la pagina de opciones del plugin
function holamundo_menu() {
    add_options_page('Hola Mundo plugin options', 'Hola Mundo', 8,
        basename(__FILE__), 'holamundo_options');
}
 
// funcion que muestra la pagina de opciones del plugin
function holamundo_options() {
    // comprobar si la peticion procede del form
    global $wpdb;
    $table_name = $wpdb->prefix."holamundo";
    if (isset($_POST['saludo_custom_new'])
        && !empty($_POST['saludo_custom_new'])) {
        $sql = "UPDATE $table_name
            SET saludo ='{$_POST['saludo_custom_new']}'
            WHERE type='custom'";
        $wpdb->query($sql);
    }
    if (isset($_POST['saludo_type'])) {
        update_option('holamundo_saludo_type', $_POST['saludo_type']);
    }
 
    // mostrar la pagina de opciones
    $saludo_default = $wpdb->get_var("SELECT saludo
        FROM $table_name
        WHERE type='default'" );
    $saludo_custom = $wpdb->get_var("SELECT saludo
        FROM $table_name
        WHERE type='custom'" );
    $saludo_type = get_option('holamundo_saludo_type');
    if ($saludo_type == "default") {
        $checked_default = "checked";
        $checked_custom = "";
    } else {
        $checked_default = "";
        $checked_custom = "checked";
    }
    echo "<div class='wrap'>";
    echo "<h2>Hola Mundo plugin options</h2>";
    echo "<form method='post' action=''>";
    echo "Display:<br />";
    echo "<input type='radio' name='saludo_type' value='default'
        ".$checked_default." /> Mensaje por defecto
        (<b>".$saludo_default."</b>)<br />";
    echo "<input type='radio' name='saludo_type' value='custom'
        ".$checked_custom." /> Mensaje por defecto
        (<b>".$saludo_custom."</b>)<br />";
    echo "New Message custom: <input type='text'
        name='saludo_custom_new' /><br />";
    echo "<input type='submit' name='update' value='Update' />";
    echo "</form>";
    echo "</div>";
}

// funcion del plugin para usar en PHP, devuelve un string
function holamundo() {
    // recuperar el saludo
    global $wpdb;
    $table_name = $wpdb->prefix."holamundo";
    $saludo_type = get_option('holamundo_saludo_type');
    $saludo = $wpdb->get_var("SELECT saludo
       FROM $table_name
       WHERE type='$saludo_type'" );
 
    // output del plugin
    return "<p>".$saludo."</p>";
}
?>