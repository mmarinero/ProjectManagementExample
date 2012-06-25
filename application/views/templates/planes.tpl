{extends file='contentparent.tpl'}
{block name=content}
{if isset($crearFases)}
{$crearFases['start']}
{foreach from=$crearFases['fields'] item=lineaForm}
    <label>
        <span>{$lineaForm.name}: </span>
    </label>
        {$lineaForm.input}
{/foreach}
<h2>Trabajadores</h2>
{foreach from=$trabajadores item=trabajador}
    <label>
        <span>{$trabajador->get('nombre')->val()}: </span>
    </label>
<input type="checkbox" class="boolean" id="check{$trabajador->getId()}" name="{$trabajador->getId()}" value="1"></input>

 <label>
      <span>Porcentaje dedicación: </span>
 </label>
<input type="text" class="int" name="dedicacion{$trabajador->getId()}" value="100"></input>
<br>
{/foreach}
<input type="submit" value="Crear plan de fases" />
{$crearFases['end']}
{else}
<form action="{'dashboard/modificarEstimacionIteracionPost'|site_url}/{$idPlan}" class="tableInside" method="post">
<table class="listado">
    <tr><th colspan="5">Iteraciones</th></tr>
    <tr>
        <td class="listadoLabel">Nombre</td>
        <td class="listadoLabel">Descripción</td>
        <td class="listadoLabel">Estimación inicio</td>
        <td class="listadoLabel">Estimación fin</td>
        <td class="listadoLabel">Planificar</td>
    </tr>
    {foreach from=$iteraciones item=iteracion}
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
    {/foreach}
</table>
<input type="submit" style="margin-left: 250px" class="button blue" value="Modificar estimaciones">

</form>
    {/if}
{/block}