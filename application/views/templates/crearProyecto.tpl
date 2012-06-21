{extends file='contentparent.tpl'}
{block name=content}
<!--script type="text/javascript">
    $(function(){
        $('.estandarForm').submit(function(){
            if (!$('#check'+$('#selectJefe').val()).attr('checked')){
                alert("Debe asignarse al jefe de proyecto y su disponibilidad");
                return false;
            }
            return true;
        });
    });
</script-->
{$crearProyecto['start']}
{foreach from=$crearProyecto['fields'] item=lineaForm}
    <label>
        <span>{$lineaForm.name}: </span>
    </label>
        {$lineaForm.input}
{/foreach}
    
{*if isset($trabajadores)*}
    
    <label>
    <span>Jefe de proyecto: </span>
    </label>
    <select id="selectJefe" name="jefeProyecto">
    {foreach from=$jefes item=trabajador}
        <option value="{$trabajador->getId()}" >{$trabajador->get('nombre')}</option>
    {/foreach}
     <label>
      <span>Porcentaje dedicación: </span>
        </label>
    <input type="text" class="int" name="dedicacion" value="100"></input>
    </select><br>
{*   <h2>Trabajadores</h2>

{foreach from=$trabajadores item=trabajador}
    <label>
        <span>{$trabajador->get('nombre')}: </span>
    </label>
<input type="checkbox" class="boolean" id="check{$trabajador->getId()}" name="{$trabajador->getId()}" value="1"></input>

 <label>
      <span>Porcentaje dedicación: </span>
 </label>
<input type="text" class="int" name="dedicacion{$trabajador->getId()}" value="100"></input>
<br>
{/foreach}
{else}
    Los trabajadores solo se pueden añadir durante la creación de proyectos.
{/if}
<br>
*}
<input type="submit" value="{$buttonText}" />
{$crearProyecto['end']}
{/block}