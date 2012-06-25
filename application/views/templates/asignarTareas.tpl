{extends file='contentparent.tpl'}
{block name=content}

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
        <input type="submit" value="Asignar actividad" />
{$crearTarea['end']}

{/block}