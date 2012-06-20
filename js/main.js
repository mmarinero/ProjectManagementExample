/* 
 * Fichero de javascrip principal de la aplicacion, asume que se han cargado:
 *  Jquery, DatePicker de Jquery UI y Jquery Validate
 */

$(function(){
    dates = $('input.date');
    dates.datepicker( $.datepicker.regional[ "es" ] );
});
