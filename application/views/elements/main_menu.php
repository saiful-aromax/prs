<?php

$user = $this->ci->session->userdata('system.user');
$config_general = $this->ci->get_general_configuration();
$current_date = $this->session->userdata('system.software_date');
$branch_info = $this->session->userdata('system.branch_info');
$sw_start_date_of_operation = isset($branch_info['sw_start_date_of_operation']) ? $branch_info['sw_start_date_of_operation'] : '1970-12-12';
$login_name = $user['login'];
$user_list = json_decode(isset($config_general['privileged_user_list']) ? $config_general['privileged_user_list'] : '');
if (!is_array($user_list)) {
    unset($user_list);
    $user_list = array();
}
$path = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

$module_name = $this->session->userdata('module_name');
if (!($module_name)) {
    $module_name = 'MIS';
}

class Menu_builder {

    private $resources = array();
    private $groups = array();
    private $ci = null;

    function add_group($name, $image) {
        $this->groups[$name] = $image;
    }

    function add_resource($group_name, $resource_name, $controller, $action = '', $subgroup_name = '', $sub_subgroup_name = '') {
        $this->resources[$group_name][] = array($resource_name, $controller, $action, $subgroup_name, $sub_subgroup_name);
    }

    function generate_menu_list() {
        echo "<ul>";
        $this->ci = & get_instance();
        foreach ($this->groups as $group_name => $image) {
            $resources = $this->resources[$group_name];
            $buffer = '';
            $count_list = 1;
            $count_array = array();
            if (count($resources) > 0) {
                $sub_group = ""; //
                $sub_subgroup = "";
//--------------create array for double column-----------------------------------------------------------------------------------
                foreach ($resources as $resource) {
                    $action = $resource[2];
                    if (empty($action)) {
                        $action = 'index';
                    }
                    if ($this->ci->is_action_permitted($resource[1], $action)) {
                        if (empty($resource[3])) {
                            
                        } else {
                            if (empty($resource[4])) {
                                if ($sub_group == $resource[3]) {
                                    $count_list++;
                                } else {
                                    if (empty($sub_group)) {
                                        
                                    } else {
                                        $count_list = 1;
                                    }
                                    $sub_group = $resource[3];
                                }
                            } else {
                                if ($sub_group == $resource[3]) {
                                    if ($sub_subgroup == $resource[4]) {
                                        $count_list++;
                                    } else {
                                        if (empty($sub_subgroup)) {
                                            
                                        } else {
                                            $count_list = 1;
                                        }
                                        $sub_subgroup = $resource[4];
                                    }
                                } else {
                                    if (empty($sub_group)) {
                                        
                                    } else {
                                        $count_list = 1;
                                    }
                                    $sub_group = $resource[3];
                                }
                            }
                        }
                        if (!empty($resource[3])) {
                            if (empty($resource[4])) {
                                $count_array[$resource[3]] = $count_list;
                            } else {
                                $count_array[$resource[4]] = $count_list;
                            }
                        }
                    }
                }

                foreach ($resources as $key => $resource) {
                    $action1 = $resource[2];
                    if (empty($action1)) {
                        $action1 = 'index';
                    }
                    if ($this->ci->is_action_permitted($resource[1], $action1)) {
                        if (empty($resource[3])) {
                            
                        } else {
                            if (empty($resource[4])) {
                                $resources[$key][5] = $count_array[$resource[3]] . ',' . (round($count_array[$resource[3]] / 2)) . ',' . ($count_array[$resource[3]] - round($count_array[$resource[3]] / 2));
                            } else {
                                $resources[$key][5] = $count_array[$resource[4]] . ',' . (round($count_array[$resource[4]] / 2)) . ',' . ($count_array[$resource[4]] - round($count_array[$resource[4]] / 2));
                            }
                        }
                    } else {
                        $resources[$key] = array();
                        unset($resources[$key]);
                    }
                }
//-------------------------------------------------------------------------------------------------------------------------------
                $sub_group = "";
                $sub_subgroup = "";
                foreach ($resources as $keys => $resource) {
                    $action = $resource[2];
                    if (empty($action)) {
                        $action = 'index';
                    }
                    if ($this->ci->is_action_permitted($resource[1], $action)) {
                        if (!empty($resource[3])) {
                            if ($sub_group != $resource[3]) {
                                $count_list = 1;
                                if ($sub_subgroup != '') {
                                    $buffer.= "</ul></li>";
                                    $sub_subgroup = '';
                                }
                                if (!empty($sub_group)) {
                                    $buffer.= "</ul>";
                                }
                                $sub_group = $resource[3];
                                $buffer.= "<li><a href='#' onclick='return false;'>$sub_group</a>\n";
                                $buffer.= "<ul>";
//----------------------------------2nd level double column start tag Div--------------------------------------------------------
                                if (isset($resource[5]) && !empty($resource[5])) {
                                    $list = explode(',', $resources[$keys][5]);
                                    if (empty($resource[4]) && ($list[1] - $list[2] == 0 || $list[1] - $list[2] == 1)) {
//  background: #303030 url(" . base_url() . "media/images/menuBarBg_2.png) repeat-x scroll left bottom;
                                        $buffer.= "<div style='width:410px; float:left;'><div style='float:left;'>";
                                    }
                                    if (empty($resource[4]) && (int) $list[1] != 0) {
                                        if ((int) $list[1] >= $count_list) {
                                            $list[1] = (int) $list[1] - $count_list;
                                            $count_list++;
                                        } else {
                                            $count_list = 1;
                                            $list[1] = (int) $list[1] - $count_list;
                                            $count_list++;
                                        }
                                    } else if (empty($resource[4]) && (int) $list[2] != 0) {
                                        if ((int) $list[2] >= $count_list) {
                                            $list[2] = (int) $list[2] - $count_list;
                                            $count_list++;
                                        } else {
                                            $count_list = 1;
                                            $list[2] = (int) $list[2] - $count_list;
                                            $count_list++;
                                        }
                                    }
                                    $resources[$keys][5] = implode(',', $list);
                                }
                            } else {
                                if (isset($resource[5]) && !empty($resource[5])) {
                                    $list = explode(',', $resources[$keys][5]);
                                    if (empty($resource[4]) && (int) $list[1] != 0) {
                                        if ((int) $list[1] >= $count_list) {
                                            $list[1] = (int) $list[1] - $count_list;
                                            $count_list++;
                                        } else {
                                            $count_list = 1;
                                            $list[1] = (int) $list[1] - $count_list;
                                            $count_list++;
                                        }
                                    } else if (empty($resource[4]) && (int) $list[2] != 0) {
                                        if ((int) $list[2] >= $count_list) {
                                            $list[2] = (int) $list[2] - $count_list;
                                            $count_list++;
                                        } else {
                                            $count_list = 1;
                                            $list[2] = (int) $list[2] - $count_list;
                                            $count_list++;
                                        }
                                    }
                                    $resources[$keys][5] = implode(',', $list);
                                }
                            }
//-------------------------------------------------------------------------------------------------------------------------------
                            if ($resource[4] != '') {
                                if ($sub_subgroup != $resource[4]) {
                                    if ($sub_subgroup != '') {
                                        $buffer.= "</ul></li>";
                                    }
                                    $sub_subgroup = $resource[4];
                                    $buffer.= "<li><a href='#' onclick='return false;'>$sub_subgroup</a>";
                                    $buffer.= "<ul>";
//----------------------------------3rd level double column start tag Div--------------------------------------------------------
                                    if (isset($resource[5]) && !empty($resource[5])) {
                                        $list = explode(',', $resources[$keys][5]);
                                        if ($list[1] - $list[2] == 0 || $list[1] - $list[2] == 1) {
//  background: #303030 url(" . base_url() . "media/images/menuBarBg_2.png) repeat-x scroll left bottom;
                                            $buffer.= "<div style='width:410px; float:left;'><div style='float:left;'>";
                                        }
                                        if (empty($resource[4]) && (int) $list[1] != 0) {
                                            if ((int) $list[1] >= $count_list) {
                                                $list[1] = (int) $list[1] - $count_list;
                                                $count_list++;
                                            } else {
                                                $count_list = 1;
                                                $list[1] = (int) $list[1] - $count_list;
                                                $count_list++;
                                            }
                                        } else if (empty($resource[4]) && (int) $list[2] != 0) {
                                            if ((int) $list[2] >= $count_list) {
                                                $list[2] = (int) $list[2] - $count_list;
                                                $count_list++;
                                            } else {
                                                $count_list = 1;
                                                $list[2] = (int) $list[2] - $count_list;
                                                $count_list++;
                                            }
                                        }
                                        $resources[$keys][5] = implode(',', $list);
                                    }
                                } else {
                                    if (isset($resource[5]) && !empty($resource[5])) {
                                        $list = explode(',', $resources[$keys][5]);
                                        if ((int) $list[1] != 0) {
                                            if ((int) $list[1] >= $count_list) {
                                                $list[1] = (int) $list[1] - $count_list;
                                                $count_list++;
                                            } else {
                                                $count_list = 1;
                                                $list[1] = (int) $list[1] - $count_list;
                                                $count_list++;
                                            }
                                        } else if ((int) $list[2] != 0) {
                                            if ((int) $list[2] >= $count_list) {
                                                $list[2] = (int) $list[2] - $count_list;
                                                $count_list++;
                                            } else {
                                                $count_list = 1;
                                                $list[2] = (int) $list[2] - $count_list;
                                                $count_list++;
                                            }
                                        }
                                        $resources[$keys][5] = implode(',', $list);
                                    }
                                }
//-------------------------------------------------------------------------------------------------------------------------------
                            } else if ($resource[4] == '' && $sub_subgroup != '') {
                                $buffer.="</ul></li>";
                                $sub_subgroup = '';
                            }
                        } else {
                            if (!empty($sub_subgroup)) {
                                $buffer.= "</ul></li>";
                                $sub_subgroup = '';
                            }
                            if (!empty($sub_group)) {
                                $buffer.= "</ul></li>";
                                $sub_group = '';
                            }
                        }
                        $buffer.= "<li>" . anchor("/$resource[1]/$resource[2]", "$resource[0]") . "</li>";
//----------------------closing tag Div for double column------------------------------------------------------------------------
                        if (isset($resource[5]) && !empty($resource[5])) {
                            $list = explode(',', $resources[$keys][5]);
                            if ((int) $list[1] == 0 && (int) $list[2] != 0) {
                                $buffer.= "</div><div style='float:left;'>";
                            }
                            if ((int) $list[1] == 0 && (int) $list[2] == 0) {
                                $buffer.= "</div></div>";
                            }
                        }
//-------------------------------------------------------------------------------------------------------------------------------
                    }
                }
                if (!empty($sub_group) && !empty($buffer)) {
                    $buffer.= "</ul>";
                    $sub_group = '';
                }
            }
            if (!empty($buffer)) {
                $img = $this->groups[$group_name];
                if (!empty($img))
                    echo "<li><a href='#' onclick='return false;'><img src=" . base_url() . "media/images/$img width='14px' height='14px' border='0' alt=' $group_name' />&nbsp;$group_name&nbsp;</a>\n";
                else
                    echo "<li><a href='#' onclick='return false;'>&nbsp;$group_name&nbsp;</a>\n";
                echo "<ul>";
                echo $buffer;
                echo "</ul>";
            }
        }
        echo "</ul>";
    }

}

