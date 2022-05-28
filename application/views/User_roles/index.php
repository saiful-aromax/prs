<div class="box box-info  col-sm-20">
    <div class="box-header with-border filter">
        <h3 class="box-title"></h3>
        <?php echo anchor('user_roles/add', 'Add New', ['class' => 'btn btn-default', 'title' => 'Add Role', 'style' => "float:right;color: #fff;"]); ?>
    </div>

    <div class="box-body">
        <table id="example2" class="table table-bordered table-striped table-hover table_index">
            <thead>
            <tr>
                <th width="5%">#</th>
                <th width="35%">Role Name</th>
                <th width="45%">Role Description</th>
                <th>Action</th>
            </tr>
            </thead>
            <?php
            $i = 0;
            //print_r($role_list);
            $space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            foreach ($user_roles as $row):
                $i++;
                ?>
                <tr <?php if ($i % 2 == 0) {
                    echo 'class="evenrow";';
                    echo ' bgcolor="#fff"';
                } else {
                    echo 'class="oddrow"';
                } ?> >
                    <td class="serial"><?php echo $i; ?></td>
                    <td style="text-align: left; padding-left: 10px"><?php
                        $tree_img = img(array('src' => base_url() . 'assets/dist/img/list.gif', 'border' => '0')) . ' ';
                        echo str_repeat($space, $row->depth) . $tree_img . $row->role_name;
                        ?></td>
                    <td style="text-align: left; padding-left: 10px"><?php echo $row->role_description; ?></td>
                    <td class="text-center">
                        <?php if (isset($role_list[$row->id])) echo anchor('user_role_wise_privileges/index/' . $row->id, img(array('src' => base_url() . 'assets/dist/img/lock.gif', 'border' => '0', 'alt' => 'Edit Permission')), array('title' => 'Edit Permission', 'class' => 'imglink')); ?>
                        <?php if ($row->depth <> 0 && isset($role_list[$row->id])) echo anchor('user_roles/edit/' . $row->id, img(array('src' => base_url() . 'assets/dist/img/edit.gif', 'border' => '0', 'alt' => 'edit')), array('title' => 'Edit', 'class' => 'imglink')); ?>
                        <?php if ($row->depth <> 0 && isset($role_list[$row->id])) echo anchor('user_roles/delete/' . $row->id, img(array('src' => base_url() . 'assets/dist/img/delete.gif', 'border' => '0', 'alt' => 'edit')),
                            array('title' => 'Delete', 'class' => 'delete', 'onclick' => "return confirm('" . DELETE_CONFIRMATION_MESSAGE . "')")); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
