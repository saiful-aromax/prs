<div class="box box-info">

    <div class="box-header">
        <?php echo anchor('indicators/add', 'Add Indicator', array('class' => 'btn btn-default', 'title' => 'Add Indicators', 'style' => "float:right;background-color:#13a5d3;color: #fff;")); ?>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="example2" class="table table-bordered table-striped table-hover table_index">
            <thead>
            <tr>
                <th width='5%'>#</th>
                <th>Indicator Name</th>
                <th  width='25%'>Indicator Code</th>
                <th  width='12%'>Project Type</th>
                <th  width='15%'>Result Type</th>
                <th width='10%'>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = $counter;
            foreach ($indicators as $row):
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
                    <td><?php echo $row->project_type; ?></td>
                    <td><?php echo $row->result_type; ?></td>
                    <td class="text-center">
                        <?php echo anchor('indicators/edit/' . $row->id,img(array('src' => base_url() . 'assets/dist/img/edit.gif', 'border' => '0', 'alt' => 'Edit Permission')), array('title' => 'Edit')); ?>
                        <?php echo anchor('indicators/delete/' . $row->id, img(array('src' => base_url() . 'assets/dist/img/delete.gif', 'border' => '0', 'alt' => 'delete')),
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
