<?php
					 $start_date = date ( 'Y-m-d', strtotime ( "0 days" ) );
		  			 $end_date = date ( 'Y-m-d', strtotime ( "0 days" ) );      
       
	   				 $recBalanceSql = "SELECT uniqueCode, ROUND(SUM(quantity*unitPrice),2) AS balance FROM(
                		SELECT DISTINCT td.componentId, td.accountId, td.itemId, td.transactionId, a.uniqueCode, td.quantity, td.unitPrice
                		FROM transaction_detail td
                		INNER JOIN transaction t ON (td.transactionId = t.componentId)
                		INNER JOIN account a ON (td.accountId = a.componentId)
                		INNER JOIN transaction_detail tdc ON (td.transactionId = tdc.transactionId AND tdc.`type` = 1)
                		INNER JOIN account ac ON (tdc.accountId = ac.componentId)
                		WHERE td.`type` = -1 AND ac.category1 = '" . Applicationconst::ACCOUNT_CAT1_ASSET . "' AND ac.category2 = '" . Applicationconst::ACCOUNT_CAT2_CURRENT_ASSET . "'
                		AND t.tdate BETWEEN '".$start_date."' AND '".$end_date."'
                		) a
                		GROUP BY a.uniqueCode";
              //  echo $recBalanceSql;                

                    $recBalancequery = $this->db->query($recBalanceSql);
                    $recBalanceData = $recBalancequery->result ();
					 $totalRec = 0.0;	
					foreach ( $recBalanceData as $value ) { $totalRec += $value->balance; } 
					echo $present_today;
					?>