$(document).ready(function () {

    $("#errorsTool").hide();

    // Get info of logged user
    var urlJSON = "applib/ipc_catalogue.php";
    $.ajax({
        type: 'POST',
        url: urlJSON,
        data: {'action': 'getUser'}
    }).done(function (data) {

        var currentUser = data;

        var urlJSON = 'applib/ipc_catalogue.php?action=getDatasets&token=';
        var table = [];

        // Get list of files from iPC-Catalogue-outbox-api
        $.ajax({
            type: 'POST',
            url: urlJSON,
            async: false,
            data: {'action': 'getDatasets'}
        }).done(function (json) {

            // response from iPC-Catalogue-outbox-api
            var datasets_parsed = JSON.parse(json);
            var id, meta, es_index, analysis;

            for (var i = 0; i < datasets_parsed.length; i++) {
                // dataset _id
                id = datasets_parsed[i][0]["_id"]

                // dataset metadata
                var metadata = datasets_parsed[i][0]["metadata"]
                es_index = metadata["es_index"]
                analysis = metadata['analysis']
                // file_locator = metadata['file_locator']

                if (analysis == "vre") {    // if data analysis from VRE

                    new_data = {
                        "_id": id,
                        "es_index": es_index,
                        "origin_analysis": analysis
                        // "file_locator": file_locator
                    }
                    table.push(new_data)
                }
            }
        })
        console.log("table: ", table)

        var sites = [];

        for (var i = 0; i < table.length; i++) {
            sites.push('applib/ipc_catalogue.php?action=getDatasetsFiles&file_id=' +
                table[i]._id + '&es_index=' + table[i].es_index + '')
        }
        // console.log("sites:", sites)

        var files = [];

        for (i = 0; i < sites.length; i++) {
            $.ajax({
                url: sites[i],
                type: "POST",
                dataType: "json",
                async: false,
                success: function (data) {
                    files.push(data)

                }
            });
        }
        // console.log("files:", files)

        var files_parsed = []

        for (var i = 0; i < files.length; i++) {
            files_parsed.push(JSON.parse(files[i]).hits.hits[0]._source)
        }
        //console.log("files_parsed:", files_parsed)

        var location_path = location.pathname.split('/')[1]
        var static_endpoint = "/" + location_path + "/applib/getData.php?uploadType=repository&urn[]=urn:"

        // GENERAL DATATABLE
        $('#ipcTable').DataTable({
            "ajax": {
                url: 'applib/ipc_catalogue.php?action=getDatasetsFiles',
                dataSrc: function (jsonData) {

                    var table = []

                    for (var i = 0; i < files_parsed.length; i++) {

                    	file_locator_parsed = files_parsed[i]['file_external_ID'].slice(2, -2) // convert array of one item to string
                    	file_format = files_parsed[i]['file_format']
                    	data_type = files_parsed[i]['experimental_strategy']

                        new_data = {
                            "file_id": files_parsed[i]['file_ID'],
                            "file_locator": file_locator_parsed,
                            "biospecimen_id": files_parsed[i]['Kids_First_Biospecimen_ID'],
                            "file_size": files_parsed[i]['file_size'],
                            "file_format": file_format,
                            "sample_type": files_parsed[i]['sample_type'],
                            "experimental_strategy": data_type,
                            "download": static_endpoint + encodeURI(file_locator_parsed) + "&format=" + file_format + "&data_type=" + data_type
                        }
                        table.push(new_data)
                    }
                    console.log("table: ", table)
                    return table;
                }
            },
            autoWidth: false,
            "columns": [
                {"data": "file_id"},
                {"data": "biospecimen_id"},
                {"data": "file_size"},
                {"data": "file_format"},
                {"data": "sample_type"},
                {"data": "download"}
            ],
            "columnDefs": [
                // targets are the number of columns
                {"title": "File ID", "targets": 0},
                {"title": "Biospecimen ID", "targets": 1},
                {"title": "Size", "targets": 2},
                {"title": "Format", "targets": 3},
                {"title": "Type", "targets": 4},
                {"title": "", "targets": 5},
                {
                    render: function (data, type, row) {
                        // Here we should put an href to the specific endpoint.
                        // console.log(row.download)
                        return '<a href="'+row.download+'"  class="btn  green" > <i class="fa fa-cloud-upload font-white" data-original-title="Import dataset to workspace"></i> &nbsp; IMPORT</a>'
                    }, "targets": 5
                }
            ]
        });
    });


    $("#datasetsReload").click(function () {
        location.reload(true);
    });
});


