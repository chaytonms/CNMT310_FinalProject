$(document).ready( function() {
    $( "#class" ).autocomplete({
      source: function( request, response ) {
        $.ajax( {
          url: "/classreg/search.php",
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
        console.log(ui.item.label);
        $("#result").html('<input type="hidden" value="' + ui.item.id +
                            '">' + ui.item.label + 
                            '&nbsp;<input type="submit" name="submit" value="Enroll">');
        return false;
      },
      minLength: 3,
    } );
});
