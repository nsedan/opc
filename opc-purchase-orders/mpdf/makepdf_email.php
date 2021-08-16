<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';
require_once( dirname(__FILE__, 5) . "/wp-load.php"); // Allows access to WP functions

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']); // MPDF instance init


$post = $_SERVER['QUERY_STRING'];
// Purchase order queries
$purchase_order = get_metadata( 'post', $post, 'purchase_order', true );
$get_order_date = get_metadata( 'post', $post, 'order_date', true );
$get_delivery_date = get_metadata( 'post', $post, 'delivery_date', true );
$order_date = date('d M Y', strtotime($get_order_date));
$delivery_date = date('d M Y', strtotime($get_delivery_date));
$additional_information = get_metadata( 'post', $post, 'additional_information', true );
$total_extra_cost = get_metadata( 'post', $post, 'total_extra_cost', true );

$print_supplier = get_metadata( 'post', $post, 'print_supplier', true );
$delivery_address = get_metadata( 'post', $post, 'delivery_address', true );
$supplier_name = get_metadata( 'post', $print_supplier, 'supplier_name', true );
$delivery_name = get_metadata( 'post', $delivery_address, 'supplier_name', true );
$supplier_address_1 = get_post_meta( $print_supplier, 'supplier_address_1', true );
$supplier_address_2 = get_post_meta( $print_supplier, 'supplier_address_2', true );
$supplier_address_3 = get_post_meta( $print_supplier, 'supplier_address_3', true );
$supplier_address_4 = get_post_meta( $print_supplier, 'supplier_address_4', true );
$supplier_postcode = get_post_meta( $print_supplier, 'supplier_postcode', true );
$delivery_address_1 = get_post_meta( $delivery_address, 'supplier_address_1', true );
$delivery_address_2 = get_post_meta( $delivery_address, 'supplier_address_2', true );
$delivery_address_3 = get_post_meta( $delivery_address, 'supplier_address_3', true );
$delivery_address_4 = get_post_meta( $delivery_address, 'supplier_address_4', true );
$delivery_postcode = get_post_meta( $delivery_address, 'supplier_postcode', true );


// Output Start
$output = '<!DOCTYPE html>
		<html xml:lang="en">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
			<title>' . get_bloginfo() . '</title>
		</head>
        <body xml:lang="en">';


