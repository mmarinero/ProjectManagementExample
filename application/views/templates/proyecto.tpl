{extends file='contentparent.tpl'}
{block name=content}
<table class="listado">
    <th colspan="2">Datos del proyecto</th>
{foreach from=$proyecto->getFields() item=field}
        <tr>
            <td class="listadoLabel">{$field->getOutputName()}: </td>
            <td class="listadoName">{$field->getHtml()}</td>
        </tr>
{/foreach}
</table>
<br>
<table class="listado">
    <tr><th colspan="2">Trabajadores </th></tr>
    <tr>
        <td class="listadoLabel">{$trabajador->get('nombre')->getOutputName()} </td>
        <td class="listadoLabel">Disponibilidad</td>
    </tr>
{foreach from=$trabajadores item=trabajador}

        <tr>
            
            <td class="listadoName">{$trabajador->get('nombre')->getHtml()}</td>
            {assign var=tempData value=$trabajador->getTempData()}
            <td class="listadoName">{$tempData.porcentaje}</td>
        </tr>
{/foreach}
</table>
{/block}