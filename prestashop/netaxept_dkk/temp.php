<?php

	/**
	 * Betaling med redirect til BBS
	 */
	
	$wsdl = "https://epayment-test.bbs.no/terminal/default.aspx";
	
	//opprette SOAP-kobling
	$client = new SoapClient($wsdl, array('trace' => true,'exceptions' => true ));
	
	
	
	//sende inn oppsettet med soap
	$result = $client->__call('Setup' , array("parameters"=>$params_transaction));
	
	
	
	//hente resultatet
	$result->SetupResult; //resultat lik: <input type="hidden" name="BBSePay_transaction" value="IYIDLFqCAyg8TVNHKzYrU2lnRGF0I1ZFUis1KzIuMC4yI1NZUyszK1BPUyNDSUQrMjArNWJkYjBkYjQ2MDI5NDljNDhmYWUjVElNKzEwKzA5MzQ5MjkzMTcjREFUKzM1NitRZ3BOUWk0d01ESXVNVFk0UndZeU1EQTVNRFpJQTA1UFMwc0dOVGM0TWpBd1RpQTFZamRqTnpJellqUmtPRE15TVRRd05qTXlOakl4WW1RNE5HVXhZMlU1WlZJSU9EUXlPRGN4TkRkVkRqSXdNRGt4TWpFMU1Ea3dPRE0zVndveE5URTFNRFl6TmpBeFh6VUJNVjg5RFM1T1JWUWdVQzlKYm5admEyVmZnUUFkUTI5dWRHVnpjMkVnUmxnZ01qVWdMU0JOYjNWdWRHRnBibUpwYTJWZmdRRkNhSFIwY0RvdkwzTm9jQzFrWlhZdVpuSmxaV052WkdVdWJtOHZabkpsWldOdlpHVXZVRWhRUTJ4cFpXNTBMME5vWldOclZISmhibk5oWTNScGIyNHVjR2h3WDRFRURUUmlNamMwTkdWak1qTXlNakpmZ1JvRmJtOWZUazlmZ1RBSU1UQXdNREF6TWpjPSNQN1MrMzUyK01JSUJBZ1lKS29aSWh2Y05BUWNDb0lIME1JSHhBZ0VCTVFzd0NRWUZLdzREQWhvRkFEQWpCZ2txaGtpRzl3MEJCd0dnRmdRVXYyZlp5emc1UGUvekg1NGRIdkp2b25INSthMHhnYmt3Z2JZQ0FRT0FGQ2U3WEZkUHZYNDUvZUw3MWhSNkIwdmNma3k4TUFrR0JTc09Bd0lhQlFBd0RRWUpLb1pJaHZjTkFRRUJCUUFFZ1lBKyt4ZURyaDI2QVU5WnZBbWJyUGhYd1hzcnJDdkZXaVozZm1sTmdzaytQcnRjTGNPWEFEM1JPNTh0dWNJM2IzQUxQcThpL0Z2SU9XalZMSlhUaEJya3pxbDdieWFGcHl1R0ZYdUh3NlAvNldvWDM0dkRlbHhCTlJRYmNkcDJFUnA5L1gvaHRGck5qRkozbDE5dVpMSGxleHE4cWxKRjFjb2xVN0VkUWt1OXdBPT0+">
	
	
	
	//post til bbs (kjøper sendes til bbs' sider)
	<form method="POST" action="<?php echo $wsdl; ?>"> 
		//$result->SetupResult; gir input BBSePay_transaction
		<input type="hidden" name="BBSePay_transaction" value="IYIDLFqCAyg8TVNHKzYrU2lnRGF0I1ZFUis1KzIuMC4yI1NZUyszK1BPUyNDSUQrMjArNWJkYjBkYjQ2MDI5NDljNDhmYWUjVElNKzEwKzA5MzQ5MjkzMTcjREFUKzM1NitRZ3BOUWk0d01ESXVNVFk0UndZeU1EQTVNRFpJQTA1UFMwc0dOVGM0TWpBd1RpQTFZamRqTnpJellqUmtPRE15TVRRd05qTXlOakl4WW1RNE5HVXhZMlU1WlZJSU9EUXlPRGN4TkRkVkRqSXdNRGt4TWpFMU1Ea3dPRE0zVndveE5URTFNRFl6TmpBeFh6VUJNVjg5RFM1T1JWUWdVQzlKYm5admEyVmZnUUFkUTI5dWRHVnpjMkVnUmxnZ01qVWdMU0JOYjNWdWRHRnBibUpwYTJWZmdRRkNhSFIwY0RvdkwzTm9jQzFrWlhZdVpuSmxaV052WkdVdWJtOHZabkpsWldOdlpHVXZVRWhRUTJ4cFpXNTBMME5vWldOclZISmhibk5oWTNScGIyNHVjR2h3WDRFRURUUmlNamMwTkdWak1qTXlNakpmZ1JvRmJtOWZUazlmZ1RBSU1UQXdNREF6TWpjPSNQN1MrMzUyK01JSUJBZ1lKS29aSWh2Y05BUWNDb0lIME1JSHhBZ0VCTVFzd0NRWUZLdzREQWhvRkFEQWpCZ2txaGtpRzl3MEJCd0dnRmdRVXYyZlp5emc1UGUvekg1NGRIdkp2b25INSthMHhnYmt3Z2JZQ0FRT0FGQ2U3WEZkUHZYNDUvZUw3MWhSNkIwdmNma3k4TUFrR0JTc09Bd0lhQlFBd0RRWUpLb1pJaHZjTkFRRUJCUUFFZ1lBKyt4ZURyaDI2QVU5WnZBbWJyUGhYd1hzcnJDdkZXaVozZm1sTmdzaytQcnRjTGNPWEFEM1JPNTh0dWNJM2IzQUxQcThpL0Z2SU9XalZMSlhUaEJya3pxbDdieWFGcHl1R0ZYdUh3NlAvNldvWDM0dkRlbHhCTlJRYmNkcDJFUnA5L1gvaHRGck5qRkozbDE5dVpMSGxleHE4cWxKRjFjb2xVN0VkUWt1OXdBPT0+">
		<input type="submit" value="Betal">   
	</form>
	
	
	
	//*** kunde sendes tilbake ***//
	
	//sjekke betalingen
	$BBSEPAY_TRANSACTION = $_GET['BBSePay_transaction'];
	$params = array
	(
	  "token"                 => "$token",
	  "merchantId"            => "$merchantId",
	  "transactionString"     => "$BBSEPAY_TRANSACTION"
	);
	$client = new SoapClient($wsdl, array('trace' => true,'exceptions' => true ));
	$result = $client->__call('ProcessSetup' , array("parameters"=>$params));
	$ProcessSetupResult = $result->ProcessSetupResult;

	//sjekke at at betalingen/kortet er OK hos BBS

	//Bekrefte betalingen (utføre salget)
	$client = new SoapClient($wsdl, array('trace' => true,'exceptions' => true ));
	$result = $client->__call('Sale', array("parameters"=>$params_sale));






