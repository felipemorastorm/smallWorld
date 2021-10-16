$( document ).ready(function() {

    $( "#readCsvButton" ).click(function() {
        $.ajax({

            'url' : 'loadDataFromExcel',
            'type' : 'POST',
            'data' : {
                'numberOfWords' : 10
            },
            'success' : function(data) {
                //overwrite write in html
                $('#showInformationTextArea').html(JSON.parse(data));
            },
            'error' : function(request,error)
            {
                alert("Request: "+JSON.stringify(request));
            }
        });
    });
});
