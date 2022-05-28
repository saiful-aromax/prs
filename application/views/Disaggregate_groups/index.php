<!--/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/23/17
 * Time: 5:15 PM
 */-->

<div class="box box-info">
    <div class="box-header">
        <?php echo anchor('disaggregate_groups/add', 'Add New', array('class' => 'btn btn-default', 'title' => 'Add Disaggregate Group', 'style' => "float:right;background-color:#13a5d3;color: #fff;")); ?>
        <?php echo form_open('disaggregate_groups/index', ['method' => 'get']); ?>
        <div class=" col-filter">
            <?php
            $attribute = 'class="col-filter" placeholder="By Name  "';
            echo form_input('name', set_value('name', isset($session_data['name']) ? $session_data['name'] : ""), $attribute);
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
                <th>Name</th>
                <th>Code</th>
                <th>Tier</th>
                <th width='10%'>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = $counter;
            foreach ($disaggregate_groups as $row):
                $i++; ?>
                <tr <?php if ($i % 2 == 0) {
                    echo 'class="evenrow";';
                    echo ' bgcolor="#fff"';
                } else {
                    echo 'class="oddrow"';
                } ?> >
                    <td class="serial"><?php echo $i; ?></td>
                    <td><?php echo $row->name; ?></td>
                    <td><?php echo $row->code; ?></td>
                    <td><?php echo $row->disaggregate_tier_name . '-' . $row->disaggregate_tier_code; ?></td>
                    <td class="text-center">
                        <?php echo anchor('Disaggregate_groups/edit/' . $row->id, img(array('src' => base_url() . 'assets/dist/img/edit.gif', 'border' => '0', 'alt' => 'Edit Permission')), array('title' => 'Edit')); ?>
                        <?php echo anchor('Disaggregate_groups/delete/' . $row->id, img(array('src' => base_url() . 'assets/dist/img/delete.gif', 'border' => '0', 'alt' => 'edit')),
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


