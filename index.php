<?php
require_once("include/header.php");
?>
<section id="page-title">
    <div class="col-lg-12 text-center"><h1>Server Information Details</h1></div>
</section>
<section id="filter-box">
    <div class="container">
        <h2>Filter</h2>
        <form id="filter-form" class="filter-form" action="" autocomplete="off" method="GET">
            <div class="row">
                <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
                    <div class="form-group multiselect-wrapper">
                        <label for="storage">RAM</label>
                        <select class="form-control multiselect" id="ram-select" name="ram[]" multiple></select>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
                    <div class="form-group multiselect-wrapper">
                        <label for="storage">Hard Disk Type</label>
                        <select class="form-control multiselect" id="hardDiskType-select" name="hardDiskType[]" multiple></select>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
                    <div class="form-group multiselect-wrapper">
                        <label for="storage">Storage</label>
                        <select class="form-control multiselect" id="storage-select" name="storage[]" multiple></select>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-5 mb-lg-0">
                    <div class="form-group multiselect-wrapper">
                        <label for="storage">Location</label>
                        <select class="form-control multiselect" id="location-select" name="location[]" multiple></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <input type="submit" name="filter-submit" id="filter-submit" value="Search" class="btn btn-primary" />
                </div>
            </div>
        </form>
    </div>
</section>

<section id="result-box">
    <div class="container">
        <h2>Server Information</h2>
        <div class="row" id="result-box-wrapper">
            <div class="col-lg-12 text-center">
                <div class="alert alert-info">
                    Please apply filter to search <strong>Server Information</strong>.
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(){

        // Get filter data
        var client = new $.RestClient('<?php echo $API_URL; ?>');

        client.add('filters');
        var filters_request = client.filters.read();

        filters_request.done(function (data, textStatus, xhrObject){
            if(xhrObject.status == 200){
                // Populate Filter Options
                filter_data = data.data;
                $.each( filter_data, function( select_key, filter_values ) {
                    $.each( filter_values, function( key, value ) {
                        $('#'+select_key+'-select').append($("<option></option>").attr("value", value).text(value));
                    });
                });
                $('.multiselect').multiselect({
                    search: true,
                    selectAll: true
                });
            } else{
                $('#filter-form').html(get_alert_message("danger", "Unable to load the filters."));
            }
        });

        // Onsubmit event for filter form
        client.add('search');
        $( "#filter-form" ).submit(function( event ) {
            event.preventDefault();
            form_values = getFormData($('#filter-form'));

            // Request the search API
            var search_request = client.search.read(form_values);
            result_wrapper = $('#result-box-wrapper');
            search_request.done(function (data, textStatus, xhrObject){
                if(xhrObject.status == 200){
                    search_html = createServerTableData(data.data);
                    result_wrapper.html(search_html);
                } else if(xhrObject.status == 204){
                    result_wrapper.html(get_alert_message("warning", "No servers matched your requirement."));
                }else{
                    result_wrapper.html(get_alert_message("danger", "Unable to load the filters."));
                }
            });

        });
    });
    
</script>
<?php 
require_once("include/footer.php");
?>