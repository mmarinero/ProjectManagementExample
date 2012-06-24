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
    <div class="lineaForm">
        <span>{$lineaForm.name}: </span>
    {$lineaForm.input}
    </div>
{/foreach}
{if isset($trabajadores)}
    <label>
    <span>Jefe de proyecto: </span>
    </label>
    <select id="selectJefe" name="jefeProyecto">
    {foreach from=$jefes item=trabajador}
        <option value="{$trabajador->getId()}" >{$trabajador->get('nombre')->val()}</option>
    {/foreach}
    </select>
     <label>
        <span>Porcentaje dedicación: </span>
     </label>
    <input type="text" class="int" name="dedicacion" value="100"></input>
    <br>
{else}
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    El jefe de proyecto no puede ser modificado una vez se ha creado el proyecto.
{/if}
<input type="submit" value="{$buttonText}" />
{$crearProyecto['end']}
{/block}