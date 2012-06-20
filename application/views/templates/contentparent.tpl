{extends file='main.tpl'}
{block name=head}
{if isset($headStart)}
    {$headStart}
{/if}
<meta charset=utf-8 />
<title>Setepros{if isset($pageTitle)} - {$pageTitle}{/if}</title>
<link rel="stylesheet" type="text/css" media="screen" href="{"css/normalize.css"|base_url}" />
<link rel="stylesheet" type="text/css" media="screen" href="{"css/main.css"|base_url}" />
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
{if isset($headEnd)}
    {$headEnd}
{/if}
{/block}
{block name=nav}
<ul>
    <li><a class="navButton" href="{"dashboard/proyecto"|site_url}{if isset($idProyecto)}/{$idProyecto}{/if}">Principal</a></li>
    <li><a class="navButton" href="{"informes/proyecto"|site_url}{if isset($idProyecto)}/{$idProyecto}{/if}">Informes</a></li>
    <div id="usernameLogout">
        {if isset($trabajador)}
            <span id="usernameWelcome">
            {$trabajador->get('nombre')->getHtml()}
            </span>
            <a class="navButton" id="logout" href="{"auth/logout"|site_url}">
            <img id="imgLogout" width="32" height="32" src="{"images/icons/logout.png"|base_url}">
            </a>
        {else}
            <span id="usernameWelcome">
                No ha iniciado sesión
            </span>
        {/if}
    </div>
</ul>
    
{/block}
{block name=sidebar}
    {if isset($proyectos)}
    <ul>
	{foreach from=$proyectos key=id item=proyecto}
	    <li>
		<a class="button {if $id == $idProyecto}red{else}blue{/if} sidebar" href="{"dashboard/proyecto"|site_url}/{$id}">{$proyecto->getField('nombre')->getHtml()}</a>
	    </li>
	{/foreach}
    </ul>
   {/if}
   {if isset($help)}
       <div id="sidebarHelp">{$help}</div>
   {/if}
{/block}
