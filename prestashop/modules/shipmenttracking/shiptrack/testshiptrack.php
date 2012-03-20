<?
# testshiptrack.php - test use of shiptrack class - note all shipping numbers are not valid.
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
# <http://www.gnu.org/licenses>.
#

    include "shiptrack.inc";
    $MyLink = new ShipTrack();
    $MyLink->PrintLink("UPS","1234324324","1","","_blank","foobar");
    echo "<br>";
    $MyLink->PrintLink("FEDEX","234234234","<font face=\"arial\"><B>FEDEX</B></font>","","_blank");
    echo "<br>";
    $MyLink->DEBUG=1;
    $MyLink->PrintLink("CCX","EI_053_12345675","1","","_blank");

?>
