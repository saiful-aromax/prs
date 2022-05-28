<style type="text/css">
    .multiselect_check {
        width: 100%;
        height: 10em;
        border: solid 1px #c0c0c0;
        overflow: auto;
    }

    .multiselect label {
        font-weight: normal;
        display: block;
    }

    .multiselect-on {
        color: #ffffff;
        background-color: #7487b8;
    }
</style>

<?php echo form_open("transactions/index/" . $type); ?>
<div class="box box-info">
    <div class="box-body col-sm-offset-2">
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
                <?php echo form_dropdown('id_reporting_periods', ['' => '--Select--'] + $reporting_periods, set_value('id_reporting_periods'), ['class' => 'input_textbox form-control', 'id' => 'id_reporting_periods', 'onchange' => 'enable_disable_column_item(this.value)']); ?>
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
        <?php if ($type == 'Result') { ?>
            <div class="form-group">
                <label class="col-sm-2 control-label required_field">Are You Show Target Data?</label>
                <div id='fiscal_year_html' class="input-group col-sm-6">
                    <?php echo form_dropdown('show_target', ['' => '--Select--', '1' => 'Yes', '0' => 'No'], set_value('show_target'), ['class' => 'input_textbox form-control', 'id' => 'show_target']); ?>
                </div>
            </div>
        <?php } ?>
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Show Another Column :</label>
            <div class="input-group col-sm-6 <?php if (form_error('id_periods')) { ?> has-error <?php } ?>">
                <div class="multiselect_check">
                    <?php foreach ($reporting_periods as $id => $value) {
                        $is_check = false;
                        ?>
                        <label style="font-weight: normal; width: 100%; margin: 0%" <?php if ($is_check) { ?> class="multiselect-on" <?php } ?>><input
                                    class="checkbox_class input_textbox"
                                    type="checkbox"
                                    name="id_periods[]"
                                    id="column_selector_<?php echo $id; ?>"
                                    value="<?php echo $id; ?>" <?php if ($is_check) { ?> checked <?php } ?>/><?php echo $value; ?>
                        </label><br>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <div class="sidebar-toggle input-group col-sm-offset-4 col-sm-4">
                <input value="Generate" onclick="ajax_generate_form()"
                       class="btn btn-success pull-right submit_buttons positive" type="button" data-toggle="offcanvas"
                       id="checkbox1" >
            </div>
        </div>
    </div>
    <div id="form_html">

    </div>
</div>
<input type="hidden" name="type" value="<?php echo $type; ?>">
<script src="<?php echo base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>
<script type="text/javascript">

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
        var type = '<?php echo $type; ?>';
        var id_years = $("#id_years").val();
        var show_target = $("#show_target").val();

        if (validate(id_projects, id_reporting_periods, id_years)) {
            $.ajax({
                url: "<?php echo base_url('transactions/ajax_generate_form'); ?>",
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
