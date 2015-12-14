<?php


/**
 * Log Killer
 * ----------
 */

Route::accept($config->manager->slug . '/plugin/' . File::B(__DIR__) . '/kill', function() use($config, $speak) {
    if($request = Request::post()) {
        Guardian::checkToken($request['token']);
        File::open(__DIR__ . DS . 'log')->delete();
        Notify::success(Config::speak('notify_success_deleted', $speak->files));
        Guardian::kick(File::D($config->url_current));
    }
});


/**
 * Broken Link was Found
 * ---------------------
 */

$_title = $speak->plugin_smart_internal_link_title;
if($file = File::exist(__DIR__ . DS . 'log')) {
    Config::merge('manager_menu', array(
        $_title => array(
            'icon' => 'exclamation-triangle',
            'url' => $config->manager->slug . '/plugin/' . File::B(__DIR__),
            'count' => count(glob($file . DS . 'posts' . DS . '*' . DS . '*.log', GLOB_NOSORT))
        )
    ));
}

// Define JS languages ...
$_options = array();
foreach(glob(POST . DS . '*', GLOB_NOSORT | GLOB_ONLYDIR) as $_page) {
    $_page = File::B($_page);
    $_options[$_page] = isset($speak->{$_page}) ? $speak->{$_page} : Text::parse($_page, '->title');
}
Config::merge('DASHBOARD.languages.MTE', array(
    'plugin_smart_internal_link' => array(
        0 => $_title,
        1 => $_options
    )
));

// Add editor toolbar button for this plugin ...
Weapon::add('SHIPMENT_REGION_BOTTOM', function() {
    echo Asset::javascript(__DIR__ . DS . 'assets' . DS . 'sword' . DS . 'button.js');
}, 20);