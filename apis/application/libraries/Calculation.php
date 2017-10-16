<?php

class Calculation {

    var $FINANCIAL_ACCURACY=1.0e-6;
    var $FINANCIAL_MAX_ITERATIONS=100;

    public function __construct(){

    }


    

    public function oneTimeCalcInput($AIR){   
        $details_arr = array();
        $MIR = ((pow((1 + ($AIR / 100)), 1 / 12) - 1) * 100);
        $details_arr['mir']     = number_format(round($MIR, 3), 3);                        
        return $details_arr;
    }


    public function getAllTax()
    {
        $CI =& get_instance();
        $CI->load->model('api_' . $CI->config->item('test_api_ver') . '/admin/product_model', 'product');

        $TAX=$CI->product->getConfigTaxRate();
        //pre($TAX,1);
        return $TAX;


    }


    public function oneTimeCalcDisbursement($P, $LPFP, $UFP){   
        //$CI =& get_instance();
        $TAX = $this->getAllTax();
      
        $details_arr = array();

        $STR    = $TAX['str'];
        $SBCR   = $TAX['sbcr'];
        $KKCR   = $TAX['kkcr'];

        $LPFA   = ($P * ($LPFP / 100));            
        $ARL    = ($P - $LPFA);
        $UFA    = ($P * ($UFP / 100));        

        $TST    = ($STR + $SBCR + $KKCR);
        $RUFA   = ($UFA / ((100 + $TST) / 100));
        $STUFA  = ($UFA - $RUFA);

        //$TFDB   = ($P - ($LPFA + $UFA));
        $TFDB   = ($LPFA + $UFA);
        $DA     = ($P - $TFDB);

        $details_arr['arl']     = round($ARL, 0);                          
        $details_arr['lpfa']    = round($LPFA, 0);                          
        $details_arr['ufa']     = round($UFA, 0);                          
        $details_arr['tst']     = number_format(round($TST, 2), 2);                          
        $details_arr['stufa']   = number_format(round($STUFA, 2), 2);                          
        $details_arr['rufa']    = number_format(round($RUFA, 2), 2);                         
        $details_arr['tfdb']    = round($TFDB, 0);                          
        $details_arr['da']      = round($DA, 0);                          

        return $details_arr;
    }

    
    public function oneTimeCalcLenderFee($P, $LFR, $NPM, $MIR, $TST){   
	    $details_arr = array();

	    $RA     = ($P * (pow((1 + ($MIR / 100)), $NPM)));
	    $LFA    = (($RA / 100) * $LFR);
	    $RLF    = ($LFA / ((100 + $TST) / 100));
	    $STLF   = ($LFA - $RLF);
	    $PL     = ($RA - $LFA);

	    $details_arr['lfa']     = round($LFA, 0);                          
	    $details_arr['stlf']    = number_format(round($STLF, 2), 2);                           
	    $details_arr['rlf']     = number_format(round($RLF, 2), 2);                         
	    $details_arr['ra']      = round($RA, 0);                          
	    $details_arr['pl']      = round($PL, 0);                          

		return $details_arr;
    }

    
    public function oneTimePenaltyCalc($P, $PFPD, $DPD, $PPRM, $TST, $RA, $LFA){   
	    $details_arr = array();
	    
	    $PFD    = (($PFPD / 100) * $P);
	    $TPF    = ($PFD * $DPD);
	    $PRM    = ($TPF * ($PPRM / 100));
	    $RRP    = ($PRM / ((100 + $TST) / 100));
	    $STRP   = ($PRM - $RRP);
	    $RA_WP  = ($RA + $TPF);
	    $PL_WP  = (($RA - $LFA) + ($TPF - $PRM));

	    $details_arr['pfd']     = round($PFD, 0);                          
	    $details_arr['tpf']     = round($TPF, 0);                           
	    $details_arr['prm']     = number_format(round($PRM, 2), 2);                         
	    $details_arr['rrp']     = number_format(round($RRP, 2), 2);                           
	    $details_arr['strp']    = number_format(round($STRP, 2), 2);                           
	    $details_arr['ra_wp']   = round($RA_WP, 0);                          
	    $details_arr['pl_wp']   = round($PL_WP, 0);                         

	    return $details_arr;
    }


    public function emiCalcInput($P, $AIR, $NPM, $LTY){   
        $details_arr = array();
                      
        $MIR    = ($AIR / $NPM);
        $TP     = ($LTY * $NPM);
        $EMI    = round($this->pmt($MIR, $TP, $P), 0); 
        $EMI_CALCULATOR    = $this->emi_calculator($P, $TP, $EMI, $MIR);

        $details_arr['mir']             = number_format(round($MIR, 3), 3);                          
        $details_arr['emi']             = $EMI;                          
        $details_arr['tp']              = $TP;                          
        $details_arr['emi_calculator']  = $EMI_CALCULATOR;                          

	    return $details_arr;
    }
    

