<?php
namespace App;
use Stripe\StripeClient;
use Stripe\Error\Card;
use Stripe\Error\InvalidRequest;
use Stripe\Error\Authentication;
use Stripe\Error\ApiConnection;
use Stripe\Error\Base;
use Stripe\Charge;
use Stripe\Plan;
use Stripe\Coupon;
use Stripe\Event;
use Stripe\ApiResource;
use Stripe\Exception\RateLimitException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidArgumentException;
use Carbon\Carbon;

class Stripe {

    private $public_test = '';
    private $secret_test = '';
    private $public_live = '';
    private $secret_live = '';
    private $client = '';


    public function __construct(){
        $this->public_test = 'pk_test_dpA6kDR6HmJWnjlpziYRLCDy00laYQcoyF';
        $this->secret_test = 'sk_test_Bw2fI6FxXXaUmATqc6laJCvg007KZzbeES';
        // $this->public_test = 'pk_test_51GvNHWBU3gnRnRsjQX3RWIwWJRYWiBXOg9fdE9XFRnuZpuot5GEhE6mYeG9yF7gv84EHP4vxdRTStWvMLhX6Pa4j00CTZs1Rww';
        // $this->secret_test = 'sk_test_51GvNHWBU3gnRnRsjg9qKpMzoS8gUpm5VcZ7mbXwEv9yugB0hzd98tzElNnPlwGUjPGmlwnS0rDAfCoUWN5Db3x6n00ZfA5WeoE';
        $this->client = new StripeClient($this->secret_test);
    }

    /**
     * Create a Customer at Stripe
     * @param array $data Data of customer like email name
     * @return object $customer Stripe Object of the customer
     */
    public function createCustomer($data){
        try {
            $customer = $this->client->customers->create($data);
            return $resp = ['status' => true, 'customer' =>  $customer];
        } catch(CardException $e) {
            return ['status'=>false, 'msg'=>$e->getError()->message];
        } catch (RateLimitException $e) {
            return ['status'=>false, 'msg'=>'Too many requests. '];
        } catch (InvalidArgumentException $e) {
            return ['status'=>false, 'msg'=>'Invalid parameters. '.$e->getError()->message];
        } catch (AuthenticationException $e) {
            return ['status'=>false, 'msg'=>'API Authentication Problem. '];
        } catch (ApiConnectionException $e) {
            return ['status'=>false, 'msg'=>'Unable to Connect Stripe. '];
        } catch (ApiErrorException $e) {
            return ['status'=>false, 'msg'=>'Api Error. '.$e->getError()->message];
        }
    }

    /**
     * Create a Card
     * @param array $card Card Data from front end
     * @return mixed Stripe Response with Status
     */
    public function createPaymentMethod($card){
        try {
            $pm = $this->client->paymentMethods->create([
                'type' => 'card',
                'card' =>  $card
            ]);
            return ['status' => true, 'card' =>  $pm];
        } catch(CardException $e) {
            return ['status'=>false, 'msg'=>$e->getError()->message];
        } catch (RateLimitException $e) {
            return ['status'=>false, 'msg'=>'Too many requests. '];
        } catch (InvalidArgumentException $e) {
            return ['status'=>false, 'msg'=>'Invalid parameters. '.$e->getError()->message];
        } catch (AuthenticationException $e) {
            return ['status'=>false, 'msg'=>'API Authentication Problem. '];
        } catch (ApiConnectionException $e) {
            return ['status'=>false, 'msg'=>'Unable to Connect Stripe. '];
        } catch (ApiErrorException $e) {
            return ['status'=>false, 'msg'=>'Api Error. '.$e->getError()->message];
        }
    }

    /**
     * Attatch Payment Method to a customer
     * @param string $customerId Stripe Id of the customer
     * @param string $paymentId Stripe Id of the method
     * @param bool Optional $makeDefault true if make default
     * @return mixed Array Object
     */
    public function attachPaymentMethod($customerId, $paymentId, $makeDefault=true){
        try
        {
            $attach = $this->client->paymentMethods->attach(
              $paymentId,
              ['customer' => $customerId]
            );
            if($makeDefault)
                $this->client->customers->update(
                  $customerId,
                  ['invoice_settings' => ['default_payment_method' => $paymentId]]
                );
            return ['status'=>true,'result'=>$attach];
        } catch(CardException $e) {
            return ['status'=>false, 'msg'=>$e->getError()->message];
        } catch (RateLimitException $e) {
            return ['status'=>false, 'msg'=>'Too many requests. '];
          // Too many requests made to the API too quickly
        } catch (InvalidArgumentException $e) {
            return ['status'=>false, 'msg'=>'Invalid parameters. '.$e->getError()->message];
          // Invalid parameters were supplied to Stripe's API
        } catch (AuthenticationException $e) {
            return ['status'=>false, 'msg'=>'API Authentication Problem. '];
          // Authentication with Stripe's API failed
          // (maybe you changed API keys recently)
        } catch (ApiConnectionException $e) {
            return ['status'=>false, 'msg'=>'Unable to Connect Stripe. '];
          // Network communication with Stripe failed
        } catch (ApiErrorException $e) {
            return ['status'=>false, 'msg'=>'Api Error. '.$e->getError()->message];
          // Display a very generic error to the user, and maybe send
          // yourself an email
        }


    }

