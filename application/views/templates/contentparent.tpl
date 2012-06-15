{extends file='main.tpl'}
{block name=head}
{$headStart}
<meta charset=utf-8 />
<title>{$pageTitle}</title>
<link rel="stylesheet" type="text/css" media="screen" href="{"css/normalize.css"|base_url}" />
<link rel="stylesheet" type="text/css" media="screen" href="{"css/main.css"|base_url}" />
<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if necessary -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{"js/jquery-1.7.2.min.js"|base_url}">\x3C/script>')</script>
{$headEnd}
{/block}
{block name=nav}
    navegación
{/block}
{block name=sidebar}
    lateral
{/block}