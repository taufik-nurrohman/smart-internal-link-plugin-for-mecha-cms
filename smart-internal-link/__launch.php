<?php


/**
 * Log Killer
 * ----------
 */

Route::accept($config->manager->slug . '/plugin/' . File::B(__DIR__) . '/do:kill', function() use($config, $speak) {
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

$_t = (array) $speak->plugin_smart_internal_link->title->title;
if($file = File::exist(__DIR__ . DS . 'log')) {
    Config::merge('manager_menu', array(
        $_t[0] => array(
            'icon' => 'exclamation-triangle',
            'url' => $config->manager->slug . '/plugin/' . File::B(__DIR__),
            'count' => count(glob($file . DS . 'posts' . DS . '*' . DS . '*.log', GLOB_NOSORT))
        )
    ));
}

// Define JS languages ...
$_o = array();
foreach(glob(POST . DS . '*', GLOB_NOSORT | GLOB_ONLYDIR) as $_p) {
    $_p = File::B($_p);
    $_o[$_p] = isset($speak->{$_p}) ? $speak->{$_p} : Text::parse($_p, '->title');
}
Config::merge('DASHBOARD.languages.MTE.plugin_smart_internal_link', array(
    0 => $_t,
    1 => $_o
));

// Add editor toolbar button for this plugin ...
Weapon::add('SHIPMENT_REGION_BOTTOM', function() {
    echo Asset::javascript(__DIR__ . DS . 'assets' . DS . 'sword' . DS . 'button.js', "", 'sword/editor.button.' . File::B(__DIR__) . '.min.js');
}, 20);