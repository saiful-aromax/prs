<?php
if ($report_type == '') { ?><div class="box box-info"><?php } ?>
    <div class="box-body table-responsive" data-pattern="priority-columns">
        <div style="text-align: center">
            <p style="font-size: 22px">
                <a href="" class="logo">
                    <img src="<?php echo base_url('assets/dist/img/logo.png'); ?>" style="width:3.8%" alt="User Image">
                </a>Performance Reporting System</p>
            <p style="text-align: center; font-size: 17px; margin: 0 0 0px; ">IP Report</p>
            <p style="text-align: center; margin-bottom: 15px"><?php echo $sub_header; ?></p>
        </div>
        <table class="table table-bordered table-striped transaction_form">
            <thead>
            <tr>
                <th>
                    Indicator Code
                </th>
                <th>
                    Indicator name
                </th>
                <th>
                    Commodity
                </th>
                <th>
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
                <?php if ($id_reporting_periods['name'] != 'Annual') { ?>
                    <th>
                        Annual Target of <?php echo $id_years['name'] . ' '; ?>
                    </th>
                <?php } ?>
                <th>
                    FY <?php echo $id_years['name'] . ' ';
                    echo $id_reporting_periods['name']; ?> Target
                </th>
                <th>
                    FY <?php echo $id_years['name'] . ' ';
                    echo $id_reporting_periods['name']; ?> Result
                </th>
                <th>
                    Deviation (%)
                </th>
                <?php if ($id_reporting_periods['name'] != 'Annual') { ?>
                    <th style="text-align: center">
                        %of Achievement
                    </th>
                <?php } ?>
                <th>
                    Deviation Narratives
                </th>
                <?php if ($id_years_next1['name']) { ?>
                    <th>
                        FY <?php echo $id_years_next1['name'] ?> Target
                    </th>
                <?php } ?>
                <?php if ($id_years_next2['name']) { ?>
                    <th>
                        FY <?php echo $id_years_next2['name'] ?> Target
                    </th>
                <?php } ?>
                <?php if ($id_years_next3['name']) { ?>
                    <th>
                        FY <?php echo $id_years_next3['name'] ?> Target
                    </th>
                <?php } ?>
                <th>
                    Out-year target Rationales
                </th>
                <th>
                    Remarks
                </th>
            </tr>
            </thead>
            <tbody>
            <?php

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
            //            echo '<pre>';
            //            print_r($transactions); die;
            foreach ($transactions as $row) { //echo $row['cluster_rowspan']; die;
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
                    <td><?php echo $row['disaggregate_name']; ?></td>
                    <td><?php echo $row['unit_name']; ?></td>
                    <?php if ($id_reporting_periods['name'] != 'Annual') { ?>
                        <td style="text-align: right">
                            <?php echo $row['transaction']['Target_annual']; ?>
                        </td>
                    <?php }
                    $target_period_value = $row['transaction']['Target_' . strtolower(str_replace(" ", "_", $id_reporting_periods['name']))];
                    $result_period_value = $row['transaction']['Result_' . strtolower(str_replace(" ", "_", $id_reporting_periods['name']))]; ?>

                    <td style="text-align: right"><?php $target_period_value; ?></td>
                    <td style="text-align: right"><?php $result_period_value; ?></td>
                    <td style="text-align: right"><?php if ($row['disaggregate_name'] == 'Sub-total') {
                            if ($row['transaction']['Target_annual'] != 0) {
                                $deviation_percent = (number_format((($result_period_value - $target_period_value) / $target_period_value) * 100, 2));
                                echo $deviation_percent . '%';
                            } else {
                                echo '0%';
                            }
                        } ?></td>
                    <?php if ($id_reporting_periods['name'] != 'Annual') { ?>
                        <td style="text-align: right">
                            0%
                        </td>
                    <?php } ?>
                    <?php if ($disaggregate_group_id != $row['disaggregate_group_id']) {
                        ?>
                        <td rowspan="<?php echo $row['disaggregate_group_rowspan']; ?>"
                            style="vertical-align: middle">
                            <p>
                                AVC intervenes, wider ranges of technology and management practices through class based
                                training and market based service delivery points that impacted farmers to utilize
                                technology
                                and management practices. AVC annual performance study shows that most of the farmers
                                used
                                at least one technology or management practices that reflected the land coverage. AVC's
                                interventions using value chain actors reached a large number
                                farmers and covered 26,260 hectare land.
                            </p>
                        </td>
                    <?php } ?>

                    <?php if ($id_years_next1['name']) { ?>
                        <td style="text-align: right">
                            <?php echo $row['transaction']['Target_annual_1']; ?>
                        </td>
                    <?php } ?>
                    <?php if ($id_years_next2['name']) { ?>
                        <td style="text-align: right">
                            <?php echo $row['transaction']['Target_annual_2']; ?>
                        </td>
                    <?php } ?>
                    <?php if ($id_years_next3['name']) { ?>
                        <td style="text-align: right">
                            <?php echo $row['transaction']['Target_annual_3']; ?>
                        </td>
                    <?php } ?>
                    <?php if ($indicator_id != $row['indicator_id']) {
                        ?>
                        <td rowspan="<?php echo $row['indicator_rowspan']; ?>"
                            style="vertical-align: middle">
                            <p>
                                A huge percentage of trained farmers who have applied the technologies in their land
                                will be considered for this indicator. Their land area will be counted here. In previous
                                years, farmers have adopted some of the trained technologies in a higher rate. Thus for
                                estimation of adoption of individual technologies, 2016 annual survey results were used
                                as a base.
                            </p>
                        </td>
                    <?php } ?>
                        <td style="vertical-align: middle">
                            <p>
                                good
                            </p>
                        </td>


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
<?php if ($report_type == '') { ?></div><?php } ?>