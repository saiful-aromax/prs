<?php

class Authorization_unauthorization extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function authorize($id_projects, $id_reporting_periods, $id_years)
    {
        $time_set_info = $this->get_time_set_info($id_years, $id_reporting_periods);
        $this->db->query("UPDATE 
  `transactions` t 
  INNER JOIN `project_indicator_disaggregate_sets` pids 
    ON pids.`id` = t.`id_project_indicator_disaggregate_sets` 
  INNER JOIN `project_indicators` `pi` 
    ON (
      `pi`.`id` = pids.`id_project_indicators` 
      AND `pi`.`reporting_period` = '$time_set_info->reporting_period_type'
    ) SET t.`is_approved` = '1' 
WHERE `pi`.`id_projects` = '$id_projects' 
  AND t.`id_time_sets` = '$time_set_info->id'");
    }


    function get_time_set_info($id_years, $id_reporting_periods)
    {
        return $this->db->query("SELECT 
  ts.`id`,
  rp.`reporting_period_type` 
FROM
  `time_sets` ts 
  INNER JOIN `reporting_periods` rp 
    ON rp.`id` = ts.`id_reporting_periods` 
WHERE ts.`id_years` = '$id_years' 
  AND rp.`id` = '$id_reporting_periods'")->row();
    }
}