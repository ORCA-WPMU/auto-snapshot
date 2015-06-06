# Auto Snapshot

Auto Snapshot is a very basic Wordpress plugin that works alongsite the [WPMUDEV](http://wpmudev.org) [Snapshot Pro](http://premium.wpmudev.org/project/snapshot/) Plugin.

It only works on multisite installations and it allows you to automatically add a snapshot whenever a site is created, this should reduce admin overheads on multisite installations a fair bit.

This isn't in any way affiliated with WPMUDEV and was born out of my need to automate this task. 

## Installation

Upload this file to your `wp-content/plugins` directory inside a `auto-snapshot` directory. Therefore the full path should be `wp-content/plugins/auto-snapshot`.

From there, you can activate it as usual and it will kick in as long as you've got [Snapshot Pro](http://premium.wpmudev.org/project/snapshot/) installed.

## Settings

The settings are currently hardcoded into the plugin but maybe one day I will update this to allow you to set them.

In the meantime you can open up `auto-snapshot.php` and play with the settings array on line `70`

It's currently set to backup all files and database tables locally as that's what I need.

## Contributing

If you want to contribute to this, please do. Make a PR or create an issue. If I have time I'll do what I can with it.

