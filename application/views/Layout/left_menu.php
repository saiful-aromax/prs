<section class="sidebar">


    <ul class="sidebar-menu">

        <li class="active treeview">
            <a href="<?php echo base_url('/'); ?>">
                <i class="fa fa-dashboard"></i> <span>Dashboard</span>

            </a>

        </li>


        <li class="treeview">
            <a href="#">
                <i class="fa  fa-user"></i> <span>Admin</span>
                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?php echo base_url('users') ?>"><i class="fa fa-circle-o"></i>User</a></li>
                <li><a href="<?php echo base_url('user_roles') ?>"><i class="fa fa-circle-o"></i>User Role</a>
                </li>
                <li><a href="<?php echo base_url('users/change_password') ?>"><i class="fa fa-circle-o"></i>Change
                        Password</a></li>
                <li><a href="<?php echo base_url('user_audit_trails') ?>"><i class="fa fa-circle-o"></i>Audit
                        Trail</a></li>

            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-cog"></i> <span>Configuration</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?php echo base_url('disaggregate_tiers') ?>"><i class="fa fa-circle-o"></i>DisaggregateTiers</a></li>
                <li><a href="<?php echo base_url('disaggregate_groups') ?>"><i class="fa fa-circle-o"></i>DisaggregateGroups</a></li>
                <li><a href="<?php echo base_url('disaggregates') ?>"><i class="fa fa-circle-o"></i>Disaggregate</a></li>
                <li><a href="<?php echo base_url('disaggregate_sets') ?>"><i class="fa fa-circle-o"></i>Disaggregate Set</a></li>
                <li><a href="<?php echo base_url('Commodities') ?>"><i class="fa fa-circle-o"></i>Commodities</a></li>
                <li><a href="<?php echo base_url('units') ?>"><i class="fa fa-circle-o"></i>Unit</a></li>
                <li><a href="<?php echo base_url('activites') ?>"><i class="fa fa-circle-o"></i>Activity</a></li>
                <li><a href="<?php echo base_url('indicators') ?>"><i class="fa fa-circle-o"></i>Indicator</a></li>
                <li><a href="<?php echo base_url('project_indicators') ?>"><i class="fa fa-circle-o"></i>Activity Indicator</a></li>
                <li><a href="<?php echo base_url('Project_indicator_disaggregate_sets') ?>"><i class="fa fa-circle-o"></i>Activity-Indicator-D-S</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-exchange"></i> <span>Transaction</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?php echo base_url('transactions/index/baseline') ?>"><i class="fa fa-circle-o"></i>Baseline Data</a></li>
                <li><a href="<?php echo base_url('transactions/index/target') ?>"><i class="fa fa-circle-o"></i>Target Data</a></li>
                <li><a href="<?php echo base_url('transactions/index/result') ?>"><i class="fa fa-circle-o"></i>Result Data</a></li>
                <li><a href="<?php echo base_url('reports/data_export') ?>"><i class="fa fa-circle-o"></i>Data Export</a></li>
                <li><a href="<?php echo base_url('reports/data_authorization') ?>"><i class="fa fa-circle-o"></i>Data Authorization</a></li>
                <li><a href="<?php echo base_url('reports/data_import') ?>"><i class="fa fa-circle-o"></i>Data Import</a></li>
            </ul>
        </li>
        <li class="treeview">
            <a href="#">
                <i class="fa fa-file"></i> <span>Report</span>
                <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="<?php echo base_url('reports/index') ?>"><i class="fa fa-circle-o"></i>
                        IP Wise Data</a></li>
<!--                <li><a href="--><?php //echo base_url('reports/index') ?><!--"><i class="fa fa-circle-o"></i>-->
<!--                        All Indicator Data</a></li>-->
<!--                <li><a href="--><?php //echo base_url('reports/index') ?><!--"><i class="fa fa-circle-o"></i>-->
<!--                        Gross Margin Data</a></li>-->
                <li><a href="<?php echo base_url('reports/IncrementalSales_report') ?>"><i class="fa fa-circle-o"></i>
                        Incremental Sales Data</a></li>
                <li><a href="<?php echo base_url('reports/GrossMargin_report') ?>"><i class="fa fa-circle-o"></i>
                        Gross Margin Data</a></li>
            </ul>
        </li>
    </ul>
</section>
<!-- /.sidebar -->
