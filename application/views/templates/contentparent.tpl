{extends file='main.tpl'}
{block name=head}
{if isset($headStart)}
    {$headStart}
{/if}
<meta charset=utf-8 />
<title>Setepros{if isset($pageTitle)} - {$pageTitle}{/if}</title>
<link rel="stylesheet" type="text/css" media="screen" href="{"css/normalize.css"|base_url}" />
<link rel="stylesheet" type="text/css" media="screen" href="{"css/main.css"|base_url}" />
<!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if necessary -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="{"js/jquery-1.7.2.min.js"|base_url}">\x3C/script>')</script>
{if isset($headEnd)}
    {$headEnd}
{/if}
{/block}
{block name=nav}
<ul>
    <li><a class="navButton" href="{"dashboard"|site_url}">Inicio</a></li>
    <li><a class="navButton" href="{"informes"|site_url}">Informes</a></li>
    <li>
        <span id="usernameLogout">
            {if isset($this->tank_auth)}
                {if $this->tank_auth->get_username()}
                    {$this->tank_auth->get_username()}
                {else}
                    No ha iniciado sesión
                {/if}
            {else}
                No ha iniciado sesión
            {/if}
            <a id="logout" href="{"auth/logout"|site_url}">
                <img width="32" height="32" src="{"images/icons/logout.png"|base_url}">
            </a>
        </span>
    </li>
</ul>
    
{/block}
{block name=sidebar}
    lateral
{/block}