<?php

class Transaction extends MY_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_form_data($data)
    {
        $return_array = [];
        foreach ($data['id_indicators'] as $id_indicator) {
            $disaggregate_index = 0;
            $indicator_index = 0;

            $indicator_info = $this->db->query("SELECT 
  i.`name`,
  i.`code`,
  COUNT(`pi`.`id_indicators`) rowspan 
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `project_indicators` `pi` 
    ON `pi`.`id` = pids.`id_project_indicators` 
  INNER JOIN `reporting_periods` rp 
    ON rp.`reporting_period_type` = `pi`.`reporting_period` 
  INNER JOIN `indicators` i 
    ON i.`id` = `pi`.`id_indicators` 
WHERE rp.`id` = '$data[id_reporting_periods]' 
  AND `pi`.`id_projects` = '$data[id_projects]' 
  AND `pi`.`id_indicators` = '$id_indicator' 
GROUP BY `pi`.`id_indicators`")->row();

            $clusters = $this->db->query("SELECT 
  dt.`id`,
  dt.`name`,
  dt.`code`,
  COUNT(dt.`id`) rowspan
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `project_indicators` `pi` 
    ON `pi`.`id` = pids.`id_project_indicators` 
  INNER JOIN `reporting_periods` rp 
    ON rp.`reporting_period_type` = `pi`.`reporting_period` 
  INNER JOIN `disaggregate_sets` ds 
    ON ds.`id` = pids.`id_disaggregate_sets` 
  INNER JOIN `disaggregate_tiers` dt 
    ON dt.`id` = ds.`id_disaggregate_tiers` 
WHERE rp.`id` = '$data[id_reporting_periods]' 
  AND `pi`.`id_projects` = '$data[id_projects]' 
  AND `pi`.`id_indicators` = '$id_indicator' 
GROUP BY dt.`id` 
ORDER BY dt.`name`")->result_array();
            foreach ($clusters as $cluster) {
                $cluster_index = 0;
                $disaggregate_groups = $this->db->query("SELECT 
  dg.`id`,
  dg.`name`,
  dg.`code`,
  (COUNT(dg.`id`) + 1) rowspan 
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `project_indicators` `pi` 
    ON `pi`.`id` = pids.`id_project_indicators` 
  INNER JOIN `reporting_periods` rp 
    ON rp.`reporting_period_type` = `pi`.`reporting_period` 
  INNER JOIN `disaggregate_sets` ds 
    ON ds.`id` = pids.`id_disaggregate_sets` 
  INNER JOIN `disaggregate_groups` dg 
    ON dg.`id` = ds.`id_disaggregate_groups` 
WHERE rp.`id` = '$data[id_reporting_periods]' 
  AND `pi`.`id_projects` = '$data[id_projects]' 
  AND `pi`.`id_indicators` = '$id_indicator' 
  AND ds.`id_disaggregate_tiers` = '$cluster[id]' 
GROUP BY dg.`id` 
ORDER BY dg.`name`")->result_array();
                foreach ($disaggregate_groups as $disaggregate_group) {
                    $indicator_index++;
                    $disaggregate_group_index = 0;
                    $disaggregates = $this->db->query("SELECT 
  d.`name`,
  d.`code`,
  u.`name` unit_name,
  pids.`id` id_project_indicator_disaggregate_sets 
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `project_indicators` `pi` 
    ON `pi`.`id` = pids.`id_project_indicators` 
  INNER JOIN `reporting_periods` rp 
    ON rp.`reporting_period_type` = `pi`.`reporting_period` 
  INNER JOIN `disaggregate_sets` ds 
    ON ds.`id` = pids.`id_disaggregate_sets` 
  INNER JOIN `disaggregates` d 
    ON d.`id` = ds.`id_disaggregates` 
  INNER JOIN `units` u 
    ON u.`id` = ds.`unit_id` 
WHERE rp.`id` = '$data[id_reporting_periods]' 
  AND `pi`.`id_projects` = '$data[id_projects]' 
  AND `pi`.`id_indicators` = '$id_indicator' 
  AND ds.`id_disaggregate_tiers` = '$cluster[id]' 
  AND ds.`id_disaggregate_groups` = '$disaggregate_group[id]'")->result_array();
                    $last_unit = "";
                    foreach ($disaggregates as $disaggregate) {
                        $return_array[$id_indicator][$disaggregate_index] = $disaggregate;
                        $last_unit = $return_array[$id_indicator][$disaggregate_index]['unit_name'];

                        $return_array[$id_indicator][$disaggregate_index]['disaggregate_group_name'] = $disaggregate_group['name'];
                        $return_array[$id_indicator][$disaggregate_index]['disaggregate_group_code'] = $disaggregate_group['code'];
                        $return_array[$id_indicator][$disaggregate_index]['disaggregate_group_rowspan'] = $disaggregate_group_index == 0 ? $disaggregate_group['rowspan'] : 0;

                        $return_array[$id_indicator][$disaggregate_index]['cluster_name'] = $cluster['name'];
                        $return_array[$id_indicator][$disaggregate_index]['cluster_code'] = $cluster['code'];
                        $return_array[$id_indicator][$disaggregate_index]['cluster_rowspan'] = $cluster_index == 0 ? $cluster['rowspan'] + 2 : 0;

                        $return_array[$id_indicator][$disaggregate_index]['indicator_name'] = $indicator_info->name;
                        $return_array[$id_indicator][$disaggregate_index]['indicator_code'] = $indicator_info->code;

                        $return_array[$id_indicator][$disaggregate_index]['indicator_rowspan'] = $disaggregate_index == 0 ? $indicator_info->rowspan + count($disaggregates) : 0;

                        $group_array[] = $return_array[$id_indicator][$disaggregate_index]['transaction'] = $this->_get_transaction($disaggregate['id_project_indicator_disaggregate_sets'], $data['id_years']);

                        $disaggregate_index++;
                        $disaggregate_group_index++;
                        $cluster_index++;
                    }


                    $group_total = $this->_get_disaggregate_group_wise_total($group_array);

                    $return_array[$id_indicator][$disaggregate_index]['name'] = 'Total';
                    $return_array[$id_indicator][$disaggregate_index]['unit_name'] = $last_unit;
                    $return_array[$id_indicator][$disaggregate_index]['id_project_indicator_disaggregate_sets'] = 'Total';


                    $return_array[$id_indicator][$disaggregate_index]['disaggregate_group_rowspan'] = 0;
                    $return_array[$id_indicator][$disaggregate_index]['cluster_rowspan'] = 0;
                    $return_array[$id_indicator][$disaggregate_index]['indicator_rowspan'] = 0;

                    $return_array[$id_indicator][$disaggregate_index]['transaction'] = $group_total;

                    $disaggregate_index++;
                    $cluster_index++;

                }
            }
        }
        return $return_array;
    }

    function get_annual_value($id_years, $id_project_indicator_disaggregate_sets, $type)
    {
        $annual_value = 0;
        $time_sets_id = $this->db->query("SELECT `id` FROM `time_sets` WHERE `id_years` = '$id_years' ORDER BY `id_reporting_periods` ASC")->result();
        $time_sets = [];
        $i = 0;
        foreach ($time_sets_id as $row) {
            $i++;
            $time_sets[$i] = $row->id;
        }
        $result = $this->db->query("SELECT 
  t.`value` 
FROM
  `transactions` t 
WHERE t.`id_time_sets` = '$time_sets[1]' 
  AND t.`id_project_indicator_disaggregate_sets` = '$id_project_indicator_disaggregate_sets' 
  AND t.`type` = '$type'")->result_array();
        if (empty($result)) {
            $result = $this->db->query("SELECT 
  IFNULL(SUM(t.`value`), 0) `value` 
FROM
  `transactions` t 
WHERE t.`id_time_sets` IN ('$time_sets[2]', '$time_sets[3]') 
  AND t.`id_project_indicator_disaggregate_sets` = '$id_project_indicator_disaggregate_sets' 
  AND t.`type` = '$type' ")->row()->value;
            if (round($result) == '0') {
                $result = $this->db->query("SELECT 
  IFNULL(SUM(t.`value`), 0) `value` 
FROM
  `transactions` t 
WHERE t.`id_time_sets` IN ('$time_sets[4]', '$time_sets[5]', '$time_sets[6]', '$time_sets[7]') 
  AND t.`id_project_indicator_disaggregate_sets` = '$id_project_indicator_disaggregate_sets' 
  AND t.`type` = '$type' ")->row()->value;
            }
            $annual_value = $result;
        } else {

            $annual_value = $result[0]['value'];
        }
        return number_format($annual_value, DECIMAL_PLACE);
    }

    function _get_form_data($id_projects, $id_indicators = null, $id_reporting_periods, $id_years)
    {
        $this->config->load('validation_rules');
        $technology_type_validation_id = $this->config->item('technology_type_id');
        $total_w_or_one_or_more_id = $this->config->item('total_w_or_one_or_more');
        $span_array = [];
        $return_array = [];
        $result_array = [];
        $indicators = $this->db->query("
        SELECT 
  `pi`.`id` id_project_indicators,
  i.`id`,
  i.`name`,
  i.`code` 
FROM
  `project_indicators` AS `pi` 
  INNER JOIN `indicators` i 
    ON `pi`.`id_indicators` = i.`id` 
WHERE `pi`.`reporting_period` = '$id_reporting_periods' 
  AND `pi`.`id_projects` = '$id_projects'")->result_array();
        foreach ($indicators as $indicator) {
            $indicator_index = 0;
            $result_array[$indicator['id']]['name'] = $indicator['name'];
            $result_array[$indicator['id']]['code'] = $indicator['code'];
            $result_array[$indicator['id']]['id'] = $indicator['id'];
            $commodities = $this->db->query(
                "SELECT 
  pids.`id_commodity`,
  c.`name`,
  c.`code` 
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `commodities` c 
    ON c.`id` = pids.`id_commodity` 
WHERE pids.`id_project_indicators` = '$indicator[id_project_indicators]' 
GROUP BY pids.`id_commodity`")->result_array();
            foreach ($commodities as $commodity) {
                $commodity_index = 0;
                $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['id'] = $commodity['id_commodity'];
                $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['name'] = $commodity['name'];
                $clusters = $this->db->query("
  SELECT 
  ds.`id_disaggregate_tiers`, 
  dt.name,
  dt.code
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `disaggregate_sets` ds 
    ON ds.`id` = pids.`id_disaggregate_sets` 
    INNER JOIN `disaggregate_tiers` dt
    ON dt.`id` = ds.`id_disaggregate_tiers`
WHERE pids.`id_project_indicators` = '$indicator[id_project_indicators]' 
  AND pids.`id_commodity` = '$commodity[id_commodity]' 
GROUP BY ds.`id_disaggregate_tiers` ")->result_array();
                foreach ($clusters as $cluster) {
                    $cluster_index = 0;
                    $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['id'] = $cluster['id_disaggregate_tiers'];
                    $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['name'] = $cluster['name'];
                    $disaggregate_groups = $this->db->query("
  SELECT 
  dg.`id`,
  dg.`name` 
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `disaggregate_sets` ds 
    ON ds.`id` = pids.`id_disaggregate_sets` 
    INNER JOIN `disaggregate_groups` dg
    ON dg.`id` = ds.`id_disaggregate_groups`
WHERE pids.`id_project_indicators` = '$indicator[id_project_indicators]' 
  AND pids.`id_commodity` = '$commodity[id_commodity]'
  AND ds.`id_disaggregate_tiers` = '$cluster[id_disaggregate_tiers]'
  GROUP BY ds.`id_disaggregate_groups`")->result_array();
                    foreach ($disaggregate_groups as $disaggregate_group) {
                        $disaggregate_group_index = 0;
                        $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['id'] = $disaggregate_group['id'];
                        $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['name'] = $disaggregate_group['name'];
                        $disaggregates = $this->db->query("
  SELECT 
  pids.id id_project_indicator_disaggregate_sets,
  d.`id`,
  d.`name` ,
  u.`name` unit_name
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `disaggregate_sets` ds 
    ON ds.`id` = pids.`id_disaggregate_sets` 
    INNER JOIN `disaggregate_groups` dg
    ON dg.`id` = ds.`id_disaggregate_groups`
    INNER JOIN `disaggregates` d
    ON ds.`id_disaggregates` = d.`id`
    INNER JOIN `units` u 
    ON u.`id` = ds.`unit_id` 
WHERE pids.`id_project_indicators` = '$indicator[id_project_indicators]'
  AND pids.`id_commodity` = '$commodity[id_commodity]'
  AND ds.`id_disaggregate_tiers` = '$cluster[id_disaggregate_tiers]'
  AND ds.`id_disaggregate_groups` = '$disaggregate_group[id]'")->result_array();
                        $total_w_or_one_or_more = [];
                        $v1 = $v2 = $v3 = $v4 = $v5 = $v6 = $v7 = $v8 = $v9 = $v10 = $v11 = $v12 = $v13 = $v14 = $v15 = $v16 = $v17 = $v18 = $v19 = $v20 = $v21 = $v22 = $v23 = $v24 = number_format(0, DECIMAL_PLACE);
                        foreach ($disaggregates as $disaggregate) {
                            if ($total_w_or_one_or_more_id != $disaggregate['id'] && $disaggregate_group['id'] != $technology_type_validation_id) {

                                $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['id'] = $disaggregate['id'];
                                $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['name'] = $disaggregate['name'];
                                $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['unit'] = $disaggregate['unit_name'];
                                $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['remarks'] = $this->get_remarks($disaggregate['id_project_indicator_disaggregate_sets'], $id_years);
                                $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['id_project_indicator_disaggregate_sets'] = $disaggregate['id_project_indicator_disaggregate_sets'];
                                $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions'] = $this->_get_transaction($disaggregate['id_project_indicator_disaggregate_sets'], $id_years);

                                $v1 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_annual'];
                                $v2 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_semi_annual_1'];
                                $v3 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_semi_annual_2'];
                                $v4 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_q1'];
                                $v5 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_q2'];
                                $v6 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_q3'];
                                $v7 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_q4'];
                                $v8 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_annual'];
                                $v9 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_semi_annual_1'];
                                $v10 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_semi_annual_2'];
                                $v11 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_q1'];
                                $v12 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_q2'];
                                $v13 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_q3'];
                                $v14 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_q4'];
                                $v15 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_annual'];
                                $v16 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_semi_annual_1'];
                                $v17 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_semi_annual_2'];
                                $v18 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_q1'];
                                $v19 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_q2'];
                                $v20 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_q3'];
                                $v21 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_q4'];

                                $v22 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_annual_1'];
                                $v23 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_annual_2'];
                                $v24 += (double)$result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_annual_3'];


                                $indicator_index++;
                                $commodity_index++;
                                $cluster_index++;
                                $disaggregate_group_index++;

                            } else {
                                $total_w_or_one_or_more['id'] = $disaggregate['id'];
                                $total_w_or_one_or_more['name'] = $disaggregate['name'];
                                $total_w_or_one_or_more['unit'] = $disaggregate['unit_name'];
                                $total_w_or_one_or_more['id_project_indicator_disaggregate_sets'] = $disaggregate['id_project_indicator_disaggregate_sets'];
                                $total_w_or_one_or_more['transactions'] = $this->_get_transaction($disaggregate['id_project_indicator_disaggregate_sets'], $id_years);
                            }


                        }
                        if ($disaggregate_group['id'] != $technology_type_validation_id) {


                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['id'] = 'Sub-total';
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['name'] = 'Sub-total';
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['unit'] = '';
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['id_project_indicator_disaggregate_sets'] = '';
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_annual'] = $v1;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_semi_annual_1'] = $v2;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_semi_annual_2'] = $v3;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_q1'] = $v4;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_q2'] = $v5;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_q3'] = $v6;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_q4'] = $v7;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_annual'] = $v8;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_semi_annual_1'] = $v9;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_semi_annual_2'] = $v10;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_q1'] = $v11;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_q2'] = $v12;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_q3'] = $v13;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_q4'] = $v14;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_annual'] = $v15;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_semi_annual_1'] = $v16;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_semi_annual_2'] = $v17;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_q1'] = $v18;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_q2'] = $v19;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_q3'] = $v20;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_q4'] = $v21;


                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_annual_1'] = $v22;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_annual_2'] = $v23;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_annual_3'] = $v24;


                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_annual'] = ($v1 != 0) ? ((($v15 - $v1) / $v1) * 100) : 0;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_semi_annual_1'] = ($v2 != 0) ? ((($v16 - $v2) / $v2) * 100) : 0;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_semi_annual_2'] = ($v3 != 0) ? ((($v17 - $v3) / $v3) * 100) : 0;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_q1'] = ($v4 != 0) ? ((($v18 - $v4) / $v4) * 100) : 0;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_q2'] = ($v5 != 0) ? ((($v19 - $v5) / $v5) * 100) : 0;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_q3'] = ($v6 != 0) ? ((($v20 - $v6) / $v6) * 100) : 0;
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_q4'] = ($v7 != 0) ? ((($v21 - $v7) / $v7) * 100) : 0;

                        } else {

                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['id'] = $total_w_or_one_or_more['id'];
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['name'] = $total_w_or_one_or_more['name'];
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['unit'] = $total_w_or_one_or_more['unit'];
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['id_project_indicator_disaggregate_sets'] = $total_w_or_one_or_more['id_project_indicator_disaggregate_sets'];
                            $result_array[$indicator['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions'] = $total_w_or_one_or_more['transactions'];

                        }

                        $indicator_index++;
                        $commodity_index++;
                        $cluster_index++;
                        $disaggregate_group_index++;
                        $span_array['indicator'][$indicator['id']] = $indicator_index;
                        $span_array['commodity'][$indicator['id']][$commodity['id_commodity']] = $commodity_index;
                        $span_array['cluster'][$indicator['id']][$commodity['id_commodity']][$cluster['id_disaggregate_tiers']] = $cluster_index;
                        $span_array['disaggregate_group'][$indicator['id']][$commodity['id_commodity']][$cluster['id_disaggregate_tiers']][$disaggregate_group['id']] = $disaggregate_group_index;
                    }
                }
            }
        }
        return ['result' => $result_array, 'span' => $span_array];
    }

    function form_data($id_projects, $id_indicators = null, $id_reporting_periods, $id_years)
    {
        $i = 0;
        $return_array = [];
        $data = $this->_get_form_data($id_projects, null, $id_reporting_periods, $id_years);
        $span_info = $data['span'];
        $indicators = $data['result'];
//        echo '<pre>';
//        print_r($indicators);
//        die;
        foreach ($indicators as $indicator) {
            $commodities = $indicator['commodities'];
            foreach ($commodities as $commodity) {
                $clusters = $commodity['clusters'];
                foreach ($clusters as $cluster) {
                    $disaggregate_groups = $cluster['disaggregate_groups'];
                    foreach ($disaggregate_groups as $disaggregate_group) {
                        $disaggregates = $disaggregate_group['disaggregates'];
                        foreach ($disaggregates as $disaggregate) {
                            $return_array[$i]['indicator_id'] = $indicator['id'];
                            $return_array[$i]['indicator_name'] = $indicator['name'];
                            $return_array[$i]['indicator_code'] = $indicator['code'];
                            $return_array[$i]['indicator_rowspan'] = $span_info['indicator'][$indicator['id']];

                            $return_array[$i]['commodity_id'] = $commodity['id'];
                            $return_array[$i]['commodity_name'] = $commodity['name'];
                            $return_array[$i]['commodity_rowspan'] = $span_info['commodity'][$indicator['id']][$commodity['id']];

                            $return_array[$i]['cluster_id'] = $cluster['id'];
                            $return_array[$i]['cluster_name'] = $cluster['name'];
                            $return_array[$i]['cluster_rowspan'] = $span_info['cluster'][$indicator['id']][$commodity['id']][$cluster['id']];

                            $return_array[$i]['disaggregate_group_id'] = $disaggregate_group['id'];
                            $return_array[$i]['disaggregate_group_name'] = $disaggregate_group['name'];
                            $return_array[$i]['disaggregate_group_rowspan'] = $span_info['disaggregate_group'][$indicator['id']][$commodity['id']][$cluster['id']][$disaggregate_group['id']];

                            $return_array[$i]['disaggregate_id'] = $disaggregate['id'];
                            $return_array[$i]['disaggregate_name'] = $disaggregate['name'];
                            $return_array[$i]['unit_name'] = $disaggregate['unit'];
                            $return_array[$i]['remarks'] = isset($disaggregate['remarks']) ? $disaggregate['remarks'] : '';
                            $return_array[$i]['transaction'] = $disaggregate['transactions'];
                            $return_array[$i]['id_project_indicator_disaggregate_sets'] = $disaggregate['id_project_indicator_disaggregate_sets'];
                            //$return_array[$i]['total'] = $disaggregate['total'];
                            $i++;
                        }
                    }
                }
            }
        }

        return $return_array;
    }

    function get_id_time_sets($id_years, $id_reporting_periods)
    {
        return $this->db->query("SELECT `id` FROM `time_sets` WHERE `id_years` = '$id_years' AND `id_reporting_periods` = '$id_reporting_periods'")->row()->id;
    }

    function get_indicator_item($id_projects, $id_reporting_periods)
    {
        $reporting_period_type = $this->db->query("SELECT `reporting_period_type` FROM `reporting_periods` WHERE `id` = '$id_reporting_periods'")->row()->reporting_period_type;
        $indicators = [];
        $result = $this->db->query("SELECT 
  `pi`.`id`,
  i.`name`,
  i.`code` 
FROM
  `project_indicators` AS `pi` 
  INNER JOIN `indicators` i 
    ON `pi`.`id_indicators` = i.`id` 
WHERE `pi`.`reporting_period` = '$reporting_period_type' 
  AND `pi`.`id_projects` = '$id_projects'")->result_array();
        foreach ($result as $row) {
            $indicators[$row['id']] = $row['name'];
        }
        return $indicators;
    }


    function _get_transaction($id_project_indicator_disaggregate_sets, $id_years)
    {
        $time_sets = $this->db->query("SELECT `id` FROM `time_sets` WHERE `id_years` = '$id_years'")->result_array();
        $types = ['Target', 'Baseline', 'Result'];
        $return_array = [];
        foreach ($types as $type) {
            $return_array[$type . '_annual'] = $this->_get_value($id_project_indicator_disaggregate_sets, $time_sets[0]['id'], $type);
            $return_array[$type . '_semi_annual_1'] = $this->_get_value($id_project_indicator_disaggregate_sets, $time_sets[1]['id'], $type);
            $return_array[$type . '_semi_annual_2'] = $this->_get_value($id_project_indicator_disaggregate_sets, $time_sets[2]['id'], $type);
            $return_array[$type . '_q1'] = $this->_get_value($id_project_indicator_disaggregate_sets, $time_sets[3]['id'], $type);
            $return_array[$type . '_q2'] = $this->_get_value($id_project_indicator_disaggregate_sets, $time_sets[4]['id'], $type);
            $return_array[$type . '_q3'] = $this->_get_value($id_project_indicator_disaggregate_sets, $time_sets[5]['id'], $type);
            $return_array[$type . '_q4'] = $this->_get_value($id_project_indicator_disaggregate_sets, $time_sets[6]['id'], $type);
        }
        $return_array['Target_annual_1'] = $this->get_annual_value(($id_years + 1), $id_project_indicator_disaggregate_sets, $type);
        $return_array['Target_annual_2'] = $this->get_annual_value(($id_years + 2), $id_project_indicator_disaggregate_sets, $type);
        $return_array['Target_annual_3'] = $this->get_annual_value(($id_years + 3), $id_project_indicator_disaggregate_sets, $type);
        return $return_array;

    }

    function _get_disaggregate_group_wise_total($data)
    {
        $types = ['Target', 'Baseline', 'Result'];
        $return_array = [];
        foreach ($types as $type) {
            $return_array[$type . '_annual'] = 0;
            $return_array[$type . '_semi_annual_1'] = 0;
            $return_array[$type . '_semi_annual_2'] = 0;
            $return_array[$type . '_q1'] = 0;
            $return_array[$type . '_q2'] = 0;
            $return_array[$type . '_q3'] = 0;
            $return_array[$type . '_q4'] = 0;
        }
        foreach ($data as $row) {
            foreach ($types as $type) {
                $return_array[$type . '_annual'] += empty($row[$type . '_annual']) ? 0 : (int)$row[$type . '_annual'];
                $return_array[$type . '_semi_annual_1'] += empty($row[$type . '_semi_annual_1']) ? 0 : (int)$row[$type . '_semi_annual_1'];
                $return_array[$type . '_semi_annual_2'] += empty($row[$type . '_semi_annual_2']) ? 0 : (int)$row[$type . '_semi_annual_2'];
                $return_array[$type . '_q1'] += empty($row[$type . '_q1']) ? 0 : (int)$row[$type . '_q1'];
                $return_array[$type . '_q2'] += empty($row[$type . '_q2']) ? 0 : (int)$row[$type . '_q2'];
                $return_array[$type . '_q3'] += empty($row[$type . '_q3']) ? 0 : (int)$row[$type . '_q3'];
                $return_array[$type . '_q4'] += empty($row[$type . '_q4']) ? 0 : (int)$row[$type . '_q4'];
            }
        }
        return $return_array;
    }

    function get_remarks($id_project_indicator_disaggregate_sets, $id_years)
    {
        $return_array = [];
        $types = ['Target', 'Baseline', 'Result'];
        $query = "SELECT 
  `transactions`.`remarks` 
FROM
  `transactions` 
  INNER JOIN `time_sets` 
    ON `time_sets`.`id` = `transactions`.`id_time_sets` 
WHERE `transactions`.`id_project_indicator_disaggregate_sets` = '$id_project_indicator_disaggregate_sets' 
  AND `transactions`.`type` = ? 
  AND `time_sets`.`id_years` = ? 
  AND `time_sets`.`id_reporting_periods` = ? ";
        foreach ($types as $type) {
            $result = $this->db->query($query, [$type, $id_years, 1])->result_array();
            $return_array[$type][$id_years][1] = empty($result) ? '' : $result[0]['remarks'];
            $result = $this->db->query($query, [$type, $id_years, 2])->result_array();
            $return_array[$type][$id_years][2] = empty($result) ? '' : $result[0]['remarks'];
            $result = $this->db->query($query, [$type, $id_years, 3])->result_array();
            $return_array[$type][$id_years][3] = empty($result) ? '' : $result[0]['remarks'];
            $result = $this->db->query($query, [$type, $id_years, 4])->result_array();
            $return_array[$type][$id_years][4] = empty($result) ? '' : $result[0]['remarks'];
            $result = $this->db->query($query, [$type, $id_years, 5])->result_array();
            $return_array[$type][$id_years][5] = empty($result) ? '' : $result[0]['remarks'];
            $result = $this->db->query($query, [$type, $id_years, 6])->result_array();
            $return_array[$type][$id_years][6] = empty($result) ? '' : $result[0]['remarks'];
            $result = $this->db->query($query, [$type, $id_years, 7])->result_array();
            $return_array[$type][$id_years][7] = empty($result) ? '' : $result[0]['remarks'];
        }
        return $return_array;
    }

    function _get_value($id_project_indicator_disaggregate_sets, $id_time_sets, $type)
    {
        return $this->db->query("SELECT 
  IFNULL(SUM(t.`value`), '') `value` 
FROM
  `transactions` t 
WHERE t.`id_project_indicator_disaggregate_sets` = '$id_project_indicator_disaggregate_sets' 
  AND t.`id_time_sets` = '$id_time_sets' 
  AND t.`type` = '$type' ")->row()->value;

    }

    function process($data)
    {
//        echo '<pre>';
//        print_r($data);
//        die;
        $id_time_sets = $data['id_time_sets'];
        foreach ($data['value'] as $key => $value) {
            $id_project_indicator_disaggregate_sets = $key;
            $type = $data['type'];
            $check = $this->db->query("SELECT 
  `id` 
FROM
  `transactions` 
WHERE `id_project_indicator_disaggregate_sets` = '$id_project_indicator_disaggregate_sets' 
  AND `type` = '$type' 
  AND `id_time_sets` = '$id_time_sets'")->result_array();
            if (empty($check)) {
                if (!empty($value) || $value != '') {
                    $remarks = $data['remarks'][$id_project_indicator_disaggregate_sets];
                    $this->db->insert('transactions', ['id_project_indicator_disaggregate_sets' => $id_project_indicator_disaggregate_sets, 'type' => $type, 'id_time_sets' => $id_time_sets, 'value' => $value, 'remarks' => $remarks]);
                }
            } else {
                if (!empty($value) || $value != '') {
                    $remarks = $data['remarks'][$id_project_indicator_disaggregate_sets];
                    $this->db->update('transactions', ['value' => $value, 'remarks' => $remarks], ['id' => $check[0]['id']]);
                } else {
                    $this->db->delete('transactions', ['id' => $check[0]['id']]);
                }
            }
        }

        foreach ($data['deviations'] as $key => $deviation) {
            $exploded_key = explode('_', $key);
            $deviation_data = [];
            $deviation_data['id_projects'] = $data['id_projects'];
            $deviation_data['id_time_sets'] = $data['id_time_sets'];
            $deviation_data['id_indicators'] = $exploded_key[0];
            $deviation_data['id_commodities'] = $exploded_key[1];
            $deviation_data['id_clusters'] = $exploded_key[2];
            $deviation_data['id_disaggregate_groups'] = $exploded_key[3];
            $deviation_data['deviation'] = $deviation;
            $check = $this->db->query("SELECT 
  d.`id` 
FROM
  `deviations` d 
WHERE d.`id_clusters` = '$deviation_data[id_clusters]' 
  AND d.`id_commodities` = '$deviation_data[id_commodities]' 
  AND d.`id_disaggregate_groups` = '$deviation_data[id_disaggregate_groups]' 
  AND d.`id_indicators` = '$deviation_data[id_indicators]' 
  AND d.`id_projects` = '$deviation_data[id_projects]' 
  AND d.`id_time_sets` = '$deviation_data[id_time_sets]' ")->result_array();
            if (empty($check)) {
                $this->db->insert('deviations', $deviation_data);
            } else {
                $this->db->update('deviations', ['deviation' => $deviation], ['id' => $check[0]['id']]);
            }
        }
        foreach ($data['deviation_narratives'] as $id_indicators => $deviation_narrative) {
            $deviation_narrative_data = [];
            $deviation_narrative_data['id_projects'] = $data['id_projects'];
            $deviation_narrative_data['id_time_sets'] = $data['id_time_sets'];
            $deviation_narrative_data['id_indicators'] = $id_indicators;
            $deviation_narrative_data['deviation_narrative'] = $deviation_narrative;
            $check = $this->db->query("SELECT 
  dn.`id` 
FROM
  `deviation_narratives` dn 
WHERE dn.`id_projects` = '$deviation_narrative_data[id_projects]' 
  AND dn.`id_indicators` = '$deviation_narrative_data[id_indicators]' 
  AND dn.`id_time_sets` = '$deviation_narrative_data[id_time_sets]'")->result_array();

            if (empty($check)) {
                $this->db->insert('deviation_narratives', $deviation_narrative_data);
            } else {
                $this->db->update('deviation_narratives', ['deviation_narrative' => $deviation_narrative], ['id' => $check[0]['id']]);
            }
        }

    }
//    function authorization($data)
//    {
//            $check = $this->db->query("SELECT
//  `id`
//FROM
//  `transactions`
//WHERE `id_project_indicator_disaggregate_sets` = 'id_project_indicator_disaggregate_sets'
//  AND `type` = '$type'
//  AND `id_time_sets` = '$id_time_sets'")->result_array();
//            if (empty($check)) {
//                if (!empty($value) || $value != '') {
//                    $this->db->update('transactions', ['value' => $value], ['id' => $check[0]['id']]);
//                }
//        }
//    }

    function get_reporting_periods($by_id = null)
    {
        $this->db->select('reporting_periods.*');
        $this->db->from('reporting_periods');
        if (!empty($by_id)) {
            $this->db->where('reporting_periods.id', $by_id);
            return $this->db->get()->result_array()[0];
        }
        $query = $this->db->get();
        $result_array = [];
        foreach ($query->result_array() as $row) {
            $result_array[$row['id']] = $row['name'];
        }
        return $result_array;
    }


    function get_activity_name_by_id($activity_id)
    {
        return $this->db->query("SELECT `name` FROM `projects` WHERE `id` = '$activity_id'")->row()->name;
    }

    function get_reporting_peroid_type_by_id($id_reporting_periods)
    {
        return $this->db->query("SELECT `reporting_period_type`  FROM `reporting_periods` WHERE `id` = '$id_reporting_periods'")->row()->reporting_period_type;

    }

    function get_years($by_id = null)
    {
        $this->db->select('*');
        $this->db->from('years');
        if (!empty($by_id)) {
            $this->db->where('id', $by_id);
            return $this->db->get()->result_array()[0];
        }
        $query = $this->db->get();
        $result_array = [];
        foreach ($query->result_array() as $row) {
            $result_array[$row['id']] = $row['name'];
        }
        return $result_array;
    }

    function get_logged_in_user_email_info()
    {
        $user_id = $this->session->userdata('data')['id'];
        return $this->db->query("SELECT 
  u.`email` `from_email`,
  u.`full_name` from_name,
  p.`cor_email` `to_email`,
  p.`cor_name` to_name,
  p.`acme_email` cc_email,
  'ACME' cc_name 
FROM
  `users` u 
  INNER JOIN `projects` p 
    ON p.`id` = u.`project_id` 
WHERE u.`id` = '$user_id'")->result_array()[0];
    }


    function authorization_action($id_projects, $id_reporting_periods, $id_years)
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

    function get_file_name_info($id_years, $id_reporting_periods, $id_projects, $time_stamp = false)
    {
        $info = $this->db->query("SELECT 
  `name` project_name,
  (SELECT 
    `name` 
  FROM
    `years` 
  WHERE `id` = '$id_years') year_name,
  (SELECT 
    `name` 
  FROM
    `reporting_periods` 
  WHERE `id` = '$id_reporting_periods') reporting_reporiod_name 
FROM
  `projects` 
WHERE `id` = '$id_projects' ")->result_array()[0];
        return $info['project_name'] . '_' . $info['year_name'] . '_' . $info['reporting_reporiod_name'] . (!$time_stamp ? '' : '');

    }

    function get_sub_header($id_years, $id_reporting_periods, $id_projects = false)
    {
        if (!$id_projects) {
            $info = $this->db->query("SELECT 
  `name` year_name,
  (SELECT 
    `name` 
  FROM
    `reporting_periods` 
  WHERE `id` = '$id_reporting_periods') reporting_reporiod_name 
FROM
  `years` 
WHERE `id` = '$id_years'")->result_array()[0];
            return "Reporting Reporiod: " . $info['reporting_reporiod_name'] . ' Fiscal Year: ' . $info['year_name'];
        } else {
            $info = $this->db->query("SELECT 
  `name` year_name,
  (SELECT 
    `name` 
  FROM
    `reporting_periods` 
  WHERE `id` = '$id_reporting_periods') reporting_reporiod_name,
  (SELECT 
    `name` 
  FROM
    `projects` 
  WHERE `id` = '$id_reporting_periods') project_name 
FROM
  `years` 
WHERE `id` = '$id_years' ")->result_array()[0];
            return 'Activity: ' . $info['project_name'] . ' Quarter: ' . $info['reporting_reporiod_name'] . ' Fiscal Year: ' . $info['year_name'];
        }
    }

    function IncrementalSales_report_data($id_indicators = null, $id_reporting_periods, $id_years)
    {

        $i = 0;
        $return_array = [];
        $data = $this->_get_IncrementalSales_report_data($id_indicators, $id_reporting_periods, $id_years);
        $span_info = $data['span'];
        $projects = $data['result'];
//        echo '<pre>';
//        print_r($indicators);
//        die;

        foreach ($projects as $project) {
            $commodities = $project['commodities'];
            foreach ($commodities as $commodity) {
                $clusters = $commodity['clusters'];
                foreach ($clusters as $cluster) {
                    $disaggregate_groups = $cluster['disaggregate_groups'];
                    foreach ($disaggregate_groups as $disaggregate_group) {
                        $disaggregates = $disaggregate_group['disaggregates'];
                        foreach ($disaggregates as $disaggregate) {
                            $return_array[$i]['project_id'] = $project['id'];
                            $return_array[$i]['project_name'] = $project['name'];
                            $return_array[$i]['project_code'] = $project['code'];
                            $return_array[$i]['project_rowspan'] = $span_info['project'][$project['id']];

                            $return_array[$i]['commodity_id'] = $commodity['id'];
                            $return_array[$i]['commodity_name'] = $commodity['name'];
                            $return_array[$i]['commodity_rowspan'] = $span_info['commodity'][$project['id']][$commodity['id']];

                            $return_array[$i]['cluster_id'] = $cluster['id'];
                            $return_array[$i]['cluster_name'] = $cluster['name'];
                            $return_array[$i]['cluster_rowspan'] = $span_info['cluster'][$project['id']][$commodity['id']][$cluster['id']];

                            $return_array[$i]['disaggregate_group_id'] = $disaggregate_group['id'];
                            $return_array[$i]['disaggregate_group_name'] = $disaggregate_group['name'];
                            $return_array[$i]['disaggregate_group_rowspan'] = $span_info['disaggregate_group'][$project['id']][$commodity['id']][$cluster['id']][$disaggregate_group['id']];

                            $return_array[$i]['disaggregate_id'] = $disaggregate['id'];
                            $return_array[$i]['disaggregate_name'] = $disaggregate['name'];
                            $return_array[$i]['unit_name'] = $disaggregate['unit'];
                            $return_array[$i]['transaction'] = $disaggregate['transactions'];
                            $return_array[$i]['id_project_indicator_disaggregate_sets'] = $disaggregate['id_project_indicator_disaggregate_sets'];
                            //$return_array[$i]['total'] = $disaggregate['total'];
                            $i++;
                        }
                    }
                }
            }
        }

        return $return_array;
    }

    function _get_IncrementalSales_report_data($id_indicators = null, $id_reporting_periods, $id_years)
    {
        $this->config->load('validation_rules');
        $technology_type_validation_id = $this->config->item('technology_type_id');
        $span_array = [];
        $return_array = [];
        $result_array = [];
        $projects = $this->db->query("
        SELECT 
  `pi`.`id` id_project_indicators,
  p.`id`,
  p.`name`,
  p.`code` 
FROM
  `project_indicators` AS `pi` 
  INNER JOIN `projects` p 
    ON `pi`.`id_projects` = p.`id` 
WHERE `pi`.`reporting_period` = '$id_reporting_periods' 
  AND `pi`.`id_indicators` = '$id_indicators'")->result_array();
        foreach ($projects as $project) {
            $project_index = 0;
            $result_array[$project['id']]['name'] = $project['name'];
            $result_array[$project['id']]['code'] = $project['code'];
            $result_array[$project['id']]['id'] = $project['id'];
            $commodities = $this->db->query(
                "SELECT 
  pids.`id_commodity`,
  c.`name`,
  c.`code` 
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `commodities` c 
    ON c.`id` = pids.`id_commodity` 
WHERE pids.`id_project_indicators` = '$project[id_project_indicators]' 
GROUP BY pids.`id_commodity`")->result_array();
            foreach ($commodities as $commodity) {
                $commodity_index = 0;
                $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['id'] = $commodity['id_commodity'];
                $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['name'] = $commodity['name'];
                $clusters = $this->db->query("
  SELECT 
  ds.`id_disaggregate_tiers`, 
  dt.name,
  dt.code
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `disaggregate_sets` ds 
    ON ds.`id` = pids.`id_disaggregate_sets` 
    INNER JOIN `disaggregate_tiers` dt
    ON dt.`id` = ds.`id_disaggregate_tiers`
WHERE pids.`id_project_indicators` = '$project[id_project_indicators]' 
  AND pids.`id_commodity` = '$commodity[id_commodity]' 
GROUP BY ds.`id_disaggregate_tiers` ")->result_array();
                foreach ($clusters as $cluster) {
                    $cluster_index = 0;
                    $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['id'] = $cluster['id_disaggregate_tiers'];
                    $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['name'] = $cluster['name'];
                    $disaggregate_groups = $this->db->query("
  SELECT 
  dg.`id`,
  dg.`name` 
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `disaggregate_sets` ds 
    ON ds.`id` = pids.`id_disaggregate_sets` 
    INNER JOIN `disaggregate_groups` dg
    ON dg.`id` = ds.`id_disaggregate_groups`
WHERE pids.`id_project_indicators` = '$project[id_project_indicators]' 
  AND pids.`id_commodity` = '$commodity[id_commodity]'
  AND ds.`id_disaggregate_tiers` = '$cluster[id_disaggregate_tiers]'
  GROUP BY ds.`id_disaggregate_groups`")->result_array();
                    foreach ($disaggregate_groups as $disaggregate_group) {
                        $disaggregate_group_index = 0;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['id'] = $disaggregate_group['id'];
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['name'] = $disaggregate_group['name'];
                        $disaggregates = $this->db->query("
  SELECT 
  pids.id id_project_indicator_disaggregate_sets,
  d.`id`,
  d.`name` ,
  u.`name` unit_name
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `disaggregate_sets` ds 
    ON ds.`id` = pids.`id_disaggregate_sets` 
    INNER JOIN `disaggregate_groups` dg
    ON dg.`id` = ds.`id_disaggregate_groups`
    INNER JOIN `disaggregates` d
    ON ds.`id_disaggregates` = d.`id`
    INNER JOIN `units` u 
    ON u.`id` = ds.`unit_id` 
WHERE pids.`id_project_indicators` = '$project[id_project_indicators]'
  AND pids.`id_commodity` = '$commodity[id_commodity]'
  AND ds.`id_disaggregate_tiers` = '$cluster[id_disaggregate_tiers]'
  AND ds.`id_disaggregate_groups` = '$disaggregate_group[id]'")->result_array();
                        $total_w_or_one_or_more = [];
                        $v1 = $v2 = $v3 = $v4 = $v5 = $v6 = $v7 = $v8 = $v9 = $v10 = $v11 = $v12 = $v13 = $v14 = $v15 = $v16 = $v17 = $v18 = $v19 = $v20 = $v21 = $v22 = $v23 = $v24 = 0.00;
                        foreach ($disaggregates as $disaggregate) {
                            $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['id'] = $disaggregate['id'];
                            $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['name'] = $disaggregate['name'];
                            $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['unit'] = $disaggregate['unit_name'];
                            $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['id_project_indicator_disaggregate_sets'] = $disaggregate['id_project_indicator_disaggregate_sets'];
                            $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions'] = $this->_get_transaction($disaggregate['id_project_indicator_disaggregate_sets'], $id_years);
                            $v1 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_annual'];
                            $v2 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_semi_annual_1'];
                            $v3 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_semi_annual_2'];
                            $v4 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_q1'];
                            $v5 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_q2'];
                            $v6 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_q3'];
                            $v7 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_q4'];
                            $v8 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_annual'];
                            $v9 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_semi_annual_1'];
                            $v10 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_semi_annual_2'];
                            $v11 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_q1'];
                            $v12 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_q2'];
                            $v13 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_q3'];
                            $v14 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Baseline_q4'];
                            $v15 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_annual'];
                            $v16 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_semi_annual_1'];
                            $v17 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_semi_annual_2'];
                            $v18 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_q1'];
                            $v19 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_q2'];
                            $v20 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_q3'];
                            $v21 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Result_q4'];

                            $v22 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_annual_1'];
                            $v23 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_annual_2'];
                            $v24 += (double)$result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates'][$disaggregate['id']]['transactions']['Target_annual_3'];

                            $project_index++;
                            $commodity_index++;
                            $cluster_index++;
                            $disaggregate_group_index++;
                        }
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['id'] = 'Incremental sales ($)';
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['name'] = 'Incremental sales ($)';
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['unit'] = '';
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['id_project_indicator_disaggregate_sets'] = '';
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_annual'] = $v1;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_semi_annual_1'] = $v2;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_semi_annual_2'] = $v3;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_q1'] = $v4;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_q2'] = $v5;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_q3'] = $v6;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_q4'] = $v7;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_annual'] = $v8;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_semi_annual_1'] = $v9;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_semi_annual_2'] = $v10;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_q1'] = $v11;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_q2'] = $v12;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_q3'] = $v13;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Baseline_q4'] = $v14;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_annual'] = $v15;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_semi_annual_1'] = $v16;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_semi_annual_2'] = $v17;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_q1'] = $v18;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_q2'] = $v19;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_q3'] = $v20;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Result_q4'] = $v21;

                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_annual_1'] = $v22;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_annual_2'] = $v23;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Target_annual_3'] = $v24;

                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_annual'] = ($v1 != 0) ? ((($v15 - $v1) / $v1) * 100) : 0;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_semi_annual_1'] = ($v2 != 0) ? ((($v16 - $v2) / $v2) * 100) : 0;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_semi_annual_2'] = ($v3 != 0) ? ((($v17 - $v3) / $v3) * 100) : 0;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_q1'] = ($v4 != 0) ? ((($v18 - $v4) / $v4) * 100) : 0;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_q2'] = ($v5 != 0) ? ((($v19 - $v5) / $v5) * 100) : 0;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_q3'] = ($v6 != 0) ? ((($v20 - $v6) / $v6) * 100) : 0;
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['transactions']['Deviation_q4'] = ($v7 != 0) ? ((($v21 - $v7) / $v7) * 100) : 0;

                        $project_index++;
                        $commodity_index++;
                        $cluster_index++;
                        $disaggregate_group_index++;
                        $span_array['project'][$project['id']] = $project_index;
                        $span_array['commodity'][$project['id']][$commodity['id_commodity']] = $commodity_index;
                        $span_array['cluster'][$project['id']][$commodity['id_commodity']][$cluster['id_disaggregate_tiers']] = $cluster_index;
                        $span_array['disaggregate_group'][$project['id']][$commodity['id_commodity']][$cluster['id_disaggregate_tiers']][$disaggregate_group['id']] = $disaggregate_group_index;
                    }
                }
            }
        }
        return ['result' => $result_array, 'span' => $span_array];
    }
}