<div class="box-body table-responsive" data-pattern="priority-columns">
    <table class="table table-bordered  transaction_form">
        <thead>
        <tr>
            <th rowspan="2">
                Indicator Code
            </th>
            <th rowspan="2" style="width: 15.6123%">
                Indicator name
            </th>
            <th rowspan="2" style="width: 8.6123%">
                Commodity
            </th>
            <th rowspan="2" style="width: 10.6123%">
                Cluster
            </th>
            <th rowspan="2">
                Disaggregate Group
            </th>
            <th rowspan="2">
                Disaggregate
            </th>
            <th rowspan="2">
                Unit
            </th>
            <?php
            $f = 0;
            $size = 0;
            if (isset($id_periods)) {
                $size = sizeof($id_periods);
                foreach ($id_periods as $id) {
                    if ($id == $id_reporting_periods) $f = 1;
                }
            }
            if ($show_target == '1' && $type == 'Result') { ?>
                <th style="width: 9.6123%" colspan="<?php echo ($f == 1) ? $size : $size + 1; ?>">
                    <?php echo "Target" ?>
                </th>
            <?php } ?>
            <th style="width: 9.6123%" colspan="<?php echo ($f == 1) ? $size : $size + 1; ?>">
                <?php echo $type ?>
            </th>
            <?php if ($type == 'Result') { ?>
                <th rowspan="2">
                    Deviation (%)
                </th>
            <?php }
            if ($type == 'Result') { ?>
                <th rowspan="2">
                    Deviation Narratives
                </th>
            <?php }
            if ($type == 'Result' || $type == 'Target') { ?>
                <th rowspan="2">
                    Out-year target Rationales
                </th>
            <?php } ?>
            <th rowspan="2" style="width: 10.6123%">
                Remarks
            </th>
        </tr>

        <tr>
            <?php if ($show_target == '1' && $type == 'Result') { ?>
                <?php if (isset($id_periods[1]) || $id_reporting_periods == 1) { ?>
                    <th>Annual</th> <?php } ?>
                <?php if (isset($id_periods[2]) || $id_reporting_periods == 2) { ?>
                    <th>Semi-annual1</th> <?php } ?>
                <?php if (isset($id_periods[3]) || $id_reporting_periods == 3) { ?>
                    <th>Semi-annual2</th> <?php } ?>
                <?php if (isset($id_periods[4]) || $id_reporting_periods == 4) { ?>
                    <th>Q1</th> <?php } ?>
                <?php if (isset($id_periods[5]) || $id_reporting_periods == 5) { ?>
                    <th>Q2</th> <?php } ?>
                <?php if (isset($id_periods[6]) || $id_reporting_periods == 6) { ?>
                    <th>Q3</th> <?php } ?>
                <?php if (isset($id_periods[7]) || $id_reporting_periods == 7) { ?>
                    <th>Q4</th> <?php } ?>
            <?php } ?>

            <?php if (isset($id_periods[1]) || $id_reporting_periods == 1) { ?>
                <th>Annual</th> <?php } ?>
            <?php if (isset($id_periods[2]) || $id_reporting_periods == 2) { ?>
                <th>Semi-annual1</th> <?php } ?>
            <?php if (isset($id_periods[3]) || $id_reporting_periods == 3) { ?>
                <th>Semi-annual2</th> <?php } ?>
            <?php if (isset($id_periods[4]) || $id_reporting_periods == 4) { ?>
                <th>Q1</th> <?php } ?>
            <?php if (isset($id_periods[5]) || $id_reporting_periods == 5) { ?>
                <th>Q2</th> <?php } ?>
            <?php if (isset($id_periods[6]) || $id_reporting_periods == 6) { ?>
                <th>Q3</th> <?php } ?>
            <?php if (isset($id_periods[7]) || $id_reporting_periods == 7) { ?>
                <th>Q4</th> <?php } ?>
        </tr>
        </thead>
        <tbody>

        <?php
        $odd_even_index = 0;
        $indicator_id = '';
        $commodity_id = '';
        $cluster_id = '';
        $disaggregate_group_id = '';
        $commodity_rowspan = 0;
        $commodity_index = 0;
        $cluster_rowspan = 0;
        $cluster_index = 0;
        $disaggregate_group_index = 0;
        $disaggregate_group_rowspan = 0;
        //echo '<pre>'; print_r($transactions); die;
        //            $i = sizeof($transactions);

        foreach ($transactions as $row) {
            $odd_even_index++;
            $odd_even_class = "odd_class";
            if ($odd_even_index % 2 == 0) {
                $odd_even_class = "even_class";
            } else {
                $odd_even_class = "odd_class";
            }
//                $i++;

            $disaggregate_group_class = $row['indicator_id'] . '_' . $row['commodity_id'] . '_' . $row['cluster_id'] . '_' . $row['disaggregate_group_id'] . ($row['disaggregate_name'] == 'Sub-total' ? '_total' : '');
            $target_disaggregate_group_subtotal_class = "";
            if ($row['disaggregate_name'] == 'Sub-total') {
                if ($id_reporting_periods == 1) {
                    $target_disaggregate_group_subtotal = $row['transaction']['Target_annual'];
                } elseif ($id_reporting_periods == 2) {
                    $target_disaggregate_group_subtotal = $row['transaction']['Target_semi_annual_1'];
                } elseif ($id_reporting_periods == 3) {
                    $target_disaggregate_group_subtotal = $row['transaction']['Target_semi_annual_2'];
                } elseif ($id_reporting_periods == 4) {
                    $target_disaggregate_group_subtotal = $row['transaction']['Target_q1'];
                } elseif ($id_reporting_periods == 5) {
                    $target_disaggregate_group_subtotal = $row['transaction']['Target_q2'];
                } elseif ($id_reporting_periods == 6) {
                    $target_disaggregate_group_subtotal = $row['transaction']['Target_q3'];
                } elseif ($id_reporting_periods == 7) {
                    $target_disaggregate_group_subtotal = $row['transaction']['Target_q4'];
                } else {
                    $target_disaggregate_group_subtotal = 0;
                }
            }

            ?>


            <tr>
                <?php if ($indicator_id != $row['indicator_id']) {
                    $commodity_id = '';
                    ?>
                    <td rowspan="<?php echo $row['indicator_rowspan']; ?>"
                        style="vertical-align: middle"><?php echo $row['indicator_code']; ?></td>
                    <td rowspan="<?php echo $row['indicator_rowspan']; ?>"
                        style="vertical-align: middle"><?php echo $row['indicator_name']; ?></td>
                <?php } ?>
                <?php if ($commodity_id != $row['commodity_id']) {
                    $cluster_id = '';
                    ?>
                    <td rowspan="<?php echo $row['commodity_rowspan']; ?>"
                        style="vertical-align: middle"><?php echo $row['commodity_name']; ?></td>
                <?php } ?>
                <?php if ($cluster_id != $row['cluster_id']) {
                    $disaggregate_group_id = '';
                    ?>
                    <td rowspan="<?php echo $row['cluster_rowspan']; ?>"
                        style="vertical-align: middle"><?php echo $row['cluster_name']; ?></td>
                <?php } ?>
                <?php if ($disaggregate_group_id != $row['disaggregate_group_id']) { ?>
                    <td rowspan="<?php echo $row['disaggregate_group_rowspan']; ?>"
                        style="vertical-align: middle"><?php echo $row['disaggregate_group_name']; ?></td>
                <?php } ?>


                <td class="<?php echo $odd_even_class; ?>"><?php echo $row['disaggregate_name']; ?></td>
                <td class="<?php echo $odd_even_class; ?>"><?php echo $row['unit_name']; ?></td>

                <?php if ($show_target == '1' && $type == 'Result') {
                    if (isset($id_periods[1]) || $id_reporting_periods == 1) { ?>
                        <td class="<?php echo $odd_even_class; ?>">
                            <?php echo $row['transaction']['Target_annual']; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($id_periods[2]) || $id_reporting_periods == 2) { ?>
                        <td class="<?php echo $odd_even_class; ?>">
                            <?php echo $row['transaction']['Target_semi_annual_1']; ?>
                        </td>

                    <?php } ?>
                    <?php if (isset($id_periods[3]) || $id_reporting_periods == 3) { ?>
                        <td class="<?php echo $odd_even_class; ?>">
                            <?php echo $row['transaction']['Target_semi_annual_2']; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($id_periods[4]) || $id_reporting_periods == 4) { ?>
                        <td class="<?php echo $odd_even_class; ?>">
                            <?php echo $row['transaction']['Target_q1']; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($id_periods[5]) || $id_reporting_periods == 5) { ?>
                        <td class="<?php echo $odd_even_class; ?>">
                            <?php echo $row['transaction']['Target_q2']; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($id_periods[6]) || $id_reporting_periods == 6) { ?>
                        <td class="<?php echo $odd_even_class; ?>">
                            <?php echo $row['transaction']['Target_q3']; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($id_periods[7]) || $id_reporting_periods == 7) { ?>
                        <td class="<?php echo $odd_even_class; ?>">
                            <?php echo $row['transaction']['Target_q4']; ?>
                        </td>
                    <?php }
                } ?>
                <?php if (isset($id_periods[1]) || $id_reporting_periods == 1) { ?>
                    <td class="<?php echo $odd_even_class; ?>">
                        <?php echo $row['transaction'][$type . '_annual']; ?>
                    </td>
                <?php } ?>
                <?php if (isset($id_periods[2]) || $id_reporting_periods == 2) { ?>
                    <td class="<?php echo $odd_even_class; ?>">
                        <?php echo $row['transaction'][$type . '_semi_annual_1']; ?>
                    </td>
                <?php } ?>
                <?php if (isset($id_periods[3]) || $id_reporting_periods == 3) { ?>
                    <td class="<?php echo $odd_even_class; ?>">
                        <?php echo $row['transaction'][$type . '_semi_annual_2']; ?>
                    </td>
                <?php } ?>
                <?php if (isset($id_periods[4]) || $id_reporting_periods == 4) { ?>
                    <td class="<?php echo $odd_even_class; ?>">
                        <?php echo $row['transaction'][$type . '_q1']; ?>
                    </td>
                <?php } ?>
                <?php if (isset($id_periods[5]) || $id_reporting_periods == 5) { ?>
                    <td class="<?php echo $odd_even_class; ?>">
                        <?php echo $row['transaction'][$type . '_q2']; ?>
                    </td>
                <?php } ?>
                <?php if (isset($id_periods[6]) || $id_reporting_periods == 6) { ?>
                    <td class="<?php echo $odd_even_class; ?>">
                        <?php echo $row['transaction'][$type . '_q3']; ?>
                    </td>
                <?php } ?>
                <?php if (isset($id_periods[7]) || $id_reporting_periods == 7) { ?>
                    <td class="<?php echo $odd_even_class; ?>">
                        <?php echo $row['transaction'][$type . '_q4']; ?>
                    </td>
                <?php } ?>
                <?php if ($type == 'Result') {
                    $deviation = 0;
                    ($id_reporting_periods == '1' && isset($row['transaction']['Deviation_annual'])) ? $deviation = $row['transaction']['Deviation_annual'] : $deviation = 0;
                    ($id_reporting_periods == '2' && isset($row['transaction']['Deviation_semi_annual_1'])) ? $deviation = $row['transaction']['Deviation_semi_annual_1'] : $deviation = 0;
                    ($id_reporting_periods == '3' && isset($row['transaction']['Deviation_semi_annual_2'])) ? $deviation = $row['transaction']['Deviation_semi_annual_2'] : $deviation = 0;
                    ($id_reporting_periods == '4' && isset($row['transaction']['Deviation_q1'])) ? $deviation = $row['transaction']['Deviation_q1'] : $deviation = 0;
                    ($id_reporting_periods == '5' && isset($row['transaction']['Deviation_q2'])) ? $deviation = $row['transaction']['Deviation_q2'] : $deviation = 0;
                    ($id_reporting_periods == '6' && isset($row['transaction']['Deviation_q3'])) ? $deviation = $row['transaction']['Deviation_q3'] : $deviation = 0;
                    ($id_reporting_periods == '7' && isset($row['transaction']['Deviation_q4'])) ? $deviation = $row['transaction']['Deviation_q4'] : $deviation = 0;

                    $deviation_class = $row['indicator_id'] . '_' . $row['commodity_id'] . '_' . $row['cluster_id'] . '_' . $row['disaggregate_group_id'];

                    if ($disaggregate_group_id != $row['disaggregate_group_id']) { ?>
                        <td rowspan="<?php echo $row['disaggregate_group_rowspan']; ?>"
                            class="deviation_<?php echo $deviation_class; ?>"
                            style="vertical-align: middle; text-align: center">
                            <?php echo $deviation . '%'; ?>
                        </td>
                    <?php }
                }
                if ($type == 'Result') {
                    if ($indicator_id != $row['indicator_id']) {
                        ?>
                        <td rowspan="<?php echo $row['indicator_rowspan']; ?>"
                            style="vertical-align: middle">
                            <textarea style="height: <?php echo ((int)$row['indicator_rowspan']) * 40; ?>px"
                                      rows="<?php echo $row['indicator_rowspan']; ?>" name=""
                                      class=""></textarea>
                        </td>
                    <?php }
                }
                if ($type == 'Result' || $type == 'Target') {
                    if ($indicator_id != $row['indicator_id']) {
                        ?>
                        <td rowspan="<?php echo $row['indicator_rowspan']; ?>"
                            style="vertical-align: middle">
                            <textarea style="height: <?php echo ((int)$row['indicator_rowspan']) * 40; ?>px"
                                <?php if ($type == 'Result') echo 'disabled' ?>
                                      name=""
                                      class=""></textarea>
                        </td>
                    <?php } ?>
                <?php } ?>
                <?php if ($indicator_id != $row['indicator_id']) {
                    ?>
                    <td rowspan="<?php echo $row['indicator_rowspan']; ?>"
                        style="vertical-align: middle">Remarks
                    </td>
                <?php } ?>
            </tr>
            <?php
            $indicator_id = $row['indicator_id'];
            $commodity_id = $row['commodity_id'];
            $cluster_id = $row['cluster_id'];
            $disaggregate_group_id = $row['disaggregate_group_id'];
        }
        ?>
        </tbody>
    </table>
