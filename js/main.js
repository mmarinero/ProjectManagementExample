/* 
 * Fichero de javascrip principal de la aplicacion, asume que se han cargado:
 *  Jquery, DatePicker de Jquery UI y Jquery Validate
 */

$(function(){
    dates = $('input.date');
    dates.datepicker( $.datepicker.regional[ "es" ] );
    $('.estandarForm').validate({
        rules: {
            nombre:"required"
        }
    });
    console.log($('.estandarForm .int'));
    $('.estandarForm .int').each(function(){
        $(this).rules('add', {
        digits:true,
        min:0,
        max:100
    });
    });
    
});
