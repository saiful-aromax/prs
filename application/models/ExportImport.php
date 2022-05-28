<?php

/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/22/17
 * Time: 3:57 PM
 */
class ExportImport extends MY_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function import($data)
    {
        $this->load->helper('url');
        if (isset($_FILES['import_file']['tmp_name'])) {
            $string = $_FILES['import_file']['name'];
            $parts = explode('.', $string);
            $last = array_pop($parts);
            if ($last != 'csv') {
                $data['error'] = 'please upload a CSV file';
            } else {
                $fp = fopen($_FILES['import_file']['tmp_name'], 'r') or die("can't open file");
                echo '<pre>';
                $count = 0;
                $flag1 = '';
                $flag2 = '';
                while ($csv_line = fgetcsv($fp, 3072)) {
                    $csv_row = [];
                    if ($count == 0) {
                        $string = preg_replace('/\s+/', '', $csv_line[8]);
                        $typeAndperiod = explode('_', $string);
                        $type = $typeAndperiod[0];
                        $period = $typeAndperiod[1];
                        $fiscal_year_name = $typeAndperiod[2];
                        //echo $type . ' ' . $period . ' ' . $fiscal_year_name . '<br>';
                    }
//                        print_r($csv_line);
//                        die;
                    if ($count != 0) {
                        for ($i = 0; $i < count($csv_line); $i++) {
                            $csv_row['Activity'] = $csv_line[0];
                            $csv_row['IndicatorCode'] = $csv_line[1];
                            $csv_row['Indicatorname'] = $csv_line[2];
                            $csv_row['Commodity'] = $csv_line[3];
                            $csv_row['Cluster'] = $csv_line[4];
                            $csv_row['DisaggregateGroup'] = $csv_line[5];
                            $csv_row['Disaggregate'] = $csv_line[6];
                            $csv_row['Unit'] = $csv_line[7];
                            $csv_row[$type . 'Q1'] = $csv_line[8];
                            if ($type == 'Result') {
                                $csv_row['Deviation'] = $csv_line[9];
                                $csv_row['DeviationNarratives'] = $csv_line[10];
                                $csv_row['Remarks'] = $csv_line[11];
                            } elseif ($type == 'Target') {
                                $csv_row['Out-yearTargetRationales'] = $csv_line[9];
                                $csv_row['Remarks'] = $csv_line[10];
                            } else {
                                $csv_row['Remarks'] = $csv_line[9];
                            }

                        }

                        if ($flag1 == '' || $flag1 != $csv_row['DisaggregateGroup']) {
                            $flag1 = $csv_row['DisaggregateGroup'];
                            $this->import_process($csv_row, $type, $period, $fiscal_year_name, 1);
                        } else {
                            $this->import_process($csv_row, $type, $period, $fiscal_year_name, 0);
                        }

                        if ($flag2 == '' || $flag2 != $csv_row['Indicatorname']) {
                            $flag2 = $csv_row['Indicatorname'];
                            $this->import_process($csv_row, $type, $period, $fiscal_year_name, 11);
                        } else {
                            $this->import_process($csv_row, $type, $period, $fiscal_year_name, 10);
                        }
                    }
                    $count++;
                }
                die;
            }
        }
    }

    function import_process($csv_row, $type, $period, $fiscal_year_name, $flag)
    {
        $csv_row['type'] = $type;
        $csv_row['period'] = $period;
        $csv_row['fiscal_year_name'] = $fiscal_year_name;
        $transactions_row = [];
        $transactions_row['id_project_indicator_disaggregate_sets'] = $this->get_pids_id($csv_row)['id'];
        $transactions_row['value'] = $csv_row[$type . $period];
        $transactions_row['type'] = $type;
        $transactions_row['id_time_sets'] = $this->get_time_sets_id($period, $fiscal_year_name);
        $transactions_row['remarks'] = $csv_row['Remarks'];

        $this->insert_csv_row($transactions_row);

        if ($flag == 1) {
            $all_id = $this->get_pids_id($csv_row);
            $deviations_row = [];
            $deviations_row['id_projects'] = $all_id['id_projects'];
            $deviations_row['id_time_sets'] = $transactions_row['id_time_sets'];
            $deviations_row['id_indicators'] = $all_id['id_indicators'];
            $deviations_row['id_commodities'] = $all_id['id_commodities'];
            $deviations_row['id_clusters'] = $all_id['id_clusters'];
            $deviations_row['id_disaggregate_groups'] = $all_id['id_disaggregate_groups'];
            $deviations_row['deviation'] = $csv_row['Deviation'];
            $this->insert_deviation_row($deviations_row);
        }
        if($flag==11){
            $all_id = $this->get_pids_id($csv_row);
            $deviation_narratives_row = [];
            $deviation_narratives_row['id_projects'] = $all_id['id_projects'];
            $deviation_narratives_row['id_indicators'] = $all_id['id_indicators'];
            $deviation_narratives_row['id_time_sets'] = $transactions_row['id_time_sets'];
            $deviation_narratives_row['deviation_narrative'] = $csv_row['DeviationNarratives'];
            $this->insert_deviation_narratives_row($deviation_narratives_row);
            //print_r($csv_row);
        }

        print_r($csv_row);
    }

    function insert_csv_row($transactions_row)
    {
        $check = $this->db->query("SELECT 
  `id` 
FROM
  `transactions` 
WHERE `id_project_indicator_disaggregate_sets` = '$transactions_row[id_project_indicator_disaggregate_sets]' 
  AND `type` = '$transactions_row[type]' 
  AND `id_time_sets` = '$transactions_row[id_time_sets]'")->result_array();
        if (empty($check)) {
            $this->db->insert('transactions', $transactions_row);
        } else {
            $this->db->update('transactions', $transactions_row, ['id' => $check[0]['id']]);
        }
    }

    function insert_deviation_row($deviations_row)
    {
        $check = $this->db->query("SELECT 
  `id` 
FROM
  `deviations` 
WHERE `id_projects` = '$deviations_row[id_projects]' 
  AND `id_time_sets` = '$deviations_row[id_time_sets]' 
  AND `id_indicators` = '$deviations_row[id_indicators]'
  AND `id_commodities` = '$deviations_row[id_commodities]'
  AND `id_clusters` = '$deviations_row[id_clusters]'
  AND `id_disaggregate_groups` = '$deviations_row[id_disaggregate_groups]'")->result_array();
        if (empty($check)) {
            $this->db->insert('deviations', $deviations_row);
        } else {
            $this->db->update('deviations', $deviations_row, ['id' => $check[0]['id']]);
        }
    }

    function insert_deviation_narratives_row($deviation_narratives_row)
    {
        $check = $this->db->query("SELECT 
  `id` 
FROM
  `deviation_narratives` 
WHERE `id_projects` = '$deviation_narratives_row[id_projects]' 
  AND `id_indicators` = '$deviation_narratives_row[id_indicators]'
  AND `id_time_sets` = '$deviation_narratives_row[id_time_sets]'")->result_array();
        if (empty($check)) {
            $this->db->insert('deviation_narratives', $deviation_narratives_row);
        } else {
            $this->db->update('deviation_narratives', $deviation_narratives_row, ['id' => $check[0]['id']]);
        }
    }

    function get_pids_id($csv_row)
    {
        return $this->db->query("SELECT 
  pids.`id`,p.`id` `id_projects`,i.`id` `id_indicators`,c.`id` `id_commodities`,dt.`id` `id_clusters`,dg.`id` `id_disaggregate_groups` 
FROM
  `project_indicator_disaggregate_sets` pids 
  INNER JOIN `project_indicators` `pi` 
    ON `pi`.`id` = pids.`id_project_indicators` 
  INNER JOIN `indicators` i 
    ON i.`id` = `pi`.`id_indicators` 
   INNER JOIN `projects` p
    ON p.`id` = `pi`.`id_projects` 
  INNER JOIN `disaggregate_sets` ds 
    ON ds.`id` = pids.`id_disaggregate_sets` 
  INNER JOIN `disaggregate_tiers` dt 
    ON dt.`id` = ds.`id_disaggregate_tiers` 
  INNER JOIN `disaggregate_groups` dg 
    ON dg.`id` = ds.`id_disaggregate_groups` 
  INNER JOIN `disaggregates` d 
    ON d.`id` = ds.`id_disaggregates` 
  INNER JOIN `units` u 
    ON u.`id` = ds.`unit_id` 
  INNER JOIN `commodities` c 
    ON c.`id` = pids.`id_commodity` 
WHERE i.`name` = '$csv_row[Indicatorname]'
AND p.`name`= '$csv_row[Activity]'
  AND c.`name` = '$csv_row[Commodity]'
  AND dt.`name` = '$csv_row[Cluster]' 
  AND dg.`name` = '$csv_row[DisaggregateGroup]'
  AND d.`name` = '$csv_row[Disaggregate]'
  AND u.`name` = '$csv_row[Unit]' ")->result_array()[0];
    }

    function get_time_sets_id($period, $fiscal_year_name)
    {
        return $this->db->query("SELECT 
  `time_sets`.`id` 
FROM
  `time_sets` 
  JOIN `reporting_periods` 
    ON `reporting_periods`.`id` = `time_sets`.`id_reporting_periods` 
  JOIN `years` 
    ON `years`.`id` = `time_sets`.`id_years` 
WHERE `reporting_periods`.`name` = '$period' 
  AND `years`.`name` = '$fiscal_year_name' ")->row()->id;
    }


}