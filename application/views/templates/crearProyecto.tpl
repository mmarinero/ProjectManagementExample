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
<input type="submit" value="{$buttonText}" />
{$crearProyecto['end']}
{/block}