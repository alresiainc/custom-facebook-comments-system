<?php
// Include the Plugin Update Checker library.

require_once ALRESIA_CFCS_DIR . 'libs/plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

class PluginUpdater
{
    private $updateChecker;

    /**
     * Constructor method for initializing the update checker.
     * 
     * @param string $repoUrl  GitHub repository URL.
     * @param string $pluginFile Path to the main plugin file.
     * @param string $pluginSlug Unique slug for the plugin.
     * @param string $branch GitHub branch for updates (default: 'main').
     */
    public function __construct($repoUrl, $pluginFile, $pluginSlug, $branch = 'main')
    {
        // Initialize the update checker.
        $this->updateChecker = PucFactory::buildUpdateChecker(
            $repoUrl,
            $pluginFile,
            $pluginSlug
        );

        // Set the branch to check for updates.
        $this->updateChecker->setBranch($branch);
    }

    /**
     * Additional methods can be added here to manage update functionality.
     */
}
