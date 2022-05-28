<?php

class Gross_margin extends MY_Model
{
    function __construct()
    {
        parent::__construct();
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

    function GrossMargin_report_data($id_reporting_periods, $id_years)
    {

        $i = 0;
        $return_array = [];
        $data = $this->_get_GrossMargin_report_data($id_reporting_periods, $id_years);
        $span_info = $data['span'];
        $projects = $data['result'];
//        echo '<pre>';
//        print_r($indicators);
//        die;

        foreach ($projects as $project) {
            $commodities = $project['commodities'];
            //echo '<pre>';print_r($commodities);
            foreach ($commodities as $commodity) {
                $clusters = $commodity['clusters'];
                foreach ($clusters as $cluster) {
                    $disaggregate_groups = $cluster['disaggregate_groups'];
                    foreach ($disaggregate_groups as $disaggregate_group) {
                        $disaggregates = $disaggregate_group['disaggregates'];
                        //echo '<pre>';print_r($disaggregates);
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

    function _get_GrossMargin_report_data($id_reporting_periods, $id_years)
    {
        $this->config->load('validation_rules');
        $technology_type_validation_id = $this->config->item('technology_type_id');
        $id_indicators = $this->config->item('id_gross_margin');
        $id_total_value_of_sales = $this->config->item('id_total_value_of_sales');
        $id_total_quantity_of_sales = $this->config->item('id_total_quantity_of_sales');
        $id_total_production = $this->config->item('id_total_production');
        $id_total_recurrent_cash_input = $this->config->item('id_total_recurrent_cash_input');
        $id_total_unit_of_production = $this->config->item('id_total_unit_of_production');
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
                $commodity_last_id = $commodity['id_commodity'];
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
                    $last_cluster_id = $cluster['id_disaggregate_tiers'];
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
                        $last_disaggregate_group_id = $disaggregate_group['id'];
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
                            $project_index++;
                            $commodity_index++;
                            $cluster_index++;
                            $disaggregate_group_index++;
                        }
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['id'] = 'Sub Total';
                        $result_array[$project['id']]['commodities'][$commodity['id_commodity']]['clusters'][$cluster['id_disaggregate_tiers']]['disaggregate_groups'][$disaggregate_group['id']]['disaggregates']['total']['name'] = 'Sub Total';
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
                        if ($disaggregate_group['id'] == $id_total_value_of_sales) {
                            $subtotal_value_of_sales_annual = $v1;
                        }
                        if ($disaggregate_group['id'] == $id_total_quantity_of_sales) {
                            $subtotal_quantity_of_sales_annual = $v1;
                        }
                        if ($disaggregate_group['id'] == $id_total_production) {
                            $subtotal_total_production_annual = $v1;
                        }
                        if ($disaggregate_group['id'] == $id_total_recurrent_cash_input) {
                            $subtotal_recurrent_cash_input_annual = $v1;
                        }
                        if ($disaggregate_group['id'] == $id_total_unit_of_production) {
                            $subtotal_total_unit_of_production_annual = $v1;
                        }

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
            //$result_array[$commodity['id_commodity']+1] = ['name'=>'Gross Margin','code'=>''];
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['id'] = $commodity_last_id + 1;
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['name'] = 'Gross Margin';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['name'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['id'] = '1';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['id'] = '1';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['name'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['id'] = '1';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['name'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['unit'] = 'USD';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['id_project_indicator_disaggregate_sets'] = '';
            if(isset($subtotal_quantity_of_sales_annual) && isset($subtotal_total_unit_of_production_annual) && isset($subtotal_recurrent_cash_input_annual) && isset($subtotal_total_production_annual) && isset($subtotal_value_of_sales_annual)){
                if ($subtotal_quantity_of_sales_annual > 0 && $subtotal_total_unit_of_production_annual > 0){
                    $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_annual'] = ((((($subtotal_value_of_sales_annual / $subtotal_quantity_of_sales_annual) * $subtotal_total_production_annual)) - $subtotal_recurrent_cash_input_annual) / $subtotal_total_unit_of_production_annual);
                }else{
                    $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_annual'] = '';
                }
            }else{
                $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_annual'] = '';
            }
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_annual_1'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_annual_2'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_annual_3'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_semi_annual_1'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_semi_annual_2'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_q1'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_q2'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_q3'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Target_q4'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Baseline_annual'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Baseline_semi_annual_1'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Baseline_semi_annual_2'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Baseline_q1'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Baseline_q2'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Baseline_q3'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Baseline_q4'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Result_annual'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Result_semi_annual_1'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Result_semi_annual_2'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Result_q1'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Result_q2'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Result_q3'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Result_q4'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Deviation_annual'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Deviation_semi_annual_1'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Deviation_semi_annual_2'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Deviation_q1'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Deviation_q2'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Deviation_q3'] = '';
            $result_array[$project['id']]['commodities'][$commodity_last_id + 1]['clusters'][1]['disaggregate_groups'][1]['disaggregates'][1]['transactions']['Deviation_q4'] = '';
            $span_array['project'][$project['id']] = $project_index + 1;
            $span_array['commodity'][$project['id']][$commodity_last_id + 1] = 1;
            $span_array['cluster'][$project['id']][$commodity_last_id + 1][1] = 1;
            $span_array['disaggregate_group'][$project['id']][$commodity_last_id + 1][1][1] = 1;
        }
//        echo '<pre>';print_r($result_array);exit;
        return ['result' => $result_array, 'span' => $span_array];
    }
}