<div class="box box-info">
    <div class="box-body col-sm-offset-2">
        <?php echo form_open("reports/ajax_generate_report"); ?>
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Activity:</label>
            <div id='project_html' class="input-group col-sm-6">
                <?php echo form_dropdown('id_projects', ['' => '--Select--'] + $projects, set_value('id_projects'), ['class' => 'input_textbox form-control', 'id' => 'id_projects']); ?>
                <span id="project_error"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Reporting Period:</label>
            <div id='reporting_period_html' class="input-group col-sm-6">
                <?php echo form_dropdown('id_reporting_periods', ['' => '--Select--'] + $reporting_periods, set_value('id_reporting_periods'), ['class' => 'input_textbox form-control', 'id' => 'id_reporting_periods']); ?>
                <span id="reporting_period_error"></span>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Fiscal Year:</label>
            <div id='fiscal_year_html' class="input-group col-sm-6">
                <?php echo form_dropdown('id_years', ['' => '--Select--'] + $years, set_value('id_years'), ['class' => 'input_textbox form-control', 'id' => 'id_years']); ?>
                <span id="fiscal_year_error"></span>
            </div>
        </div>
<!--        <a download="somedata.csv" href="#" onclick="return ExcellentExport.csv(this, 'datatable');">Export to CSV</a>-->

        <input type="hidden" name="report_type" value="pdf">
        <input type="hidden" name="type" value="<?php echo $type; ?>">

        <div class="form-group" style="margin-bottom: 15px;">
            <div class="sidebar-toggle input-group col-sm-offset-4 col-sm-4">
                <input value="Generate" onclick="ajax_generate_report()"
                       class="btn btn-success pull-right submit_buttons positive" type="button" data-toggle="offcanvas"
                       id="checkbox1" data-collapsed="false"> &nbsp;
                <button type="submit"
                        class="btn btn-success pull-right submit_buttons positive">PDF
                </button>

            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
    <div id="form_html">

    </div>
</div>
<script src="<?php echo base_url('assets/excellentexport/excellentexport.js'); ?>"></script>
<script type="text/javascript">

    function ajax_generate_report(report_type = '') {
        var id_projects = $("#id_projects").val();
        var id_reporting_periods = $("#id_reporting_periods").val();
        var type = '<?php echo $type; ?>';
        var id_years = $("#id_years").val();
        if (validate(id_projects, id_reporting_periods, id_years)) {
            $.ajax({
                url: "<?php echo base_url('reports/ajax_generate_report'); ?>",
                type: "post",
                data: {
                    id_projects: id_projects,
                    id_years: id_years,
                    type: type,
                    report_type: report_type,
                    id_reporting_periods: id_reporting_periods
                },
                success: function (response) {
                    $('#form_html').html(response);
                    $('#checkbox1').removeAttr('data-toggle');
                },
                error: function (xhr) {
                    alert("Something went wrong!");
                }
            });
        } else {
            $('#form_html').html("");
        }
    }

    function validate(id_projects, id_reporting_periods, id_years) {
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
        return status;

    }
</script>
