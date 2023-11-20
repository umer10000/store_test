<?php

namespace App\Http\Controllers\Front;

use App\EmailSetting;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\Coupon;
use App\Models\Customers;
use App\Models\OptionProduct;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderShippingLabel;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Settings;
use App\Models\States;
use App\SellerAddress;
use App\User;
use Exception;
use Facade\FlareClient\Http\Response;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session as FacadesSession;
use SplititSdkClient\Configuration;
use SplititSdkClient\ObjectSerializer;
use SplititSdkClient\Api\LoginApi;
use SplititSdkClient\Api\InstallmentPlanApi;
use SplititSdkClient\Model\LoginRequest;
use SplititSdkClient\Model\PlanData;
use SplititSdkClient\Model\ConsumerData;
use SplititSdkClient\Model\RequestHeader;
use SplititSdkClient\Model\AddressData;
use SplititSdkClient\Model\PlanApprovalEvidence;
use SplititSdkClient\Model\CardData;
use SplititSdkClient\Model\MoneyWithCurrencyCode;
use SplititSdkClient\Model\InitiateInstallmentPlanRequest;
use SplititSdkClient\Model\CreateInstallmentPlanRequest;

use Validator;

class PaymentController extends Controller
{
    public function stripeCharge(Request $request)
    {

        $inputs = $request->all();
        // dd($inputs);
        $validator = Validator::make($inputs, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address1' => 'required',
            'country' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'state' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                "status" => false,
                "data" => [],
                "errors" => $validator->messages()->first(),
                "message" => "Unexpected Error occured."
            ];
        }

        $stripe = [
            "secret_key" => env("SECRET_KEY"),
            "publishable_key" => env("PUBLISHABLE_KEY"),
        ];

