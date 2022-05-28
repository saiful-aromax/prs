
<!--/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/31/17
 * Time: 10:23 AM
 */-->

<div class="box box-info">
    <div class="box-header">
        <?php echo anchor('project_indicator_disaggregate_sets/', 'Back', array('class' => 'btn btn-default', 'title' => 'Add Project indicator disaggregate set', 'style' => "float:right;background-color:#13a5d3;color: #fff;")); ?>
        <?php echo form_open('project_indicator_disaggregate_sets/view/'.$pi_id.'/'.$c_id, ['method' => 'get']); ?>

        <div class=" col-filter">
            <?php
            $attribute = 'class="col-filter" placeholder="By Disaggrigate Code  "';
            echo form_input('ds_code', set_value('ds_code', isset($session_data['ds_code']) ? $session_data['ds_code'] : ""), $attribute);
            ?>
        </div>

        <div class=" col-filter">
            <button type="submit" id="submit" class="btn btn-search"><i class="fa fa-search"></i> Search</button>
        </div>
        <?php echo form_close()?>
    </div>


    <!-- /.box-header -->
    <div class="box-body">
        <table id="example2" class="table table-bordered table-striped table-hover table_index">
            <thead>
            <tr>
                <th width='5%'>#</th>
                <th> Disaggrigate Code</th>
                <th width='10%'>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = $counter;
            foreach ($disaggregate_set as $id=>$value):
                $i++; ?>
                <tr <?php if ($i % 2 == 0) {
                    echo 'class="evenrow";';
                    echo ' bgcolor="#fff"';
                } else {
                    echo 'class="oddrow"';
                } ?> >
                    <td class="serial"><?php echo $i; ?></td>
                    <td><?php echo $value; ?></td>
                    <td class="text-center">

                        <?php echo anchor('Project_indicator_disaggregate_sets/delete/' . $id.'/'.$pi_id.'/'.$c_id, img(array('src' => base_url() . 'assets/dist/img/delete.gif', 'border' => '0', 'alt' => 'edit')),
                            array('title' => 'Delete', 'onclick' => "return confirm('" . DELETE_CONFIRMATION_MESSAGE . "')")); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div align="center" id="paging"><?php //echo $this->pagination->create_links(); ?></div>

    </div>
    <!-- /.box-body -->
</div>



