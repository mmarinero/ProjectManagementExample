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
{/block}