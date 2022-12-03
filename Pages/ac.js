$(document).ready( function() {
    $( "#class" ).autocomplete({
      source: function( request, response ) {
        $.ajax( {
          url: "search.php",
          method: "post",
          dataType: "json",
          data: {
            term: request.term
          },
          success: function( data ) {
            response( data );
          },
        } );
      },
      select: function(event,ui) {
        event.preventDefault();
        $("#search").val(ui.item.name);
        $("#class").val(ui.item.label)
        return false;
      },
      minLength: 3,
    } );
});