    /**
     * Make a payment method default for Stripe 
     * @param string $customerId Stripe Id of the customer
     * @param string $paymentId Stripe Id of the method
     * @return mixed Array Object
     */
    public function makeDefaultPaymentMethod($customerId,$paymentId){

        $default = $this->client->customers->update(
              $customerId,
              ['invoice_settings' => ['default_payment_method' => $paymentId]]
            );
        return $default;
    }

    /**
     * Get Card Details from Stripe
     * @param string $paymentId Stripe Id of the method
     * @return mixed Array Object
     */
    public function retrieveMethod($paymentId){
        $method = $this->client->paymentMethods->retrieve(
          $paymentId,
          []
        );
        return $method;
    }

    /**
     * Detach a payament Method
     * From Customer
     * @param string $paymentId Stripe Id of the method
     * @return mixed Array Object
     */
    public function detachMethod($paymentId){
        try{
            $method = $this->client->paymentMethods->detach(
                $paymentId,
                []
            );
            return ['status' => true, 'card' => $method];

        } catch(CardException $e) {
            return ['status'=>false, 'msg'=>$e->getError()->message];
        } catch (RateLimitException $e) {
            return ['status'=>false, 'msg'=>'Too many requests. '];
        } catch (InvalidArgumentException $e) {
            return ['status'=>false, 'msg'=>'Invalid parameters. '.$e->getError()->message];
        } catch (AuthenticationException $e) {
            return ['status'=>false, 'msg'=>'API Authentication Problem. '];
        } catch (ApiConnectionException $e) {
            return ['status'=>false, 'msg'=>'Unable to Connect Stripe. '];
        } catch (ApiErrorException $e) {
            return ['status'=>false, 'msg'=>'Api Error. '.$e->getError()->message];
        }
    }


    /**
     * Create a Charge
     * @param mixed $amount which need to be charged
     * @param string $customer Stripe Id of th customer
     * @param string $currency Currency of payment
     * @return mixed Default is usd Detailed object with array data
     */
    public function createCharge($amount, $customer, $currency='usd', $description = "Transaction From WYI"){
        try{
            $charge = $this->client->charges->create([
            'customer' =>$customer['stripe_id'],
            'amount' => ($amount*100),
            'currency' => $currency,
            'description' => $description,
            ]);
            return ['status'=>true,'charge'=>$charge];
        } catch(CardException $e) {
            return ['status'=>false, 'msg'=>$e->getError()->message];
        } catch (RateLimitException $e) {
            return ['status'=>false, 'msg'=>'Too many requests. '];
        } catch (InvalidArgumentException $e) {
            return ['status'=>false, 'msg'=>'Invalid parameters. '.$e->getError()->message];
        } catch (AuthenticationException $e) {
            return ['status'=>false, 'msg'=>'API Authentication Problem. '];
        } catch (ApiConnectionException $e) {
            return ['status'=>false, 'msg'=>'Unable to Connect Stripe. '];
        } catch (ApiErrorException $e) {
            return ['status'=>false, 'msg'=>'Api Error. '.$e->getError()->message];
        }
    }

    /**
     * Process a payment For customer
     * Using exsiting payment method
     * @param \App\Model\User $user User Data
     * @param \App\Model\PaymentMethod $card Card Data
     * @param float $amount Amount to be processed
     * @param string $currency Amount to be processed
     * @param string optional $description Description about text
     */
    public function processPayment($user, $card, $amount, $currency='gbp', $description = 'Transaction for WYI'){
        try {
            $charge = $this->client->paymentIntents->create([
                'customer' =>$user->stripe_id,
                'amount' => ($amount*100),
                'currency' => $currency,
                'confirm'=>true,
                'payment_method'=>$card->p_key,
                'receipt_email'=>$user->email,
                'description' => $description,
                'statement_descriptor_suffix' => 'WYI'
              ]);
              return ['status'=>true,'charge'=>$charge];
        } catch(CardException $e) {
            return ['status'=>false, 'msg'=>$e->getError()->message];
        } catch (RateLimitException $e) {
            return ['status'=>false, 'msg'=>'Too many requests. '];
        } catch (InvalidArgumentException $e) {
            return ['status'=>false, 'msg'=>'Invalid parameters. '.$e->getError()->message];
        } catch (AuthenticationException $e) {
            return ['status'=>false, 'msg'=>'API Authentication Problem. '];
        } catch (ApiConnectionException $e) {
            return ['status'=>false, 'msg'=>'Unable to Connect Stripe. '];
        } catch (ApiErrorException $e) {
            return ['status'=>false, 'msg'=>'Api Error. '.$e->getError()->message];
        }
    }


}