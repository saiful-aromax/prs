<form action="data_import" method="post" enctype="multipart/form-data">
<div class="box box-info">
    <div class="box-body col-sm-offset-2">
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Choose Your File</label>
            <div class="input-group col-sm-6 <?php if (form_error('import_file')) { ?> has-error <?php } ?>">
                <input class="form-control"  type="file" name="import_file" id="import_file">
                <?php echo(form_error('import_file')); ?>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <div class=" input-group col-sm-offset-4 col-sm-4">
                <input value="Import"
                       class="btn btn-success pull-right submit_buttons positive" name="submit" type="submit" >
            </div>
        </div>
    </div>
</div>
</form>

<!--<script type="text/javascript">

    function enable_disable_column_item(id) {
        $('.checkbox_class').each(function (i) {
            $("#column_selector_" + i).attr("disabled",false);
        });

        $("#column_selector_" + id).attr("disabled",true);
    }

    $(".checkbox_class").click(function () {
        if ($(this).is(':checked')) {
            $(this).parent().addClass("multiselect-on");
        } else {
            $(this).parent().removeClass("multiselect-on");
        }
    });

    function ajax_generate_form() {

        var id_periods = [];
        $('.checkbox_class:checked').each(function (i) {
            id_periods[i] = $(this).val();
        });
        var id_projects = $("#id_projects").val();
        var id_reporting_periods = $("#id_reporting_periods").val();
        var type = $("#type").val();
        var id_years = $("#id_years").val();
        var show_target = $("#show_target").val();

        if (validate(id_projects, id_reporting_periods, id_years, type)) {
            $.ajax({
                url: "<?php /*echo base_url('reports/ajax_data_export_report'); */?>",
                type: "post",
                data: {
                    id_projects: id_projects,
                    id_years: id_years,
                    type: type,
                    id_reporting_periods: id_reporting_periods,
                    show_target: show_target,
                    id_periods: id_periods
                },
                success: function (response) {
                    $('#form_html').html(response);
                },
                error: function (xhr) {
                    alert("Something went wrong!");
                }
            });
        } else {
            $('#form_html').html("");
        }
    }

    function validate(id_projects, id_reporting_periods, id_years,type) {
        var status = true;
        var error_message = "";
        if (!id_projects) {
            error_message = "Please select Activity!";
            $("#project_html").addClass("has-error");
            $("#project_error").html("<p class=\"help-block\"><i class=\"fa fa-times-circle-o\"></i>" + error_message + "</p>");
            status = false;
        } else {
            $("#project_html").removeClass("has-error");
            $("#project_error").html("");
        }
        if (!id_reporting_periods) {
            error_message = "Please select Reporting Period!";
            $("#reporting_period_html").addClass("has-error");
            $("#reporting_period_error").html("<p class=\"help-block\"><i class=\"fa fa-times-circle-o\"></i>" + error_message + "</p>");
            status = false;
        } else {
            $("#reporting_period_html").removeClass("has-error");
            $("#reporting_period_error").html("");
        }
        if (!id_years) {
            error_message = "Please select Fiscal Year!";
            $("#fiscal_year_html").addClass("has-error");
            $("#fiscal_year_error").html("<p class=\"help-block\"><i class=\"fa fa-times-circle-o\"></i>" + error_message + "</p>");
            status = false;
        } else {
            $("#fiscal_year_html").removeClass("has-error");
            $("#fiscal_year_error").html("");
        }
        if (!type) {
            error_message = "Please select Type!";
            $("#type_html").addClass("has-error");
            $("#type_html_error").html("<p class=\"help-block\"><i class=\"fa fa-times-circle-o\"></i>" + error_message + "</p>");
            status = false;
        } else {
            $("#type_html").removeClass("has-error");
            $("#type_html_error").html("");
        }
        return status;

    }
</script>-->