<div class="box box-info">
    <div class="box-body table-responsive" data-pattern="priority-columns">
        <div style="text-align: center">
            <p style="font-size: 22px">
                <a href="" class="logo">
                    <img src="<?php echo base_url('assets/dist/img/logo.png'); ?>" style="width:3.8%" alt="User Image">
                </a>
                Performance Reporting System</p>
            <p style="text-align: center; font-size: 17px; margin: 0 0 0px; ">IP Report</p>
            <p style="text-align: center; margin-bottom: 15px"><?php echo $sub_header; ?></p>
        </div>
        <table class="table table-bordered table-striped transaction_form">
            <thead>
            <tr>
                <th rowspan="2">
                    Activities Code
                </th>
                <th rowspan="2">
                    Activities name
                </th>
                <th rowspan="2">
                    Commodity
                </th>
                <th rowspan="2">
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
                <th colspan="2">
                    Baseline
                </th>
                <?php if ($id_reporting_periods['name'] != 'Annually') { ?>
                    <th rowspan="2">
                        Annual Target of <?php echo $id_years['name'] . ' '; ?>
                    </th>
                <?php } ?>
                <th rowspan="2">
                    FY <?php echo $id_years['name'] . ' ';
                    echo $id_reporting_periods['name']; ?> Target
                </th>
                <th rowspan="2">
                    FY <?php echo $id_years['name'] . ' ';
                    echo $id_reporting_periods['name']; ?> Result
                </th>
                <th rowspan="2">
                    Deviation (%)
                </th>
                <?php if ($id_reporting_periods['name'] != 'Annually') { ?>
                    <th rowspan="2" style="text-align: center">
                        %of Achievement
                    </th>
                <?php } ?>
                <th rowspan="2">
                    Deviation Narratives
                </th>
                <?php if ($id_years_next1['name']) { ?>
                    <th rowspan="2">
                        FY <?php echo $id_years_next1['name'] ?> Target
                    </th>
                <?php } ?>
                <?php if ($id_years_next2['name']) { ?>
                    <th rowspan="2">
                        FY <?php echo $id_years_next2['name'] ?> Target
                    </th>
                <?php } ?>
                <?php if ($id_years_next3['name']) { ?>
                    <th rowspan="2">
                        FY <?php echo $id_years_next3['name'] ?> Target
                    </th>
                <?php } ?>
                <th rowspan="2">
                    Out-year target Rationales
                </th>
                <th rowspan="2">
                    Remarks
                </th>
            </tr>
            <tr>
                <th>Year</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $project_id = '';
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
                    <?php if ($project_id != $row['project_id']) {
                        $commodity_id = '';
                        ?>
                        <td rowspan="<?php echo $row['project_rowspan']; ?>"
                            style="vertical-align: middle"><?php echo $row['project_code']; ?></td>
                        <td rowspan="<?php echo $row['project_rowspan']; ?>"
                            style="vertical-align: middle"><?php echo $row['project_name']; ?></td>
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
                    <td><?php echo "2011"; ?></td>
                    <td><?php echo "5024"; ?></td>
                    <?php if ($id_reporting_periods['name'] != 'Annually') { ?>
                        <td style="text-align: right">
                            <?php echo $row['transaction']['Target_annual']; ?>
                        </td>
                    <?php }
                    $target_period_value = $row['transaction']['Target_'.strtolower(str_replace(" ", "_", $id_reporting_periods['name']))];
                    $result_period_value = $row['transaction']['Result_'.strtolower(str_replace(" ", "_", $id_reporting_periods['name']))];
                    ?>
                    <td style="text-align: right"><?php $target_period_value; ?></td>
                    <td style="text-align: right"><?php $result_period_value; ?></td>
                    <td style="text-align: right"><?php if ($row['disaggregate_name'] == 'Sub-total') {
                            if ($row['transaction']['Target_annual'] != 0) {
                                $deviation_percent = (number_format((($result_period_value - $target_period_value) / $target_period_value) * 100, DECIMAL_PLACE));
                                echo $deviation_percent . '%';
                            } else {
                                echo '0%';
                            }
                        } ?></td>
                    <?php if ($id_reporting_periods['name'] != 'Annually') { ?>
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
                    <?php if ($project_id != $row['project_id']) {
                        ?>
                        <td rowspan="<?php echo $row['project_rowspan']; ?>"
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
                $project_id = $row['project_id'];
                $commodity_id = $row['commodity_id'];
                $cluster_id = $row['cluster_id'];
                $disaggregate_group_id = $row['disaggregate_group_id'];
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
