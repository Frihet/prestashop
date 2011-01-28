<?php

class PdfInvoiceVendor extends Module
{
	function __construct()
	{
		$this->name = 'pdfinvoicevendor';
		$this->tab = 'Invoice';
		$this->version = '0.1';

		parent::__construct();

		$this->displayName = $this->l('Prints vendor info on invoices');
		$this->description = $this->l('Prints vendor info on invoices');
	}
	
	function install()
	{
		if
		(
			parent::install() == false
			OR $this->registerHook('PDFInvoice') == false
		)
			return false;
		return true;
	}

	public function hookPDFInvoice($params)
        {
		global $cookie;

		$params['pdf']->Ln(10);

		$sql = "
		  select
		   pl.name as product,
		   v.title vendor
		  from
		   PREFIX_orders as o
		   join PREFIX_cart_product as cp on
		    o.id_order = {$params['id_order']}
		    and o.id_cart = cp.id_cart
		   join PREFIX_product_lang as pl on
		    cp.id_product = pl.id_product
		    and pl.id_lang = {$cookie->id_lang}
		   join PREFIX_vendor as v on
		    cp.id_vendor = v.id_vendor
		";

                $sql = "
                        select pl.name as product, v.title vendor, ad.phone phone from
                        orders as o join cart_product as cp on o.id_order = {$params['id_order']} and o.id_cart =
                        cp.id_cart join product_lang as pl on cp.id_product = pl.id_product and
                        pl.id_lang = {$cookie->id_lang} join vendor as v on cp.id_vendor = v.id_vendor join
                        address as ad on v.id_customer = ad.id_customer group by product";

	 	$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		$res = Db::getInstance()->ExecuteS(trim($sql));

		if (count($res)) {

			$params['pdf']->SetFont('Arial', 'B', 8);
			$params['pdf']->SetFillColor(240, 240, 240);
			$params['pdf']->Cell(95, 4, Tools::iconv('utf-8', $params['pdf']->encoding(), $this->l("Product")), "B", 0, "L", true);
			$params['pdf']->Cell(55, 4, Tools::iconv('utf-8', $params['pdf']->encoding(), $this->l("Vendor")), "B", 0, "L", true);
                        //   Espen Lyngaas fix for phone number
                        $params['pdf']->Cell(40, 4, Tools::iconv('utf-8', $params['pdf']->encoding(), $this->l("Phone")), "B", 0, "L", true);
			$params['pdf']->Ln();
			$params['pdf']->SetFont('Arial', '', 8);
			$params['pdf']->SetFillColor(255, 255, 255);

			foreach ($res AS $row) {
				$params['pdf']->Cell(95, 4, Tools::iconv('utf-8', $params['pdf']->encoding(), $row['product']), "TB");
				$params['pdf']->Cell(55, 4, Tools::iconv('utf-8', $params['pdf']->encoding(), $row['vendor']), "TB");
                                $params['pdf']->Cell(40, 4, Tools::iconv('utf-8', $params['pdf']->encoding(), $row['phone']), "TB");
				$params['pdf']->Ln();
			}
		}

        }
}

?>
