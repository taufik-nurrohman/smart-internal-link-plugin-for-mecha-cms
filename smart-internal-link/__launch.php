<?php


/**
 * Log Killer
 * ----------
 */

Route::accept($config->manager->slug . '/plugin/' . basename(__DIR__) . '/kill', function() use($config, $speak) {
    if($request = Request::post()) {
        Guardian::checkToken($request['token']);
        File::open(PLUGIN . DS . basename(__DIR__) . DS . 'log')->delete();
        Notify::success(Config::speak('notify_success_deleted', $speak->files));
        Guardian::kick(dirname($config->url_current));
    }
});


/**
 * Broken Link was Found
 * ---------------------
 */

$title = Config::speak('plugin_smart_internal_link_title');
if($file = File::exist(PLUGIN . DS . basename(__DIR__) . DS . 'log')) {
    Config::merge('manager_menu', array(
        $title => array(
            'url' => $config->manager->slug . '/plugin/' . basename(__DIR__),
            'icon' => 'exclamation-triangle',
            'count' => count(glob($file . DS . '*' . DS . '*.log', GLOB_NOSORT))
        )
    ));
} else {
    if(Route::is($config->manager->slug . '/plugin/' . basename(__DIR__))) {
        Weapon::add('shield_before', function() {
            Config::set('file.configurator', false);
        });
    }
}

// Define JS languages ...
Config::merge('DASHBOARD.languages', array(
    'plugin_smart_internal_link_title' => $title,
    'plugin_smart_internal_link_title_types' => $speak->article . ',' . $speak->page
));

// Add editor toolbar button for this plugin ...
Weapon::add('SHIPMENT_REGION_BOTTOM', function() {
    echo Asset::javascript('cabinet/plugins/' . basename(__DIR__) . '/sword/button.js');
}, 20);