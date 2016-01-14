Smart Internal Link Plugin for Mecha CMS
========================================

> Automatic and maintainable internal links.

This plugin will help you to create an automatic [hyperlink](https://en.wikipedia.org/wiki/Hyperlink "Hyperlink – Wikipedia") by using the available article/page slug. The internal link that created will comes with the title of the article/page. This plugin can also tell you immediately if there are some broken internal links on certain pages.

#### For Articles

##### Example Shortcode

~~~ .no-highlight
{{article.link:article-slug}}
{{article.link:article-slug#hash}}
{{article.link:article-slug?query=string}}

{{article.link:article-slug}}link text{{/article}}
{{article.link:article-slug#hash}}link text{{/article}}
{{article.link:article-slug?query=string}}link text{{/article}}
~~~

##### The Output

~~~ .html
<a class="auto-link" href="http://localhost/article/article-slug" title="The Title">The Title</a>
<a class="auto-link" href="http://localhost/article/article-slug#hash" title="The Title">The Title</a>
<a class="auto-link" href="http://localhost/article/article-slug?query=string" title="The Title">The Title</a>

<a class="auto-link" href="http://localhost/article/article-slug" title="The Title">link text</a>
<a class="auto-link" href="http://localhost/article/article-slug#hash" title="The Title">link text</a>
<a class="auto-link" href="http://localhost/article/article-slug?query=string" title="The Title">link text</a>
~~~

#### For Pages

##### Example Shortcode

~~~ .no-highlight
{{page.link:page-slug}}
{{page.link:page-slug#hash}}
{{page.link:page-slug?query=string}}

{{page.link:page-slug}}link text{{/page}}
{{page.link:page-slug#hash}}link text{{/page}}
{{page.link:page-slug?query=string}}link text{{/page}}
~~~

##### The Output

~~~ .html
<a class="auto-link" href="http://localhost/page-slug" title="The Title">The Title</a>
<a class="auto-link" href="http://localhost/page-slug#hash" title="The Title">The Title</a>
<a class="auto-link" href="http://localhost/page-slug?query=string" title="The Title">The Title</a>

<a class="auto-link" href="http://localhost/page-slug" title="The Title">link text</a>
<a class="auto-link" href="http://localhost/page-slug#hash" title="The Title">link text</a>
<a class="auto-link" href="http://localhost/page-slug?query=string" title="The Title">link text</a>
~~~