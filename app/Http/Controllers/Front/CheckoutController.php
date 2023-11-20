<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\CustomerAddress;
use App\Models\Customers;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Settings;
use App\Models\States;
use App\SellerAddress;
use App\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use FedEx\RateService\Request as FedexRequest;
use FedEx\RateService\ComplexType;
use FedEx\RateService\SimpleType;
use SoapClient;
use FedEx\AddressValidationService\Request as FedExValidationRequest;
use FedEx\AddressValidationService\ComplexType as FedExValidationComplexType;
use FedEx\AddressValidationService\SimpleType as FedExValidationSimpleType;

use FedEx\ValidationAvailabilityAndCommitmentService\Request as FedexAvailableRequest;
use FedEx\ValidationAvailabilityAndCommitmentService\ComplexType as FedexAvailble;
use FedEx\ValidationAvailabilityAndCommitmentService\SimpleType as FedexAvailbleSimple;
use FedEx\ShipService;
use FedEx\ShipService\ComplexType as shipComplexType;
use FedEx\ShipService\SimpleType as shipSimpleType;
use ZipArchive;
use Illuminate\Support\Facades\File;

class CheckoutController extends Controller
{
    public function __construct()
    {
        ini_set('default_socket_timeout', 600);
    }

    public function index()
    {


        if (Auth::check() && Auth::user()->role_id == 2) {
            return redirect()->route('all-products.index');
        }

        if (Auth::check() && Auth::user()->role_id == 1) {
            return redirect()->route('all-products.index');
        }

        $items = Cart::content();

        $products = array();
        $totalAmount = 0;
        $vat_charges = 0;
        if (!empty($items) && count($items) > 0) {
            foreach ($items as $item) {
                $Product = Product::find($item->id);
                $amount = (float)$item->price * (int)$item->qty;
                $totalAmount += $amount;
                $vat_charges += $Product->vat;
                $product = array(
                    'row_id' => $item->rowId,
                    'productId' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price,
                    'subtotal' => $amount,
                    'quantity' => $item->qty,
                    'options' => $item->options,
                    'product_type' => $Product->product_type,
                    'shipping' => $Product->shipping,
                    'shipping_charges' => $Product->shipping_charges,
                    'vat_charges' => $Product->vat,
                    'product_image' => $Product->product_image,
                );
                $products[] = $product;
            }
        } else {
            return redirect('/all-products');
        }

        if (Auth::user() && Auth::user()->role_id == 3) {
            $shippingAddress = CustomerAddress::where('customer_id', Auth::user()->buyer->id)->first();
            if ($shippingAddress) {
                $state = States::where('id', $shippingAddress->state)->first();
                $city = Cities::where('id', $shippingAddress->city)->first();
            } else {
                $shippingAddress = [];
                $state = [];
                $city = [];
            }
        } else {
            $shippingAddress = [];
            $state = [];
            $city = [];
        }

        $settings = Settings::with('shipping_cost')->first();
        $countries = Countries::all();

        return view('front.checkout.index', compact('products', 'totalAmount', 'settings', 'shippingAddress', 'state', 'city', 'countries', 'vat_charges'));
    }



    public function success($order_no)
    {
        $order = Order::where('order_no', $order_no)->with(['orderItems.product', 'orderItems' => function ($q) {
            $q->where('product_type', 'Downloadable')->whereNull('downloaded');
        }])->first();

        try {
            if (!empty($order->orderItems) && count($order->orderItems)) {

                $zip = new ZipArchive();
                $tempFile = tmpfile();
                $tempFileUri = stream_get_meta_data($tempFile)['uri'];

                // store the public path
                $publicDir = public_path('uploads/downloadable');
                $zip_name = time() . ".zip"; // Zip name
                if ($zip->open(public_path('uploads/downloadable/' . $zip_name), ZipArchive::CREATE) === TRUE) {

                    foreach ($order->orderItems as $orderItem) {
                        $path = public_path('uploads/products/' . $orderItem->product->product_file);
                        if (!$zip->addFile($path, basename($path))) {
                            echo 'Could not add file to ZIP: ' . $path;
                        }
                    }
                    $zip->close();
                } else {
                    echo 'Could not open ZIP file.';
                }
                $path = '/download/' . $order_no . '/' . $zip_name;
                return view('front.checkout.success', compact('path'));
            } else {
                return view('front.checkout.success');
            }
        } catch (\Exception $ex) {
            return view('front.checkout.success')->withErrors($ex->getMessage());
        }
        // return response()->download(public_path($zip_name));
    }


