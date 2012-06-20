{extends file='contentparent.tpl'}
{block name=content}
{$crearProyecto['start']}
{foreach from=$crearProyecto['fields'] item=lineaForm}
    <label>
        <span>{$lineaForm.name}: </span>
    </label>{$lineaForm.input}
{/foreach}
<br>
<input type="submit" value="Crear proyecto" />
{$crearProyecto['end']}
{/block}