$mb = new Menu_builder();
// admin menu 
$group_name = $this->lang->line('label_admin');
$mb->add_group($group_name, 'administration_24.png');
$mb->add_resource($group_name, $this->lang->line('label_manage_user'), 'users');
$mb->add_resource($group_name, $this->lang->line('label_manage_user_role'), 'user_roles');
$mb->add_resource($group_name, $this->lang->line('label_change_password'), 'users', 'change_password');
$mb->add_resource($group_name, $this->lang->line('label_user_audit_trail'), 'user_audit_trails');
//$mb->add_resource($group_name, 'User Access Log','user_access_logs');
$mb->add_resource($group_name, $this->lang->line('label_system_log'), 'pages', 'system_log');
$mb->add_resource($group_name, $this->lang->line('label_notification_ho_user'), 'notification_message_for_ho_users', '');
$mb->add_resource($group_name, $this->lang->line('label_admin_action'), 'admin_actions', '');

if (isset($config_general['rearrange_member_code']) && $config_general['rearrange_member_code'] == 1) {
    $mb->add_resource($group_name, $this->lang->line('label_rearrange_member_code'), 'member_code_changes', '');
}
if (isset($config_general['admin_action_privilege']) && $config_general['admin_action_privilege'] == 1 && in_array($login_name, $user_list)) {
    $mb->add_resource($group_name, $this->lang->line('label_admin_panel'), 'admin_panels', '');
}
//$mb->add_resource($group_name, $this->lang->line('label_scheduled_task'),'scheduled_tasks','general_dashboard_report');
$mb->add_resource($group_name, $this->lang->line('label_export_import_database'), 'export_import_databases');
$mb->add_resource($group_name, $this->lang->line('label_branch_notification_messege'), 'branch_notification_messeges');
if($user['is_head_office'] == 1){
$mb->add_resource($group_name, $this->lang->line('label_database_download'), 'database_downloads','index');
}
$mb->add_resource($group_name, $this->lang->line('label_logout'), 'auths', 'logout');

