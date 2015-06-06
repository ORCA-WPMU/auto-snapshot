<?php
/*
Plugin Name: Auto Snapshots
Plugin URI:  http://enshrined.co.uk
Description: Automatically add a snapshot when a new site is added
Version:     0.1-alpha
Author:      Daryll Doyle
Author URI:  http://enshrined.co.uk
Text Domain: auto-snapshot
 */

require_once(ABSPATH . 'wp-content/plugins/snapshot/snapshot.php');

if (!class_exists('EnshrinedAutoSnapshot')) {
    /**
     * Class EnshrinedAutoSnapshot
     */
    class EnshrinedAutoSnapshot
    {
        /**
         * Contains the WPMU Dev Snapshot instance
         *
         * @var WPMUDEVSnapshot
         */
        private $snapshot;

        /**
         * @param WPMUDEVSnapshot $snapshot
         */
        function __construct(WPMUDEVSnapshot $snapshot)
        {
            $this->snapshot = $snapshot;

            add_filter('all_plugins', array($this, 'hideFromAdmins'), 12, 1);
            add_action('wpmu_new_blog', array($this, 'setUpSnapshot'), 10, 6);
        }


        /**
         * Hide the plugin from those who aren't network admins
         *
         * @param  array $plugins A list of all plugins
         * @return array          Plugins to show the users
         */
        function hideFromAdmins($plugins)
        {
            if (is_multisite()) {
                if (!is_super_admin() || !is_network_admin()) {
                    unset($plugins['auto-snapshot.php']);
                }
            }

            return $plugins;
        }

        /**
         * Set up a snapshot when a new blog is added
         *
         * @param int    $blog_id Blog ID.
         * @param int    $user_id User ID.
         * @param string $domain  Site domain.
         * @param string $path    Site path.
         * @param int    $site_id Site ID. Only relevant on multi-network installs.
         * @param array  $meta    Meta data. Used to set initial site options.
         */
        function setUpSnapshot($blog_id, $user_id, $domain, $path, $site_id, $meta)
        {
            $blog = get_blog_details($blog_id, true);

            $snapArray = array(
                'snapshot-action'                                     => 'add',
                'snapshot-blog-id'                                    => $blog_id,
                'snapshot-blog-id-search'                             => '',
                'snapshot-name'                                       => $blog->blogname,
                'snapshot-notes'                                      => sprintf('Auto snapshot for %s',
                    $blog->blogname),
                'snapshot-files-option'                               => 'all',
                'snapshot-destination-sync'                           => 'archive',
                'snapshot-files-ignore'                               => '',
                'snapshot-tables-option'                              => 'all',
                'snapshot-interval'                                   => 'snapshot-daily',
                'snapshot-interval-offset[snapshot-hourly][tm_min]'   => 4,
                'snapshot-interval-offset[snapshot-daily][tm_hour]'   => 0,
                'snapshot-interval-offset[snapshot-daily][tm_min]'    => 0,
                'snapshot-interval-offset[snapshot-weekly][tm_wday]'  => 6,
                'snapshot-interval-offset[snapshot-weekly][tm_hour]'  => 14,
                'snapshot-interval-offset[snapshot-weekly][tm_min]'   => 4,
                'snapshot-interval-offset[snapshot-monthly][tm_mday]' => 6,
                'snapshot-interval-offset[snapshot-monthly][tm_hour]' => 14,
                'snapshot-interval-offset[snapshot-monthly][tm_min]'  => 4,
                'snapshot-archive-count'                              => 10,
                'snapshot-destination'                                => 'local',
                'snapshot-destination-directory'                      => '',
            );

            $this->snapshot->snapshot_add_update_action_proc($snapArray);

        }

    }
}

/**
 * Check that WPMUDEVSnapshot is loaded before we load this
 */
if (isset($wpmudev_snapshot) && $wpmudev_snapshot instanceof WPMUDEVSnapshot) {
    $enshrinedAutoSnapshot = new EnshrinedAutoSnapshot($wpmudev_snapshot);
}