        try {

            $cardStripe = \Stripe\Stripe::setApiKey($stripe['secret_key']);

            $cardStripe = \Stripe\Token::create(array(
                "card" => array(
                    "number" => $request['card_no'],
                    "exp_month" => $request['exp_mon'],
                    "exp_year" => $request['exp_year'],
                    "cvc" => $request['cvv']
                )
            ));

            if (!empty($cardStripe)) {

                $customer = new \Stripe\StripeClient(
                    $stripe['secret_key']
                );
                $abc = $customer->customers->create([
                    'description' => 'Shopping',
                    'email' => $request->email,
                    'source' => $cardStripe['id'],
                ]);
                $charge = \Stripe\Stripe::setApiKey($stripe['secret_key']);

                $charge = \Stripe\Charge::create([
                    'amount' => $request['amount'] * 100,
                    'currency' => 'usd',
                    'customer' => $abc
                ]);

                if ($charge['status'] === 'succeeded') {

                    /*  USER DETAIL */
                    if (!Auth::check()) {
                        $user = User::where('email', $request->input('email'))->first();
                        if (empty($user) && $user == null) {
                        }
                        if (!empty($user->id)) {
                            $buyer = Buyer::where('user_id', $user->id)->first();
                            if (!empty($buyer) && $buyer == null) {
                            }
                        }
                    } else {
                        $user = Auth::user();
                    }
                    $setting = Settings::find(1);
                    $items = Cart::content();
                    $totalAmount = 0;
                    $tax = 0;
                    if (!empty($items) && count($items) > 0) {
                        $subtotal = 0;
                        $discount = 0;

                        foreach ($items as $item) {
                            $subtotal += (float)$item->price * (int)$item->qty;
                        }

                        $service = ($subtotal * $setting->service_charges) / 100;
                        $tax = (($subtotal + $service) * $setting->tax) / 100;

                        $totalAmount = ($subtotal - $discount) + $tax + $request->input('shipping_cost') + $service;
                        $stripe_total = ($totalAmount / 100) * $setting->stripe_percentage + $setting->stripe_charges;

                        $totalAmount += $stripe_total;

                        $today = date("Ymd");
                        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                        $order_no = $today . $rand;


                        $order = Order::create([
                            'order_no' => $order_no,
                            // 'seller_id' => $product->seller_id,
                            'buyer_id' => auth()->user() ? auth()->user()->buyer->id : null, //Users table ID
                            'buyer_name' => $buyer->name ?? $request->input('first_name') . ' ' . $request->input('last_name'),
                            'buyer_email' => $user->email ?? $request->input('email'),
                            'sub_total' => $subtotal,
                            'tax' => $tax,
                            'shipping_cost' => $request->input('shipping_cost'),
                            'vat_charges' => $request->input('vat_charges'),
                            // 'shipping_name' => $request->input('shipping'),
                            // 'packaging_type' => $request->input('packaging_type'),
                            'total_amount' => $totalAmount,
                            'order_status' => 'pending',
                            'country' => $request->input('country'),
                            'state' => $request->input('state'),
                            'city' => $request->input('city'),
                            'address1' => $request->input('address1'),
                            // 'address2' => $request->input('address2'),
                            'zip' => $request->input('zip_code'),
                            'phone_no' => $request->input('phone'),
                            'note' => $request->input('note'),
                            'term_accepted' => $request->input('term_condition'),
                            'service_charges' => $request->input('service_charges'),
                            'order_status' => "paid",
                        ]);
                        $counter = 0;
                        foreach ($items as $index => $item) {
                            $shipping = (isset($request->shipping[$counter])) ? $request->shipping[$counter] : null;
                            $packaging_type = (isset($request->packaging_type[$counter])) ? $request->packaging_type[$counter] : null;
                            $shipping_cost_array = (isset($request->shipping_cost_array[$counter])) ? $request->shipping_cost_array[$counter] : null;
                            $counter++;
                            $product = Product::find($item->id);
                            $orderItems = OrderItem::create([
                                'order_id' => $order->id,
                                'product_id' => $item->id,
                                'seller_id' => $product->seller_id,
                                'product_per_price' => $item->price,
                                'product_qty' => $item->qty,
                                'tax' => $tax,
                                'product_subtotal_price' => $subtotal,
                                'shipping_name' => $shipping,
                                'packaging_type' => $packaging_type,
                                'shipping_cost' => $shipping_cost_array,
                                'product_type' => $product->product_type,
                                'shipping_type' => $product->shipping,
                                'downloaded' => null,
                            ]);

                            $country = Countries::where('id', $request->country)->value('sortname');
                            $state = States::where('id', $request->state)->value('name');
                            $city = Cities::where('id', $request->city)->value('name');

                            $seller_address = SellerAddress::where('seller_id', $product->seller_id)->first();

                            $s_country = Countries::where('id', $seller_address->country)->value('sortname');
                            $s_state = States::where('id', $seller_address->state)->value('name');
                            $s_city = Cities::where('id', $seller_address->city)->value('name');

                            $postal_code = trim($request->zip_code);
                            $seller_address_data = ['country' => trim($s_country), 'state' => trim($s_state), 'city' => trim($s_city), 'postal_code' => trim($seller_address->zip_code), 'company_name' => trim($seller_address->company_name), 'email' => $seller_address->email, 'phone_number' => $seller_address->phone_no, 'name' => $seller_address->first_name . ' ' . $seller_address->last_name, 'address1' => trim($seller_address->address1)];
                            $address_data = ['country' => trim($country), 'state' => trim($state), 'city' => trim($city), 'postal_code' => trim($postal_code), 'name' => $request->first_name . ' ' . $request->last_name, 'phone_number' => $request->phone, 'address1' => trim($request->address1)];

                            if ($product->product_type == "Physical" && $product->shipping == 1) {
                                $ship_data =  CheckoutController::shipService($seller_address_data, $address_data, $product, $orderItems, $item);

                                if (!empty($ship_data) && count($ship_data) > 0) {
                                    foreach ($ship_data as $ship_dataa) {
                                        OrderShippingLabel::create([
                                            'order_item_id' => $orderItems->id,
                                            'tracking_number' => $ship_dataa->CompletedShipmentDetail->CompletedPackageDetails[0]->TrackingIds[0]->TrackingNumber,
                                            'shipping_doc_name' => 'label_' . $ship_dataa->CompletedShipmentDetail->CompletedPackageDetails[0]->TrackingIds[0]->TrackingNumber . '.pdf'
                                        ]);
                                    }
                                }
                            }
                        }
                        Payment::create([
                            'order_id' => $order->id,
                            'amount' => $totalAmount,
                            'pay_method_name' => 'Stripe',
                        ]);
                        // decrease the quantities of all the products in the cart
                        $this->decreaseQuantities();
                        $emailSetting = EmailSetting::find(1);
                        $mailData = array(
                            'order' => $order,
                            'countOrderItems' => count(array($orderItems)),
                            'orderItems' => $orderItems,
                            'setting' => $setting,
                            'email' => $setting->email,
                            'to' => $order->buyer_email,
                            'emailSetting' => $emailSetting,
                        );

                        Mail::send('front.emails.orderConfirmationEmail', $mailData, function ($message) use ($mailData) {
                            $message->to($mailData['to'])->from($mailData['email'])
                                ->subject('Order Confirmation');
                        });

                        Cart::instance('default')->destroy();
                        session()->forget('coupon');

                        return [
                            "status" => true,
                            "data" => ['order_no' => $order->order_no],
                            "errors" => [],
                            "message" => "Successfully added"
                        ];
                    } else {
                    } //PAYPAL SUCCEED END
                }
            }
        } catch (\Stripe\Exception\CardException $e) {
            // DB::rollBack();
            // alert($e);
            //echo 'invalid card';
            throw $e;
        }
    }


    public function paypalCharge(Request $request)
    {

        /*  USER DETAIL */
        if (!Auth::check()) {
            $user = User::where('email', $request->input('email'))->first();
            if (empty($user) && $user == null) {
                // $user = User::create([
                //     'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                //     'email' => $request->input('email'),
                //     'password' => Hash::make($request->input('password')),
                //     'roll_id' => 2,
                // ]);
            }
            if (!empty($user->id)) {
                $buyer = Buyer::where('user_id', $user->id)->first();
                if (!empty($buyer) && $buyer == null) {
                    // $buyer = Buyer::create([
                    //     'user_id' => $user->id,
                    //     'name' => $request->input('first_name'),
                    //     'email' => $request->input('email'),
                    //     'phone_number' => $request->input('phone'),
                    //     'zip_code' => $request->input('zip_code')
                    // ]);
                }
            }
        } else {
            $user = Auth::user();
        }



        $setting = Settings::find(1);
        $items = Cart::content();
        $totalAmount = 0;
        $tax = 0;
        if (!empty($items) && count($items) > 0) {
            $subtotal = 0;
            $discount = 0;

            foreach ($items as $item) {
                $subtotal += (float)$item->price * (int)$item->qty;
            }

            $service = ($subtotal * $setting->service_charges) / 100;
            $tax = (($subtotal + $service) * $setting->tax) / 100;

            $totalAmount = ($subtotal - $discount) + $tax + $request->input('shipping_cost') + $service;

            $paypal_total = ($totalAmount / 100) * $setting->paypal_percentage + $setting->paypal_charges;
            $totalAmount += $paypal_total;
            $today = date("Ymd");
            $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
            $order_no = $today . $rand;



            $order = Order::create([
                'order_no' => $order_no,
                // 'seller_id' => $product->seller_id,
                'buyer_id' => auth()->user() ? auth()->user()->buyer->id : null, //Users table ID
                'buyer_name' => $buyer->name ?? $request->input('first_name') . ' ' . $request->input('last_name'),
                'buyer_email' => $user->email ?? $request->input('email'),
                'sub_total' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $request->input('shipping_cost'),
                'vat_charges' => $request->input('vat_charges'),
                // 'shipping_name' => $request->input('shipping'),
                // 'packaging_type' => $request->input('packaging_type'),
                'total_amount' => $totalAmount,
                'order_status' => 'pending',
                'country' => $request->input('country'),
                'state' => $request->input('state'),
                'city' => $request->input('city'),
                'address1' => $request->input('address1'),
                // 'address2' => $request->input('address2'),
                'zip' => $request->input('zip_code'),
                'phone_no' => $request->input('phone'),
                'note' => $request->input('note'),
                'term_accepted' => $request->input('term_condition'),
                'service_charges' => $request->input('service_charges'),
                'order_status' => "paid",
            ]);
            $counter = 0;
            foreach ($items as $index => $item) {
                $shipping = (isset($request->shipping[$counter])) ? $request->shipping[$counter] : null;
                $packaging_type = (isset($request->packaging_type[$counter])) ? $request->packaging_type[$counter] : null;
                $shipping_cost_array = (isset($request->shipping_cost_array[$counter])) ? $request->shipping_cost_array[$counter] : null;

                $counter++;
                $product = Product::find($item->id);
                $orderItems = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'seller_id' => $product->seller_id,
                    'product_per_price' => $item->price,
                    'product_qty' => $item->qty,
                    'tax' => $tax,
                    'product_subtotal_price' => $subtotal,
                    'shipping_name' => $shipping,
                    'packaging_type' => $packaging_type,
                    'shipping_cost' => $shipping_cost_array,
                    'product_type' => $product->product_type,
                    'shipping_type' => $product->shipping,
                    'downloaded' => null,
                ]);
                $country = Countries::where('id', $request->country)->value('sortname');
                $state = States::where('id', $request->state)->value('name');
                $city = Cities::where('id', $request->city)->value('name');

                $seller_address = SellerAddress::where('seller_id', $product->seller_id)->first();

                $s_country = Countries::where('id', $seller_address->country)->value('sortname');
                $s_state = States::where('id', $seller_address->state)->value('name');
                $s_city = Cities::where('id', $seller_address->city)->value('name');

                $postal_code = trim($request->zip_code);
                $seller_address_data = ['country' => trim($s_country), 'state' => trim($s_state), 'city' => trim($s_city), 'postal_code' => trim($seller_address->zip_code), 'company_name' => trim($seller_address->company_name), 'email' => $seller_address->email, 'phone_number' => $seller_address->phone_no, 'name' => $seller_address->first_name . ' ' . $seller_address->last_name, 'address1' => trim($seller_address->address1)];
                $address_data = ['country' => trim($country), 'state' => trim($state), 'city' => trim($city), 'postal_code' => trim($postal_code), 'name' => $request->first_name . ' ' . $request->last_name, 'phone_number' => $request->phone, 'address1' => trim($request->address1)];

                if ($product->product_type == "Physical" && $product->shipping == 1) {
                    $ship_data =  CheckoutController::shipService($seller_address_data, $address_data, $product, $orderItems, $item);
                    if (!empty($ship_data) && count($ship_data) > 0) {
                        foreach ($ship_data as $ship_dataa) {
                            OrderShippingLabel::create([
                                'order_item_id' => $orderItems->id,
                                'tracking_number' => $ship_dataa->CompletedShipmentDetail->CompletedPackageDetails[0]->TrackingIds[0]->TrackingNumber,
                                'shipping_doc_name' => 'label_' . $ship_dataa->CompletedShipmentDetail->CompletedPackageDetails[0]->TrackingIds[0]->TrackingNumber . '.pdf'
                            ]);
                        }
                    }
                }
            }
            Payment::create([
                'order_id' => $order->id,
                'amount' => $totalAmount,
                'pay_method_name' => 'Paypal',
            ]);
            // decrease the quantities of all the products in the cart
            $this->decreaseQuantities();
            // $setting = Settings::find(1);
            $emailSetting = EmailSetting::find(1);
            $mailData = array(
                'order' => $order,
                'countOrderItems' => count(array($orderItems)),
                'orderItems' => $orderItems,
                'setting' => $setting,
                'email' => $setting->email,
                'to' => $order->buyer_email,
                'emailSetting' => $emailSetting,
            );

            Mail::send('front.emails.orderConfirmationEmail', $mailData, function ($message) use ($mailData) {
                $message->to($mailData['to'])->from($mailData['email'])
                    ->subject('Order Confirmation');
            });

            Cart::instance('default')->destroy();
            session()->forget('coupon');

            return [
                "status" => true,
                "data" => ['order_no' => $order->order_no],
                "errors" => [],
                "message" => "Successfully added"
            ];
        } else {
        } //PAYPAL SUCCEED END


    }

    public function splitItCharge(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address1' => 'required',
            'country' => 'required',
            'city' => 'required',
            'zip_code' => 'required',
            'state' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                "status" => false,
                "data" => [],
                "errors" => $validator->messages()->first(),
                "message" => "Unexpected Error occured."
            ];
        }


        // Configuration::sandbox()->setApiKey(env("API_KEY"));
        Configuration::production()->setApiKey(env("API_KEY"));
        # Using the sandbox environment. Use Configuration::production() for production or omit the parameter.
        $loginApi = new LoginApi(Configuration::production());

        try {
            $requestt = new LoginRequest();

            # Replace with your login information
            $requestt->setUserName(env("SPLIT_USERNAME"));
            $requestt->setPassword(env("SPLIT_PASSWORD"));
            $loginResponse = $loginApi->loginPost($requestt);

            $session_id = $loginResponse->getSessionId();

            # Use Configuration::production() for production API
            $installmentPlanApi = new InstallmentPlanApi(
                Configuration::production(),
                $session_id
            );

            //$installmentPlanApi->setCulture('de-DE'); -> optionally set culture to be used in subsequent calls to this API.

            $initiateRequest = new InitiateInstallmentPlanRequest();

            $plan_data = new PlanData();
            $plan_data->setNumberOfInstallments(4);
            $plan_data->setAmount(new MoneyWithCurrencyCode(array("value" => $request->amount, "currency_code" => "USD")));

            $billing_address = new AddressData(array(
                "address_line" => $request->address1,
                // "address_line2" => $request->address2,
                "city" => $request->city,
                "country" => $request->country,
                "zip" => $request->zip_code
            ));

            $consumer_data = new ConsumerData(array(
                "full_name" => $request->first_name . '' . $request->last_name,
                "email" => $request->email,
                "phone_number" => $request->phone,
                "culture_name" => "en-us",
                "is_locked" => false,
                "is_data_restricted" => false,
            ));

            $initiateRequest->setPlanData($plan_data);
            $initiateRequest->setBillingAddress($billing_address);
            $initiateRequest->setConsumerData($consumer_data);

            $initResponse = $installmentPlanApi->installmentPlanInitiate($initiateRequest);

            // echo "Calling /installmentPlanApi...\r\n";
            // echo $initResponse->getResponseHeader();

            $createRequest = new CreateInstallmentPlanRequest();

            $createRequest->setCreditCardDetails(new CardData(array(
                'card_number' => $request->card_no,
                'card_cvv' => $request->cvv,
                'card_holder_full_name' => $request->card_holder_full_name,
                'card_exp_month' => $request->exp_mon,
                'card_exp_year' => $request->exp_year
            )));

            $createRequest->setInstallmentPlanNumber($initResponse->getInstallmentPlan()->getInstallmentPlanNumber());
            $createRequest->setPlanApprovalEvidence(new PlanApprovalEvidence(array(
                "are_terms_and_conditions_approved" => true
            )));

            $createResponse = $installmentPlanApi->installmentPlanCreate($createRequest);
            if ($createResponse->getResponseHeader('Succeeded') == true) {

                /*  USER DETAIL */
                if (!Auth::check()) {
                    $user = User::where(
                        'email',
                        $request->input('email')
                    )->first();
                    if (
                        empty($user) && $user == null
                    ) {
                        // $user = User::create([
                        //     'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                        //     'email' => $request->input('email'),
                        //     'password' => Hash::make($request->input('password')),
                        //     'roll_id' => 2,
                        // ]);
                    }
                    if (!empty($user->id)) {
                        $buyer = Buyer::where('user_id', $user->id)->first();
                        if (!empty($buyer) && $buyer == null) {
                            // $buyer = Buyer::create([
                            //     'user_id' => $user->id,
                            //     'name' => $request->input('first_name'),
                            //     'email' => $request->input('email'),
                            //     'phone_number' => $request->input('phone'),
                            //     'zip_code' => $request->input('zip_code')
                            // ]);
                        }
                    }
                } else {
                    $user = Auth::user();
                }

                $setting = Settings::find(1);
                $items = Cart::content();
                $totalAmount = 0;
                $tax = 0;
                if (!empty($items) && count($items) > 0) {
                    $subtotal = 0;
                    $discount = 0;

                    foreach ($items as $item) {
                        $subtotal += (float)$item->price * (int)$item->qty;
                    }

                    $service = ($subtotal * $setting->service_charges) / 100;
                    $tax = (($subtotal + $service) * $setting->tax) / 100;


                    $totalAmount = ($subtotal - $discount) + $tax + $request->input('shipping_cost') + $service;
                    $splitit_total = ($totalAmount / 100) * $setting->splitit_percentage + $setting->splitit_charges;
                    $totalAmount += $splitit_total;

                    $today = date("Ymd");
                    $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
                    $order_no = $today . $rand;



                    $order = Order::create([
                        'order_no' => $order_no,
                        // 'seller_id' => $product->seller_id,
                        'buyer_id' => auth()->user() ? auth()->user()->buyer->id : null, //Users table ID
                        'buyer_name' => $buyer->name ?? $request->input('first_name') . ' ' . $request->input('last_name'),
                        'buyer_email' => $user->email ?? $request->input('email'),
                        'sub_total' => $subtotal,
                        'tax' => $tax,
                        'shipping_cost' => $request->input('shipping_cost'),
                        'vat_charges' => $request->input('vat_charges'),
                        // 'shipping_name' => $request->input('shipping'),
                        // 'packaging_type' => $request->input('packaging_type'),
                        'total_amount' => $totalAmount,
                        'order_status' => 'pending',
                        'country' => $request->input('country'),
                        'state' => $request->input('state'),
                        'city' => $request->input('city'),
                        'address1' => $request->input('address1'),
                        // 'address2' => $request->input('address2'),
                        'zip' => $request->input('zip_code'),
                        'phone_no' => $request->input('phone'),
                        'note' => $request->input('note'),
                        'term_accepted' => $request->input('term_condition'),
                        'service_charges' => $request->input('service_charges'),
                        'order_status' => "paid",
                    ]);
                    $counter = 0;
                    foreach ($items as $index => $item) {
                        $shipping = (isset($request->shipping[$counter])) ? $request->shipping[$counter] : null;
                        $packaging_type = (isset($request->packaging_type[$counter])) ? $request->packaging_type[$counter] : null;
                        $shipping_cost_array = (isset($request->shipping_cost_array[$counter])) ? $request->shipping_cost_array[$counter] : null;

                        $counter++;
                        $product = Product::find($item->id);
                        $orderItems = OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $item->id,
                            'seller_id' => $product->seller_id,
                            'product_per_price' => $item->price,
                            'product_qty' => $item->qty,
                            'tax' => $tax,
                            'product_subtotal_price' => $subtotal,
                            'shipping_name' => $shipping,
                            'packaging_type' => $packaging_type,
                            'shipping_cost' => $shipping_cost_array,
                            'product_type' => $product->product_type,
                            'shipping_type' => $product->shipping,
                            'downloaded' => null,
                        ]);
                        $country = Countries::where('id', $request->country)->value('sortname');
                        $state = States::where('id', $request->state)->value('name');
                        $city = Cities::where('id', $request->city)->value('name');

                        $seller_address = SellerAddress::where('seller_id', $product->seller_id)->first();

                        $s_country = Countries::where(
                            'id',
                            $seller_address->country
                        )->value('sortname');
                        $s_state = States::where('id', $seller_address->state)->value('name');
                        $s_city = Cities::where('id', $seller_address->city)->value('name');

                        $postal_code = trim($request->zip_code);
                        $seller_address_data = ['country' => trim($s_country), 'state' => trim($s_state), 'city' => trim($s_city), 'postal_code' => trim($seller_address->zip_code), 'company_name' => trim($seller_address->company_name), 'email' => $seller_address->email, 'phone_number' => $seller_address->phone_no, 'name' => $seller_address->first_name . ' ' . $seller_address->last_name, 'address1' => trim($seller_address->address1)];
                        $address_data = ['country' => trim($country), 'state' => trim($state), 'city' => trim($city), 'postal_code' => trim($postal_code), 'name' => $request->first_name . ' ' . $request->last_name, 'phone_number' => $request->phone, 'address1' => trim($request->address1)];

                        if ($product->product_type == "Physical" && $product->shipping == 1) {
                            $ship_data =  CheckoutController::shipService($seller_address_data, $address_data, $product, $orderItems, $item);

                            if (!empty($ship_data) && count($ship_data) > 0) {
                                foreach ($ship_data as $ship_dataa) {
                                    OrderShippingLabel::create([
                                        'order_item_id' => $orderItems->id,
                                        'tracking_number' => $ship_dataa->CompletedShipmentDetail->CompletedPackageDetails[0]->TrackingIds[0]->TrackingNumber,
                                        'shipping_doc_name' => 'label_' . $ship_dataa->CompletedShipmentDetail->CompletedPackageDetails[0]->TrackingIds[0]->TrackingNumber . '.pdf'
                                    ]);
                                }
                            }
                        }
                    }
                    Payment::create([
                        'order_id' => $order->id,
                        'amount' => $totalAmount,
                        'pay_method_name' => 'Split IT',
                    ]);

                    // decrease the quantities of all the products in the cart
                    $this->decreaseQuantities();

                    // $setting = Settings::find(1);

                    $emailSetting = EmailSetting::find(1);
                    $mailData = array(
                        'order' => $order,
                        'countOrderItems' => count(array($orderItems)),
                        'orderItems' => $orderItems,
                        'setting' => $setting,
                        'email' => $setting->email,
                        'to' => $order->buyer_email,
                        'emailSetting' => $emailSetting,
                    );


                    Mail::send('front.emails.orderConfirmationEmail', $mailData, function ($message) use ($mailData) {
                        $message->to($mailData['to'])->from($mailData['email'])
                            ->subject('Order Confirmation');
                    });
                    Cart::instance('default')->destroy();
                    session()->forget('coupon');

                    return [
                        "status" => true,
                        "data" => ['order_no' => $order->order_no],
                        "errors" => [],
                        "message" => "Successfully added"
                    ];
                } else {
                } //Cart Count Check End


            } else {
                //                        return redirect('/shop')->with(['error' => 'Your Card Item is Empty']);
            } //SPLIT IT SUCCESS END

            // echo 'done';


        } catch (Exception $e) {
            throw $e;
        }
    }


    protected function decreaseQuantities()
    {

        foreach (Cart::content() as $item) {
            // $product = Product::with('products_options')->find($item->model->id);
            $product = Product::find($item->model->id);

            // if (count($item->options) > 0) {
            //     $itemx = explode(',', $item->options->options_id);
            //     foreach ($itemx as $productOption) {
            //         //                echo $productOption;
            //         $productOption = OptionProduct::where('option_val_id', $productOption)->where('product_id', $item->id)->first();
            //         $productOption->update([
            //             'qty' => $productOption->qty - $item->qty
            //         ]);
            //     }
            // }


            $product->update(['qty' => $product->qty - $item->qty]);
        }
    }

    protected function productsAreNoLongerAvailable()
    {
        foreach (Cart::content() as $item) {
            $product = Product::find($item->model->id);
            if ($product->product_qty < $item->qty) {
                return true;
            }
        }

        return false;
    }


    private function validateCoupon($couponCode, $subtotal)
    {
        $isValidCoupon = false;
        $couponError = false;
        $discount = 0;

        if ($couponCode != null || $couponCode != '') {

            $userId = 0;

            if (Auth::check()) {
                $userId = Auth::user()->customers->id;
            }
            $coupon = Coupon::where('code', $couponCode)->whereStatus(1)->first();
            //            print_r($coupon);
            //            echo "asd";
            if ($coupon != null) {
                if ($coupon->expiration_date != null && $coupon->expiration_date <= date('Y-m-d')) {
                    $couponError = true;
                }

                if ($coupon->customer_id != null) {
                    $customers = explode(',', $coupon->customer_id);
                    if (!in_array($userId, $customers)) {
                        $couponError = true;
                    }
                }
                /*  Usage: In case of specific customers */
                if ($coupon->customer_id != null && $coupon->usage == 0) {
                    $couponError = true;
                }
                if ($coupon->usage === $coupon->used) {
                    $couponError = true;
                }
            } else {
                $couponError = true;
            }
        } else {
            $couponError = true;
        }

        if ($couponError == false) {
            if ($coupon->type == 'value') {
                $discount = $coupon->value;
            } else {
                $discount = ($subtotal / 100) * $coupon->value;
            }
            $isValidCoupon = true;
        }

        return array('isValidCoupon' => $isValidCoupon, 'discount' => $discount);
    }

    private function decreaseCouponUsage($couponCode)
    {
        $coupon = Coupon::where('code', $couponCode)->whereStatus(1)->first();

        if ($coupon != null) {
            $used = $coupon->used;
            $coupon->used = $used + 1;
            $coupon->save();
        }
    }


    public function productClaim(Request $request)
    {
        // dd($request->all());

        /*  USER DETAIL */
        try {
            if (!Auth::check()) {
                $user = User::where(
                    'email',
                    $request->input('email')
                )->first();
                if (
                    empty($user) && $user == null
                ) {
                    // $user = User::create([
                    //     'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                    //     'email' => $request->input('email'),
                    //     'password' => Hash::make($request->input('password')),
                    //     'roll_id' => 2,
                    // ]);
                }
                if (!empty($user->id)) {
                    $buyer = Buyer::where('user_id', $user->id)->first();
                    if (!empty($buyer) && $buyer == null) {
                        // $buyer = Buyer::create([
                        //     'user_id' => $user->id,
                        //     'name' => $request->input('first_name'),
                        //     'email' => $request->input('email'),
                        //     'phone_number' => $request->input('phone'),
                        //     'zip_code' => $request->input('zip_code')
                        // ]);
                    }
                }
            } else {
                $user = Auth::user();
            }

            $today = date("Ymd");
            $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
            $order_no = $today . $rand;
            $product = Product::where('id', $request->product_id)->first();

            $order = Order::create([
                'order_no' => $order_no,
                'seller_id' => $product->seller_id,
                'buyer_id' => auth()->user() ? auth()->user()->buyer->id : null, //Users table ID
                'buyer_name' => $buyer->name ?? $request->input('first_name') . ' ' . $request->input('last_name'),
                'buyer_email' => $user->email ?? $request->input('email'),
                'sub_total' => 0,
                'tax' => 0,
                'shipping_cost' => 0,
                'vat_charges' => 0,
                // 'shipping_name' => $request->input('shipping'),
                // 'packaging_type' => $request->input('packaging_type'),
                'total_amount' => 0,
                'order_status' => 'paid',
                'country' => $request->input('country'),
                'state' => $request->input('state'),
                'city' => $request->input('city'),
                'address1' => $request->input('address1'),
                // 'address2' => $request->input('address2'),
                'zip' => $request->input('zip_code'),
                'phone_no' => $request->input('phone'),
                'note' => $request->input('note'),
                'term_accepted' => $request->input('term_condition'),
                'service_charges' => $request->input('service_charges') ?? 0,
                // 'order_status' => "paid",
            ]);

            $counter = 0;
            $shipping = (isset($request->shipping[$counter])) ? $request->shipping[$counter] : null;
            $packaging_type = (isset($request->packaging_type[$counter])) ? $request->packaging_type[$counter] : null;
            $shipping_cost_array = (isset($request->shipping_cost_array[$counter])) ? $request->shipping_cost_array[$counter] : null;

            $counter++;

            $orderItems = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $request->product_id,
                'seller_id' => $product->seller_id,
                'product_per_price' => $product->price,
                'product_qty' => 1,
                'tax' => 0,
                'product_subtotal_price' => 0,
                'shipping_name' => $shipping,
                'packaging_type' => $packaging_type,
                'shipping_cost' => $shipping_cost_array,
                'product_type' => $product->product_type,
                'shipping_type' => $product->shipping,
                'downloaded' => 'download',
            ]);

            // $pathToFile = url('/uploads/products/'.$product->product_file);
            // return redirect()->back()->with('downloadfile' , $pathToFile);

            //PDF file is stored under project/public/download/info.pdf
            $file = public_path() . '/uploads/products/' . $product->product_file;

            $headers = array(
                'Content-Type: application/zip',
            );
            FacadesSession::flash('download', $file);
            return response()->download($file, date('ymd') . $product->product_file, $headers);
        } catch (\Exception $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