    public function download($orderNo, $file_name)
    {

        $order = Order::where('order_no', $orderNo)->with(['orderItems.product', 'orderItems' => function ($q) {
            $q->where('product_type', 'Downloadable')->whereNull('downloaded');
        }])->first();

        if (!empty($order->orderItems) && count($order->orderItems)) {
            foreach ($order->orderItems as $orderItem) {
                $orderItem->update([
                    'downloaded' => "yes"
                ]);
            }
            $file_path = public_path('uploads/downloadable/' . $file_name);
            return response()->download($file_path);
        } else {
            $file_path = public_path('uploads/downloadable/' . $file_name);
            File::delete($file_path);
            return view('front.checkout.download-error');
        }
        // $file_path = public_path($file_name);
        // return response()->download($file_path);
    }

    protected function decreaseQuantities()
    {
        foreach (Cart::content() as $item) {
            $product = Product::find($item->model->id);

            $product->update(['quantity' => $product->quantity - $item->qty]);
        }
    }

    protected function productsAreNoLongerAvailable()
    {
        foreach (Cart::content() as $item) {
            $product = Product::find($item->model->id);
            if ($product->quantity < $item->qty) {
                return true;
            }
        }

        return false;
    }


    //Fedex get frieght Rate
    public function getFreghtRate(Request $request)
    {
        // return $this->validateFedexAddress();

        $d = Cart::get($request->row_id);
        $data = [];
        $product = Product::where('id', $request->id)->first();

        $country = Countries::where('id', $request->country)->value('sortname');
        $state = States::where('id', $request->state)->value('name');
        $city = Cities::where('id', $request->city)->value('name');

        $seller_address = SellerAddress::where('seller_id', $product->seller_id)->first();

        $s_country = Countries::where('id', $seller_address->country)->value('sortname');
        $s_state = States::where('id', $seller_address->state)->value('name');
        $s_city = Cities::where('id', $seller_address->city)->value('name');

        $postal_code = $request->zip_code;
        $seller_address_data = ['country' => trim($s_country), 'state' => trim($s_state), 'city' => trim($s_city), 'postal_code' => trim($seller_address->zip_code), 'company_name' => trim($seller_address->company_name), 'email' => $seller_address->email, 'phone_number' => $seller_address->phone_no, 'name' => $seller_address->first_name . ' ' . $seller_address->last_name, 'address1' => trim($seller_address->address1)];
        $address_data = ['country' => trim($country), 'state' => trim($state), 'city' => trim($city), 'postal_code' => trim($postal_code), 'name' => $request->first_name . ' ' . $request->last_name, 'phone_number' => $request->phone_number, 'address1' => trim($request->address1)];

        $availble_service = $this->fedexAvailableService($address_data, $seller_address_data);
        // $shipService = $this->shipService($seller_address_data, $address_data, $product, $order_data = null);
        // dd($$availble_service)
        if ($availble_service->HighestSeverity == "ERROR") {
            $data['status'] = false;
            $data['message'] = $availble_service->Notifications[0]->Message;
            return $data;
        }
        try {

            $rateRequest = new ComplexType\RateRequest();

            //authentication & client details
            $rateRequest->WebAuthenticationDetail->UserCredential->Key = env("FEDEX_KEY");
            $rateRequest->WebAuthenticationDetail->UserCredential->Password = env("FEDEX_PASSWORD");
            $rateRequest->ClientDetail->AccountNumber = env("FEDEX_ACCOUNT_NUMBER");
            $rateRequest->ClientDetail->MeterNumber = env("FEDEX_METER_NUMBER");

            $rateRequest->TransactionDetail->CustomerTransactionId = 'testing rate service request';

            //version
            $rateRequest->Version->ServiceId = 'crs';
            $rateRequest->Version->Major = 28;
            $rateRequest->Version->Minor = 0;
            $rateRequest->Version->Intermediate = 0;

            $rateRequest->ReturnTransitAndCommit = true;

            //shipper
            $rateRequest->RequestedShipment->PreferredCurrency = 'USD';
            $rateRequest->RequestedShipment->Shipper->Address->StreetLines = $seller_address->address1;
            $rateRequest->RequestedShipment->Shipper->Address->City = $seller_address_data['city'];
            $rateRequest->RequestedShipment->Shipper->Address->StateOrProvinceCode = '';
            $rateRequest->RequestedShipment->Shipper->Address->PostalCode = $seller_address_data['postal_code'];
            $rateRequest->RequestedShipment->Shipper->Address->CountryCode = $seller_address_data['country'];

            //recipient
            $rateRequest->RequestedShipment->Recipient->Address->StreetLines = $request->address1;
            $rateRequest->RequestedShipment->Recipient->Address->City = $city;
            $rateRequest->RequestedShipment->Recipient->Address->StateOrProvinceCode = '';
            $rateRequest->RequestedShipment->Recipient->Address->PostalCode = $postal_code;
            $rateRequest->RequestedShipment->Recipient->Address->CountryCode = $country;

            //shipping charges payment
            $rateRequest->RequestedShipment->ShippingChargesPayment->PaymentType = SimpleType\PaymentType::_SENDER;

            //rate request types
            $rateRequest->RequestedShipment->RateRequestTypes = [];

            $rateRequest->RequestedShipment->PackageCount = $d->qty;
            $abc = [];
            for ($i = 0; $i < $d->qty; $i++) {
                $abc[] = new ComplexType\RequestedPackageLineItem();
            }

            $rateRequest->RequestedShipment->RequestedPackageLineItems = $abc;

            for ($i = 0; $i < $d->qty; $i++) {
                //package 1
                $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Weight->Value = (int)$product->weight;
                $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Weight->Units = SimpleType\WeightUnits::_LB;
                $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Dimensions->Length = (int)$product->length;
                $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Dimensions->Width = (int)$product->width;
                $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Dimensions->Height = (int)$product->height;
                $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->Dimensions->Units = SimpleType\LinearUnits::_IN;
                $rateRequest->RequestedShipment->RequestedPackageLineItems[$i]->GroupPackageCount = 1;
            }

            // dd($rateRequest);
            $rateServiceRequest = new FedexRequest();
            $rateServiceRequest->getSoapClient()->__setLocation(FedexRequest::PRODUCTION_URL); //use production URL

            $rateReply = $rateServiceRequest->getGetRatesReply($rateRequest); // send true as the 2nd argument to return the SoapClient's stdClass response.

            // dd($rateReply);
            $service_type = '';
            $currenct_amount = '';
            $amount = 0;
            if (!empty($rateReply->RateReplyDetails) && $rateReply->HighestSeverity != "ERROR") {
                foreach ($rateReply->RateReplyDetails as $rateReplyDetail) {
                    $service_type = $rateReplyDetail->ServiceType;
                    $packaging_type = $rateReplyDetail->PackagingType;
                    if (!empty($rateReplyDetail->RatedShipmentDetails)) {
                        foreach ($rateReplyDetail->RatedShipmentDetails as $ratedShipmentDetail) {
                            $currenct_amount = $ratedShipmentDetail->ShipmentRateDetail->TotalNetCharge->Currency . ' ' . $ratedShipmentDetail->ShipmentRateDetail->TotalNetCharge->Amount;
                            $amount = $ratedShipmentDetail->ShipmentRateDetail->TotalNetCharge->Amount;

                            $data['status'] = true;
                            $data['message'] = "Successfull";
                            $data[$service_type] = ['currency_amount' => $currenct_amount, 'amount' => $amount, 'service_type' => $service_type, 'packaging_type' => $packaging_type];
                        }
                    }
                }
            } else {
                $data['status'] = false;
                $data['message'] = $rateReply->Notifications[0]->Message;
            }
            return $data;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    //WORKING ONLY IN TESTING
    public function validateFedexAddress()
    {
        // $validation = new FedExValidationComplexType\AddressValidationRequest();

        // $validation->WebAuthenticationDetail->UserCredential->Key = 'bGj3NHRq18wUGjHt';
        // $validation->WebAuthenticationDetail->UserCredential->Password = 'ewDxF9ZYRVjMyCK0QoRKXwUoM';
        // $validation->ClientDetail->AccountNumber = 756309056;
        // $validation->ClientDetail->MeterNumber = 253985882;

        $addressValidationRequest = new FedExValidationComplexType\AddressValidationRequest();

        // User Credentials
        $addressValidationRequest->WebAuthenticationDetail->UserCredential->Key =
            'bGj3NHRq18wUGjHt';
        $addressValidationRequest->WebAuthenticationDetail->UserCredential->Password =
            'ewDxF9ZYRVjMyCK0QoRKXwUoM';

        // Client Detail
        $addressValidationRequest->ClientDetail->AccountNumber = 756309056;
        $addressValidationRequest->ClientDetail->MeterNumber = 253985882;

        // Version
        $addressValidationRequest->Version->ServiceId = 'aval';
        $addressValidationRequest->Version->Major = 4;
        $addressValidationRequest->Version->Intermediate = 0;
        $addressValidationRequest->Version->Minor = 0;

        // Address(es) to validate.
        $addressValidationRequest->AddressesToValidate = [new FedExValidationComplexType\AddressToValidate()]; // just validating 1 address in this example.
        $addressValidationRequest->AddressesToValidate[0]->Address->StreetLines = ['12345 Main Street'];
        $addressValidationRequest->AddressesToValidate[0]->Address->City = 'Anytown';
        $addressValidationRequest->AddressesToValidate[0]->Address->StateOrProvinceCode = 'NY';
        $addressValidationRequest->AddressesToValidate[0]->Address->PostalCode = 47711;
        $addressValidationRequest->AddressesToValidate[0]->Address->CountryCode = 'US';

        $request = new FedExValidationRequest();
        //$request->getSoapClient()->__setLocation(Request::PRODUCTION_URL);
        $request->getSoapClient()->__setLocation(FedExValidationRequest::PRODUCTION_URL);
        $addressValidationReply = $request->getAddressValidationReply($addressValidationRequest);

        var_dump($addressValidationReply);
    }


    public function fedexAvailableService($address_data, $seller_address_data)
    {
        $shipDate = new \DateTime();
        $serviceAvailabilityRequest = new FedexAvailble\ServiceAvailabilityRequest();
        //web authentication detail
        $serviceAvailabilityRequest->WebAuthenticationDetail->UserCredential->Key = env("FEDEX_KEY");
        $serviceAvailabilityRequest->WebAuthenticationDetail->UserCredential->Password = env("FEDEX_PASSWORD");
        //client detail
        $serviceAvailabilityRequest->ClientDetail->AccountNumber = env("FEDEX_ACCOUNT_NUMBER");
        $serviceAvailabilityRequest->ClientDetail->MeterNumber = env("FEDEX_METER_NUMBER");
        //version
        $serviceAvailabilityRequest->Version->ServiceId = 'vacs';
        $serviceAvailabilityRequest->Version->Major = 14;
        $serviceAvailabilityRequest->Version->Intermediate = 0;
        $serviceAvailabilityRequest->Version->Minor = 0;
        //origin
        $serviceAvailabilityRequest->Origin->PostalCode = $seller_address_data['postal_code'];
        $serviceAvailabilityRequest->Origin->CountryCode = $seller_address_data['country'];
        //destination
        $serviceAvailabilityRequest->Destination->PostalCode = $address_data['postal_code'];
        $serviceAvailabilityRequest->Destination->CountryCode = $address_data['country'];
        //ship date
        $serviceAvailabilityRequest->ShipDate = $shipDate->format('Y-m-d');

        $request = new FedexAvailableRequest();
        $request->getSoapClient()->__setLocation(FedexRequest::PRODUCTION_URL);

        try {
            $serviceAvailabilityReply = $request->getServiceAvailabilityReply($serviceAvailabilityRequest);
            return $serviceAvailabilityReply;
        } catch (\Exception $e) {
            var_dump($request->getSoapClient()->__getLastResponse());
        }
    }

    public static function shipService($shipper_data, $recipient_data, $product_data, $order_data, $cartItem)
    {

        $userCredential = new shipComplexType\WebAuthenticationCredential();
        $userCredential
            ->setKey(env("FEDEX_KEY"))
            ->setPassword(env("FEDEX_PASSWORD"));

        $webAuthenticationDetail = new shipComplexType\WebAuthenticationDetail();
        $webAuthenticationDetail->setUserCredential($userCredential);

        $clientDetail = new shipComplexType\ClientDetail();
        $clientDetail
            ->setAccountNumber(env("FEDEX_ACCOUNT_NUMBER"))
            ->setMeterNumber(env("FEDEX_METER_NUMBER"));

        $version = new shipComplexType\VersionId();
        $version
            ->setMajor(26)
            ->setIntermediate(0)
            ->setMinor(0)
            ->setServiceId('ship');

        $shipperAddress = new shipComplexType\Address();
        $shipperAddress
            ->setStreetLines($shipper_data['address1'])
            ->setCity($shipper_data['city'])
            ->setStateOrProvinceCode($shipper_data['state'])
            ->setPostalCode($shipper_data['postal_code'])
            ->setCountryCode($shipper_data['country']);

        $shipperContact = new shipComplexType\Contact();
        $shipperContact
            ->setCompanyName($shipper_data['company_name'])
            ->setEMailAddress($shipper_data['email'])
            ->setPersonName($shipper_data['name'])
            ->setPhoneNumber(($shipper_data['phone_number']));

        $shipper = new shipComplexType\Party();
        $shipper
            ->setAccountNumber(env("FEDEX_ACCOUNT_NUMBER"))
            ->setAddress($shipperAddress)
            ->setContact($shipperContact);

        $recipientAddress = new shipComplexType\Address();
        $recipientAddress
            ->setStreetLines($recipient_data['address1'])
            ->setCity($recipient_data['city'])
            ->setStateOrProvinceCode($recipient_data['state'])
            ->setPostalCode($recipient_data['postal_code'])
            ->setCountryCode($recipient_data['country']);

        $recipientContact = new shipComplexType\Contact();
        $recipientContact
            ->setPersonName($recipient_data['name'])
            ->setPhoneNumber($recipient_data['phone_number']);

        $recipient = new shipComplexType\Party();
        $recipient
            ->setAddress($recipientAddress)
            ->setContact($recipientContact);

        // dd($cartItem->qty);
        $packageLineItemArray = [];
        $counter = 0;
        $resultData = [];
        $master = null;
        for ($i = 1; $i <= (int)$cartItem->qty; $i++) {
            $counter++;
            $labelSpecification = new shipComplexType\LabelSpecification();
            $labelSpecification
                ->setLabelStockType(new shipSimpleType\LabelStockType(shipSimpleType\LabelStockType::_PAPER_7X4POINT75))
                ->setImageType(new shipSimpleType\ShippingDocumentImageType(shipSimpleType\ShippingDocumentImageType::_PDF))
                ->setLabelFormatType(new shipSimpleType\LabelFormatType(shipSimpleType\LabelFormatType::_COMMON2D));
            // dd($cartItem->qty);

            // echo ${"packageLineItem" . "_" . "counter"};
            $packageLineItem1  = new shipComplexType\RequestedPackageLineItem();
            $packageLineItem1
                ->setSequenceNumber($counter)
                ->setItemDescription('Box #' . $counter)
                ->setDimensions(new shipComplexType\Dimensions(array(
                    'Width' => $product_data->width,
                    'Height' => $product_data->height,
                    'Length' => $product_data->length,
                    'Units' => shipSimpleType\LinearUnits::_IN
                )))
                ->setWeight(new shipComplexType\Weight(array(
                    'Value' => $product_data->weight,
                    'Units' => shipSimpleType\WeightUnits::_LB
                )));
            // $packageLineItemArray[] = ${"packageLineItem" . "_" . "counter"};

            // dd($packageLineItemArray);
            $shippingChargesPayor = new shipComplexType\Payor();
            $shippingChargesPayor->setResponsibleParty($shipper);

            $shippingChargesPayment = new shipComplexType\Payment();
            $shippingChargesPayment
                ->setPaymentType(shipSimpleType\PaymentType::_SENDER)
                ->setPayor($shippingChargesPayor);

            $requestedShipment = new shipComplexType\RequestedShipment();
            $requestedShipment->setShipTimestamp(date('c'));

            if ($i > 1) {
                $requestedShipment->setMasterTrackingId($master);
            }

            $requestedShipment->setDropoffType(new shipSimpleType\DropoffType(shipSimpleType\DropoffType::_REGULAR_PICKUP));

            $serviceType = strtoupper($order_data->shipping_name);
            $packaging_type = strtoupper($order_data->packaging_type);
            // dd($order_data);
            $requestedShipment->setServiceType(new shipSimpleType\ServiceType($serviceType));
            $requestedShipment->setPackagingType(new shipSimpleType\PackagingType($packaging_type));
            $requestedShipment->setShipper($shipper);
            $requestedShipment->setRecipient($recipient);
            $requestedShipment->setLabelSpecification($labelSpecification);
            $requestedShipment->setRateRequestTypes([]);
            $requestedShipment->setPackageCount((int)$cartItem->qty);
            $abc = [];
            // foreach ($packageLineItemArray as $index => $packageLineItemArraySingle) {
            //     $abc[] = $packageLineItemArraySingle;

            //     // echo "<pre>";
            //     // print_r($packageLineItemArraySingle);
            // }

            $requestedShipment->setRequestedPackageLineItems([
                $packageLineItem1,
            ]);
            // dd($abc);

            $requestedShipment->setShippingChargesPayment($shippingChargesPayment);

            $processShipmentRequest = new shipComplexType\ProcessShipmentRequest();
            $processShipmentRequest->setWebAuthenticationDetail($webAuthenticationDetail);
            $processShipmentRequest->setClientDetail($clientDetail);
            $processShipmentRequest->setVersion($version);
            $processShipmentRequest->setRequestedShipment($requestedShipment);

            $shipService = new ShipService\Request();
            // $shipService->getSoapClient()->__setLocation('https://wsbeta.fedex.com:443/web-services/ship');
            $shipService->getSoapClient()->__setLocation('https://ws.fedex.com:443/web-services/ship');
            $result = $shipService->getProcessShipmentReply($processShipmentRequest);

            if (!empty($result->CompletedShipmentDetail->CompletedPackageDetails[0])) {
                if ($i == 1) {
                    $master =  $result->CompletedShipmentDetail->MasterTrackingId;
                    $TrackingNumber =  $result->CompletedShipmentDetail->MasterTrackingId->TrackingNumber;
                } else {
                    // print_r($result->CompletedShipmentDetail->CompletedPackageDetails[0]->TrackingIds);
                }
                // Tracking Number
                $tracking_number = $result->CompletedShipmentDetail->CompletedPackageDetails[0]->TrackingIds[0]->TrackingNumber;
                // Save .pdf label
                file_put_contents('uploads/shipmentDocs/label_' . $tracking_number . '.pdf', $result->CompletedShipmentDetail->CompletedPackageDetails[0]->Label->Parts[0]->Image);
                $resultData[] = $result;
                // dd($result);
            } else {
            }
        }
        // dd($resultData);
        return $resultData;
    }
}
