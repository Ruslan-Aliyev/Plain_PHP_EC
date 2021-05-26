<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once __DIR__ . '/vendor/autoload.php';

use Omnipay\Omnipay;

$stripeGateway = Omnipay::create('Stripe');
$stripeGateway->setApiKey('sk_test_51ISw50HC8M2JxTUFZ0fNbVPvrEMM8ld25Ntemq53sSxyKSOggo2RYs71mpgYASRaxevWSwieCmZPDI8Hs6UOxLWV003IcdkMas');

$paypalGateway = Omnipay::create('PayPal_Rest');
$paypalGateway->setClientId('AYzhvnHiHYcf215rSaLsUktSWO9AZ_848z9crMoVO5bk6Tr_9xiFjPcTkdSNej3C67EJ23O-CDBnsRU4');
$paypalGateway->setSecret('EMnGiSlyXijukNaswbgcPcJ7o_0LweOb9PBjK6MRojX1frlL5RDMHxsRKl-v5nunuP50GpVNMp07G-gl');
$paypalGateway->setTestMode(true);

if (isset($_POST['gateway']) && $_POST['gateway'] === 'Stripe')
{
    $token = $_POST['stripeToken']; 

    $response = $stripeGateway->purchase([
        'amount' => $_POST['amount'],
        'currency' => 'USD',
        'token' => $token,
    ])->send();

    dd($response);
}

if (isset($_POST['gateway']) && $_POST['gateway'] === 'Paypal')
{
    $response = $paypalGateway->purchase(array(
        'amount' => $_POST['amount'],
        'currency' => 'USD',
        'returnUrl' => getBaseUrl() . 'index.php',
        'cancelUrl' => getBaseUrl() . 'index.php',
    ))->send();

    if ($response->isRedirect()) 
    {
        $response->redirect();
    } 
    else 
    {
        dd( $response->getMessage() );
    }
}

if (array_key_exists('paymentId', $_GET) && array_key_exists('PayerID', $_GET)) 
{
    $transaction = $paypalGateway->completePurchase(array(
        'payer_id'             => $_GET['PayerID'],
        'transactionReference' => $_GET['paymentId'],
    ));
    $response = $transaction->send();
 
    if ($response->isSuccessful()) 
    { // The customer has successfully paid.
        dd( $response->getData() );

        dd("Payment is successful. Your transaction id is: " . $payment_id);
    } 
    else 
    {
        dd( $response->getMessage() );
    }
}

// ---

function getBaseUrl() 
{
    $baseUrl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $urlParts = explode('/', $baseUrl);
    array_pop($urlParts);
    $baseUrl = implode('/', $urlParts);
    $baseUrl .= '/';

    return $baseUrl;
}

function dd($content) 
{
    echo '<pre>';
    var_dump($content);
    echo '----------';
    echo '</pre>';
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <title>Stripe Payment</title>
        <script src="https://js.stripe.com/v3/"></script>
        <style>
            .box {
                margin: 20px;
                border: 1px solid black;
                padding: 20px;
            }
        </style>
    </head>
    <body>
        <div class="box">
            <h3>Pay by Stripe</h3>

            <div id="paymentResponse"></div>
            
            <form action="" method="POST" id="stripeForm">
                <div class="form-group">
                    <label>AMOUNT</label>
                    <input type="number" name="amount" id="amount" step="0.01" class="field" placeholder="Enter amount" required="" autofocus="">
                </div>

                <div class="form-group">
                    <label>CARD NUMBER</label>
                    <div id="card_number" class="field"></div>
                </div>
                <div class="row">
                    <div class="left">
                        <div class="form-group">
                            <label>EXPIRY DATE</label>
                            <div id="card_expiry" class="field"></div>
                        </div>
                    </div>
                    <div class="right">
                        <div class="form-group">
                            <label>CVC CODE</label>
                            <div id="card_cvc" class="field"></div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="gateway" value="Stripe">

                <button type="submit" class="btn btn-success" id="payBtn">Pay by Stripe</button>
            </form>
        </div>

        <div class="box">
            <h3>Pay by Paypal</h3>

            <form action="" method="POST" id="paypalForm">
                <div class="form-group">
                    <label>AMOUNT</label>
                    <input type="number" name="amount" id="amount" step="0.01" class="field" placeholder="Enter amount" required="" autofocus="">
                </div>

                <input type="hidden" name="gateway" value="Paypal">

                <button type="submit" class="btn btn-success" id="payBtn">Pay by Paypal</button>
            </form>
        </div>

        <script type="text/javascript">
            var stripe = Stripe('pk_test_51ISw50HC8M2JxTUFdmtaIrYoz714yudM5V9s2U3SEqTN0ugI3TnlVP7Oxj3KAMLNLsYe6GAvC2FpCJ30OheFkLqG00tuhMBMUr'); // Public Key

            var style = {
                base: {
                    fontWeight: 400,
                    fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                    fontSize: '16px',
                    lineHeight: '1.4',
                    color: '#555',
                    backgroundColor: '#fff',
                    '::placeholder': {
                        color: '#888',
                    },
                },
                invalid: {
                    color: '#eb1c26',
                }
            };

            var elements = stripe.elements();

            var cardElement = elements.create('cardNumber', {
                style: style
            });
            cardElement.mount('#card_number');

            elements.create('cardExpiry', {
                'style': style
            }).mount('#card_expiry');
            elements.create('cardCvc', {
                'style': style
            }).mount('#card_cvc');

            // Validate input of the card elements
            var resultContainer = document.getElementById('paymentResponse');
            cardElement.addEventListener('change', function(event) {
                if (event.error) {
                    resultContainer.innerHTML = '<p>'+event.error.message+'</p>';
                } else {
                    resultContainer.innerHTML = '';
                }
            });

            var stripeForm = document.getElementById('stripeForm');
            stripeForm.addEventListener('submit', function(e) {
                e.preventDefault();

                stripe.createToken(cardElement).then(function(result) {
                    if (result.error) 
                    {
                        resultContainer.innerHTML = '<p>'+result.error.message+'</p>';
                    } 
                    else 
                    {
                        stripeTokenHandler(result.token);
                    }
                });
            });

            function stripeTokenHandler(token) 
            {
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', token.id);
                stripeForm.appendChild(hiddenInput);
                stripeForm.submit();
            }
        </script>

    </body>
</html>