ob_start(); ?>
	<style>
		body{padding:0;margin:0;height:100%;width:100%;color:#000;font-family:Arial,Helvetica,sans-serif;font-size:8px;background-color:rgba(0,0,0,.025)}
		.logo-img{padding-left:25rem;width:30rem}.header-table{border-spacing:0}#table-title{margin-top:4rem}.bold{font-weight: bold}
		.product-table{font-size:9px;margin-bottom:1rem;margin-left:auto;margin-right:auto;color:#212529;border-spacing:0;border:.25px solid #333}
		.product-table td,.product-table th{vertical-align:top;border:.25px solid #333;text-align:center;padding:2px 10px;vertical-align: middle}
		.pt2{padding-top:2rem}.pt3{padding-top:3rem}.w13{width:13%}.w20{width:20%}h1,h2{background:#c3d82f;padding:10px 5px;}
		.header-table td{font-size:20px}.product-table tbody tr:nth-child(4n+2),.product-table tbody tr:nth-child(4n+1){background:#E8E8E8;}
	</style>


		<?php 
		if ( $total_extra_cost != '' ){
			$total_extra_cost = '£'.number_format((float)$total_extra_cost, 2, '.', ',');
		} else { $total_extra_cost = '£0.00';}
		?>
	
		<h1> Purchase Order #<?= $purchase_order ?></h1>
		<table class="header-table">
			<tbody>
				<tr>
					<td class="w13 bold">Order Date</td>
					<td class="w20"><?= $order_date ?></td>
					<td class="w13 bold">Delivery Date</td>
					<td class="w20"><?= $delivery_date ?></td>
					<td rowspan="8"><img class="logo-img" src="../static/img/logo-trans.png"></td>
				</tr>
				<tr>
					<td class="pt2 bold">Supplier</td>
					<td class="pt2"><?= $supplier_name ?></td>
					<td class="pt2 bold">Deliver To</td>
					<td class="pt2"><?= $delivery_name ?></td>
				</tr>
				<tr>
					<td></td>
					<td><?= $supplier_address_1 ?></td>
					<td></td>
					<td><?= $delivery_address_1 ?></td>
				</tr>
				<tr>
					<td></td>
					<td><?= $supplier_address_2 ?></td>
					<td></td>
					<td><?= $delivery_address_2 ?></td>
				</tr>
				<tr>
					<td></td>
					<td><?= $supplier_address_3 ?></td>
					<td></td>
					<td><?= $delivery_address_3 ?></td>
				</tr>
				<tr>
					<td></td>
					<td><?= $supplier_address_4 ?></td>
					<td></td>
					<td><?= $delivery_address_4 ?></td>
				</tr>
				<tr>
					<td></td>
					<td><?= $supplier_postcode ?></td>
					<td></td>
					<td><?= $delivery_postcode ?></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td class="pt3 bold">Extra Costs</td>
					<td class="pt3"><?= $total_extra_cost ?></td>
				</tr>
				<tr>
					<td colspan="4" class="bold"><?php if( $additional_information ){ echo 'Notes'; }?></td>
				</tr>
				<tr>
					<td colspan="4" style="text-align: justify"><?= $additional_information ?></td>
					<td></td>
				</tr>
			</tbody>
		</table>
		<?php $output .= ob_get_clean();

	// POLines
	$output .= '<h2 id="table-title">Products</h2>
			<table class="product-table">
				<thead>
					<tr>
						<th>Product</th>
						<th>Per Pack</th>
						<th>Per Carton</th>
						<th>Face</th>
						<th>Material</th>
						<th>Reverse</th>
						<th>Coating</th>
						<th>Finishes</th>
						<th>Packing</th>
						<th>Flat Size</th>
						<th>Folded Size</th>
						<th>Unit Cost</th>
						<th>Qty Req.</th>
						<th>Total Cost</th>
					</tr>
				</thead>
				<tbody>';

	$products = get_metadata( 'post', $post, 'products', true );
	$sum_qty_req = 0;
	$sum_cost = 0;

	for ($p = 0; $p < $products; ++$p){
		$product_id = get_metadata( 'post', $post, 'products_'.$p.'_product_code', true );
		$product_info = wc_get_product($product_id);

		//Products table fields
		$sku = $product_id ? $product_info->get_sku() : 'NOT FOUND';
		$product_name = get_metadata( 'post', $post, 'products_'.$p.'_name', true );
		$product_per_pack = get_metadata( 'post', $post, 'products_'.$p.'_per_pack', true );
		$product_per_carton = get_metadata( 'post', $post, 'products_'.$p.'_per_carton', true );
		$product_qty_req = get_metadata( 'post', $post, 'products_'.$p.'_quantity_requested', true );
		$product_unit_cost = get_metadata( 'post', $post, 'products_'.$p.'_unit_cost', true );
		$product_total_cost = get_metadata( 'post', $post, 'products_'.$p.'_total_cost', true );
		$product_flat_size = get_metadata( 'post', $post, 'products_'.$p.'_flat_size', true );
		$product_folded_size = get_metadata( 'post', $post, 'products_'.$p.'_folded_size', true );
		$product_finishes = get_metadata( 'post', $post, 'products_'.$p.'_finishes', true );
		$product_packing = get_metadata( 'post', $post, 'products_'.$p.'_packing', true );
		$product_face = get_metadata( 'post', $post, 'products_'.$p.'_face', true );
		$product_material = get_metadata( 'post', $post, 'products_'.$p.'_material', true );
		$product_reverse = get_metadata( 'post', $post, 'products_'.$p.'_reverse', true );
		$product_coating = get_metadata( 'post', $post, 'products_'.$p.'_coating', true );

		//PO Sums
		$sum_qty_req += $product_qty_req;
		$sum_cost += $product_total_cost;


		$output .= '<tr>
					<td>'.$sku.'</td>
					<td rowspan="2">'.$product_per_pack.'</td>
					<td rowspan="2">'.$product_per_carton.'</td>
					<td rowspan="2">'.$product_face.'</td>
					<td rowspan="2">'.$product_material.'</td>
					<td rowspan="2">'.$product_reverse.'</td>
					<td rowspan="2">'.$product_coating.'</td>
					<td rowspan="2">'.$product_finishes.'</td>
					<td rowspan="2">'.$product_packing.'</td>
					<td rowspan="2">'.$product_flat_size.'</td>
					<td rowspan="2">'.$product_folded_size.'</td>
					<td rowspan="2">£'.$product_unit_cost.'</td>
					<td rowspan="2">'.$product_qty_req.'</td>
					<td rowspan="2">£'.number_format((float)$product_total_cost, 2, '.', ',').'</td>
				</tr>
				<tr>
					<td>'.$product_name.'</td>
				</tr>';
	}

	$output .= '<tr>
					<th colspan="12"></th>
					<td><strong>'.$sum_qty_req.'</strong></td>
					<td><strong>£'.number_format((float)$sum_cost, 2, '.', ',').'</strong></td>
				</tr>
			</tbody>
			</table>';



$output .= '</body></html>';
// Output End

// Header and footer
$header = '<div style="font-weight: bold; font-size: 8pt; font-style: italic; text-align: right;">{DATE d.m.Y}</div><hr>';
$footer = '<hr><div style="font-weight: bold; font-size: 8pt; font-style: italic; text-align: center;">{PAGENO} / {nb} </div>';


$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);
$mpdf->WriteHTML( $output );
$pdf = $mpdf->Output('', 'S');

sendEmail( $pdf, $post );

function sendEmail( $pdf, $post ){
	$mail = new PHPMailer(true);

	// File name change to post date creation
	$get_order_date = get_metadata( 'post', $post, 'order_date', true );
	$order_date = date('Y-m-d', strtotime($get_order_date));
	$pdf_filename = $order_date . '.pdf';

	// Settings queries
	$sender_email = get_field('sender_email', "option");
	$sender_password = get_field('sender_password', "option");
	$from_email = get_field('from_email', "option");
	$smtp_host = get_field('smtp_host', "option");
	$port_email = get_field('port_email', "option");
	$tls_email = get_field('tls_email', "option");

	// PO email queries
	$to_email = get_metadata( 'post', $post, 'to_email', true );
	$cc1_email = get_metadata( 'post', $post, 'cc1_email', true );
	$cc2_email = get_metadata( 'post', $post, 'cc2_email', true );
	$cc3_email = get_metadata( 'post', $post, 'cc3_email', true );
	$subject_email = get_metadata( 'post', $post, 'subject_email', true );
	$body_email = get_metadata( 'post', $post, 'body_email', true );


	try {
		//Server settings
		$mail->SMTPDebug = 0;                      					// Enable verbose debug output
		$mail->isSMTP();                                            // Send using SMTP
		$mail->Host       = $smtp_host;                    			// Set the SMTP server to send through
		$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
		$mail->Username   = $sender_email;           				// SMTP username - Email address needs unsecure apps available
		$mail->Password   = $sender_password;                     	// SMTP password
		$mail->SMTPSecure = $tls_email;         					// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
		$mail->Port       = $port_email;                            // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

		//Recipients
		$mail->setFrom($sender_email, $from_email);
		$mail->addAddress($to_email);
		$mail->addBCC($sender_email);
		if($cc1_email){ $mail->addCC($cc1_email); }
		if($cc2_email){ $mail->addCC($cc2_email); }
		if($cc3_email){ $mail->addCC($cc3_email); }

		// Attachments
		$mail->addStringAttachment($pdf, $pdf_filename);

		// Content
		$mail->isHTML(true);
		$mail->Subject = $subject_email;
		$mail->Body    = $body_email;
		$mail->AltBody = strip_tags($body_email);

		$mail->send();

		// Redirect to purchase orders page
		$url = get_admin_url( null, '', 'admin' ) . 'edit.php?post_type=purchase_orders';
		echo "<script type='text/javascript'>window.location.replace('$url');</script>";

	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
}


exit;

?>