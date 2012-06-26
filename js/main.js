/* 
 * Fichero de javascrip principal de la aplicacion, asume que se han cargado:
 *  Jquery, DatePicker de Jquery UI y Jquery Validate
 */

$(function(){
    $('.estadosForm select').change(function(){
        $(this).parent().submit();
    });
    dates = $('input.date');
    dates.datepicker( $.datepicker.regional[ "es" ] );
    $('.estandarForm').validate({
        rules: {
            nombre:"required"
        }
    });
    dates.rules('remove', 'date');
    $('.estandarForm .int').each(function(){
            $(this).rules('add', {
            digits:true
        });
    });
    $('#crearProyecto .int').each(function(){
            $(this).rules('add', {
            digits:true,
            min:0,
            max:100
        });
    });
    $('#crearPlanFases .int').each(function(){
            $(this).rules('add', {
            required:true,
            digits:true,
            min:0,
            max:100
        });
    });
});
