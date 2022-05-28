<div class="box box-info">
    <div class="box-body table-responsive" data-pattern="priority-columns">
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
                    <table class="table table-bordered table-striped transaction_form">
                        <tr>
                            <td rowspan="2">Disaggregate</td>
                            <td rowspan="2">Unit</td>
                            <td colspan="7">Baseline</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </th>
                <td>
                    Remarks
                </td>

            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($transactions as $row) {
                ?>
                <tr>
                    <td><?php echo $row['code']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <table class="table table-bordered table-striped transaction_form">
                            <?php foreach ($row['commodities'] as $commodity) { ?>
                                <tr>
                                    <td>
                                        <?php echo $commodity['name']; ?>

                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    </td>
                    <td>
                        <table class="table table-bordered table-striped transaction_form">
                            <?php foreach ($row['commodities'] as $commodity) { ?>
                                <?php foreach ($commodity['clusters'] as $cluster) { ?>
                                    <tr>
                                        <td>
                                            <?php echo $cluster['name']; ?>

                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </table>
                    </td>
                    <td>
                        <table class="table table-bordered table-striped transaction_form">
                            <?php foreach ($row['commodities'] as $commodity) { ?>
                                <?php foreach ($commodity['clusters'] as $cluster) { ?>
                                    <?php foreach ($cluster['disaggregate_groups'] as $disaggregate_group) { ?>
                                        <tr>
                                            <td>
                                                <?php echo $disaggregate_group['name']; ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </table>
                    </td>
                    <td>
                        <table class="table table-bordered table-striped transaction_form">
                            <?php foreach ($row['commodities'] as $commodity) { ?>
                                <?php foreach ($commodity['clusters'] as $cluster) { ?>
                                    <?php foreach ($cluster['disaggregate_groups'] as $disaggregate_group) { ?>
                                        <?php foreach ($disaggregate_group['disaggregates'] as $disaggregate) { ?>
                                            <tr>
                                                <td>
                                                    <?php echo $disaggregate['name']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $disaggregate['unit']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $disaggregate['transactions']['Baseline_annual']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $disaggregate['transactions']['Baseline_semi_annual_1']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $disaggregate['transactions']['Baseline_semi_annual_2']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $disaggregate['transactions']['Baseline_q1']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $disaggregate['transactions']['Baseline_q2']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $disaggregate['transactions']['Baseline_q3']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $disaggregate['transactions']['Baseline_q4']; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                        </table>
                    </td>


                    <td>
                        <textarea name="remarks" value=""/>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<style>
    .inline {
        display: inline;
        border: 1px solid red;
        margin: 10px;
    }
    .inline > div{
        display: inline;
        border: 1px solid red;
        margin: 10px;
    }
    .inline > div > div{
        display: inline;
        border: 1px solid red;
        margin: 10px;
    }
</style>
<?php
foreach ($transactions as $row) { ?>
    <div class="inline" style="width: 100%"><?php echo $row['code']; ?>
    <?php foreach ($row['commodities'] as $commodity) { ?>
        <div class="inline"><?php echo $commodity['name']; ?>
        <?php foreach ($commodity['clusters'] as $cluster) { ?>
            <div class="inline"><?php echo $cluster['name']; ?>
            <?php foreach ($cluster['disaggregate_groups'] as $disaggregate_group) { ?>
                <div class="inline"><?php echo $disaggregate_group['name']; ?>
                <?php foreach ($disaggregate_group['disaggregates'] as $disaggregate) { ?>
                    <div class='inline'>
                        <div>
                            <?php echo $disaggregate['name']; ?>
                        </div>
                        <div>
                            <?php echo $disaggregate['unit']; ?>
                        </div>
                        <div>
                            <?php echo $disaggregate['transactions']['Baseline_annual']; ?>
                        </div>
                        <div>
                            <?php echo $disaggregate['transactions']['Baseline_semi_annual_1']; ?>
                        </div>
                        <div>
                            <?php echo $disaggregate['transactions']['Baseline_semi_annual_2']; ?>
                        </div>
                        <div>
                            <?php echo $disaggregate['transactions']['Baseline_q1']; ?>
                        </div>
                        <div>
                            <?php echo $disaggregate['transactions']['Baseline_q2']; ?>
                        </div>
                        <div>
                            <?php echo $disaggregate['transactions']['Baseline_q3']; ?>
                        </div>
                        <div>
                            <?php echo $disaggregate['transactions']['Baseline_q4']; ?>
                        </div>
                    </div><br>
                <?php } ?>
                </div>
            <?php } ?>
            </div>
        <?php } ?>
        </div>
    <?php } ?>
    </div>
<?php } ?>
<input type="hidden" name="id_time_sets" value="<?php echo $id_time_sets; ?>">
<div class="form-group" style="margin-bottom: 15px;">
    <div class="input-group col-sm-offset-4 col-sm-4">
        <input value="Process"
               class="btn btn-success pull-right submit_buttons positive" type="submit">
    </div>
</div>
<?php echo form_close(); ?>

