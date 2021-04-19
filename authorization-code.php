<?php
/*
 * Below are the constants you should change to match your setup
 */
$redirect_uri = 'https://your-url.com/';
$client_id = '1234567890abcdefghijklmnopqrstuvwxyzABCD';
$client_secret = '1234567890abcdefghijklmnopqrstuvwxyzABCD';

$tmp = null;

if ( isset( $_GET['code'] ) ) {

	$curl_post_data = array(
		'grant_type'    => 'authorization_code',
		'code'          => $_GET['code'],
		'redirect_uri'  => $redirect_uri,
		'client_id'     => $client_id,
		'client_secret' => $client_secret
	);

	$curl = curl_init( 'https://leoadventures.com/oauth/token/' );

	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_POST, true );
	curl_setopt( $curl, CURLOPT_POSTFIELDS, $curl_post_data );
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5' );
	curl_setopt( $curl, CURLOPT_REFERER, 'http://www.example.com/1' );

	$curl_response = curl_exec( $curl );
	$code_response = json_decode( $curl_response );
	curl_close( $curl );

	$tmp = $code_response;

	/*
	 * If there is no error in the return, the following will request the user information from the server
	 */
	$curl = curl_init( 'https://leoadventures.com/oauth/testme/?access_token=' . $tmp->access_token );

	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $curl, CURLOPT_POST, false );
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5' );
	curl_setopt( $curl, CURLOPT_REFERER, 'http://www.example.com/1' );

	$curl_response  = curl_exec( $curl );
	$token_response = json_decode( $curl_response );
	curl_close( $curl );

	$user = $token_response;
}

?>

<?php if ( ! is_null( $tmp ) ) :

	print '<p>Below is the return from the OAuth Server. This information can be used to request the user information.</p>';
	print '<pre>';
	print_r( $tmp );
	print '</pre>';

	print '<p>Below is authorized user information given the access token provided. This information is what is used to log the user into the client.';
	print '<pre>';
	print_r( $user );
	print '</pre>';

	print '<a href="' . $redirect_uri . '">Return to Form</a>';

else : ?>

	<h3>Login Form Example</h3>
	<form id="leoadventures-auth-form" method="GET" action="https://leoadventures.com/oauth/authorize/">
	    <input type="hidden" name="state" value="randomString123" />
	    <input type="hidden" name="response_type" value="code" />
	    <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />
	    <input type="hidden" name="redirect_uri" value="<?php echo $redirect_uri; ?>" />
	    <button type="submit" class="" id="leo-login-submit-button">
	         <img id="leoadventures_verification_button" width="350" height="72" alt="LeoAdventures verification button" srcset="https://leoadventures.com/wp-content/uploads/2021/03/Leo-White-350.png 1x, https://leoadventures.com/wp-content/uploads/2021/03/Leo-White-700.png 2x" src="https://leoadventures.com/wp-content/uploads/2021/03/Leo-White-350.png"/>
	    </button>
	</form>

<?php endif;