    public function emiCalcDisbursement($P, $LPFP, $UFP){   
        $TAX = $this->getAllTax();

        $details_arr = array();
        $STR    = $TAX['str'];
        $SBCR   = $TAX['sbcr'];
        $KKCR   = $TAX['kkcr'];

        $LPFA   = ($P * ($LPFP / 100));            
        $ARL    = ($P - $LPFA);
        $UFA    = ($P * ($UFP / 100));        

        $TST    = ($STR + $SBCR + $KKCR);
        $RUFA   = ($UFA / ((100 + $TST) / 100));
        $STUFA  = ($UFA - $RUFA);

        $TFDB   = ($LPFA + $UFA);
        $DA     = ($ARL - $UFA);

        $details_arr['arl']     = round($ARL, 0);                          
        $details_arr['lpfa']    = round($LPFA, 0);                          
        $details_arr['ufa']     = round($UFA, 0);                          
        $details_arr['tst']     = number_format(round($TST, 2), 2);                          
        $details_arr['stufa']   = number_format(round($STUFA, 2), 2);                          
        $details_arr['rufa']    = number_format(round($RUFA, 2), 2);                          
        $details_arr['tfdb']    = round($TFDB, 0);                          
        $details_arr['da']      = round($DA, 0);                          
	    
	    return $details_arr;
    }

    
    public function emiCalcLenderFee($EMI, $LFR, $TST){   
        $details_arr = array();

        $RA     = $EMI;
        $LFA    = (($EMI / 100) * $LFR);
        $RLF    = ($LFA / ((100 + $TST) / 100));
        $STLF   = ($LFA - $RLF);
        $PL     = ($EMI - $LFA);

        $details_arr['lfa']     = round($LFA, 0);                          
        $details_arr['stlf']    = number_format(round($STLF, 2), 2);                          
        $details_arr['rlf']     = number_format(round($RLF, 2), 2);                          
        $details_arr['ra']      = round($RA, 0);                          
        $details_arr['pl']      = round($PL, 0); 

	    return $details_arr;
    }


    public function emiPenaltyCalc($OBFPF, $PFPD, $DPD, $PPRM, $TST, $EMI, $LFA, $OUTSTND_BAL){   
        $details_arr = array();      
       
        $FPF    = (($OBFPF / 100) * $OUTSTND_BAL);
        $PFD    = (($PFPD / 100) * $OUTSTND_BAL);
        $TPF    = ($FPF + ($PFD * $DPD));
        $PRM    = ($TPF * ($PPRM / 100));
        $RRP    = ($PRM / ((100 + $TST) / 100));
        $STRP   = ($PRM - $RRP);

        $RA_WP  = ($EMI + $TPF);
        $PL_WP  = (($EMI - $LFA) + ($TPF - $PRM));

        $details_arr['fpf']     = number_format(round($FPF, 1), 1);                            
        $details_arr['pfd']     = number_format(round($PFD, 1), 1);                           
        $details_arr['tpf']     = number_format(round($TPF, 1), 1);                          
        $details_arr['prm']     = number_format(round($PRM, 1), 1);                           
        $details_arr['rrp']     = number_format(round($RRP, 2), 2);                           
        $details_arr['strp']    = number_format(round($STRP, 2), 2);                           
        $details_arr['ra_wp']   = round($RA_WP, 0); 
        $details_arr['pl_wp']   = round($PL_WP, 0);                         

	    return $details_arr;
    }

    function pmt($monthly_interest, $months, $loan) {
       $interest = $monthly_interest / 100;
       $amount = $interest * -$loan * pow((1 + $interest), $months) / (1 - pow((1 + $interest), $months));
       return round($amount, 0);
    }

    function ipmt($monthly_interest_percentage, $outstanding_loanamount) {
       return $monthly_interest_amount = round(($outstanding_loanamount * ($monthly_interest_percentage/100)), 0);
       //return $monthly_interest_amount;
    }

    function ppmt($monthly_interest_amount, $emi_amount) {
       return $monthly_principle_amount = round(($emi_amount - $monthly_interest_amount), 0);
       //return round($monthly_principle_amount, 0);
    }

