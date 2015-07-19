<?php


/**
 * Log Killer
 * ----------
 */

Route::accept($config->manager->slug . '/plugin/' . File::B(__DIR__) . '/kill', function() use($config, $speak) {
    if($request = Request::post()) {
        Guardian::checkToken($request['token']);
        File::open(PLUGIN . DS . File::B(__DIR__) . DS . 'log')->delete();
        Notify::success(Config::speak('notify_success_deleted', $speak->files));
        Guardian::kick(File::D($config->url_current));
    }
});


/**
 * Broken Link was Found
 * ---------------------
 */

$title = Config::speak('plugin_smart_internal_link_title');
if($file = File::exist(PLUGIN . DS . File::B(__DIR__) . DS . 'log')) {
    Config::merge('manager_menu', array(
        $title => array(
            'icon' => 'exclamation-triangle',
            'url' => $config->manager->slug . '/plugin/' . File::B(__DIR__),
            'count' => count(glob($file . DS . '*' . DS . '*.log', GLOB_NOSORT))
        )
    ));
} else {
    if(Route::is($config->manager->slug . '/plugin/' . File::B(__DIR__))) {
        Weapon::add('shield_before', function() {
            Config::set('file.configurator', false);
        });
    }
}

// Define JS languages ...
Config::merge('DASHBOARD.languages.MTE', array(
    'plugin_smart_internal_link' => array(
        0 => $title,
        1 => array(
            'article' => $speak->article,
            'page' => $speak->page
        )
    )
));

// Add editor toolbar button for this plugin ...
Weapon::add('SHIPMENT_REGION_BOTTOM', function() {
    echo Asset::javascript('cabinet/plugins/' . File::B(__DIR__) . '/assets/sword/button.js');
}, 20);