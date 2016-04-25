<?php

function do_smart_internal_link($content) {
    global $config, $speak;
    $pages = glob(POST . DS . '*', GLOB_NOSORT | GLOB_ONLYDIR);
    $shortcode_prefix = array();
    foreach($pages as &$page) {
        $page = File::B($page);
        $shortcode_prefix[] = '{{' . $page . '.link:';
    }
    unset($page);
    if( ! Text::check($shortcode_prefix)->in($content)) {
        return $content;
    }
    $pattern = '\{\{(' . implode('|', $pages) . ')\.link\:([a-z0-9\-]+)([\#?&].*?)?\}\}(([^\{\}]*?)\{\{\/\1\}\})?';
    return preg_replace_callback('#(?<!`)' . $pattern . '(?!`)#s', function($matches) use($config, $speak) {
        if(empty($matches[1]) || ! Get::kin($matches[1] . 'Anchor')) {
            return $matches[0];
        }
        if( ! $post = call_user_func('Get::' . $matches[1] . 'Anchor', $matches[2])) {
            // Create error log file of broken link(s)
            if($config->page_type !== 'manager') {
                File::write(sprintf($speak->plugin_smart_internal_link->description->log, $config->url_current))->saveTo(__DIR__ . DS . 'log' . DS . 'posts' . DS . $matches[1] . DS . $matches[2] . '.' . md5($config->url_current) . '.log', 0600);
            }
            return '<s class="text-error" title="' . $speak->plugin_smart_internal_link->title->link . '">' . (isset($matches[4]) && $matches[5] !== "" ? $matches[5] : $speak->plugin_smart_internal_link->title->link) . '</s>';
        }
        $text = isset($matches[4]) && $matches[5] !== "" ? $matches[5] : $post->title;
        $text = str_replace(array('&#123;', '&#125;'), array('{', '}'), $text);
        $suffix = isset($matches[3]) ? str_replace('&', '&amp;', $matches[3]) : "";
        return '<a class="auto-link" href="' . $post->url . $suffix . '" title="' . Text::parse($post->title . ($suffix ? $config->title_separator . $suffix : ""), '->text') . '">' . $text . '</a>';
    }, $content);
}

// Apply `do_smart_internal_link` filter
Filter::add('shortcode', 'do_smart_internal_link', 21);