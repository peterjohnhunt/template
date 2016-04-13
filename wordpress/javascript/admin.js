jQuery(document).ready(function($){

//░░░░░░░░░░░░░░░░░░░░░░░░
//
//	 DIRECTORY
//
//	 _ExclusiveTemplate
//
//░░░░░░░░░░░░░░░░░░░░░░░░

//▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀▀
// _ExclusiveTemplate
//▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄▄

if( $('#page_template')[0] ){

    $('#page_template option').each(function(index, option) {

        if( option.text.indexOf('(In Use)') > -1 ){
            option.disabled = true;
        }

    });

}

});