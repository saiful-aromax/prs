<?php

if ($row->old_value != '') {
    $oldvalue = json_decode($row->old_value, TRUE);
}

if ($row->new_value != '') {
    $newvalue = json_decode($row->new_value, TRUE);
}

?>
<div class="box-body">
    <table class="table table-bordered table-striped table-hover table_index" style="width: 60%">
        <tbody>
        <tr>
            <td>Action :</td>
            <td><?php echo strtoupper($row->action); ?></td>
        </tr>
        <tr>
            <td>By :</td>
            <td><?php echo strtoupper($row->user_name); ?></td>
        </tr>
        <tr>
            <td>Date :</td>
            <td><?php echo date('d/m/Y- h:i:s A', $row->time_stamp); ?></td>
        </tr>
        <tr>
            <td>Table :</td>
            <td><?php echo $row->table_name; ?></td>
        </tr>
        <tr>
            <td>IP Address :</td>
            <td><?php echo $row->ip_address; ?></td>
        </tr>
        <?php if ($row->action != 'update') { ?>
            <tr>
            <td colspan="2">
                <table class="table table-bordered table-striped table-hover table_index">
                    <tr>
                        <th>
                            Column
                        </th>
                        <th>
                            <?php
                            if ($row->action == 'delete') {
                                echo "Old Value";
                            } else if ($row->action == 'insert') {
                                echo "New Value";
                            }
                            ?>
                        </th>
                    </tr>

                    <?php

                    if ($row->action == 'delete' && isset($oldvalue)) {
                        foreach ($oldvalue as $okey) {
                            foreach ($okey as $oldarr => $oldarrval) {
                                echo '<tr><td><b>' . $oldarr . '</b></td><td>' . $oldarrval . '<br/></td></tr>';
                            }
                        }
                    } else if ($row->action == 'insert' && isset($newvalue) && $row->table_name == "user_role_wise_privileges") {
                        foreach ($newvalue as $key1 => $val1) {
                            foreach ($val1 as $key => $val) {
                                echo '<tr><td><b>' . $key . '</b>:' . $val . '<br/></td></tr>';
                            }
                        }

                    } else if ($row->action == 'insert' && isset($newvalue)) {
                        foreach ($newvalue as $key => $val) {
                            echo '<tr><td><b>' . $key . '</b></td><td>' . $val . '<br/></td></tr>';
                        }

                    }
                    ?>

                    </td>
                    </tr>
                </table>
            </td>
            </tr><?php } else { ?>

            <tr>
                <td colspan="2">
                    <table class="table table-bordered table-striped table-hover table_index">
                        <tr>
                            <th>Column</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                        </tr>

                        <?php
                        if (isset($oldvalue) && isset($newvalue)) {
                            if (isset($oldvalue[0])) {
                                foreach ($oldvalue as $okey) {
                                    foreach ($okey as $oldarrkey => $oldarrval) {
                                        $oldarr[$oldarrkey] = $oldarrval;
                                    }
                                }
                            } else {
                                foreach ($oldvalue as $oldarrkey => $oldarrval) {
                                    $oldarr[$oldarrkey] = $oldarrval;
                                }
                            }

                            foreach ($newvalue as $nkey => $nval) {
                                $newarr[$nkey] = $nval;
                            }

                            $result = array_diff_assoc($oldarr, $newarr);

                        }
                        foreach ($result as $rkey => $rval) {

                            ?>
                            <tr>
                                <td><?php echo $rkey; ?></td>
                                <td><?php echo isset($oldarr[$rkey]) ? $oldarr[$rkey] : '&nbsp;'; ?></td>
                                <td><?php echo isset($newarr[$rkey]) ? $newarr[$rkey] : '&nbsp;'; ?></td>
                            </tr>
                            <?php
                        }
                        ?>

                        </td>
                        </tr>
                    </table>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>