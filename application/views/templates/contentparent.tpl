{extends file='head.tpl'}
{block name=nav}
<div id="proyectosHeader">
    Proyectos
</div>
<ul>
    {if isset($trabajador)}
        {if $trabajador->get('rol')->val() == 'admin'}
            {if !$idProyecto}
            <li>
                <a class="navButton" href="{"auth/register"|site_url}">Registrar usuario</a>
            </li>
            <li>
                <a class="navButton" href="{"dashboard/crearProyecto"|site_url}">Crear proyecto</a>
            </li>
            {else}
            <li>
                <a class="navButton" href="{"dashboard/editarProyecto"|site_url}/{$idProyecto}">Editar proyecto</a>
            </li>

            <li>
                <a class="navButton" onclick="return confirm('¿Está seguro que desea eliminar este proyecto');" href="{"dashboard/eliminarProyecto"|site_url}/{$idProyecto}">Eliminar proyecto</a>
            </li>
            {/if}
        {/if}
        {if $trabajador->get('rol')->val() == 'Jefe de proyecto'}
            {if $idProyecto}
            <li><a class="navButton" href="{"dashboard/planes"|site_url}{if isset($idProyecto)}/{$idProyecto}{/if}">Planes</a></li>
            {/if}
        {/if}
        <li><a class="navButton" href="{"informes/lista"|site_url}{if isset($idProyecto)}/{$idProyecto}{/if}">Informes</a></li>
    {else}
        <li><a class="navButton" href="{"auth/login"|site_url}">login</a></li>
    {/if}
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
        {foreachelse}
            <h3 style="width:90%;text-align:center;">No hay proyectos disponibles</h3>
	{/foreach}
    </ul>
    {else}
    <h3 style="width:90%;text-align:center;">Panel de proyectos actualmente vacio</h3>
   {/if}
   {if isset($help)}
       <div id="sidebarHelp">{$help}</div>
   {/if}
{/block}
