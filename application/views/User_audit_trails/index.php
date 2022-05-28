<div class="box box-info">
    <div class="box-header">
        <?php echo form_open('user_audit_trails/index', ['method' => 'get']); ?>
        <div class=" col-filter">
            <div class="input-group date " data-provide="datepicker">
                <input name="from_date" type="text" class="form-control "
                       value="<?php echo isset($from_date) ? $from_date : "" ?>" style="width: 105px">
                <div class="input-group-addon ">
                    <span class="glyphicon glyphicon-th "></span>
                </div>
            </div>
        </div>
        <div class=" col-filter">
            <div class="input-group date " data-provide="datepicker" id="datepicker">
                <input name="to_date" type="text" class="form-control datepicker " id="datepicker"
                       value="<?php echo isset($to_date) ? $to_date : "" ?>"
                       data-date-format="YYYY-MM-DD"
                       style="width: 105px">
                <div class="input-group-addon ">
                    <span class="glyphicon glyphicon-th "></span>
                </div>
            </div>
        </div>
        <div class=" col-filter">
            <?php echo form_dropdown('action', $actions, isset($action) ? $action : "", ['class' => 'input_textbox form-control']); ?>
        </div>
        <div class=" col-filter">
            <?php echo form_dropdown('user_id', $users, isset($user_id) ? $user_id : "", ['class' => 'input_textbox form-control']); ?>
        </div>
        <div class=" col-filter">
            <?php echo form_dropdown('table', $tables, isset($table) ? $table : "", ['class' => 'input_textbox form-control']); ?>
        </div>
        <div class=" col-filter">
            <button type="submit" id="submit" class="btn btn-search"><i class="fa fa-search"></i> Search</button>
        </div>
    </div>


    <div class="box-body">
        <table id="example2" class="table table-bordered table-striped table-hover table_index">
            <thead>
            <tr>
                <th>#</th>
                <th>Timestamp</th>
                <th>User</th>
                <th>IP Address</th>
                <th>Table</th>
                <th>User Action</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php $i = $counter;
            foreach ($audit_trails as $row):
                $i++; ?>
                <tr <?php if ($i % 2 == 0) {
                    echo 'class="evenrow";';
                    echo ' bgcolor="#fff"';
                } else {
                    echo 'class="oddrow"';
                } ?> >
                    <td class="serial"><?php echo $i; ?></td>
                    <td><?php echo date('d-m-Y h:i:s A', $row->time_stamp); ?></td>
                    <td><?php echo $row->login; ?></td>
                    <td><?php echo $row->ip_address; ?></td>
                    <td><?php echo $row->table_name; ?></td>
                    <td><?php echo ucfirst($row->action); ?></td>
                    <td class="text-center">
                        <?php echo anchor('user_audit_trails/view/' . $row->id, img(array('src' => base_url() . 'assets/dist/img/edit.gif', 'border' => '0', 'alt' => 'View')), array('title' => 'View', 'class' => 'imglink')); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div align="center" id="paging"><?php echo $this->pagination->create_links(); ?></div>
        <div class="text-right"><?php echo $total_rows; ?> Records found</div>
    </div>
    <!-- /.box-body -->
</div>


