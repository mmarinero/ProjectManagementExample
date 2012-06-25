{extends file='contentparent.tpl'}
{block name=content}
<table class="listado">
    <tr><th colspan="5">Iteraciones</th></tr>
    <tr>
        <td class="listadoLabel">Nombre</td>
        <td class="listadoLabel">Descripción</td>
        <td class="listadoLabel">Estimación inicio</td>
        <td class="listadoLabel">Estimación fin</td>
        <td class="listadoLabel">Planificar</td>
    </tr>
    {foreach from=$actividades item=iteracion}
        <tr>
            <td class="listadoName">{$iteracion->get('nombre')->getHtml()} </td>
            <td class="listadoName">{$iteracion->get('descripcion')->getHtml()}</td>
            <td class="listadoName">{$iteracion->get('inicio')->getInputHtml()}</td>
            <td class="listadoName">{$iteracion->get('fin')->getInputHtml()}</td>
            <td class="listadoName">
                {if !$iteracion->get('cerrada')->val() && !isset($cerrada)}
                    {assign value=true var=cerrada}
                <a href="{'actividades/planIteracion'|site_url}/{$idProyecto}/{$idPlan}/{$iteracion->getId()}">
                    <img width="32" height="32" src="{"images/icons/plan.png"|base_url}">
                </a>
                {/if}
            </td>
        </tr>
    {foreachelse}
        <h2 style="text-align: center"> No hay actividades en este proyecto hasta ahora.</h2>
    {/foreach}
</table>
{$crearActividad['start']}

    <label>
        <span>{$crearActividad['fields'].nombre.name}: </span>
    </label>
        {$crearActividad['fields'].nombre.input}
    <label>
        <span>{$crearActividad['fields'].descripcion.name}: </span>
    </label>
        {$crearActividad['fields'].descripcion.input}
    <label>
        <span>{$crearActividad['fields'].horas.name}: </span>
    </label>
        {$crearActividad['fields'].horas.input}
    <label>
        <span>Rol que ejecutará la actividad: </span>
    </label>
    <select id="selectRol" name="rol">
    {foreach from=$roles key=rol item=level}
        <option value="{$rol}" >{$rol}</option>
    {/foreach}
    </select><br>
    
    <h2>Predecesoras</h2>
{foreach from=$actividades item=actividad}
    <label>
        <span>{$actividad->get('nombre')->getHtml()}: </span>
    </label>
<input type="checkbox" class="boolean" id="actividad{$actividad->getId()}" name="actividad{$actividad->getId()}" value="{$actividad->getId()}"></input>
<br>
{foreachelse}
    Todavía no hay actividades en esta iteración
    <br>
    <br>
{/foreach}
<!--
    <h2>Trabajadores</h2>
{foreach from=$trabajadoresProyecto item=trabajador}
    <label>
        <span>{$trabajador->get('nombre')->getHtml()}: </span>
    </label>
<input type="checkbox" class="boolean" id="trabajador{$trabajador->getId()}" name="trabajador{$trabajador->getId()}" value="1"></input>
<br>
{/foreach}
-->
    <input type="submit" value="Nueva actividad" />
{$crearActividad['end']}
{/block}