//var_dump( $client->__getTypes()); gir dette:

array(37) {
  [0]=>
  string(551) "struct SetupRequest {
 string Amount;
 string AutoAuth;
 string CurrencyCode;
 string CustomerEmail;
 string CustomerNumber;
 string CustomerPhoneNumber;
 string Description;
 string ExpiryDate;
 string IssuerList;
 string Language;
 string OrderDescription;
 string OrderNumber;
 string Pan;
 string PanHash;
 string RecurringExpiryDate;
 string RecurringFrequency;
 string RecurringType;
 string RedirectOnError;
 string RedirectUrl;
 string SecurityCode;
 string ServiceType;
 string SessionId;
 string TransactionId;
 string TransactionReconRef;
}"
  [1]=>
  string(56) "struct MerchantTranslationException {
 string Message;
}"
  [2]=>
  string(40) "struct GenericError {
 string Message;
}"
  [3]=>
  string(56) "struct UniqueTransactionIdException {
 string Message;
}"
  [4]=>
  string(51) "struct AuthenticationException {
 string Message;
}"
  [5]=>
  string(47) "struct ValidationException {
 string Message;
}"
  [6]=>
  string(56) "struct BBSException {
 string Message;
 Result Result;
}"
  [7]=>
  string(455) "struct Result {
 string AuthenticatedStatus;
 string AuthenticatedWith;
 string AuthorizationCode;
 string AuthorizationId;
 string CardExpiryDate;
 string CustomerIP;
 dateTime ExecutionTime;
 string IssuerCountry;
 string IssuerCountryCode;
 string IssuerId;
 string MerchantId;
 string PanHash;
 string RecurringType;
 string ResponseCode;
 string ResponseSource;
 string ResponseText;
 string SessionId;
 string SessionNumber;
 string TransactionId;
}"
  [8]=>
  string(45) "struct SecurityException {
 string Message;
}"
  [9]=>
  string(49) "struct NotSupportedException {
 string Message;
}"
  [10]=>
  string(33) "struct UserCancelledException {
}"
  [11]=>
  string(170) "struct ReconResult {
 string AmountCredits;
 string AmountDebits;
 string AmountNet;
 string BatchReconRef;
 string CurrencyCode;
 int NumberCredits;
 int NumberDebits;
}"
  [12]=>
  string(74) "struct Setup {
 string token;
 string merchantId;
 SetupRequest request;
}"
  [13]=>
  string(45) "struct SetupResponse {
 string SetupResult;
}"
  [14]=>
  string(303) "struct SetupPaymentWithUI {
 string token;
 string merchantId;
 string currencyCode;
 string transactionId;
 string amount;
 string orderNumber;
 string orderDescription;
 string customerEmail;
 string customerPhoneNumber;
 string description;
 string redirectUrl;
 string language;
 string sessionId;
}"
  [15]=>
  string(71) "struct SetupPaymentWithUIResponse {
 string SetupPaymentWithUIResult;
}"
  [16]=>
  string(279) "struct SetupPayment {
 string token;
 string merchantId;
 string currencyCode;
 string transactionId;
 string amount;
 string orderNumber;
 string orderDescription;
 string customerEmail;
 string customerPhoneNumber;
 string description;
 string redirectUrl;
 string sessionId;
}"
  [17]=>
  string(59) "struct SetupPaymentResponse {
 string SetupPaymentResult;
}"
  [18]=>
  string(268) "struct SetupCallcenterPayment {
 string token;
 string merchantId;
 string currencyCode;
 string transactionId;
 string amount;
 string orderNumber;
 string orderDescription;
 string customerEmail;
 string customerPhoneNumber;
 string description;
 string sessionId;
}"
  [19]=>
  string(79) "struct SetupCallcenterPaymentResponse {
 string SetupCallcenterPaymentResult;
}"
  [20]=>
  string(85) "struct ProcessSetup {
 string token;
 string merchantId;
 string transactionString;
}"
  [21]=>
  string(59) "struct ProcessSetupResponse {
 Result ProcessSetupResult;
}"
  [22]=>
  string(125) "struct Sale {
 string token;
 string merchantId;
 string transactionId;
 string transactionReconRef;
 string batchReconRef;
}"
  [23]=>
  string(43) "struct SaleResponse {
 Result SaleResult;
}"
  [24]=>
  string(96) "struct Auth {
 string token;
 string merchantId;
 string transactionId;
 string batchReconRef;
}"
  [25]=>
  string(43) "struct AuthResponse {
 Result AuthResult;
}"
  [26]=>
  string(153) "struct Capture {
 string token;
 string merchantId;
 string transactionId;
 string description;
 string transactionAmount;
 string transactionReconRef;
}"
  [27]=>
  string(49) "struct CaptureResponse {
 Result CaptureResult;
}"
  [28]=>
  string(122) "struct Annul {
 string token;
 string merchantId;
 string transactionId;
 string description;
 string transactionAmount;
}"
  [29]=>
  string(45) "struct AnnulResponse {
 Result AnnulResult;
}"
  [30]=>
  string(123) "struct Credit {
 string token;
 string merchantId;
 string transactionId;
 string description;
 string transactionAmount;
}"
  [31]=>
  string(47) "struct CreditResponse {
 Result CreditResult;
}"
  [32]=>
  string(246) "struct Recon {
 string token;
 string merchantId;
 string currencyCode;
 string numberCredits;
 string numberDebits;
 string amountCredits;
 string amountDebits;
 string amountNet;
 string batchReconRef;
 string sessionNumber;
 string issuerId;
}"
  [33]=>
  string(50) "struct ReconResponse {
 ReconResult ReconResult;
}"
  [34]=>
  string(8) "int char"
  [35]=>
  string(17) "duration duration"
  [36]=>
  string(11) "string guid"
}
?>