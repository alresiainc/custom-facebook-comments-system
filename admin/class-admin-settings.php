<?php
class Admin_Settings
{
    public static function display()
    {
?>
        <div class="wrap">
            <h1>Plugin Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('cfcs_plugin_options');
                do_settings_sections('cfcs_plugin');
                submit_button();
                ?>
            </form>
        </div>
<?php
    }

    public static function init()
    {
        add_action('admin_init', [__CLASS__, 'setup_settings']);
    }

    public static function setup_settings()
    {
        register_setting('cfcs_plugin_options', 'cfcs_plugin_options');

        add_settings_section('cfcs_plugin_main', 'Main Settings', null, 'cfcs_plugin');

        add_settings_field('enable_feature', 'Enable Feature', [__CLASS__, 'enable_feature_callback'], 'cfcs_plugin', 'cfcs_plugin_main');
    }

    public static function enable_feature_callback()
    {
        $options = get_option('cfcs_plugin_options');
        echo '<input type="checkbox" name="cfcs_plugin_options[enable_feature]" value="1"' . checked(1, $options['enable_feature'] ?? '', false) . '>';
    }
}

Admin_Settings::init();
