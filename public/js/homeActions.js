$( document ).ready(function() {

    $( "#readCsvButton" ).click(function() {
        $.ajax({

            'url' : 'loadDataFromExcel',
            'type' : 'POST',
            'data' : {
                'valid' : 1
            },
            'success' : function(data) {
                //overwrite write in html
                $('#showInformationTextArea').html(JSON.parse(data));
            },
            'error' : function(request,error)
            {
                $('#showInformationTextArea').html("Request: "+JSON.stringify(request));
            }
        });
    });
    $( "#readCsvButtonErrors" ).click(function() {
        $.ajax({

            'url' : 'loadDataFromExcel',
            'type' : 'POST',
            'data' : {
                'valid' : 0
            },
            'success' : function(data) {
                //overwrite write in html
                $('#showInformationTextArea').html(JSON.parse(data));
            },
            'error' : function(request,error)
            {
                $('#showInformationTextArea').html("Request: "+JSON.stringify(request));
            }
        });
    });

    $( "#launchCalcutionButton" ).click(function() {
        $.ajax({

            'url' : 'launchScoreRankingOperation',
            'type' : 'POST',
            'data' : {
                'numberOfWords' : 10
            },
            'success' : function(data) {
                $('#showInformationTextArea').html(data);
            },
            'error' : function(request,error)
            {
                $('#showInformationTextArea').html("Request: "+JSON.stringify(request));
            }
        });
    });

});
