<?php
#
###################################################################
#################
# class ShipTrack
# provide the carrier and number, class returns an HTML link to the carrier's site.
# 04/12/2002
# version - 0.5 beta
# Tom Melendez ShipTrack@supertom.com
# http://www.supertom.com/code/
# 
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU Lesser General Public License as
# published by the Free Software Foundation, either version 3 of the
# License, or (at your option) any later version.
#
# This program is distributed in the hope that it will be useful, but
# WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
# General Public License for more details.
#
# You should have received a copy of the GNU Lesser General Public
# License along with this program. If not, see
# <http://www.gnu.org/licenses/>.
#
#################
# Carriers Currently Supported (these are the literal carrier codes used by the class - more on way!):
# "not tested" means not used with valid shipping numbers, the links do work (some sites are slow, though!).
#
## UPS 		    UPS
## FEDEX 		FEDEX
## ROADWAY   	Roadway
## BAX    		BAX Global
## NEWPENN    	NEW PENN
## ABF   		ABF
## REDSTAR		Red Star
## YELLOW		Yellow
## DHL		    DHL
## FFE		    Fedex Freight East
## EMERY		Emery
## GOD		    G.O.D.
## OLDDOMINION	Old Dominion
## USPS		    US Post Office (not tested)
## CCX		    CCX (Cargo Connect - not tested) (must be in following format - AIRLINECODE_AIRWAYPREFIX_AIRWAYNUMBER
#               example: EI_053_12345675
#               see the ccx.txt file for more information
##
### PLEASE SEND MORE SHIPPING URLs, AS WELL AS SAMPLE TRACKING NUMBERS! ########
#
# Description of Usage:
#
#    $MyLink = new ShipTrack();
#    $MyLink->PrintLink($CARRIER,$TRACKING_NUMBER,$LINKTEXT="",$SHIPPING_TYPE="",$OPENWINDOW="",$EXTRACODE="")
#
# CARRIER, NUMBER required
# LINKTEXT
#	1=Carrier Code
#	2="Track"
#	Otherwise, whatever text you provide - this could also be valid HTML - <font face=\"arial\"><B>FEDEX</B></font>
#
# SHIPPING_TYPE - NOT CURRENTLY USED
# 	Will be Pro Number, etc.  Default will be pro number
#
# OPENWINDOW
#	name of frame to open window in:
#		examples are:
#		_blank
#		_parent
#		_top
#
#	Whatever you put here will be printed in the tag
#	If blank, target tag is omitted
#
# EXTRACODE
#	Anything you put here will be printed inside the <a href link tag, after the target attribute, must be valid code
#   must include a preceding space, and code must be properly escaped.
#
#
#    Actual Sample Usage:
#    include "shiptrack.inc";
#    $MyLink = new ShipTrack();
#    $MyLink->PrintLink("UPS","1234324324","1","","_blank");
#    $MyLink->PrintLink("FEDEX","22223333","Federal Express","","_blank");
#
#
###################################################################

    class ShipTrack {

        var $CARRIER                ="";
        var $TRACKING_NUMBER        ="";
        var $LINKTEXT               ="";
        var $SHIPPING_TYPE          ="";
        var $OPENWINDOW             ="";
        var $EXTRACODE              ="";
        var $DEBUG                  =0; //set to 1 to print debug information

        function ShipTrack()
        {
            $this->CARRIER="";
            $this->TRACKING_NUMBER="";
            $this->SHIPPING_TYPE="";
            $this->OPENWINDOW="";
            $this->EXTRACODE="";

        }

        ##### Public Methods #######

        function PrintLink($carrier,$tracking_number,$linktext="",$shipping_type="",$openwindow="",$extracode="")
        {
            if ($this->DEBUG) {
                echo "In Func PrintLink:<br>";
                $this->Debug($carrier,$tracking_number,$linktext,$shipping_type,$openwindow,$extracode);
            }
            echo $this->ReturnLink($carrier,$tracking_number,$linktext,$shipping_type,$openwindow,$extracode);
        }

        function ReturnLink($carrier,$tracking_number,$linktext="",$shipping_type="",$openwindow="",$extracode="")
        {
            if ($this->DEBUG) {
                echo "In Func ReturnLink:<br>";
                $this->Debug($carrier,$tracking_number,$linktext,$shipping_type,$openwindow,$extracode);
            }
            $this->Initialize($carrier,$tracking_number,$linktext,$shipping_type,$openwindow,$extracode);
            return $this->MakeLink();

        }



        ##### Private Methods #######
        function Initialize($carrier,$tracking_number,$linktext="",$shipping_type="",$openwindow="",$extracode="")
        {


            $this->CARRIER="";
            $this->TRACKING_NUMBER="";
            $this->SHIPPING_TYPE="";
            $this->OPENWINDOW="";
            $this->EXTRACODE="";

            $this->CARRIER=$carrier;
            $this->TRACKING_NUMBER=$tracking_number;
            $this->SetLinkText($linktext);
            $this->SetOpenWindow($openwindow);
            $this->SHIPPING_TYPE=$shipping_type;
            $this->EXTRACODE=$extracode;

        }

        function SetLinkText($linktext="")
        {
                $this->LINKTEXT="";

                if (!strlen($linktext)) {
                    $this->LINKTEXT=$this->CARRIER;
                }
                else {
                    switch($linktext) {
                        case 1:
                            $this->LINKTEXT=$this->CARRIER;
                            break;

                        case 2:
                            $this->LINKTEXT="Track";

                            break;
                        default:
                            $this->LINKTEXT=$linktext;

                    }
                }

        }

        function SetOpenWindow($openwindow="")
        {
                $this->OPENWINDOW="";

                if (!strlen($openwindow)) {
                    $this->OPENWINDOW="";
                }
                else {
                    $this->OPENWINDOW=" target=\"$openwindow\"";
                }

        }



        function MakeLink()
        {
            $link="";
            switch(strtoupper($this->CARRIER)) {
                case "UPS":
                      $link="<a href=\"http://wwwapps.ups.com/etracking/tracking.cgi?tracknums_displayed=1&TypeOfInquiryNumber=T&HTMLVersion=4.0&InquiryNumber1=".$this->TRACKING_NUMBER."&track=Track\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
    			break;

                case "FEDEX":
                     $link="<a href=\"http://www.fedex.com/cgi-bin/tracking?tracknumbers=".$this->TRACKING_NUMBER."&language=english&action=track&cntry_code=us\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "ROADWAY":  //roadway
                     $link="<a href=\"http://www.quiktrak.roadway.com/cgi-bin/quiktrak?type=0&pro0=".$this->TRACKING_NUMBER."&zip0=&pro1=&zip1=&pro2=&zip2=&pro3=&zip3=&pro4=&zip4=&pro5=&zip5=&pro6=&zip6=&pro7=&zip7=&pro8=&zip8=&pro9=&zip9=&auth=0qmsUAkRe7M&submit.x=6&submit.y=22\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "BAX":  //BAX
                    $link = "<a href=\"http://www.baxglobal.com/win-cgi/cwstrack.dll?trackby=H&trackbyno=".$this->TRACKING_NUMBER."&org=&dst=&mnth1=&day1=&year1=&mnth2=&day2=&year2=&submit1.x=14&submit1.y=18\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "NEWPENN":  //NEW PENN
                    $link = "<a href=\"http://www.newpenn.com/npweb/tracking.txt/process?p_input_typ=1&p_trak_1=".$this->TRACKING_NUMBER."\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "ABF": //ABF
                    $link = "<a href=\"http://www.abfs.com/trace/abftrace.asp?blnBOL=TRUE&blnPO=TRUE&blnShipper=TRUE&blnConsignee=TRUE&blnABFGraphic=TRUE&blnOrigin=TRUE&blnDestination=TRUE&RefType=a&bhcp=1&Ref=".$this->TRACKING_NUMBER."\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "REDSTAR":  //Red Star
                    $link="<a href=\"http://www.usfc.com/tools/truckingresultsdetail.asp?txtLookupNumber=".$this->TRACKING_NUMBER."&radLookupNumberType=H&SearchType=1\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "YELLOW":  //Yellow
                    $link = "<a href=\"http://www2.yellowcorp.com/dynamic/services/servlet?diff=protrace&CONTROLLER=com.yell.ec.inter.yfsgentracking.http.controller.TrackPro&DESTINATION=%2Fyfsgentracking%2Ftrackingresults.jsp&SOURCE=%2Fyfsgentracking%2Ftrackpro.jsp&FBNUM2=&FBNUM3=&FBNUM4=&FBNUM5=&FBNUM1=".$this->TRACKING_NUMBER."\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "DHL":  //DHL
                     $link="<a href=\"http://www.dhl.com/cgi-bin/tracking.pl?AWB=".$this->TRACKING_NUMBER."&TID=CP_ENG\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "FFE":  //Fedex Freight East
                     $link="<a href=\"http://www.fedexfreight.fedex.com/protrace.jsp?as_type=PRO&as_pro=".$this->TRACKING_NUMBER."\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "EMERY":  //Emery
                     $link="<a href=\"http://www.emeryworld.com/tracking/trackformaction.asp?optTYPE=SHIPNUM&PRO1=".$row->TRACKING_NUMBER."\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "GOD":  //G.O.D.
                     $link="<a href=\"http://www.1800dialgod.com/quickpro.asp?cat=search&Prono=".$this->TRACKING_NUMBER."\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "OLDDOMINION":  //Old Dominion
                     $link="<a href=\"http://www.odfl.com/trace/Trace.jsp?Type=P&pronum=".$this->TRACKING_NUMBER."\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "USPS":        //US Post Office
                     $link="<a href=\"http://trkcnfrm1.smi.usps.com/netdata-cgi/db2www/cbd_243.d2w/output?CAMEFROM=OK&strOrigTrackNum=".$this->TRACKING_NUMBER."\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";
                break;

                case "CCX":        //CCX
                     list($apc,$awp,$awn) = split("_",$this->TRACKING_NUMBER);
                     $link="<a href=\"http://www.cargoserv.com/tracking.asp?Carrier=$apc&Pfx=$awp&Shipment=$awn\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";

                     # THIS URL IS also valid - if you are looking to parse the info, this is the better URL to use
                     # $link="<a href=\"http://www.ccx.com/cx/msfsr?ccd=$apc&awn=$awn&awp=$awp&id=R88888888\"".$this->OPENWINDOW.$this->EXTRACODE.">".$this->LINKTEXT."</a>";

                break;

		case "POSTEN.NO": // Posten Norge AS
		     $link="<a href='http://sporing.posten.no/sporing.html?q={$this->TRACKING_NUMBER}'{$this->OPENWINDOW}{$this->EXTRACODE}>{$this->LINKTEXT}</a>";
		break;

                default:
                    echo "Carrier Code <b>".$this->CARRIER."</B> Not Found";
            }
            return $link;

        }

        function Debug($carrier,$tracking_number,$linktext="",$shipping_type="",$openwindow="",$extracode="")
        {
            echo "Carrier: ".$carrier ."<br>";
            echo "Tracking Number: ".$tracking_number."<br>";
            echo "LinkText: ".$linktext ."<br>";
            echo "Shipping Type: ".$shipping_type."<br>";
            echo "Open Window: ".$openwindow."<br>";
            echo "ExtraCode: ".$extracode."<br>";

        }


    }



# Usage

# $MyLink = new ShipTrack();

# $MyLink->PrintLink("UPS","1234324324","1","","_top"," (your valid code here)");
# echo "<br>";
# $MyLink->PrintLink("FEDEX","22223333","<font face=\"arial\"><B>FEDEX</B></font>","","_blank");
# echo "<br>";
# $MyLink->DEBUG=1;
# $MyLink->PrintLink("CCX","EI_053_12345675","1","","_blank");


?>
