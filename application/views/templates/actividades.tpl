{extends file='contentparent.tpl'}
{block name=content}

{foreach from=$formsTareas item=formTarea}
    {$formTarea['start']}
        <div class="lineaForm">
        <label>
            <span>{$formTarea['fields'].nombre->getName()}: </span>
        </label>
             <span style="color: black;font-style: normal">{$formTarea['fields'].nombre->getHtml()} </span>
        </div>
        <div class="lineaForm">
        <label>
            <span>{$formTarea['fields'].descripcion->getName()}: </span>
        </label>
              <span style="color: black;font-style: normal">{$formTarea['fields'].descripcion->getHtml()}</span>
        </div>
        <div class="lineaForm">
        <label>
            <span>{$formTarea['fields'].horas->getName()}: </span>
        </label>
              <span style="color: black;font-style: normal">{$formTarea['fields'].horas->getHtml()}</span>
        </div>
        <div class="lineaForm">
        <label>
            <span>{$formTarea['fields'].seguimiento->getName()}: </span>
        </label>
            {$formTarea['fields'].seguimiento->getInputHtml()}
        </div>
         <div class="lineaForm">
        <label>
            <span>{$formTarea['fields'].estado->getName()}: </span>
        </label>
            <select id="estado" name="estado">
            {foreach from=TareaPersonal::$estados item=estado}
                <option value="{$estado}" >{$estado}</option>
            {/foreach}
            </select>
        </div>
        <input type="submit" value="Modificar tarea" />
    {$formTarea['end']}
    <br>
{foreachelse}
    <h1 style="text-align: center"> No tiene actividades pendientes en este proyecto</h1>
{/foreach}
{/block}