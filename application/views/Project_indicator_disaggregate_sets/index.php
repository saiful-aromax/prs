
<!--/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/31/17
 * Time: 10:23 AM
 */-->

<div class="box box-info">
    <div class="box-header">
        <?php echo anchor('project_indicator_disaggregate_sets/add', 'Add New', array('class' => 'btn btn-default', 'title' => 'Add Project indicator disaggregate set', 'style' => "float:right;background-color:#13a5d3;color: #fff;")); ?>
        <?php echo form_open('project_indicator_disaggregate_sets/index', ['method' => 'get']); ?>
        <div class=" col-filter">
            <?php
            $attribute = 'class="col-filter" placeholder="By Project Indicator Code  "';
            echo form_input('pi_code', set_value('pi_code', isset($session_data['pi_code']) ? $session_data['pi_code'] : ""), $attribute);
            ?>
        </div>

        <div class=" col-filter">
            <button type="submit" id="submit" class="btn btn-search"><i class="fa fa-search"></i> Search</button>
        </div>
    </div>


    <!-- /.box-header -->
    <div class="box-body">
        <table id="example2" class="table table-bordered table-striped table-hover table_index">
            <thead>
            <tr>
                <th width='5%'>#</th>
                <th> Project Indicator Code</th>
                <th> Commodity Code</th>
                <th width='10%'>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = $counter;
            foreach ($indicator_disaggregates as $row):
                $i++; ?>
                <tr <?php if ($i % 2 == 0) {
                    echo 'class="evenrow";';
                    echo ' bgcolor="#fff"';
                } else {
                    echo 'class="oddrow"';
                } ?> >
                    <td class="serial"><?php echo $i; ?></td>
                    <td><?php echo $row->pi_code; ?></td>
                    <td><?php echo $row->c_code; ?></td>
                    <td class="text-center">
                        <?php echo anchor('Project_indicator_disaggregate_sets/view/' . $row->id.'/'.$row->c_id, img(array('src' => base_url() . 'assets/dist/img/view.gif', 'border' => '0', 'alt' => 'View')), array('title' => 'View')); ?>
                        <?php echo anchor('Project_indicator_disaggregate_sets/edit/' . $row->id.'/'.$row->c_id, img(array('src' => base_url() . 'assets/dist/img/edit.gif', 'border' => '0', 'alt' => 'Edit')), array('title' => 'Edit')); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div align="center" id="paging"><?php //echo $this->pagination->create_links(); ?></div>

    </div>
    <!-- /.box-body -->
</div>



