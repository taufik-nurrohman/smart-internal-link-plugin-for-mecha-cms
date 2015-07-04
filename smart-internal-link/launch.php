<?php

Filter::add('shortcode', function($content) use($config) {
    if(
        strpos($content, '{{article.link:') === false &&
        strpos($content, '{{page.link:') === false
    ) {
        return $content;
    }
    $speak = Config::speak();
    return preg_replace_callback('#(?<!`)\{\{(article|page)\.link\:([a-z0-9\-]+)([\#?].*?)?\}\}(([^\{]*?)\{\{\/\1\}\})?(?!`)#s', function($matches) use($config, $speak) {
        $article = $matches[1] === 'article';
        $data = $article ? Get::articleAnchor($matches[2]) : Get::pageAnchor($matches[2]);
        if($data === false) {
            // Create error log file for broken links
            if($config->page_type !== 'manager') {
                File::write(sprintf($speak->plugin_smart_internal_link_title_broken_log, $config->url_current, $config->url . '/' . ($article ? $config->index->slug . '/' : "") . $matches[2]))->saveTo(PLUGIN . DS . basename(__DIR__) . DS . 'log' . DS . $matches[1] . 's' . DS . $matches[2] . '.' . md5($config->url_current) . '.log', 0600);
            }
            return '<s class="text-error" title="' . $speak->plugin_smart_internal_link_title_broken_label . '">' . (isset($matches[4]) && $matches[5] !== "" ? $matches[5] : $speak->plugin_smart_internal_link_title_broken_label) . '</s>';
        }
        $text = isset($matches[4]) && $matches[5] !== "" ? $matches[5] : $data->title;
        return '<a class="auto-link" href="' . $data->url . (isset($matches[3]) ? $matches[3] : "") . '" title="' . strip_tags($data->title) . '">' . $text . '</a>';
    }, $content);
});