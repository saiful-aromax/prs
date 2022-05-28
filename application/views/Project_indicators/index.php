<!--
/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/29/17
 * Time: 2:59 PM
 */-->
<div class="box box-info">
    <div class="box-header">
        <?php echo anchor('project_indicators/add', 'Add New', array('class' => 'btn btn-default', 'title' => 'Add Project Indicator ', 'style' => "float:right;background-color:#13a5d3;color: #fff;")); ?>
        <?php echo form_open('project_indicators/index', ['method' => 'get']); ?>
        <div class=" col-filter">
            <?php
            $attribute = 'class="col-filter" placeholder="By Code  "';
            echo form_input('code', set_value('code', isset($session_data['code']) ? $session_data['code'] : ""), $attribute);
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
                <th> Activity </th>
                <th> Indicator </th>
                <th  width='15%'> Indicator Type </th>
                <th  width='15%'> Reporting Period </th>
                <th> Code</th>
                <th width='10%'>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = $counter;
            foreach ($project_indicators as $row):
                $i++; ?>
                <tr <?php if ($i % 2 == 0) {
                    echo 'class="evenrow";';
                    echo ' bgcolor="#fff"';
                } else {
                    echo 'class="oddrow"';
                } ?> >
                    <td class="serial"><?php echo $i; ?></td>
                    <td><?php echo $row->project_name; ?></td>
                    <td><?php echo $row->indicator_name; ?></td>
                    <td><?php echo $row->indicator_type; ?></td>
                    <td><?php echo $row->reporting_period; ?></td>
                    <td><?php echo $row->code; ?></td>
                    <td class="text-center">
                        <?php echo anchor('project_indicators/edit/' . $row->id,img(array('src' => base_url() . 'assets/dist/img/edit.gif', 'border' => '0', 'alt' => 'Edit Permission')), array('title' => 'Edit')); ?>
                        <?php echo anchor('project_indicators/delete/' . $row->id, img(array('src' => base_url() . 'assets/dist/img/delete.gif', 'border' => '0', 'alt' => 'delete')),
                            array('title' => 'Delete', 'onclick' => "return confirm('" . DELETE_CONFIRMATION_MESSAGE . "')")); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div align="center" id="paging"><?php echo $this->pagination->create_links(); ?></div>

    </div>
    <!-- /.box-body -->
</div>