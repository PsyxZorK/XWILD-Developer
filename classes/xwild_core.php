<?php

class Xwild_core
{
    public static $instance;
    private static $initiated = false;

    public static function init(){
        if ( ! self::$initiated ) {
            self::init_hooks();
        }
    }
    private static function init_hooks(){
        self::$initiated = true;
        $xwild_core = Xwild_core::get_instance();

        add_action('admin_menu', array($xwild_core, 'add_page_admins'), 1);
        add_action('admin_init', array($xwild_core, 'register_settings'));

        add_filter('plugin_action_links_' . XWILD_FW_PLUGIN_DIR, array($xwild_core, 'own_actions_links'));
        add_filter('plugin_row_meta', array($xwild_core, 'plugin_meta'), 1, 2);
    }
    public function add_page_admins() {
        add_menu_page(
            'Настройки ASTM Темы',
            'Настройки',
            'manage_options',//права страницы
            'xwild_fw_index',//короткий адрес страницы
            array( $this, 'get_xwild_fw_index' ),//функция страницы
            'dashicons-admin-tools',//иконка плагина
            3
        );
    }
    public function get_xwild_fw_index() {
        ?>
        <div class="wrap">
            <h1>Настройки ASTM Темы</h1>
            <form action="options.php" method="post" novalidate="novalidate">
                <?php settings_fields( 'xwild-astm_setting' ); ?>
                <div class="card" style="max-width:100%">
                    <?php do_settings_sections( 'xwild_fw_index' ); ?>
                </div>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function own_actions_links($links) {
        unset( $links['edit'] );
        $links['settings']   = '<a href="admin.php?page=xwild_fw_index">' . __( 'Settings' ) . '</a>';
        $links['deactivate'] = '<span id="xwild_deactivate">' . $links['deactivate'] . '</span>';

        return $links;
    }
    public function plugin_meta( $links, $file ) {
        if ( $file == XWILD_FW_PLUGIN_DIR ) {
            $href    = admin_url( 'admin.php?page=xwild_fw_index' );
            $anchor  = __( 'Settings' );
            $links[] = "<a href='{$href}'>{$anchor}</a>";
            $links[] = 'Code is poetry!';
        }

        return $links;
    }

    public function register_settings() {
        // sections group search
        add_settings_section(
            'xwild-astm_setting', // id
            __( 'Основные настройки', 'astm' ), // title
            '__return_null', // callback
            'xwild_fw_index' // page
        );
        add_settings_section(
            'xwild-astm_contact', // id
            __( 'Контактные данные', 'astm' ), // title
            '__return_null', // callback
            'xwild_fw_index' // page
        );
        //option fields
        register_setting( 'xwild-astm_setting', 'test_setting_textarea' );
        add_settings_field(
            'test_setting_textarea', // id
            __( 'Test', 'astm' ), // title
            array( $this, 'render_settings_field' ), // callback
            'xwild_fw_index', // page
            'xwild-astm_setting', // section
            array(
                'label_for' => 'test_setting_textarea',
                'type'      => 'textarea',
                'descr'     => '',
            ) // args
        );
        //option fields
        register_setting( 'xwild-astm_setting', 'test_setting_select' );
        add_settings_field(
            'test_setting_select', // id
            __( 'Test', 'astm' ), // title
            array( $this, 'render_settings_field' ), // callback
            'xwild_fw_index', // page
            'xwild-astm_setting', // section
            array(
                'label_for' => 'test_setting_select',
                'type'      => 'select',
                'descr'     => '',
                'values' => array(
                    'page' => __( 'Test 1', 'astm' ),
                    'group'   => __( 'Test 2', 'astm' ),
                ),
                'default' => 'page',
            ) // args
        );
        //option fields
        register_setting( 'xwild-astm_setting', 'test_setting_checkbox' );
        add_settings_field(
            'test_setting_checkbox', // id
            __( 'Test', 'astm' ), // title
            array( $this, 'render_settings_field' ), // callback
            'xwild_fw_index', // page
            'xwild-astm_setting', // section
            array(
                'label_for' => 'test_setting_checkbox',
                'type'      => 'checkbox',
                'descr'     => '',

            ) // args
        );
        //option fields
        register_setting( 'xwild-astm_setting', 'test_setting_radio' );
        add_settings_field(
            'test_setting_radio', // id
            __( 'Test', 'astm' ), // title
            array( $this, 'render_settings_field' ), // callback
            'xwild_fw_index', // page
            'xwild-astm_setting', // section
            array(
                'label_for' => 'test_setting_radio',
                'type'      => 'radio',
                'descr'     => '',
                'values' => array(
                    'page' => __( 'Test 1', 'astm' ),
                    'group'   => __( 'Test 2', 'astm' ),
                ),
                'default' => 'page',
            ) // args
        );
    }

    public function render_settings_field( $atts ) {
        $id   = $atts['label_for'];
        $type = $atts['type'];
        switch ($type){
            default:
                $form_option = esc_attr( get_option( $id ) );
                echo "<input name=\"{$id}\" type=\"{$type}\" id=\"{$id}\" value=\"{$form_option}\" />";
                break;
            case 'textarea':
                $form_option = esc_attr( get_option( $id ) );
                echo "<textarea name=\"{$id}\" type=\"{$type}\" id=\"{$id}\" />{$form_option}</textarea>";
                break;
            case 'checkbox':
                $checked = checked( '1', get_option( $id ), false );
                echo '<label>';
                echo "<input name=\"{$id}\" type=\"checkbox\" id=\"{$id}\" value=\"1\" {$checked} />\n";
                echo __( 'Включить' );
                echo '</label>';
                break;
            case 'radio':
                $current = get_option($id);
                $default = $atts['default'];
                foreach ( $atts['values'] as $value => $title ) {
                    if($current){
                        $checked = checked( $value, $current, false );
                        echo '<label>';
                        echo "<input name=\"{$id}\" type=\"radio\" id=\"{$id}\" value=\"{$value}\" {$checked} />\n";
                        echo @$title;
                        echo '</label><br>';
                    }else{
                        $checked = checked( $value, $default, false );
                        echo '<label>';
                        echo "<input name=\"{$id}\" type=\"radio\" id=\"{$id}\" value=\"{$value}\" {$checked} />\n";
                        echo @$title;
                        echo '</label><br>';
                    }
                }
                break;
            case 'select':
                $current = get_option($id);
                $default = $atts['default'];
                echo '<label>';
                echo "<select name=\"{$id}\" id=\"{$id}\">";
                foreach ( $atts['values'] as $value => $title ) {
                    if($current){
                        $selected = selected( $value, $current, false );
                        echo "<option value=\"{$value}\" {$selected}>{$title}</option>";
                    }else{
                        $select = selected( $value, $default, false );
                        echo "<option value=\"{$value}\" {$select}>{$title}</option>";
                    }
                }
                echo '</select>';
                echo '</label>';
                break;
        }
        if (array_key_exists('descr', $atts)){
            echo "<p class=\"description\">{$atts['descr']}</p>";
        }
    }
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}