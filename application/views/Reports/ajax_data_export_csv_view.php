<?php
$value_string = "";
switch ($id_reporting_periods) {
    case 1:
        $value_string = $type . '_Annual_' . $fiscal_year_name;
        break;
    case 2:
        $value_string = $type . '_ Semi-Annual-1_' . $fiscal_year_name;
        break;
    case 3:
        $value_string = $type . '_Semi-Annual-2_' . $fiscal_year_name;
        break;
    case 4:
        $value_string = $type . '_Q1_' . $fiscal_year_name;
        break;
    case 5:
        $value_string = $type . '_Q2_' . $fiscal_year_name;
        break;
    case 6:
        $value_string = $type . '_Q3_' . $fiscal_year_name;
        break;
    case 7:
        $value_string = $type . '_Q4_' . $fiscal_year_name;
        break;
}
?>
<div class="box-body table-responsive" data-pattern="priority-columns" id="data_export">
    <table class="table table-bordered  transaction_form" id="data_export_table">
        <thead>
        <tr>
            <th>
                Activity
            </th>
            <th>
                Indicator Code
            </th>
            <th style="width: 15.6123%">
                Indicator name
            </th>
            <th style="width: 8.6123%">
                Commodity
            </th>
            <th style="width: 10.6123%">
                Cluster
            </th>
            <th>
                Disaggregate Group
            </th>
            <th>
                Disaggregate
            </th>
            <th>
                Unit
            </th>
            <th style="width: 9.6123%">
                <?php echo $value_string ?>
            </th>
            <?php if ($type == 'Result') { ?>
                <th>
                    Deviation (%)
                </th>
                <th>
                    Deviation Narratives
                </th>
            <?php }
            if ($type == 'Target') { ?>
                <th>
                    Out-year target Rationales
                </th>
            <?php } ?>
            <th style="width: 10.6123%">
                Remarks
            </th>

        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($transactions as $row) {

            if ($row['disaggregate_name'] != 'Sub-total') {
                ?>
                <tr>
                    <td
                            style="vertical-align: middle"><?php echo $activity_name; ?></td>
                    <td
                            style="vertical-align: middle"><?php echo $row['indicator_code']; ?></td>
                    <td
                            style="vertical-align: middle"><?php echo $row['indicator_name']; ?></td>
                    <td
                            style="vertical-align: middle"><?php echo $row['commodity_name']; ?></td>
                    <td
                            style="vertical-align: middle"><?php echo $row['cluster_name']; ?></td>
                    <td
                            style="vertical-align: middle"><?php echo $row['disaggregate_group_name']; ?></td>
                    <td><?php echo $row['disaggregate_name']; ?></td>
                    <td><?php echo $row['unit_name']; ?></td>
                    <?php if (isset($id_periods[1]) || $id_reporting_periods == 1) { ?>
                        <td>
                            <?php echo $row['transaction'][$type . '_annual']; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($id_periods[2]) || $id_reporting_periods == 2) { ?>
                        <td>
                            <?php echo $row['transaction'][$type . '_semi_annual_1']; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($id_periods[3]) || $id_reporting_periods == 3) { ?>
                        <td>
                            <?php echo $row['transaction'][$type . '_semi_annual_2']; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($id_periods[4]) || $id_reporting_periods == 4) { ?>
                        <td>
                            <?php echo $row['transaction'][$type . '_q1']; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($id_periods[5]) || $id_reporting_periods == 5) { ?>
                        <td>
                            <?php echo $row['transaction'][$type . '_q2']; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($id_periods[6]) || $id_reporting_periods == 6) { ?>
                        <td>
                            <?php echo $row['transaction'][$type . '_q3']; ?>
                        </td>
                    <?php } ?>
                    <?php if (isset($id_periods[7]) || $id_reporting_periods == 7) { ?>
                        <td>
                            <?php echo $row['transaction'][$type . '_q4']; ?>
                        </td>
                    <?php } ?>
                    <?php
                    if ($type == 'Result' || $type == 'Target') {

                        ?>
                        <td>Deviation</td>
                        <td>Deviation Narrative</td>

                    <?php } ?>
                    <td
                            style="vertical-align: middle">Remarks
                    </td>
                </tr>
                <?php

            }
        }
        ?>
        </tbody>
    </table>
</div>