<div class="box box-info  col-sm-20">
    <div class="box-header with-border filter">
        <h3 class="box-title"></h3>
        <?php echo anchor('Users/add', 'Add User', array('class' => 'btn btn-default', 'title' => 'Add User', 'style' => "float:right;color: #fff;")); ?>
        <?php echo form_open('users/index', array('id' => 'search_form', 'name' => 'search_form', 'method' => "GET", 'style' => 'float:left;')); ?>
        <!-- /.box-header -->

        <div class=" col-filter">
            <?php
            $field_title = $this->lang->line('field_search_by_name');
            $attribute = 'class="col-filter" placeholder="By Name or Code "' . $field_title . "";
            echo form_input('txt_name', set_value('txt_name', isset($session_data['name']) ? $session_data['name'] : ""), $attribute);
            ?>
        </div>
        <div class=" col-filter">
            <?php echo form_dropdown('cbo_user_role', ['' => '--Role-- '] + $user_roles, set_value('cbo_user_role', isset($session_data['user_role']) ? $session_data['user_role'] : ""), 'class="form-control" placeholder="User Role " '); ?>
        </div>

        <div class=" col-filter">
            <?php echo form_dropdown('cbo_user_status', ['' => '--Status-- ', '1' => 'Active', '0' => 'Inactive'], set_value('cbo_user_status', isset($session_data['user_status']) ? $session_data['user_status'] : ""), 'class="form-control" placeholder="User Status " '); ?>
        </div>

        <div class=" col-filter">
            <?php echo form_dropdown('cbo_project', ['' => '--Project-- '] + $projects, set_value('cbo_project', isset($session_data['user_role']) ? $session_data['user_role'] : ""), 'class="form-control" placeholder="Project " '); ?>
        </div>
        <div class=" col-filter">
            <button type="submit" id="submit" class="btn btn-search"><i class="fa fa-search"></i> Search</button>
        </div>
        <?php echo form_close(); ?>
    </div>
    <div class="box-body">
        <table id="example2" class="table table-bordered table-striped table-hover table_index">
            <thead>
            <tr>
                <th>#</th>
                <th>Login</th>
                <th>Full Name</th>
                <th>User Role</th>
                <th>Current Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = 0;
            foreach ($users as $row):
                $i++;
                ?>
                <tr <?php if ($i % 2 == 0) {
                    echo 'class="evenrow";';
                    echo ' bgcolor="#fff"';
                } else {
                    echo 'class="oddrow"';
                } ?> >
                    <td class="serial"><?php echo $i; ?></td>
                    <td><?php echo $row->login; ?></td>
                    <td><?php echo $row->full_name; ?></td>
                    <td><?php echo $row->role_name; ?></td>
                    <td class="text-center"><?php echo ($row->status == 'A') ? img(array('src' => base_url() . 'assets/dist/img/apply2.png', 'border' => '0', 'alt' => 'Active')) : img(array('src' => base_url() . 'assets/dist/img/dimed_ok.png', 'border' => '0', 'alt' => 'Inactive')); ?></td>
                    <td class="text-center">
                        <?php if ($row->is_super_admin != 1) {
                            echo anchor('users/edit/' . $row->id, img(array('src' => base_url() . 'assets/dist/img/edit.gif', 'border' => '0', 'alt' => 'edit')), array('title' => 'Edit', 'class' => 'imglink'));
                        } ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php echo $this->pagination->create_links(); ?>
    </div>
    <!-- /.box-body -->
</div>