    function emi_calculator($p, $tp, $emi, $mir){
        $emi_cal_arr = array();
        $outstnd_bal = $p; 
        $paid_bal = 0;

        for($i=0; $i<$tp; $i++){
            $monthly_emi_arr = array();
            $emi_interest = $emi_principal = $paid_bal = 0;

            if($i == 0){
                $paid_bal       = $paid_bal + $emi_principal;            
                $outstnd_bal    = $outstnd_bal - $paid_bal;
                $monthly_emi_arr ['emi_interest'] = $emi_interest;
                $monthly_emi_arr ['emi_principal'] = $emi_principal;
                $monthly_emi_arr ['outstnd_bal'] = $outstnd_bal;

                $emi_cal_arr[]  = $monthly_emi_arr; 
            }

            $emi_interest   = $this->ipmt($mir, $outstnd_bal);
            $emi_principal  = $this->ppmt($emi_interest, $emi);
            $paid_bal       = $paid_bal + $emi_principal;            
            $outstnd_bal    = $outstnd_bal - $paid_bal;

            $monthly_emi_arr ['emi_interest']   = $emi_interest;
            $monthly_emi_arr ['emi_principal']  = $emi_principal;
            $monthly_emi_arr ['outstnd_bal']    = ($outstnd_bal >= 0) ? $outstnd_bal : 0;

            $emi_cal_arr[]  = $monthly_emi_arr; 
        }
        //pre($emi_cal_arr,1);
        return $emi_cal_arr;   
    }

    public function getMIRInterestAdjustment($AIR,$INTEREST){   
        $details_arr = array();
        $cal_int = ($AIR * $INTEREST) /100;
        $calinterest    = number_format($cal_int,1);  
        $calAIR=$AIR-$calinterest;

        return $calAIR;
    }



    public function XIRR($values, $dates, $guess ='')
    { 
        if ((!is_array($values)) && (!is_array($dates))) return null;
        if (count($values) != count($dates)) return null;
        
        // create an initial bracket, with a root somewhere between bot and top
        $x1 = 0.0;
        $x2 = $guess;
        $f1 = $this->XNPV($x1, $values, $dates);
        $f2 = $this->XNPV($x2, $values, $dates);
        
        for ($i = 0; $i < $this->FINANCIAL_MAX_ITERATIONS; $i++)
        {
            if (($f1 * $f2) < 0.0) break;
            if (abs($f1) < abs($f2)) {
                $f1 = $this->XNPV($x1 += 1.6 * ($x1 - $x2), $values, $dates);
            } else {
                $f2 = $this->XNPV($x2 += 1.6 * ($x2 - $x1), $values, $dates);
            }
        }
        if (($f1 * $f2) > 0.0) return null;
        
        $f = $this->XNPV($x1, $values, $dates);
        if ($f < 0.0) {
            $rtb = $x1;
            $dx = $x2 - $x1;
        } else {
            $rtb = $x2;
            $dx = $x1 - $x2;
        }
        
        for ($i = 0;  $i < $this->FINANCIAL_MAX_ITERATIONS; $i++)
        {
            $dx *= 0.5;
            $x_mid = $rtb + $dx;
            $f_mid = $this->XNPV($x_mid, $values, $dates);
            if ($f_mid <= 0.0) $rtb = $x_mid;
            if ((abs($f_mid) < $this->FINANCIAL_ACCURACY) || (abs($dx) < $this->FINANCIAL_ACCURACY)) return $x_mid;
        }
        return null;
    }



    public function XNPV($rate, $values, $dates)
    {
        if ((!is_array($values)) || (!is_array($dates))) return null;
        if (count($values) != count($dates)) return null;
        
        $xnpv = 0.0;
        for ($i = 0; $i < count($values); $i++)
        {
            $xnpv += $values[$i] / pow(1 + $rate,$this->DATEDIFF('day', $dates[0], $dates[$i]) / 365);
        }
        return (is_finite($xnpv) ? $xnpv: null);
    }

    Public function DATEDIFF($datepart, $startdate, $enddate)
    {
        switch (strtolower($datepart)) {
            case 'yy':
            case 'yyyy':
            case 'year':
                $di = getdate($startdate);
                $df = getdate($enddate);
                return $df['year'] - $di['year'];
                break;
            case 'q':
            case 'qq':
            case 'quarter':
                die("Unsupported operation");
                break;
            case 'n':
            case 'mi':
            case 'minute':
                return ceil(($enddate - $startdate) / 60); 
                break;
            case 'hh':
            case 'hour':
                return ceil(($enddate - $startdate) / 3600); 
                break;
            case 'd':
            case 'dd':
            case 'day':
                return ceil(($enddate - $startdate) / 86400); 
                break;
            case 'wk':
            case 'ww':
            case 'week':
                return ceil(($enddate - $startdate) / 604800); 
                break;
            case 'm':
            case 'mm':
            case 'month':
                $di = getdate($startdate);
                $df = getdate($enddate);
                return ($df['year'] - $di['year']) * 12 + ($df['mon'] - $di['mon']);
                break;
            default:
                die("Unsupported operation");
        }
    }



}



?>