<?php

//*****************************************************************************
// Filename: SetupRequestClass.php
//
// Author: Robert Tran, BBS AS
// Copyright (C) 2008, BBS AS.
//
// Last Modified: rot
//     $Author: rot $ $Date: 2008-10-21 09:00:00 +0100 (to, 01 nov 2008) $Revision$
//     $Changed: rot $ $Date: 2008-10-21 09:00:00 +0100 (to, 01 nov 2008) $Revision$
//*****************************************************************************

// -----------------------------------------------------------------------------
// short description
// -----------------------------------------------------------------------------
//
// Base class SetupRequest
//
// 
// 
//
// =============================================================================


class SetupRequest {
  
	public $Amount;
	public $CurrencyCode;
	public $CustomerEmail;
	public $CustomerPhoneNumber;
	public $Description;
	public $Language;
	public $OrderDescription;
	public $OrderNumber;
	public $PanHash;
	public $RecurringExpiryDate;
	public $RecurringFrequency;
	public $RecurringType;
	public $RedirectUrl;
	public $ServiceType;
	public $SessionId;
	public $TransactionId;


	function SetupRequest (
							$Amount,
							$CurrencyCode,
							$CustomerEmail,
							$CustomerPhoneNumber,
							$Description,
							$Language,
							$OrderDescription,
							$OrderNumber,
							
							$PanHash,                    /* PanHash == Personal Account Number HASH */
							$RecurringExpiryDate,        /* Subscription expired date               */ 
							$RecurringFrequency,         /* number of days between captures         */
							$RecurringType,              /* S: store ; R: Recurring                 */
							
							$RedirectUrl,
							$ServiceType,                /* B : BBS Hosted UI ; M : Merchant Hosted UI ; C : Call Center Solution */ 
							$SessionId,
							$TransactionId
						   ) {
	
		$this->Amount                   = $Amount             ;
		$this->CurrencyCode             = $CurrencyCode       ;
		$this->CustomerEmail            = $CustomerEmail      ;
		$this->CustomerPhoneNumber      = $CustomerPhoneNumber;
		$this->Description              = $Description        ;
		$this->Language                 = $Language           ;
		$this->OrderDescription         = $OrderDescription   ;
		$this->OrderNumber              = $OrderNumber        ;
		
		$this->PanHash                  = $PanHash            ;
		$this->RecurringExpiryDate      = $RecurringExpiryDate;
		$this->RecurringFrequency       = $RecurringFrequency ;
		$this->RecurringType            = $RecurringType      ;
		
		$this->RedirectUrl              = $RedirectUrl        ;
		$this->ServiceType              = $ServiceType        ;
		$this->SessionId                = $SessionId          ;
		$this->TransactionId            = $TransactionId      ;
	}
}


?>
