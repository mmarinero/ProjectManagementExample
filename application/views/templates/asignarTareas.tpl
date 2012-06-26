{extends file='contentparent.tpl'}
{block name=content}
{if empty($tareas)}
<h2>Tareas de la actividad {$actividad->get('nombre')->val()}</h2>
{/if}
{if !empty($tareas)}
<table class="listado">
    <tr><th colspan="7">Tareas de la actividad {$actividad->get('nombre')->val()}</th></tr>
    <tr>
        <td class="listadoLabel">Nombre</td>
        <td class="listadoLabel">Descripción</td>
        <td class="listadoLabel">Horas</td>
        <td class="listadoLabel">tipo</td>
        <td class="listadoLabel">Trabajador</td>
        <td class="listadoLabel">Seguimiento</td>
        <td class="listadoLabel">Estado</td>
        
    </tr>
    {foreach from=$tareas item=tarea}
        <tr>
            <td class="listadoName">{$tarea->get('nombre')->getHtml()} </td>
            <td class="listadoName">{$tarea->get('descripcion')->getHtml()}</td>
            <td class="listadoName">{$tarea->get('horas')->getHtml()}</td>
            <td class="listadoName">{$tarea->get('tipo')->getHtml()}</td>
            <td class="listadoName">{$tarea->get('Trab')->get('nombre')->getHtml()}</td>
            <td class="listadoName">{$tarea->get('seguimiento')->getHtml()}</td>
            <td class="listadoName">
                <form class="estadosForm" method="post" action="{"actividades/modificarEstado"|site_url}/{$url}{$tarea->getId()}">
                    <select id="estado" name="estado">
                        <option value="{$tarea->get('estado')->val()}" >{$tarea->get('estado')->val()}</option>
                        {if $tarea->get('estado')->val()=='Pendiente'}
                        <option value="Confirmada" >Confirmada</option>
                        {else}
                        <option value="Pendiente" >Pendiente</option>
                        {/if}
                    </select>
                </form>
            </td>
        </tr>      
    {/foreach}
</table>
{/if}

    {$crearTarea['start']}
        <div class="lineaForm">
        <label>
            <span>{$crearTarea['fields'].nombre.name}: </span>
        </label>
            {$crearTarea['fields'].nombre.input}
        </div>
        <div class="lineaForm">
        <label>
            <span>{$crearTarea['fields'].descripcion.name}: </span>
        </label>
            {$crearTarea['fields'].descripcion.input}
        </div>
        <div class="lineaForm">
        <label>
            <span>{$crearTarea['fields'].horas.name}: </span>
        </label>
            {$crearTarea['fields'].horas.input}
        </div>
        <div class="lineaForm">
        <label>
            <span>{$crearTarea['fields'].tipo.name}: </span>
        </label>
            <select id="tipo" name="tipo">
            {foreach from=TareaPersonal::$tipos item=tipo}
                <option value="{$tipo}" >{$tipo}</option>
            {/foreach}
            </select>
        </div>
        <div class="lineaForm">
        <label>
            <span>Trabajador: </span>
        </label>
        <select id="selectTrabajador" name="trabajador">
        {foreach from=$trabajadores item=trabajador}
            <option value="{$trabajador->getId()}" >{$trabajador->get('nombre')->val()}</option>
        {/foreach}
        </select>
        </div>
        <input type="submit" value="Asignar tarea" />
{$crearTarea['end']}

{/block}