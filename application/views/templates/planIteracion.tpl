{extends file='contentparent.tpl'}
{block name=content}
{if empty($actividades)}
    <h2 style="text-align: center"> No hay actividades en este proyecto hasta ahora.</h2>
{else}
<table class="listado">
    <tr><th colspan="{if $iteracion->get('iniciada')->val()}7{else}6{/if}">Actividades</th></tr>
    <tr>
        <td class="listadoLabel">Nombre</td>
        <td class="listadoLabel">Descripción</td>
        <td class="listadoLabel">Horas</td>
        <td class="listadoLabel">rol</td>
        <td class="listadoLabel">Predecesoras</td>
        <td class="listadoLabel">Cerrada</td>
        {if $iteracion->get('iniciada')->val()}
        <td class="listadoLabel">Asignar</td>
        {/if}
        
    </tr>
    {foreach from=$actividades item=actividad}
        <tr>
            <td class="listadoName">{$actividad->get('nombre')->getHtml()} </td>
            <td class="listadoName">{$actividad->get('descripcion')->getHtml()}</td>
            <td class="listadoName">{$actividad->get('horas')->getHtml()}</td>
            <td class="listadoName">{$actividad->get('rol')->getHtml()}</td>
            <td class="listadoName">
            {foreach from=$predecesoras[$actividad->getId()] item=predecesora}
            {$predecesora->get('nombre')->getHtml()}, 
            {/foreach}
            </td>
             <td class="listadoName">{$actividad->get('cerrada')->getHtml()}</td>
            {if $iteracion->get('iniciada')->val()}
                <td class="listadoName">
                    <a href="{$asignarURL}/{$actividad->getId()}" class="button">Asignar</a>
                    <br><br>
                    <a href="{$cerrarURL}/{$actividad->getId()}" class="button">cerrar</a>
                </td>
            {/if}
        </tr>      
    {/foreach}
</table>
{/if}
{if !empty($actividades) && !$iteracion->get('iniciada')->val()}
    <a style="margin:20px 20px 20px 250px;text-align: center" href="{$iniciarURL}" class="button">Iniciar Iteración</a>
{/if}
{if !$iteracion->get('iniciada')->val()}
    {$crearActividad['start']}
        <div class="lineaForm">
        <label>
            <span>{$crearActividad['fields'].nombre.name}: </span>
        </label>
            {$crearActividad['fields'].nombre.input}
        </div>
        <div class="lineaForm">
        <label>
            <span>{$crearActividad['fields'].descripcion.name}: </span>
        </label>
            {$crearActividad['fields'].descripcion.input}
        </div>
        <div class="lineaForm">
        <label>
            <span>{$crearActividad['fields'].horas.name}: </span>
        </label>
            {$crearActividad['fields'].horas.input}
        </div>
        <div class="lineaForm">
        <label>
            <span>Rol que ejecutará la actividad: </span>
        </label>
        <select id="selectRol" name="rol">
        {foreach from=$roles key=rol item=level}
            <option value="{$rol}" >{$rol}</option>
        {/foreach}
        </select>
        </div><br>

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
        <input type="submit" value="Nueva actividad" />
{$crearActividad['end']}
{/if}

{/block}