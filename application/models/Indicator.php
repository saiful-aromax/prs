<?php
/** 
	* Indicator Model Class.
	* @pupose		Manage Indicators information
	*		
	* @filesource	\app\model\Indicator.php
	* @version      $Revision: 1 $
	* @author       $Author: Saiful Islam $
	* @lastmodified $Date: 2017-05-22 $
*/
class Indicator extends MY_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function get_list($offset, $limit, $cond)
    {

        if ($offset == 0 && $limit == 0) {
            $this->db->select('count(0) as num');
        } else {
            $this->db->select('indicators.*');
        }

        $this->db->from('indicators');
        if (!empty($cond['name'])) {
            $where = "( indicators.name LIKE '%{$cond['name']}%')";
            $this->db->where($where);
        }
        if ($offset == 0 && $limit == 0) {
            return $this->db->get()->row()->num;
        }
        $this->db->limit($limit, $offset);
        $this->db->order_by('indicators.name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Reads data of specific indicators
     * @author  :   Sara
     * @uses    :   To  read data of specific indicators
     * @access  :   public
     * @param   :   int $id
     * @return  :   array
     */
    function get_info_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('indicators');
        $this->db->where('id', $id);
        return $this->db->get()->row();
    }

    function add($data)
    {
        return $this->db->insert('indicators', $data);
    }

    /**
     * Reads data of specific indicators
     * @author  :   Sara
     * @uses    :   To  read data of specific indicators
     * @access  :   public
     * @param   :   int $id
     * @return  :   array
     */
//    function get_list_by_id($id)
//    {
//        $query=$this->db->where('tiers', array('id' => $id));
//        return $query->row();
//    }

    function edit($id, $data)
    {
        //echo '<pre>'; print_r($data); die;
        return $this->db->update('indicators', $data, ['id' => $id]);
    }

    /**
     * Deletes particular data
     * @author  :   Sara
     * @uses    :   To delete particular data
     * @access  :   public
     * @param   :   int $id, $delete_by
     * @return  :   boolean
     */
    function delete($id)
    {
        return $this->db->delete('indicators', ['id' => $id]);
    }

    function get_item(){
        $this->db->select('*');
        $this->db->from('indicators');
        $query =  $this->db->get();
        $result_array = [];
        foreach ($query->result() as $row){
            $result_array[$row->id] =$row->name;
        }
        return $result_array;
    }
}
