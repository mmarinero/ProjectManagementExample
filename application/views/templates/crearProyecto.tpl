{extends file='contentparent.tpl'}
{block name=content}
{$crearProyecto['start']}
{foreach from=$crearProyecto['fields'] item=lineaForm}
    <label>
        <span>{$lineaForm.name}: </span>
    </label>
        {$lineaForm.input}
{/foreach}
    
{if isset($trabajadores)}
    <h2>Trabajadores</h2>
{foreach from=$trabajadores item=trabajador}
    <label>
        <span>{$trabajador->get('nombre')}: </span>
    </label>
<input type="checkbox" class="boolean" name="{$trabajador->getId()}" value="1"></input>
 <label>
      <span>Porcentaje dedicación: </span>
 </label>
<input type="text" class="int" name="dedicacion{$trabajador->getId()}" value="100"></input>
{/foreach}
{else}
    Los trabajadores solo se pueden añadir durante la creación de proyectos.
{/if}
<br>

<input type="submit" value="{$buttonText}" />
{$crearProyecto['end']}
{/block}