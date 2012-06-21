{extends file='main.tpl'}
{block name=head}
{if isset($headStart)}
    {$headStart}
{/if}
<meta charset=utf-8 />
<title>Setepros{if isset($pageTitle)} - {$pageTitle}{/if}</title>
<link rel="stylesheet" type="text/css" media="screen" href="{"css/normalize.css"|base_url}" />
<link rel="stylesheet" type="text/css" media="screen" href="{"css/main.css"|base_url}" />
<link rel="stylesheet" type="text/css" media="screen" href="{"css/ui-lightness/jquery-ui-1.8.21.custom.css"|base_url}" />
<style type="text/css">
    .button {
        background: #777 url({"images/icons/button.png"|base_url}) repeat-x bottom;
    }
</style>
<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if necessary -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{"js/jquery-1.7.2.min.js"|base_url}">\x3C/script>')</script>
<script src="{"js/jquery.validate.min.js"|base_url}"></script>
<script src="{"js/messages_es.js"|base_url}"></script>
<script src="{"js/jquery-ui-1.8.21.custom.min.js"|base_url}"></script>
<script src="{"js/jquery.ui.datepicker-es.js"|base_url}"></script>
<script src="{"js/main.js"|base_url}"></script>

{if isset($headEnd)}
    {$headEnd}
{/if}
{/block}