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

/* Registra los Widgets propios del Theme */

// registrar la funcion que se ejecuta al activar el plugin
register_activation_hook(__FILE__, 'helloworld_activate');
 
// funcion que se ejecuta al activar el plugin
function helloworld_activate() {
    // crear la tabla del plugin e insertar dos registros
    global $wpdb;
    $table_name= $wpdb->prefix."helloworld";
 
    $sql = "CREATE TABLE $table_name (
        `id` mediumint( 9 ) NOT NULL auto_increment,
        `type` tinytext NOT NULL,
        `saludo` tinytext NOT NULL,
        PRIMARY KEY  (`id`)
        )";
    $wpdb->query($sql);
 
    $sql = "INSERT INTO $table_name (`id`, `type`, `saludo`)
        VALUES (1, 'default', 'Hello World')";
    $wpdb->query($sql);
 
    $sql = "INSERT INTO $table_name (`id`, `type`, `saludo`)
        VALUES (2, 'custom', 'Hola Mundo')";
    $wpdb->query($sql);
 
    // añadir la option del plugin
    add_option('helloworld_saludo_type', 'default');
}

// registrar la funcion que se ejecuta al desactivar el plugin
register_deactivation_hook(__FILE__, 'helloworld_deactivate');
 
// funcion que se ejecuta al desactivar el plugin
function helloworld_deactivate() {
    // borrar la tabla del plugin
    global $wpdb;
    $table_name = $wpdb->prefix."helloworld";
 
    $sql = "DROP TABLE $table_name";
    $wpdb->query($sql);
 
    // borrar la option del plugin
    delete_option('helloworld_saludo_type');
}

// registrar la funcion que se ejecuta al desactivar el plugin
register_deactivation_hook(__FILE__, 'helloworld_deactivate');
 
// crear un item en el panel de administracion
add_action('admin_menu', 'helloworld_menu');
 
// crear la pagina de opciones del plugin
function helloworld_menu() {
    add_options_page('Hello World plugin options', 'Hello World', 8,
        basename(__FILE__), 'helloworld_options');
}
 
// funcion que muestra la pagina de opciones del plugin
function helloworld_options() {
    // comprobar si la peticion procede del form
    global $wpdb;
    $table_name = $wpdb->prefix."helloworld";
    if (isset($_POST['saludo_custom_new'])
        && !empty($_POST['saludo_custom_new'])) {
        $sql = "UPDATE $table_name
            SET saludo ='{$_POST['saludo_custom_new']}'
            WHERE type='custom'";
        $wpdb->query($sql);
    }
    if (isset($_POST['saludo_type'])) {
        update_option('helloworld_saludo_type', $_POST['saludo_type']);
    }
 
    // mostrar la pagina de opciones
    $saludo_default = $wpdb->get_var("SELECT saludo
        FROM $table_name
        WHERE type='default'" );
    $saludo_custom = $wpdb->get_var("SELECT saludo
        FROM $table_name
        WHERE type='custom'" );
    $saludo_type = get_option('helloworld_saludo_type');
    if ($saludo_type == "default") {
        $checked_default = "checked";
        $checked_custom = "";
    } else {
        $checked_default = "";
        $checked_custom = "checked";
    }
    echo "<div class='wrap'>n";
    echo "<h2>Hello World plugin options</h2>";
    echo "<form method='post' action=''>";
    echo "Display:<br />";
    echo "<input type='radio' name='saludo_type' value='default'
        ".$checked_default." /> Message default
        (<b>".$saludo_default."</b>)<br />";
    echo "<input type='radio' name='saludo_type' value='custom'
        ".$checked_custom." /> Message custom
        (<b>".$saludo_custom."</b>)<br />";
    echo "New Message custom: <input type='text'
        name='saludo_custom_new' /><br />";
    echo "<input type='submit' name='update' value='Update' />";
    echo "</form>";
    echo "</div>";
}

// funcion del plugin para usar en PHP, devuelve un string
function helloworld() {
    // recuperar el saludo
    global $wpdb;
    $table_name = $wpdb->prefix."helloworld";
    $saludo_type = get_option('helloworld_saludo_type');
    $saludo = $wpdb->get_var("SELECT saludo
       FROM $table_name
       WHERE type='$saludo_type'" );
 
    // output del plugin
    return "<p>".$saludo."</p>";
}
?>