</div>
</div>
<input type="hidden" name="id_time_sets" value="<?php echo $id_time_sets; ?>">

<div class="form-group">
    <div class="input-group col-sm-offset-4 col-sm-4">
        <a href="" class="btn btn-success pull-right submit_buttons positive" download="DataExport.csv" onclick="return ExcellentExport.csv(this, 'data_export_table');"> Export to CSV</a>
    </div>
</div>

<?php echo form_close(); ?>

<div style="visibility: hidden;">
    <?php $this->load->view('Reports/ajax_data_export_csv_view'); ?>
</div>

<script src="<?php echo base_url('assets/excellentexport/excellentexport.js'); ?>"></script>

<script>
    $('.data_entry').on("keyup change", function () {
        var value = $(this).val();
        if (isNaN(value) || value < 0) {
            $(this).val('');
        }
//        $(".data_entry").each(function (index) {
//        });
        var class_list = $(this).attr('class').split(' ');
        var group_total = 0;
        $("." + class_list[3]).each(function (index) {
            group_total = group_total * 1 + ($(this).val() == '' ? 0 : Number($(this).val()));
        });
        $('.' + class_list[3] + '_total').val(group_total);
        var target = "<?php echo $target_disaggregate_group_subtotal?>";
        var deviation_result = (target > 0) ? ((group_total - target) / target) * 100 : 0;
        $('.deviation_' + class_list[3]).html(deviation_result + '%');
    });

    function on_submit_validation() {
        var status = false;

        if ($('.5_4_4_9_total').val()) {
            var sub_5_4_4_9_total = parseFloat($('.5_4_4_9_total').val());
            var sub_5_5_4_8_total = parseFloat($('.5_5_4_8_total').val());
            if (sub_5_4_4_9_total != sub_5_5_4_8_total) {
                alert('Error!');
                status = false;
            } else {
                status = true;
            }
        } else {
            status = true;
        }

        return status;
    }
</script>
<style>
    .odd_class {
        background: white;
    }

    .even_class {
        background-color: rgba(103, 58, 183, 0.07);
    }
</style>