//configuration menu
if ($module_name == 'MIS') {
    // ************ MIS CONFIGURATION MENU *************
    $group_name = $this->lang->line('label_config');
    $mb->add_group($group_name, 'configuration_16.png');
    $mb->add_resource($group_name, $this->lang->line('label_config_general'), 'config_generals', 'view');
    $mb->add_resource($group_name, $this->lang->line('label_config_auto_id'), 'config_auto_ids');
    $mb->add_resource($group_name, $this->lang->line('label_config_holiday'), 'config_holidays');   
    $mb->add_resource($group_name, $this->lang->line('label_division'), 'po_divisions', '', $this->lang->line('label_address_configuration'));
    $mb->add_resource($group_name, $this->lang->line('label_district'), 'po_districts', '', $this->lang->line('label_address_configuration'));
    $mb->add_resource($group_name, $this->lang->line('label_thana'), 'po_thanas', '', $this->lang->line('label_address_configuration'));
    $mb->add_resource($group_name, $this->lang->line('label_union_ward'), 'po_unions_or_wards', '', $this->lang->line('label_address_configuration'));
    $mb->add_resource($group_name, $this->lang->line('label_villages_block'), 'po_village_or_blocks', '', $this->lang->line('label_address_configuration'));
    $mb->add_resource($group_name, $this->lang->line('label_working_area'), 'po_working_areas', '', $this->lang->line('label_address_configuration'));
    $mb->add_resource($group_name, $this->lang->line('label_branch'), 'po_branches','',$this->lang->line('label_branch'));
    $mb->add_resource($group_name, $this->lang->line('label_branch_wise_day_back_permission'), 'branch_wise_day_back_permissions','add',$this->lang->line('label_branch'));
    $mb->add_resource($group_name, $this->lang->line('label_branch_opening_members'), 'po_branch_opening_members');
    $mb->add_resource($group_name, $this->lang->line('label_branch_opening_loan'), 'po_branch_opening_balances');
    $mb->add_resource($group_name, $this->lang->line('label_branch_opening_saving'), 'po_branch_opening_savings');
    $mb->add_resource($group_name, $this->lang->line('label_branch_opening_current_member_employment'), 'branch_opening_employment_informations','current_member_employment_index');
    $mb->add_resource($group_name, $this->lang->line('label_branch_opening_dropout_member_employment'), 'branch_opening_employment_informations','dropout_member_employment_index');
    $mb->add_resource($group_name, $this->lang->line('label_branch_opening_purpose_wise_loan_disbursement'), 'branch_opening_purpose_wise_loan_disbursements');
    $mb->add_resource($group_name, $this->lang->line('label_thana_wise_opening_opening_balance'), 'po_thana_wise_opening_balances');
    $mb->add_resource($group_name, $this->lang->line('label_branch_opening_information'), 'po_branch_opening_informations');
    $mb->add_resource($group_name, $this->lang->line('label_funding_organization'), 'po_funding_organizations');
    $mb->add_resource($group_name, $this->lang->line('label_loan_product_category'), 'loan_product_categories', '', $this->lang->line('label_loan_savings'));
    $mb->add_resource($group_name, $this->lang->line('label_loan_product'), 'loan_products', '', $this->lang->line('label_loan_savings'));
    $mb->add_resource($group_name, $this->lang->line('label_loan_purpose_category'), 'loan_purpose_categories', '', $this->lang->line('label_loan_savings'));
    $mb->add_resource($group_name, $this->lang->line('label_loan_purpose'), 'loan_purposes', '', $this->lang->line('label_loan_savings'));
    $mb->add_resource($group_name, $this->lang->line('label_loan_sub_purpose'), 'loan_sub_purposes', '', $this->lang->line('label_loan_savings'));
    $mb->add_resource($group_name, $this->lang->line('label_loan_product_interest_rate'), 'loan_product_interest_rates', 'add', $this->lang->line('label_loan_savings'));
    $mb->add_resource($group_name, $this->lang->line('label_saving_product'), 'saving_products', '', $this->lang->line('label_loan_savings'));
    if (isset($config_general['is_savings_category_system_applicable']) && $config_general['is_savings_category_system_applicable']) {
        $mb->add_resource($group_name, $this->lang->line('label_loan_saving_categoy'), 'saving_product_categories', '', $this->lang->line('label_loan_savings'));
    }
    $mb->add_resource($group_name, $this->lang->line('label_saving_product_interest_rate'), 'saving_product_interest_rates', '', $this->lang->line('label_loan_savings'));
    $mb->add_resource($group_name, $this->lang->line('label_educational_qualification'), 'educational_qualifications');
    $mb->add_resource($group_name, $this->lang->line('label_area'), 'po_areas');
    $mb->add_resource($group_name, $this->lang->line('label_zone'), 'po_zones');
    $mb->add_resource($group_name, $this->lang->line('label_region'), 'po_regions');
    $mb->add_resource($group_name, $this->lang->line('label_mra_report_information'), 'mra_report_informations');
    if (strtotime($current_date['current_date']) == strtotime($sw_start_date_of_operation)) {
        //migration menu in configuration menu				
        $mb->add_resource($group_name, $this->lang->line('label_member_migration'), 'member_migrations', '', $this->lang->line('label_data_migration'));
        //$mb->add_resource($group_name, 'Loan and Savings Migration','migrations','','Data Migrations');
        //$mb->add_resource($group_name, 'Loan & Saving Migration (Optimize)','data_migrations_op','','Data Migrations');
        $mb->add_resource($group_name, $this->lang->line('label_data_migration'), 'data_migrations', '', $this->lang->line('label_data_migration'));
        //$mb->add_resource($group_name, 'Member Excel Migration','migration_excels','excel','Data Migrations');
        $mb->add_resource($group_name, $this->lang->line('label_loan_and_saving_remigration'), 'opening_balances', '', $this->lang->line('label_data_migration'));
    }
    $mb->add_resource($group_name, $this->lang->line('label_report_description'), 'report_descriptions');
    if ($user['is_super_admin'] == 1 && isset($config_general['is_sms_service_active']) && $config_general['is_sms_service_active']) {
        $mb->add_resource($group_name, 'SMS Services Configuration', 'sms_service_configs');
        $mb->add_resource($group_name, 'SMS Alert Services', 'sms_alert_services', 'add');
    }
    $mb->add_resource($group_name, 'Target Config', 'monthly_targets', 'add');    
    $mb->add_resource($group_name, $this->lang->line('label_insurance_claim_purpose'), 'insurance_claim_purposes', 'index');    
    // ************ EMPLOYEES ************* 
    $group_name = $this->lang->line('label_employees');
    $mb->add_group($group_name, 'user_16.png');
    $mb->add_resource($group_name, $this->lang->line('label_employee_department'), 'employee_departments');
    $mb->add_resource($group_name, $this->lang->line('label_employee_designation'), 'employee_designations');
    $mb->add_resource($group_name, $this->lang->line('label_employees'), 'employees');
    $mb->add_resource($group_name, $this->lang->line('label_employee_responsibility_histories'), 'employee_responsibility_histories');

    if (strtotime($current_date['current_date']) != strtotime($sw_start_date_of_operation)) {
        $mb->add_resource($group_name, $this->lang->line('label_employee_promotion'), 'employee_promotions');
        $mb->add_resource($group_name, $this->lang->line('label_employee_branch_transfer'), 'employee_branch_transfers');
        $mb->add_resource($group_name, $this->lang->line('label_employee_branch_transfer_with_samity_change'), 'Employee_branch_transfer_with_field_officer_changes');
        $mb->add_resource($group_name, $this->lang->line('label_employee_termination'), 'employee_terminations');
    }
    //if (SITE_NAME === 'demomob' || SITE_NAME === 'microfin_v3') {
    $mb->add_resource($group_name, 'Field Officer Movement', 'employee_movements');
    //}
    // *************** SAMITY MENU ****************
    $group_name = $this->lang->line('label_samity');
    $mb->add_group($group_name, 'samity_name.png');
    $mb->add_resource($group_name, $this->lang->line('label_samity'), 'samities');
    
    if(SITE_NAME == "dcl" || SITE_NAME == "dcl_test"){
        
    }
    else{
        $mb->add_resource($group_name, $this->lang->line('label_samity_group'), 'samity_groups');
        $mb->add_resource($group_name, $this->lang->line('label_samity_subgroup'), 'samity_subgroups');
    }
    $mb->add_resource($group_name, $this->lang->line('label_samity_transfers'), 'samity_transfers');
    if (strtotime($current_date['current_date']) != strtotime($sw_start_date_of_operation)) {
        $mb->add_resource($group_name, $this->lang->line('label_samity_employee_change'), 'samity_employee_changes');
        $mb->add_resource($group_name, $this->lang->line('label_samity_day_change'), 'samity_day_changes');
        $mb->add_resource($group_name, $this->lang->line('label_samity_closing'), 'samity_closings');
    }
    // *************** MEMBERS MENU ****************
    $group_name = $this->lang->line('label_members');
    $mb->add_group($group_name, 'organization_16.png');
    $mb->add_resource($group_name, $this->lang->line('label_member_information'), 'members');
    $mb->add_resource($group_name, $this->lang->line('label_member_information_by_national_id'), 'member_national_ids');
    if (strtotime($current_date['current_date']) != strtotime($sw_start_date_of_operation)) {
        $mb->add_resource($group_name, $this->lang->line('label_member_transfer'), 'member_transfers');
        $mb->add_resource($group_name, $this->lang->line('label_member_product_transfer'), 'member_product_transfers');
        $mb->add_resource($group_name, $this->lang->line('label_member_closing'), 'member_closings');
        $mb->add_resource($group_name, $this->lang->line('label_black_list_member'), 'member_black_lists');
        $mb->add_resource($group_name, $this->lang->line('label_member_attendence'), 'member_attendences');
        if (isset($config_general['pass_book_entry_system_allowed']) && $config_general['pass_book_entry_system_allowed'] == 1) {
            $mb->add_resource($group_name, $this->lang->line('label_member_pass_book_sale'), 'member_passbook_sales');
        }
        $mb->add_resource($group_name, $this->lang->line('label_insurance_claims'), 'member_insurance_claims');
        if (SITE_NAME == "bmi") {
            $mb->add_resource($group_name, $this->lang->line('label_member_medical_allowance'), 'member_medical_allowances');
        }
    }
    $mb->add_resource($group_name, $this->lang->line('label_member_transaction_status'), 'members', 'status');
    $mb->add_resource($group_name, $this->lang->line('label_member_passbook_apps_code'), 'additional_reports', 'passbook_apps_code');
    // *************** SAVINGS MENU ****************
    $group_name = $this->lang->line('label_savings');
    $group_name = explode("/", $group_name);
    $group_name = $group_name[0];
    $mb->add_group($group_name, 'savings_24.png');
    //$insurance = (isset($config_general['is_insurance_required']) && $config_general['is_insurance_required']==1)?'/Insurance':'';
    $mb->add_resource($group_name, $this->lang->line('label_savings'), 'savings');
    //$mb->add_resource($group_name, $this->lang->line('label_test_savings'),'test_savings');	
    if (strtotime($current_date['current_date']) != strtotime($sw_start_date_of_operation)) {
        $mb->add_resource($group_name, $this->lang->line('label_saving_deposit'), 'saving_deposits');
        $mb->add_resource($group_name, $this->lang->line('label_saving_withdraw'), 'saving_withdraws');
        if (isset($config_general['is_insurance_required']) && ($config_general['is_insurance_required'] == 1)) {
            $mb->add_resource($group_name, $this->lang->line('label_insurance_claim'), 'insurance_claims');
        }
    }
    if (isset($config_general['is_SKT_required']) && ($config_general['is_SKT_required'] == 1)) {
        $mb->add_resource($group_name, $this->lang->line('label_skt_collection'), 'skt_collections');
        $mb->add_resource($group_name, $this->lang->line('label_skt_withdraw'), 'skt_withdraws');
    }
    if (strtotime($current_date['current_date']) != strtotime($sw_start_date_of_operation)) {
        $mb->add_resource($group_name, $this->lang->line('label_saving_closing'), 'saving_closings');
    }
    $mb->add_resource($group_name, $this->lang->line('label_saving_status'), 'savings', 'status');
    $mb->add_resource($group_name, $this->lang->line('label_saving_interest_calculation'), 'saving_interest_calculations');
    $mb->add_resource($group_name, $this->lang->line('label_fdr_saving_interests'), 'fdr_saving_interests', 'index');
    $mb->add_resource($group_name, $this->lang->line('label_saving_adjustments'), 'saving_adjustments');
    //$mb->add_resource($group_name, $this->lang->line('label_savings_status_details'), 'savings_status_details');
    // *************** LOANS MENU ****************
    $group_name = $this->lang->line('label_loans');
    $mb->add_group($group_name, 'loan_24.png');
    if (isset($config_general['is_loan_proposal_form_mandatory']) && $config_general['is_loan_proposal_form_mandatory']) {
        $mb->add_resource($group_name, $this->lang->line('label_loan_proposal_forms'), 'loan_proposal_forms');
    }

    $mb->add_resource($group_name, $this->lang->line('label_regular_loan_account'), 'loans');

    $mb->add_resource($group_name, $this->lang->line('label_one_time_loan_account'), 'one_time_loan_accounts');
    /*     * *******issue #6981 Loan transactin and onetime loan transaction menu required on Migration time********************** */
    $mb->add_resource($group_name, $this->lang->line('label_regular_loan_transaction'), 'loan_transactions');
    $mb->add_resource($group_name, $this->lang->line('label_one_time_loan_transaction'), 'one_time_loan_transactions');
    if (strtotime($current_date['current_date']) != strtotime($sw_start_date_of_operation)) {
        $mb->add_resource($group_name, $this->lang->line('label_loan_reschedule'), 'loan_reschedules');
        $mb->add_resource($group_name, $this->lang->line('label_overdue_loan_collection'), 'overdue_loan_collections');
        //$mb->add_resource($group_name, 'Loan Waiver','loan_waivers');
        $mb->add_resource($group_name, $this->lang->line('label_loan_rebate'), 'loan_rebates');
        $mb->add_resource($group_name, 'Loan Waiver for Death Members', 'loan_waiver_of_death_members');
        $mb->add_resource($group_name, $this->lang->line('label_loan_adjustment'), 'loan_adjustments');
        //$mb->add_resource($group_name, 'Loan Penalty','loan_penalties');	
        $mb->add_resource($group_name, $this->lang->line('label_loan_write_off_eligible_list'), 'loan_write_offs', 'write_off_eligible_list');
        $mb->add_resource($group_name, $this->lang->line('label_loan_write_off'), 'loan_write_offs', 'index');
        $mb->add_resource($group_name, $this->lang->line('label_loan_write_off_collection'), 'loan_write_off_collections', 'index');
    }
    $mb->add_resource($group_name, $this->lang->line('label_loan_status'), 'loans', 'status');
    $mb->add_resource($group_name, $this->lang->line('label_donor_loan'), 'donor_loans');
    $mb->add_resource($group_name, $this->lang->line('label_donor_loan_repayment'), 'donor_loan_repayments');
    $mb->add_resource($group_name, $this->lang->line('label_donor_loan_imposed_interests'), 'donor_loan_imposed_interests');
    if (isset($config_general['is_extra_service_charge_allowed_overdue_loanee']) && $config_general['is_extra_service_charge_allowed_overdue_loanee']) {
        $mb->add_resource($group_name, $this->lang->line('label_overdue_loan_service_charges'), 'overdue_loan_extra_service_charges');
    }
    //$mb->add_resource($group_name, $this->lang->line('label_loan_rebate_schedules'),'loan_rebate_schedules');	
    //Error tools	
    //$mb->add_resource($group_name, 'Error Detection Tools','error_detection_tools');
    // *************** PROCESS MENU ****************
    $group_name = $this->lang->line('label_process');
    $mb->add_group($group_name, 'transaction_20.png');
    if (strtotime($current_date['current_date']) != strtotime($sw_start_date_of_operation)) {
        $mb->add_resource($group_name, $this->lang->line('label_auto_process'), 'transactions', 'auto_process');
        $mb->add_resource($group_name, $this->lang->line('label_o-o_loan_auto_process'), 'onetime_loan_auto_process', 'auto_process');
    }
    $mb->add_resource($group_name, $this->lang->line('label_transaction_authorization'), 'transaction_authorizations', 'authorization_index');
    $mb->add_resource($group_name, $this->lang->line('label_transaction_unauthorization'), 'transaction_unauthorizations', 'unauthorization_index');
    if (isset($config_general['is_sms_service_active']) && $config_general['is_sms_service_active']) {
        $mb->add_resource($group_name, 'SMS System', 'sms_alert_services', 'index');
    }
    $mb->add_resource($group_name, $this->lang->line('label_process_day_end'), 'process_day_ends');
    $mb->add_resource($group_name, $this->lang->line('label_process_month_end'), 'process_month_ends');
    $mb->add_resource($group_name, $this->lang->line('label_pass_book_balance'), 'pass_book_balances');
    $mb->add_resource($group_name, $this->lang->line('label_branch_wise_reconciliation'), 'branch_wise_reconciliations');
    $mb->add_resource($group_name, $this->lang->line('label_con_branch_info'), 'weekly_reports', 'con_branch_process_index');

    // *************** REPORT MENU ****************
    $group_name = $this->lang->line('label_reports');
    $mb->add_group($group_name, 'report_16.png');
    // ---------- POMIS Reports -----------
    $pksf_pomis = "";
    $pksf_pomis_group = "";
    if (!empty($config_general['name_before_pomis_reports'])) {
        $pksf_pomis = $config_general['name_before_pomis_reports'] . " ";
        $pksf_pomis_group = $config_general['name_before_pomis_reports'] . "-";
    }
    $mb->add_resource($group_name, '1.1 ' . $pksf_pomis . $this->lang->line('label_po_mis_1_report'), 'po_mis_reports', 'po_mis_1_index', '1 ' . $pksf_pomis_group . $this->lang->line('label_po_mis_report'));
    $mb->add_resource($group_name, '1.2 ' . $pksf_pomis . $this->lang->line('label_po_mis_2_report'), 'po_mis_reports', 'po_mis_2_index', '1 ' . $pksf_pomis_group . $this->lang->line('label_po_mis_report'));
    $mb->add_resource($group_name, '1.3 ' . $pksf_pomis . $this->lang->line('label_po_mis_2A_report'), 'po_mis_reports', 'po_mis_2A_index', '1 ' . $pksf_pomis_group . $this->lang->line('label_po_mis_report'));
    $mb->add_resource($group_name, '1.4 ' . $pksf_pomis . $this->lang->line('label_po_mis_3_report'), 'po_mis_reports', 'po_mis_3_index', '1 ' . $pksf_pomis_group . $this->lang->line('label_po_mis_report'));
    $mb->add_resource($group_name, '1.5 ' . $pksf_pomis . $this->lang->line('label_po_mis_3A_report'), 'po_mis_reports', 'po_mis_3A_index', '1 ' . $pksf_pomis_group . $this->lang->line('label_po_mis_report'));
    $mb->add_resource($group_name, '1.6 ' . $pksf_pomis . $this->lang->line('label_po_mis_5a_report'), 'po_mis_reports', 'po_mis_5a_index', '1 ' . $pksf_pomis_group . $this->lang->line('label_po_mis_report'));
    // ---------- MRA Reports -----------
    $mb->add_resource($group_name, '2.1 ' . $this->lang->line('label_mra_mfi_01_report'), 'mra_reports', 'mra_mfi_01_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.2 ' . $this->lang->line('label_mra_mfi_02_report'), 'mra_reports', 'mra_mfi_02_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.3 ' . $this->lang->line('label_mra_mfi_03a_report'), 'mra_reports', 'mra_mfi_03a_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.4 ' . $this->lang->line('label_mra_mfi_03b_report'), 'mra_reports', 'mra_mfi_03b_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.5 ' . $this->lang->line('label_mra_mfi_04a_report'), 'mra_reports', 'mra_mfi_04a_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.6 ' . $this->lang->line('label_mra_mfi_04b_report'), 'mra_reports', 'mra_mfi_04b_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.7 ' . $this->lang->line('label_mra_mfi_05_report'), 'mra_reports', 'mra_mfi_05_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.8 ' . $this->lang->line('label_mra_mfi_06_report'), 'mra_reports', 'mra_mfi_06_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.9 ' . $this->lang->line('label_mra_cdb_02a_report'), 'mra_reports', 'mra_cdb_02a_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.10 ' . $this->lang->line('label_mra_cdb_03a_report'), 'mra_reports', 'mra_cdb_03a_report', '2 ' . $this->lang->line('label_mra_report'));
    //
    $mb->add_resource($group_name, '2.11 ' . $this->lang->line('label_mra_llp_01_report'), 'mra_reports', 'mra_llp_01_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.12 ' . $this->lang->line('label_mra_llp_02_report'), 'mra_reports', 'mra_llp_02_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.13 ' . $this->lang->line('label_mra_llp_03_report'), 'mra_reports', 'mra_llp_03_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.14 ' . $this->lang->line('label_mra_llp_04_report'), 'mra_reports', 'mra_llp_04_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.15 ' . $this->lang->line('label_mra_llp_05_report'), 'mra_reports', 'mra_llp_05_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.16 ' . $this->lang->line('label_mra_llp_06_report'), 'mra_reports', 'mra_llp_06_report', '2 ' . $this->lang->line('label_mra_report'));
    $mb->add_resource($group_name, '2.17 ' . $this->lang->line('label_mra_monthly_report'), 'mra_reports', 'mra_monthly_report', '2 ' . $this->lang->line('label_mra_report'));
    // ---------- Regular & General Reports -----------
    $mb->add_resource($group_name, '3.1 ' . $this->lang->line('label_component_wise_daily_collection_report'), 'component_wise_daily_collection_reports', 'component_wise_daily_collection_report_index', '3 ' . $this->lang->line('label_regular_and_general_report'));

    $mb->add_resource($group_name, '3.2 ' . $this->lang->line('label_component_wise_daily_unauthorized_data_collection'), 'unauthorized_daily_recov_collections', 'component_wise_daily_unauthorized_data_collection_index', '3 ' . $this->lang->line('label_regular_and_general_report'));
    //$mb->add_resource($group_name, 'Branch Managers report','regular_and_general_reports','branch_manager_report_index','3 Regular & General Report - (Branch Level)');
    //$mb->add_resource($group_name, 'Field Officer Report (Samity & Component Wise)','regular_and_general_reports','field_worker_report_index','3 Regular & General Report - (Branch Level)');
    
    //$mb->add_resource($group_name, '3.3 '.$this->lang->line('label_branch_manager_report'),'amlan_field_officers_reports','branch_manager_report_index','3 '.$this->lang->line('label_regular_and_general_report'));
    $mb->add_resource($group_name, '3.3 ' . $this->lang->line('label_branch_manager_report'), 'weekly_reports', 'branch_manager_report_index', '3 ' . $this->lang->line('label_regular_and_general_report'));
    if (isset($config_general['show_savings_loan_statement_report']) && $config_general['show_savings_loan_statement_report'] == 1) {
        $mb->add_resource($group_name, '3.3(a)' . $this->lang->line('label_savings_loans_statement_reports'), 'savings_loans_statement_reports', 'index', '3 ' . $this->lang->line('label_regular_and_general_report'));
    }
    //$mb->add_resource($group_name, '3.4 '.$this->lang->line('label_field_officers_report'),'amlan_field_officers_reports','field_worker_report_index','3 '.$this->lang->line('label_regular_and_general_report'));	
    $mb->add_resource($group_name, '3.4 ' . $this->lang->line('label_field_officers_report(New)'), 'weekly_reports', 'field_worker_report_index', '3 ' . $this->lang->line('label_regular_and_general_report'));
	$mb->add_resource($group_name, '3.4(a) ' . $this->lang->line('label_field_officers_report(New)'), 'field_officer_reports', 'index', '3 ' . $this->lang->line('label_regular_and_general_report'));


    //$mb->add_resource($group_name, '3.5 '.$this->lang->line('label_loan_report'),'regular_and_general_reports','loan_field_officer_wise_index','3 '.$this->lang->line('label_regular_and_general_report'));
    $mb->add_resource($group_name, '3.6 ' . $this->lang->line('label_loan_classification_and_dmr'), 'regular_and_general_reports', 'loan_classification_and_dmr_index', '3 ' . $this->lang->line('label_regular_and_general_report'));
    $mb->add_resource($group_name, '3.7 ' . $this->lang->line('label_samity_wise_monthly_loan_and_savings_baisc_collection_sheet'), 'regular_and_general_reports', 'samity_wise_monthly_loan_and_savings_baisc_collection_sheet_index', '3 ' . $this->lang->line('label_regular_and_general_report'));
    $mb->add_resource($group_name, '3.8 ' . $this->lang->line('label_samity_wise_monthly_loan_and_savings_collection_sheet'), 'regular_and_general_reports', 'samity_wise_monthly_loan_and_savings_collection_sheet_index', '3 ' . $this->lang->line('label_regular_and_general_report'));
    //$mb->add_resource($group_name, '3.9 '.$this->lang->line('label_samity_wise_monthly_loan_and_savings_working_sheet'),'regular_and_general_reports','samity_wise_monthly_loan_and_savings_working_sheet_index','3 '.$this->lang->line('label_regular_and_general_report'));
    $mb->add_resource($group_name, '3.9 ' . $this->lang->line('label_samity_wise_monthly_loan_and_savings_working_sheet'), 'working_sheets', 'index', '3 ' . $this->lang->line('label_regular_and_general_report'));

    //$mb->add_resource($group_name, '3.10 '.$this->lang->line('label_component_wise_periodic_collection_report'),'amlan_field_officers_reports','component_wise_periodic_collection_report_index','3 '.$this->lang->line('label_regular_and_general_report'));
    //$mb->add_resource($group_name, '3.10 ' . $this->lang->line('label_component_wise_periodic_collection_report(New)'), 'weekly_reports', 'component_wise_periodic_collection_report_index', '3 ' . $this->lang->line('label_regular_and_general_report'));
	$mb->add_resource($group_name, '3.10 '.$this->lang->line('label_component_wise_periodic_collection_report'),'periodical_reports','periodical_report_index','3 '.$this->lang->line('label_regular_and_general_report'));
 

    $mb->add_resource($group_name, '3.11 ' . $this->lang->line('label_samity_wise_monthly_loan_and_savings_basic_collection_sheet_print'), 'regular_and_general_reports', 'samity_wise_monthly_loan_and_savings_basic_collection_pdf_index', '3 ' . $this->lang->line('label_regular_and_general_report'));
    //if(SITE_NAME=="newera" || SITE_NAME=="cdip" || SITE_NAME=="desha"||SITE_NAME=="newera_test" || SITE_NAME=="desha_test" || SITE_NAME=="cdip_test"){
    $mb->add_resource($group_name, '3.12 ' . $this->lang->line('label_monthly_collection_sheet'), 'collection_sheets', 'index', '3 ' . $this->lang->line('label_regular_and_general_report'));
    // }
    //$mb->add_resource($group_name, '3.13 '.$this->lang->line('label_samity_wise_monthly_loan_and_savings_working_sheet'),'working_sheets','index','3 '.$this->lang->line('label_regular_and_general_report'));

    $mb->add_resource($group_name, '3.13 ' . $this->lang->line('label_dcr_report_index'), 'component_wise_daily_collection_reports', 'dcr_report_index', '3 ' . $this->lang->line('label_regular_and_general_report'));
    $mb->add_resource($group_name, '3.14 ' . $this->lang->line('label_manual_collection_sheet'), 'manual_collection_sheets', 'index', '3 ' . $this->lang->line('label_regular_and_general_report'));
    $mb->add_resource($group_name, '3.15 ' . $this->lang->line('label_write_off_collection_sheets'), 'write_off_collection_sheets', 'index', '3 ' . $this->lang->line('label_regular_and_general_report'));
    $mb->add_resource($group_name, '3.16 ' . $this->lang->line('label_consolidated_branch_manager_report'), 'weekly_reports', 'con_branch_manager_report_index', '3 ' . $this->lang->line('label_regular_and_general_report'));

    // ---------- Register Reports -----------
    $mb->add_resource($group_name, '4.1.1 ' . $this->lang->line('label_admission_register'), 'register_reports', 'admission_register_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.2 ' . $this->lang->line('label_savings_refund_register'), 'register_reports', 'savings_refund_register_report_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.3 ' . $this->lang->line('label_loan_disbursement_register'), 'register_reports', 'loan_disbursement_master_report_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.4 ' . $this->lang->line('label_fully_paid_loan_register'), 'register_reports', 'fully_paid_loan_register_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.5 ' . $this->lang->line('label_member_cancellation_register'), 'register_reports', 'member_cancellation_register_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.6 ' . $this->lang->line('label_member_wise_subsidy_loan_saving_ledger'), 'register_reports', 'member_wise_subsidy_loan_saving_ledger_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.7 ' . $this->lang->line('label_inactive_member_register'), 'regular_and_general_reports', 'inactive_member_register', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.8(a) ' . $this->lang->line('label_saving_interest_information_report'), 'register_reports', 'saving_interest_information_report_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.8(b) ' . $this->lang->line('label_saving_interest_register_report'), 'saving_interest_register_reports', 'saving_interest_reg_report_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.8(c) ' . $this->lang->line('label_saving_interest_provision_report'), 'saving_interst_provisions', 'index_saving_provision', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    
    $mb->add_resource($group_name, '4.1.9 ' . $this->lang->line('label_due_register'), 'due_register_reports', 'index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    //$mb->add_resource($group_name, '4.10 '.$this->lang->line('label_daily_recoverable_collection_register'),'amlan_field_officers_reports','daily_recoverable_collection_register_index','4 '.$this->lang->line('label_register_report'));
    $mb->add_resource($group_name, '4.1.10 ' . $this->lang->line('label_daily_recoverable_collection_register(New)'), 'weekly_reports', 'daily_recoverable_collection_register_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.11 ' . $this->lang->line('label_written_off_register'), 'register_reports', 'written_off_register_report_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.12 ' . $this->lang->line('label_written_off_amount_collection_register'), 'register_reports', 'written_off_amount_collection_report_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.13 ' . $this->lang->line('label_dual_loanee_register'), 'dual_loanee_register_reports', 'index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.14 ' . $this->lang->line('label_loan_waiver_index'), 'register_reports', 'loan_waiver_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.15 ' . $this->lang->line('label_rebate_register_index'), 'loan_rebate_registers', 'index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.16 ' . $this->lang->line('label_due_register_index'), 'register_reports', 'due_collection_register_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.17 ' . $this->lang->line('label_loan_adjustment_register_report_index'), 'register_reports', 'loan_adjustment_register_report_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.18 ' . $this->lang->line('label_transfer_register_index'), 'transfer_register_reports', 'index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.19 ' . $this->lang->line('label_holiday_due_register_index'), 'holiday_due_registers', 'holiday_due_register_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.20 ' . $this->lang->line('label_loan_disbursement_n_recovery_index'), 'loan_dirsbursement_n_recovery_registers', 'index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.21 ' . $this->lang->line('loan_proposal_register_index'), 'register_reports', 'loan_proposal_register_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.22 ' . $this->lang->line('label_fdr_register_index'), 'register_reports', 'fdr_register_index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.23 ' . $this->lang->line('label_borrowerwise_loan_distribution_reports'), 'borrowerwise_loan_distribution_reports', 'index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.24 ' . $this->lang->line('label_samitywise_member_register_reports'), 'samitywise_member_register_reports', 'index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.25 ' . $this->lang->line('label_insurance_claim_registers'), 'insurance_claim_registers', 'index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");
    $mb->add_resource($group_name, '4.1.26 ' . $this->lang->line('label_savings_collection_register'), 'saving_collection_registers', 'index', '4 ' . $this->lang->line('label_register_report'), "4.1 Regular");

    $mb->add_resource($group_name, '4.2.1 ' . $this->lang->line('label_admission_register_topsheets'), 'topsheet_admission_registers', 'index', '4 ' . $this->lang->line('label_register_report'), "4.2 Topsheet");
    $mb->add_resource($group_name, '4.2.2 ' . $this->lang->line('label_loan_disbursement_register_topsheets'), 'topsheet_loan_disbursement_registers', 'index', '4 ' . $this->lang->line('label_register_report'), "4.2 Topsheet");
    $mb->add_resource($group_name, '4.2.3 ' . $this->lang->line('label_savings_refund_register_topsheets'), 'topsheet_savings_refund_registers', 'index', '4 ' . $this->lang->line('label_register_report'), "4.2 Topsheet");
    $mb->add_resource($group_name, '4.2.4 ' . $this->lang->line('label_fully_paid_loan_register_topsheets'), 'topsheet_fully_paid_loan_registers', 'index', '4 ' . $this->lang->line('label_register_report'), "4.2 Topsheet");
    $mb->add_resource($group_name, '4.2.5 ' . $this->lang->line('label_due_register_topsheets'), 'topsheet_due_registers', 'index', '4 ' . $this->lang->line('label_register_report'), "4.2 Topsheet");

    // ---------- Consolidated Reports -----------
    $mb->add_resource($group_name, '5.1 ' . $this->lang->line('label_consolidated_balancing'), 'consolidated_reports', 'consolidated_balancing_report_index', '5 ' . $this->lang->line('label_consolidated_report'));
    $mb->add_resource($group_name, '5.2 ' . $this->lang->line('label_ratio_analysis_statement'), 'additional_reports', 'ratio_analysis_statement_index', '5 ' . $this->lang->line('label_consolidated_report'));
    $mb->add_resource($group_name, '5.3 ' . $this->lang->line('label_consolidated_ratio_analysis'), 'additional_reports', 'consolidated_ratio_analysis_statement_index', '5 ' . $this->lang->line('label_consolidated_report'));
    // ---------- OTHERS Reports -----------
    $mb->add_resource($group_name, '6 ' . $this->lang->line('label_pass_book_report'), 'pass_book_reports');
    $mb->add_resource($group_name, '7 ' . $this->lang->line('label_branchwise_samity_list'), 'branchwise_samity_reports');
    $mb->add_resource($group_name, '8 ' . $this->lang->line('label_samitywise_member_list'), 'samity_wise_member_reports');
    $mb->add_resource($group_name, '9 ' . $this->lang->line('label_branch_samity_wise_collection'), 'additional_reports', 'branch_samity_wise_collection_index');
    $mb->add_resource($group_name, '10 ' . $this->lang->line('label_member_migration_balance'), 'member_migration_balances', 'member_migration_balance_index');
    // ---------- This two lines are block for Issue# 3865-------
    //$mb->add_resource($group_name, '11 Member migration opening balance mismatchs','member_migration_balances','migration_opening_outstanding_mismatch');
    //$mb->add_resource($group_name, '12 On Date Transaction Status','additional_reports','ondate_transaction_status_index');
    $mb->add_resource($group_name, '13 ' . $this->lang->line('label_advance_due_register'), 'process_month_ends', 'advance_due');
    // ---------- This two lines are block for Issue# 3865-------
    //$mb->add_resource($group_name, '14 Data Validate','data_validities');
    //$mb->add_resource($group_name, '15 Loan And Saving Check','loan_saving_checks');
    $mb->add_resource($group_name, '16 ' . $this->lang->line('label_loan_statement_recoverable_calculation'), 'loan_statements', 'recoverable_loan_index');

    $mb->add_resource($group_name, '17.1 ' . $this->lang->line('label_member_wise_pass_book_balancing_register_report'), 'pass_book_balancing_register_reports', 'member_wise_pass_book_balancing_register_report_index', '17 ' . $this->lang->line('label_pass_book_balancing_register_report'));
    $mb->add_resource($group_name, '17.2 ' . $this->lang->line('label_credit_officer_wise_pass_book_balancing_register_report'), 'pass_book_balancing_register_reports', 'credit_officer_wise_pass_book_balancing_register_report_index', '17 ' . $this->lang->line('label_pass_book_balancing_register_report'));
    $mb->add_resource($group_name, '17.3 ' . $this->lang->line('label_branch_wise_pass_book_balancing_register_report'), 'pass_book_balancing_register_reports', 'branch_wise_pass_book_balancing_register_report_index', '17 ' . $this->lang->line('label_pass_book_balancing_register_report'));
    $mb->add_resource($group_name, '17.4 ' . $this->lang->line('label_pass_book_checking_report'), 'pass_book_checking_reports', 'index', '17 ' . $this->lang->line('label_pass_book_balancing_register_report'));

    // ---------- Monthly Reports -----------
    //$mb->add_resource($group_name, '18.1  '.$this->lang->line('label_monthly_product_wise_loan_purposes_report'),'report_monthly_product_wise_loan_purposes','index','18 '.$this->lang->line('label_monthly_report'));

    

    $mb->add_resource($group_name, '18.1  ' . $this->lang->line('label_msp_register_report'), 'report_msp_registers', 'index', '18 ' . $this->lang->line('label_monthly_report'));
    $mb->add_resource($group_name, '18.2  ' . $this->lang->line('label_monthly_progress_report'), 'monthly_progress_reports', 'index', '18 ' . $this->lang->line('label_monthly_report'));

    
    //$mb->add_resource($group_name, '18.3  '.$this->lang->line('label_monthly_employment_information'),'report_employment_informations','index','18 '.$this->lang->line('label_monthly_report'));

    $mb->add_resource($group_name, '18.3  ' . $this->lang->line('label_monthly_purpose_wise_loan_report'), 'monthly_purpose_wise_loan_reports', 'index', '18 ' . $this->lang->line('label_monthly_report'));
    //$mb->add_resource($group_name, '18.4  '.$this->lang->line('label_monthly_borrower_wise_loan_disbursement_recovery_statement'),'report_me_borrower_wise_loan_disbursement_recovery_statements','index','18 '.$this->lang->line('label_monthly_report'));
    //$mb->add_resource($group_name, 'Genearte PDF','test_pdfs');
    $mb->add_resource($group_name, '18.4 ' . $this->lang->line('label_monthly_seasonal_loan_report'), 'monthly_seasonal_reports', 'index', '18 ' . $this->lang->line('label_monthly_report'));
    $mb->add_resource($group_name, '18.5 ' . $this->lang->line('label_monthly_target_achievement_report'), 'monthly_targets', 'target_achievement_report_index', '18 ' . $this->lang->line('label_monthly_report'));
    $mb->add_resource($group_name, '18.6 ' . $this->lang->line('label_monthly_bm_report'), 'monthly_reports', 'monthly_branch_manager_report_index', '18 ' . $this->lang->line('label_monthly_report'));
    if (isset($config_general['is_show_achievement_report']) && $config_general['is_show_achievement_report'] == 1) {
        $mb->add_resource($group_name, '18.7 ' . $this->lang->line('label_progress_reports'), 'progress_reports', 'index', '18 ' . $this->lang->line('label_monthly_report'));
    }

    if (isset($config_general['show_monthly_target_achievement_report']) && $config_general['show_monthly_target_achievement_report'] == 1) {
        $mb->add_resource($group_name, '18.8 ' . $this->lang->line('label_target_achievement_reports'), 'target_achievement_reports', 'index', '18 ' . $this->lang->line('label_monthly_report'));
    }
    
    if (isset($config_general['show_peridoical_mis_ais_report']) && $config_general['show_peridoical_mis_ais_report'] == 1) {
        $mb->add_resource($group_name, '18.9 ' . $this->lang->line('label_periodical_mis_ais_progress_report'), 'periodical_mis_ais_progress_reports', 'index', '18 ' . $this->lang->line('label_monthly_report'));
    }
    $mb->add_resource($group_name, '18.10 ' . $this->lang->line('label_district_upazila_wise_cumulative_loan_disbursement_report'), 'district_upazila_wise_cumulative_loan_disbursement_reports', 'index', '18 ' . $this->lang->line('label_monthly_report'));
    
    // ----------------------------------------- ME Reports --------------------------------------------------------------
    //$mb->add_resource($group_name, '19.1 ' . $this->lang->line('label_index_loan_information_top_sheet'), 'me_reports', 'index_loan_information_top_sheet', '19 ' . $this->lang->line('label_me_reports'));
    $mb->add_resource($group_name, '19.1 ' . $this->lang->line('label_monthly_statement_agroshor_report'), 'monthly_statement_agroshor_reports', 'index', '19 ' . $this->lang->line('label_me_reports'));
    $mb->add_resource($group_name, '19.2  ' . $this->lang->line('label_index_purpose_wise_agroshor_activities'), 'purpose_wise_loan_activity_reports', 'index', '19 ' . $this->lang->line('label_me_reports'));
    //$mb->add_resource($group_name, '19.2 ' . $this->lang->line('label_index_monthly_productwise_borrower_classification'), 'me_reports', 'index_monthly_productwise_borrower_classification', '19 ' . $this->lang->line('label_me_reports'));
    //$mb->add_resource($group_name, '19.3 ' . $this->lang->line('label_index_monthly_district_wise_loan_information'), 'me_reports', 'index_monthly_district_wise_loan_information', '19 ' . $this->lang->line('label_me_reports'));
    $mb->add_resource($group_name, '19.3 ' . $this->lang->line('label_index_member_employment_information_reports'), 'member_employment_information_reports', 'index', '19 ' . $this->lang->line('label_me_reports'));
    //$mb->add_resource($group_name, '19.4  '.$this->lang->line('label_index_monthly_product_wise_loan_purpose'),'me_reports','index_monthly_product_wise_loan_purpose','19 '.$this->lang->line('label_me_reports'));
    //$mb->add_resource($group_name, '19.4  ' . $this->lang->line('label_index_monthly_product_wise_loan_purpose'), 'report_monthly_product_wise_loan_purposes', 'index', '19 ' . $this->lang->line('label_me_reports'));
    // $mb->add_resource($group_name, '19.5  '.$this->lang->line('label_index_monthly_product_wise_employment'),'me_reports','index_monthly_product_wise_employment','19 '.$this->lang->line('label_me_reports'));
    //$mb->add_resource($group_name, '19.5  ' . $this->lang->line('label_index_monthly_product_wise_employment'), 'report_employment_informations', 'index', '19 ' . $this->lang->line('label_me_reports'));
    $mb->add_resource($group_name, '19.4  ' . $this->lang->line('label_employment_register_reports'), 'employment_register_reports', 'index', '19 ' . $this->lang->line('label_me_reports'));

    

    
    $mb->add_resource($group_name, '20 ' . $this->lang->line('label_disaster_management_report'), 'disaster_management_reports', 'index');
    $mb->add_resource($group_name, '21 ' . $this->lang->line('label_mis_ais_cross_check_report'), 'mis_ais_cross_check_reports', 'mis_ais_cross_check_report_index');
    $mb->add_resource($group_name, '22 ' . $this->lang->line('label_periodical_progress_reports'), 'periodical_progress_reports', 'index');

    if(isset($config_general['show_daily_monitoring_report']) && $config_general['show_daily_monitoring_report'] == 1){
        $mb->add_resource($group_name, '23.1 ' . $this->lang->line('label_daily_monitoring_report'), 'daily_monitoring_reports', 'index','23 Monitoring Reports');
    }
    if(isset($config_general['show_at_a_glance_monthly_monitoring_report']) && $config_general['show_at_a_glance_monthly_monitoring_report'] == 1){
        $mb->add_resource($group_name, '23.2 ' . $this->lang->line('label_monthly_monitoring_report'), 'monthly_monitoring_reports', 'index','23 Monitoring Reports');
    }
    
    
    
    
}

if ($module_name == 'AIS') {
    // ********************* AIS CONFIGURATION MENU ******************
    $group_name = 'Configuration';
    $mb->add_group($group_name, 'configuration_16.png');
    $mb->add_resource($group_name, 'General Configuration (AIS)', 'ais_config_generals', 'view');
    $mb->add_resource($group_name, 'Ledger Accounts', 'acc_ledgers');
    $mb->add_resource($group_name, 'Opening Balances (GL)', 'acc_opening_balances', 'add');
    if ($user['is_head_office'] == 1) {
        $mb->add_resource($group_name, 'Opening Balances (Fund Transfer)', 'acc_opening_balances_fund_transfers', 'add');
    }
    //$mb->add_resource($group_name, 'Branches','po_branches');
    //$mb->add_resource($group_name, 'Funding Organizations','po_funding_organizations');
    //$mb->add_resource($group_name, 'Parties','acc_parties','index');
    $mb->add_resource($group_name, 'Auto Voucher Configuration', 'config_auto_vouchers', 'add');
    $mb->add_resource($group_name, 'HO Ledger Code Configuration', 'config_ledger_codes', 'add');
    $mb->add_resource($group_name, 'Others Ledger Code Configuration', 'config_non_cash_ft_ledger_codes', 'add');
    $mb->add_resource($group_name, 'Others Report Configuration', 'config_acc_reports', 'index');
    //$mb->add_resource($group_name, 'Auto ID Configuration','config_auto_ids','voucher_id_configuration'); 
    $mb->add_resource($group_name, 'Cumulative OP Balances from MIS', 'acc_opening_balances', 'cumulative_opening_balance_report_index');
    $mb->add_resource($group_name, 'Budgets', 'acc_budgets', 'index');
    $mb->add_resource($group_name, 'Volt Registers', 'acc_volt_registers', 'add');
    $mb->add_resource($group_name, 'Acc Customize Report', 'acc_custom_reports', 'index');
    $mb->add_resource($group_name, 'Service Charge Allocation', 'service_charge_allocations', 'add');
    // ********************* Vouchers MENU ******************                
    $receipt_voucher = (isset($config_general['receipt_voucher_name']) && !empty($config_general['receipt_voucher_name'])) ? $config_general['receipt_voucher_name'] : 'Receipt Voucher';
    $payment_voucher = (isset($config_general['payment_voucher_name']) && !empty($config_general['payment_voucher_name'])) ? $config_general['payment_voucher_name'] : 'Payment Voucher';

    $group_name = $this->lang->line('label_vouchers');
    $mb->add_group($group_name, 'transaction_20.png');
    $mb->add_resource($group_name, $this->lang->line('label_voucher_list'), 'acc_vouchers', 'index');
    $mb->add_resource($group_name, $receipt_voucher, 'acc_vouchers', 'add_receipt_voucher');
    $mb->add_resource($group_name, 'Contra Voucher', 'acc_vouchers', 'add_contra_voucher');
    $mb->add_resource($group_name, $payment_voucher, 'acc_vouchers', 'add_payment_voucher');
    $mb->add_resource($group_name, 'Journal Voucher', 'acc_vouchers', 'add_journal_voucher');
    $mb->add_resource($group_name, 'Fund Transfer', 'acc_vouchers', 'add_fund_transfer');
    // ********************* Auto Vouchers MENU ******************
    $group_name = 'Auto Vouchers';
    $mb->add_group($group_name, 'transaction_20.png');
    $mb->add_resource($group_name, $receipt_voucher, 'acc_auto_vouchers', 'add_receipt_voucher');
    $mb->add_resource($group_name, $payment_voucher, 'acc_auto_vouchers', 'add_payment_voucher');
    $mb->add_resource($group_name, 'Journal Voucher', 'acc_auto_vouchers', 'add_journal_voucher');
    $mb->add_resource($group_name, 'Branch Transfer JV', 'acc_auto_vouchers', 'add_branch_tr_journal_voucher');
    if (isset($config_general['is_auto_voucher_used_for_emp_salary']) && ($config_general['is_auto_voucher_used_for_emp_salary'] == 1)) {
        $mb->add_resource($group_name, 'Salary Payment Voucher', 'acc_auto_vouchers', 'add_salary_payment_voucher');
        $mb->add_resource($group_name, 'Salary Journal Voucher', 'acc_auto_vouchers', 'add_salary_journal_voucher');
    }
    // *************** PROCESS MENU ****************
    $group_name = 'Process';
    $mb->add_group($group_name, 'oparation.png');
    $mb->add_resource($group_name, 'AIS Day End', 'acc_day_ends');
    $mb->add_resource($group_name, 'AIS Month End', 'acc_month_ends');
    if (SITE_NAME == "bmi" || SITE_NAME == "demo") {
        $mb->add_resource($group_name, 'AIS Year End', 'acc_year_ends');
    }
    $mb->add_resource($group_name, $this->lang->line('title_bank_book_reconciliation'), 'acc_bank_book_reconciliations');
    // ********************* REPORTS MENU ******************
    $group_name = 'Reports';
    $mb->add_group($group_name, 'report_16.png');
    $mb->add_resource($group_name, '01 Chart of Accounts Report', 'acc_ledgers', 'report_view');
    $mb->add_resource($group_name, '02 Opening Balance Report', 'acc_opening_balances', 'opening_balance_report_index');
    $mb->add_resource($group_name, '03 Daily Transaction', 'acc_daily_transactions', 'daily_transaction_filter');
    $mb->add_resource($group_name, '04 Ledger Report', 'acc_ledger_reports', 'ledger_report');
    $mb->add_resource($group_name, '05 Cash Book', 'acc_cash_books', 'cash_book_filter');
    $mb->add_resource($group_name, '06 Bank Book', 'acc_bank_books', 'bank_book_filter');
    $mb->add_resource($group_name, '07 Cash & Bank Book', 'acc_cash_bank_books', 'index');
    $mb->add_resource($group_name, '08 Trial Balance Report', 'acc_trial_balance_reports', 'trial_balance');
    $mb->add_resource($group_name, '09 Receipt Payment Statement', 'acc_receipt_payment_reports', 'receipt_payment_report');
    $mb->add_resource($group_name, '10 Income Statement', 'acc_income_statements', 'income_statment_filter');
    $mb->add_resource($group_name, '11 Balance Sheet', 'acc_balance_sheets', 'balance_sheet_report_filter');
    $mb->add_resource($group_name, '12 Cash Flow Statement', 'acc_cash_flow_statements', 'cash_flow_statement_filter');
    $mb->add_resource($group_name, '13 Budget Report', 'acc_budgets', 'budget_report');
    $mb->add_resource($group_name, '14 Budget Variance Report', 'acc_budgets', 'budget_variance_report');
    $mb->add_resource($group_name, '15 Branch Wise Ledger Report', 'acc_ledger_reports', 'branch_wise_ledger_report_index');
    $mb->add_resource($group_name, '16 Fund Transfer Report', 'acc_fund_transfer_reports', 'index');
    $mb->add_resource($group_name, '17 Subsidiary Ledger Report', 'acc_ledger_reports', 'subsidiary_ledger_report_index');
    $mb->add_resource($group_name, '18 ' . $this->lang->line('title_bank_book_reconciliation_report'), 'acc_bank_book_reconciliations', 'bank_book_reconciliation_report_filter');
    $mb->add_resource($group_name, '19 Fund Transfer Balancing Report', 'acc_fund_transfer_reports', 'branch_transfer_balancing_report_index');
}

$mb->generate_menu_list();
?>
<!-- Navigation item -->
