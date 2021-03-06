<?php
defined('BASEPATH') OR exit('');

/**
 * Description of Transaction
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 27th RabAwwal, 1437A.H (8th Jan., 2016)
 */
class Transaction extends CI_Model{
    public function __construct() {
        parent::__construct();
    }
    
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Get all transactions
     * @param type $orderBy
     * @param type $orderFormat
     * @param type $start
     * @param type $limit
     * @return boolean
     */
    public function getAll($orderBy, $orderFormat, $start, $limit){
        $this->db->select('transactions.ref, transactions.totalMoneySpent, transactions.modeOfPayment, transactions.staffId,
                transactions.transDate, transactions.lastUpdated, transactions.amountTendered, transactions.changeDue,
                CONCAT_WS(" ", admin.first_name, admin.last_name) as "staffName"');
        $this->db->select_sum('transactions.quantity');
        $this->db->join('admin', 'transactions.staffId = admin.id', 'LEFT');
        $this->db->limit($limit, $start);
        $this->db->group_by('ref');
        $this->db->order_by($orderBy, $orderFormat);
        
        //SELECT ref, SUM(quantity) as 'quantity', totalMoneySpent, modeOfPayment,
        //customerName, staffId, transDate, lastUpdated, amountTendered, changeDue FROM `transactions` GROUP BY (ref)
        
        $run_q = $this->db->get('transactions');
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
            return FALSE;
        }
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * 
     * @param type $_iN item Name
     * @param type $_iC item Code
     * @param type $desc Desc
     * @param type $q quantity bought
     * @param type $_up unit price
     * @param type $_tp total price
     * @param type $_tas total amount spent
     * @param type $_at amount tendered
     * @param type $_cd change due
     * @param type $_mop mode of payment
     * @param type $_tt transaction type whether (sale{1} or return{2})
     * @param type $ref
     * @param float $_va VAT Amount
     * @param int $_vp VAT Percentage
     * @return boolean
     */
    public function add($_iN, $_iC, $desc, $q, $_up, $_tp, $_tas, $_at, $_cd, $_mop, $_tt, $ref, $_va, $_vp){
        $data = ['itemName'=>$_iN, 'itemCode'=>$_iC, 'description'=>$desc, 'quantity'=>$q, 'unitPrice'=>$_up, 'totalPrice'=>$_tp,
            'amountTendered'=>$_at, 'changeDue'=>$_cd, 'modeOfPayment'=>$_mop, 'transType'=>$_tt, 
            'staffId'=>$this->session->admin_id, 'totalMoneySpent'=>$_tas, 'ref'=>$ref, 'vatAmount'=>$_va,
            'vatPercentage'=>$_vp];
        
        //set the datetime based on the db driver in use
        $this->db->platform() == "sqlite3" 
                ? 
        $this->db->set('transDate', "datetime('now')", FALSE) 
                : 
        $this->db->set('transDate', "NOW()", FALSE);
        
        $this->db->insert('transactions', $data);
        
        if($this->db->affected_rows()){
            return $this->db->insert_id();
        }
        
        else{
            return FALSE;
        }
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Primarily used t check whether a prticular ref exists in db
     * @param type $ref
     * @return boolean
     */
    public function isRefExist($ref){
        $q = "SELECT DISTINCT ref FROM transactions WHERE ref = ?";
        
        $run_q = $this->db->query($q, [$ref]);
        
        if($run_q->num_rows() > 0){
            return TRUE;
        }
        
        else{
            return FALSE;
        }
    }
    
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    public function transSearch($value){
        $this->db->select('transactions.ref, transactions.totalMoneySpent, transactions.modeOfPayment, transactions.staffId,
                transactions.transDate, transactions.lastUpdated, transactions.amountTendered, transactions.changeDue,
                CONCAT_WS(" ", admin.first_name, admin.last_name) as "staffName"');
        $this->db->select_sum('transactions.quantity');
        $this->db->join('admin', 'transactions.staffId = admin.id', 'LEFT');
        $this->db->like('ref', $value);
        $this->db->or_like('itemName', $value);
        $this->db->or_like('itemCode', $value);
        $this->db->group_by('ref');
        
        $run_q = $this->db->get('transactions');
        
        if($run_q->num_rows() > 0){
            return $run_q->result();
        }
        
        else{
            return FALSE;
        }
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Get all transactions with a particular ref
     * @param type $ref
     * @return boolean
     */
    public function gettransinfo($ref){
        $q = "SELECT * FROM transactions WHERE ref = ?";
        
        $run_q = $this->db->query($q, [$ref]);
        
        if($run_q->num_rows() > 0){
            return $run_q->result_array();
        }
        
        else{
            return FALSE;
        }
    } 
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */

    /**
     * selects the total number of transactions done so far
     * @return boolean
     */
    public function totalTransactions(){
        $q = "SELECT count(DISTINCT REF) as 'totalTrans' FROM transactions";
       
        $run_q = $this->db->query($q);
       
        if($run_q->num_rows() > 0){
            foreach($run_q->result() as $get){
                return $get->totalTrans;
            }
        }

        else{
            return FALSE;
        }
    }



   /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Calculates the total amount earned today
     * @return boolean
     */
    public function totalEarnedToday(){
        $q = "SELECT SUM(totalPrice) as 'totalEarnedToday' FROM transactions WHERE DATE(transDate) = CURRENT_DATE";
       
        $run_q = $this->db->query($q);
       
        if($run_q->num_rows() > 0){
            foreach($run_q->result() as $get){
                return $get->totalEarnedToday;
            }
        }

        else{
            return FALSE;
        }
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
	
	
    //Not in use yet
    public function totalEarnedOnDay($date){
        $q = "SELECT SUM(totalPrice) as 'totalEarnedToday' FROM transactions WHERE DATE(transDate) = {$date}";
       
        $run_q = $this->db->query($q);
       
        if($run_q->num_rows() > 0){
            foreach($run_q->result() as $get){
                return $get->totalEarnedToday;
            }
        }

        else{
            return FALSE;
        }
    }
    
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
}
