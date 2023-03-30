<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if form was submitted

    // echo"<pre>";
    // echo $_POST['payment'];
    // echo $_POST['stripeToken'];
    // echo"</pre>";
    
    // die;
    
    $payment = $_POST['payment'];
    $token = $_POST['stripeToken'];
    
    require('stripe/stripe-php/init.php');
    
    $secretKey = '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';
    
    \Stripe\Stripe::setApiKey($secretKey);
    
    try{
        $data = \Stripe\Charge::create(array(
            "amount"=>$payment,
            "currency"=>"GBP",
            "description"=>"Van shipping",
            "source"=>$token,
        ));
        echo "<script>localStorage.setItem('error', '1');</script>";
        echo "<script>localStorage.setItem('Massage', 'Transaction Successful);</script>";
    }
    catch (\Stripe\Exception\CardException $e){
        $ErrorMassage = $e->getError()->message;
        echo "<script>localStorage.setItem('error', '1');</script>";
        echo "<script>localStorage.setItem('Massage', '".$ErrorMassage."');</script>";
    }
    catch (\Stripe\Exception\InvalidRequestException $e) {
        $ErrorMassage = $e->getError()->message;
        echo "<script>localStorage.setItem('error', '1');</script>";
        echo "<script>localStorage.setItem('Massage', '".$ErrorMassage."');</script>";
    } catch (Exception $e) {
        $errors= $e->getError()->message;
        error_log("Another problem occurred, maybe unrelated to Stripe.");
        echo "<script>localStorage.setItem('error', '1');</script>";
        echo "<script>localStorage.setItem('Massage', 'Another problem occurred, maybe unrelated to Stripe.');</script>";
      }
  }
  

?>
<!doctype html>
<html lang="en">
	
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"
          name="viewport">
    <meta content="ie=edge" http-equiv="X-UA-Compatible">
    <title>Local Van Quotes</title>

    <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
    <link crossorigin="anonymous"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
          integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw=="
          referrerpolicy="no-referrer" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            max-width: 100%;
        }

        .blue {
            color: #4761A5;
        }

        .bg-primary-color {
            background-color: #4761A5;
        }

        .btn-primary {

            border: 1px solid #4761A5 !important;
            background-color: #4761A5 !important;
            color: #ffffff;
        }

        .btn-primary:hover {

            background-color: #4761A5;
            border: 1px solid #4761A5 !important;
        }


        .bold, label {
            font-weight: 700;
        }

        label:hover {
            cursor: pointer;
        }

        h2 {
            font-weight: bold;
        }

        .center {
            align-content: center;
            text-align: center;
        }

        .card {
            padding: 8px 0;
        }

        span {
            font-weight: 700;
        }

        .col-md-3 {
            margin-top: 0.5rem;
        }

        .van-active {
            background-color: #4761A5;
            border-color: white;
            color: white;
        }

        .disabled1 {
            background-color: gray;
        }

        .input-lg {
            font-size: 1.29rem;
        }
    </style>

</head>
<body>
<div class="container">
    <div class="row container">
        <div class="col-md-12 mt-3">

            <div class="form-row align-items-center">
                <div class="col-md-4 col-sm-12">
                    <label for="originPostCode">Collection Postcode</label>
                    <input class="form-control input-lg" id="originPostCode" name="originPostCode"
                           placeholder="Collection Postcode"
                           type="text">
                </div>
                <div class="col-md-1 col-sm-12 mt-4 text-center bold">
					<!-- <img alt="Match" src=""> -->
                    <img alt="right" src="./image/right.svg">
                </div>
                <div class="col-md-4 col-sm-12">
                    <label for="destinationPostCode">Destination Postcode</label>
                    <input class="form-control input-lg" id="destinationPostCode" name="destinationPostCode"
                           placeholder="Destination Postcode"
                           type="text">
                </div>
                <div class="col-md-1  col-sm-12 mt-4 text-center bold">
                  <img alt="right" src="./image/right.svg">
                </div>
                <div class="col-md-2 col-sm-12 mt-4">
                    <button class="btn btn-primary col" onclick="getMiles()">Get Free Quote</button>
                </div>
            </div>
        </div>
    </div>

<div class=" d-none" id="dataBox">

    <div class="row mt-3" id="part2">

        <input id="miles" name="miles" type="hidden" value="0">


        <div class="col-md-12 mt-3">
            <h2>Which vehicle do you need?</h2>
            <p>
                It is important that you select the correct vehicle for the amount that you wish to move. Please be sure
                to consult our Size Guide for advice on the size of vehicle that would be suitable for you.
            </p>
        </div>
        <div class="col-md-12 bg-primary-color">
            <div class="row my-3">
                <div class="col-md-3 col-sm-6">

                    <div class="card" data-html="true" data-placement="top" data-toggle="tooltip" data-trigger="hover"
                         title="Typical Specifications<br>Length: 1.2 m / 5.58 ft<br>Width: 1.49 m / 4.89 ft<br>Height: 1.2 m / 3.94 ft<br>Payload: 600 - 900kg<br>Seats (inc driver): 2">

                        <div class="center">
                            <label for="1">
                                <img alt="small van" src="./image/SMALL%20VAN%20(1).jpg" class="img-fluid w-50">
                                <br>
                                <input id="1" name="van-size" onchange="updateTruck()" type="radio" value="small">
                                Small Van
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">

                    <div class="card" data-html="true" data-placement="top" data-toggle="tooltip" data-trigger="hover"
                         title="Typical Specifications<br>Length: 2.4 m / 7.87 ft<br>Width: 1.7 m / 5.58 ft<br>Height: 1.4 m / 4.59 ft<br>Payload: 600 - 1200kg<br>Seats (inc driver): 3">
                        <div class="center">
                            <label for="2">
                                <img alt="Medium Van" src="./image/MEDIUM%20VAN%20(1).jpg" class="img-fluid w-50">
                                <br>
                                <input id="2" name="van-size" onchange="updateTruck()" type="radio" value="medium">
                                Medium Van
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">

                    <div class="card" data-html="true" data-placement="top" data-toggle="tooltip" data-trigger="hover"
                         title="Typical Specifications<br>Length: 3.4 m / 11.16 ft<br>Width: 1.7 m / 5.58 ft<br>Height: 1.7 m / 5.58 ft<br>Payload: 1,200 - 1,500kg<br>Seats (inc driver): 3">

                        <div class="center">
                            <label for="3">
                                <img alt="Large van" src="./image/LARGE%20VAN%20(1).jpg" class="img-fluid w-50">
                                <br>
                                <input checked id="3" name="van-size" onchange="updateTruck()" type="radio"
                                       value="large">
                                Large van
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">

                    <div class="card" data-html="true" data-placement="top" data-toggle="tooltip" data-trigger="hover"
                         title="Typical Specifications<br>Length: 4.0 m / 13.12 ft<br>Width: 2.0 m / 6.56 ft<br>Height: 2.2 m / 7.22 ft<br>Payload: 1,200 - 1,600kg<br>Seats (inc driver): 3">

                        <div class="center">
                            <label for="4">
                                <img alt="Luton Van" src="./image/LUTON%20VAN%20(1).jpg" class="img-fluid w-50">
                                <br>
                                <input id="4" name="van-size" onchange="updateTruck()" type="radio" value="luton">
                                Luton Van
                            </label>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="col-md-12 m-2 mt-3 pt-3">
            <h2>Will you need help with loading and unloading?</h2>
            <p>
                Our drivers will be happy to help you with your move, and we can also provide extra people if you'd like
                to make your move a bit quicker.
            </p>
        </div>

        <div class="col-md-12 bg-primary-color">
            <div class="row my-3">
                <!--                <div class="col-md-3 col-sm-6">-->

                <!--                    <div class="card" data-toggle="tooltip" data-placement="top" data-html="true" data-trigger="hover" title="Your driver will arrive and drive only, he will not load and unload your items for you">-->
                <!--                        <div class="center">-->
                <!--                            <label for="0helper">-->
                <!--                                <img alt="no_help" src="../no-help.PNG" width="24%">-->
                <!--                                <br>-->
                <!--                                <input checked id="0helper" value="0" name="helper" type="radio">-->
                <!--                                No help needed-->
                <!--                            </label>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->
                <div class="col-md-4 mt-3 col-sm-4">

                    <div class="card" data-html="true" data-placement="top" data-toggle="tooltip" data-trigger="hover"
                         title="Your driver will arrive and load and unload your items for you">
                        <div class="center">
                            <label for="1helper">
                                <img alt="Driver" src="./image/driver-2.PNG" width="22%">
                                <br>
                                <input checked id="1helper" name="helper" type="radio" value="1">
                                Driver helping
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-3 col-sm-4">

                    <div class="card" data-html="true" data-placement="top" data-toggle="tooltip" data-trigger="hover"
                         title="Your driver will arrive with one other helper and both will load and unload your items for you">
                        <div class="center">
                            <label for="2helper">
                                <img alt="1_Person" src="./image/1-person.PNG" width="23%">
                                <br>
                                <input id="2helper" name="helper" type="radio" value="2">
                                Driver helping + 1 helper
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mt-3 col-sm-4">

                    <div class="card" data-html="true" data-placement="top" data-toggle="tooltip" data-trigger="hover"
                         title="Your driver will arrive with two other helpers and all will load and unload your items for you">
                        <div class="center">
                            <label for="3helper">
                                <img alt="2_Person" src="./image/2-person.PNG" width="33%">
                                <br>
                                <input id="3helper" name="helper" type="radio" value="3">
                                Driver helping + 2 helpers
                            </label>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="col-md-12 m-2 pt-3 mt-3">
            <h2> Can I travel in the van?</h2>
        </div>


        <div class="col-md-12 bg-primary-color py-2">
            <h4 class="text-white px-2" id="travelWithVan">
                <i data-feather="bell"></i>
                Due to the Covid-19 outbreak, only one passenger will be allowed to travel in the van and this will be
                at the driver's discretion.
            </h4>

        </div>

        <div class="col-md-12 mt-3 m-2 pt-3">
            <h2>
                Where you're moving

            </h2>
            <p> Your Local Van quotes depend on how much work needs to be carried out at each end of the move, things
                like stairs might increase the prices, but it's important that we know everything so that we can give
                you
                accurate quotes.
            </p>
        </div>

        <div class="col-md-12 bg-primary-color text-white pb-5">
            <h3 class="bold mt-3">Collection Address</h3>
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="pickupStreet">Street Address</label>
                    <input class="form-control" id="pickupStreet" name="pickupStreet" type="text">
                </div>
                <div class="col-md-3">
                    <label for="pickupCity">City</label>
                    <input class="form-control" id="pickupCity" name="pickupCity" type="text">
                </div>
                <div class="col-md-3">
                    <label for="pickupPostCode">Postcode</label>
                    <input class="form-control" id="pickupPostCode" name="pickupPostCode" type="text">
                </div>
                <div class="col-md-3">
                    <label for="collection_stairs">At my collection address there</label>
                    <select class="form-control" id="collection_stairs" name="delivery_stairs">
                        <option value="0">are no flights of stairs</option>
                        <option value="1">is 1 flight of stairs</option>
                        <option value="2">are 2 flights of stairs</option>
                        <option value="3">are 3 flights of stairs</option>
                        <option value="4">are 4 flights of stairs</option>
                        <option value="5">are 5 flights of stairs</option>
                        <option value="6">are 6 flights of stairs</option>
                        <option value="7">are 7 flights of stairs</option>
                        <option value="8">are 8 flights of stairs</option>
                        <option value="9">are 9 flights of stairs</option>
                        <option value="10">are 10 flights of stairs</option>

                    </select>
                </div>

            </div>
            <br>
            <img alt="to" class="img-responsive" src="./image/arrow.svg" width="10%">
            <h3 class="bold mt-3">Delivery Address</h3>
            <div class="form-row align-items-center">
                <div class="col-md-3">
                    <label for="deliveryStreet">Street Address</label>
                    <input class="form-control" id="deliveryStreet" name="deliveryStreet" type="text">
                </div>
                <div class="col-md-3">
                    <label for="deliveryCity">City</label>
                    <input class="form-control" id="deliveryCity" name="deliveryCity" type="text">
                </div>
                <div class="col-md-3">
                    <label for="deliveryPostCode">Postcode</label>
                    <input class="form-control" id="deliveryPostCode" name="deliveryPostCode" type="text">
                </div>
                <div class="col-md-3">
                    <label for="delivery_stairs">At my delivery address there</label>
                    <select class="form-control" id="delivery_stairs" name="delivery_stairs">
                        <option value="0">are no flights of stairs</option>
                        <option value="1">is 1 flight of stairs</option>
                        <option value="2">are 2 flights of stairs</option>
                        <option value="3">are 3 flights of stairs</option>
                        <option value="4">are 4 flights of stairs</option>
                        <option value="5">are 5 flights of stairs</option>
                        <option value="6">are 6 flights of stairs</option>
                        <option value="7">are 7 flights of stairs</option>
                        <option value="8">are 8 flights of stairs</option>
                        <option value="9">are 9 flights of stairs</option>
                        <option value="10">are 10 flights of stairs</option>

                    </select>
                </div>

            </div>

        </div>
        <!--        yahan rehta ha-->


        <div class="col-md-12 m-2 pt-3">
            <h2>
                How many hours do you want the vehicle for?
            </h2>

            <p class="mt-2"> We only charge from pick up to delivery, we estimate that your move will take around <span id="durationInHours" class="bold"></span>, if you think it will take less time you can reduce the number of hours. If you do
                need more time on the day all of our drivers have a pay as you go rate. Need more help? See our Help &
                Support section on the top menu.
            </p>
        </div>

<!--        <input type="hidden" name="durationInHours" id="durationInHours" value="3">-->
        <div class="col-md-12 col-sm-12 bg-primary-color text-white py-3">

            <h5 class="px-2">
                <label for="vanTime">I need the vehicle for :</label>

                <select id="vanTime" class=" form-control col-sm-12" name="vanTime">

                    <option selected value="3">3 hours</option>
                    <option value="3.5">3.5 hours</option>
                    <option value="4">4 hours</option>
                    <option value="4.5">4.5 hours</option>
                    <option value="5">5 hours</option>
                    <option value="5.5">5.5 hours</option>
                    <option value="6">6 hours</option>
                    <option value="6.5">6.5 hours</option>
                    <option value="7">7 hours</option>
                    <option value="7.5">7.5 hours</option>
                    <option value="8">8 hours</option>
                    <option value="8.5">8.5 hours</option>
                    <option value="9">9 hours</option>
                    <option value="9.5">9.5 hours</option>
                    <option value="10">10 hours</option>
                    <option value="10.5">10.5 hours</option>
                    <option value="11">11 hours</option>
                    <option value="11.5">11.5 hours</option>
                    <option value="12">12 hours</option>
                    <option value="12.5">12 hours.5</option>
                    <option value="13">13 hours</option>
                    <option value="13.5">13.5 hours</option>
                    <option value="14">14 hours</option>
                    <option value="14.5">14.5 hours</option>
                    <option value="15">15 hours</option>

                </select>


            </h5>
            <p class="mt-2" >The time above is the estimated driving time only. Don't forget to add on time for loading and unloading.</p>


        </div>

        <div class="col-md-12 m-2 pt-3">
            <h2>
                Where you're moving

            </h2>
            <p>
                Tell us when you're moving, so we can check and guarantee the drivers availability.
            </p>
        </div>


        <div class="col-md-12 bg-primary-color text-white py-3">

            <h5 class="px-2">
                <label for="moveDate" class="mt-2">I am planning to move on :</label>
                <input class="moveDate" id="moveDate" name="moveDate" placeholder="Choose a date" type="text" autocomplete="off">

                <label for="moveTime" class="mt-2">at:</label>
                <select id="moveTime" name="moveTime">
                    <option value="00:00">00:00</option>
                    <option value="00:15">00:15</option>
                    <option value="00:30">00:30</option>
                    <option value="00:45">00:45</option>
                    <option value="01:00">01:00</option>
                    <option value="01:15">01:15</option>
                    <option value="01:30">01:30</option>
                    <option value="01:45">01:45</option>
                    <option value="02:00">02:00</option>
                    <option value="02:15">02:15</option>
                    <option value="02:30">02:30</option>
                    <option value="02:45">02:45</option>
                    <option value="03:00">03:00</option>
                    <option value="03:15">03:15</option>
                    <option value="03:30">03:30</option>
                    <option value="03:45">03:45</option>
                    <option value="04:00">04:00</option>
                    <option value="04:15">04:15</option>
                    <option value="04:30">04:30</option>
                    <option value="04:45">04:45</option>
                    <option value="05:00">05:00</option>
                    <option value="05:15">05:15</option>
                    <option value="05:30">05:30</option>
                    <option value="05:45">05:45</option>
                    <option value="06:00">06:00</option>
                    <option value="06:15">06:15</option>
                    <option value="06:30">06:30</option>
                    <option value="06:45">06:45</option>
                    <option value="07:00">07:00</option>
                    <option value="07:15">07:15</option>
                    <option value="07:30">07:30</option>
                    <option value="07:45">07:45</option>
                    <option value="08:00">08:00</option>
                    <option value="08:15">08:15</option>
                    <option value="08:30">08:30</option>
                    <option value="08:45">08:45</option>
                    <option selected="selected" value="09:00">09:00</option>
                    <option value="09:15">09:15</option>
                    <option value="09:30">09:30</option>
                    <option value="09:45">09:45</option>
                    <option value="10:00">10:00</option>
                    <option value="10:15">10:15</option>
                    <option value="10:30">10:30</option>
                    <option value="10:45">10:45</option>
                    <option value="11:00">11:00</option>
                    <option value="11:15">11:15</option>
                    <option value="11:30">11:30</option>
                    <option value="11:45">11:45</option>
                    <option value="12:00">12:00</option>
                    <option value="12:15">12:15</option>
                    <option value="12:30">12:30</option>
                    <option value="12:45">12:45</option>
                    <option value="13:00">13:00</option>
                    <option value="13:15">13:15</option>
                    <option value="13:30">13:30</option>
                    <option value="13:45">13:45</option>
                    <option value="14:00">14:00</option>
                    <option value="14:15">14:15</option>
                    <option value="14:30">14:30</option>
                    <option value="14:45">14:45</option>
                    <option value="15:00">15:00</option>
                    <option value="15:15">15:15</option>
                    <option value="15:30">15:30</option>
                    <option value="15:45">15:45</option>
                    <option value="16:00">16:00</option>
                    <option value="16:15">16:15</option>
                    <option value="16:30">16:30</option>
                    <option value="16:45">16:45</option>
                    <option value="17:00">17:00</option>
                    <option value="17:15">17:15</option>
                    <option value="17:30">17:30</option>
                    <option value="17:45">17:45</option>
                    <option value="18:00">18:00</option>
                    <option value="18:15">18:15</option>
                    <option value="18:30">18:30</option>
                    <option value="18:45">18:45</option>
                    <option value="19:00">19:00</option>
                    <option value="19:15">19:15</option>
                    <option value="19:30">19:30</option>
                    <option value="19:45">19:45</option>
                    <option value="20:00">20:00</option>
                    <option value="20:15">20:15</option>
                    <option value="20:30">20:30</option>
                    <option value="20:45">20:45</option>
                    <option value="21:00">21:00</option>
                    <option value="21:15">21:15</option>
                    <option value="21:30">21:30</option>
                    <option value="21:45">21:45</option>
                    <option value="22:00">22:00</option>
                    <option value="22:15">22:15</option>
                    <option value="22:30">22:30</option>
                    <option value="22:45">22:45</option>
                    <option value="23:00">23:00</option>
                    <option value="23:15">23:15</option>
                    <option value="23:30">23:30</option>
                    <option value="23:45">23:45</option>
                </select>
                <br>
            </h5>

            <h6 class=" px-2">
                <label class="mt-3" for="description">Please enter a brief description of the items you will be moving
                    and any additional
                    contact numbers. Please also let us know if you need any items assembled or re-assembled.</label>
                <textarea class="form-control" id="description" name="description" placeholder="Description"></textarea>
            </h6>

        </div>


        <div class="col-md-12 m-2 pt-3">
            <h2>
                About you


            </h2>
            <p>
                We ask for your details so that we can send you a text with a link to your quotes. When you book we'll
                send a confirmation email and pass your name, email address and phone number to the driver you choose.
            </p>
        </div>

        <div class="col-md-12 bg-primary-color text-white py-3">
            <form action="" method="post" id="details_form">
            <div class="form-row align-items-center">
                <div class="col">
                    <label for="name">Name</label>
                    <input class="form-control" id="name" name="name" placeholder="Name..." type="text">
                </div>
                <div class="col">
                    <label for="email">Email</label>
                    <input class="form-control" id="email" name="email" placeholder="Email..." type="email">
                </div>
                <div class="col">
                    <label for="phone">Phone</label>
                    <input class="form-control" id="phone" name="phone" placeholder="Phone..." type="tel">
                </div>
                <input type="hidden" name="van_size" id="van_size">
                <input type="hidden" name="man_required" id="man_required">
                <input type="hidden" name="to" id="to">
                <input type="hidden" name="from" id="from">
                <input type="hidden" name="date" id="date">
                <input type="hidden" name="time" id="time">
                <input type="hidden" name="from_stairs" id="from_stairs">
                <input type="hidden" name="to_stairs" id="to_stairs">
                <input type="hidden" name="price" id="price">
                <input type="hidden" name="miles" id="milesTotal">

<!--                <input type="hidden" name="from" id="from">-->


            </div>
            </form>
        </div>
        <p style="font-size: 11px;color: red"> *We will be in touch about this move by email and text message. </p>

    </div>
    <div class="row" id="btn">
        <div class="col-md-3 m-2 pt-3"></div>
        <div class="col-md-6 m-2 pt-3">
            <!--                <p class="center loading d-none">Loading...</p>-->
            <button class="btn btn-primary loading col-md-12" onclick="getQuotes()">Get me the quote <i class="mt-1 pt-1"
                                                                                                data-feather="truck"></i>
            </button>

        </div>
        <div class="col-md-3 m-2 pt-3"></div>
    </div>
    <div class="row mt-5" id="part3">
        <div class="card col-md-12 shadow-lg mb-3">
            <div class="text-center">

                <img alt="van" class="text-center" src="./image/van-1.svg" width="10%">
                <h3>Your Estimated Quote Is </h3>

                <h4 class="blue bold" id="quote">0</h4>

                <p>We have received your details our company's representative will get back to you shortly.
                    Please save this order number with you #2022-12
                </p>

                <!-- <form method="POST">
                  <input type="number" name="payment" value="" id="quoteStrip" class="form-control d-none">
                  <button type="submit" id="checkout-button" class="btn btn-primary ">Place order</button>
                </form>  -->
<!-- 
                <form  method="post">
                  <input type="number" name="payment" value="" id="quoteStrip" class="form-control d-none">
                    <script id="scripts" src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="pk_test_51MqvJ6DaZYlKuMDpj5Bz2QQ5sTIdl8AhLqgyXRob69dlU7nRnuMgeXXkaQ78WzzrtMDcSvHGNbm5FKsOZS6QRGd100dszWaN8L"
                    data-amount=""
                    data-name="Van Shipping"
                    data-description="Program code"
                    data-image="https://local-van.com/wp-content/uploads/2020/08/Untitled-2.png"
                    data-currency="GBP"
                    >
                    </script>
                </form> -->


                <form  method="post">
                  <input type="number" name="payment" value="" id="quoteStrip" class="form-control d-none">
                    <script id="scripts" src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                    data-key="pk_live_51MC4OlKoGRAfYvh6e7hQqkuedZmMHXnsDsc7O7b4Q91FD8D33XqmAZ2JDO0BnqA7UbtDWx1YclmzrqBoAXIJaLAB00PMsMRmss"
                    data-amount=""
                    data-name="Van Shipping"
                    data-description="Program code"
                    data-image="https://local-van.com/wp-content/uploads/2020/08/Untitled-2.png"
                    data-currency="GBP"
                    >
                    </script>
                </form>


            </div>
        </div>
    </div>
</div>

</div>

<script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCj5aV5dl-3uYtjIWsCr3Ot8C7twgLFI5U&libraries=places">
</script>

<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script crossorigin="anonymous"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script crossorigin="anonymous"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
<script crossorigin="anonymous"
        integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ=="
        referrerpolicy="no-referrer"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>


<script>
    //adding autoComplete originPostCode field
    $(document).ready(function(){

var autocomplete;
var id='originPostCode';
autocomplete=new google.maps.places.Autocomplete((document.getElementById(id)),{
  types:['geocode'],
})
});

    
</script>
<script>
    //adding autoComplete originPostCode field
    $(document).ready(function(){

var autocomplete;
var id='destinationPostCode';
autocomplete=new google.maps.places.Autocomplete((document.getElementById(id)),{
  types:['geocode'],
})
});

    
</script>

<script>
    // $('#part2').hide();
    $('#btn').hide();
    $('#part3').hide();
    $('#moveDate').datepicker({
        format: "dd/mm/yyyy",
        startDate: "today",
        todayBtn: true,
        clearBtn: true,
        language: "en-GB",
        orientation: "top auto",
        autoClose: true,
        todayHighlight: true,
        toggleActive: true
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    feather.replace();

    updateTruck();
    updateHelp();


    $("input[name=\"helper\"]").on("click change", function () {
        updateHelp();
    });



    function updateTruck() {

        $("input[name=\"van-size\"]").parent().parent().parent().removeClass("van-active");

        $("input[name=\"van-size\"]:checked").parent().parent().parent().addClass("van-active");

        seatCheck();
    }

    function updateHelp() {

        $("input[name=\"helper\"]").parent().parent().parent().removeClass("van-active");

        $("input[name=\"helper\"]:checked").parent().parent().parent().addClass("van-active");
        seatCheck();


    }

    function seatCheck() {
        let van = $("input[name=\"van-size\"]:checked").val();
        let helper = $("input[name=\"helper\"]:checked").val();
        if (van <= 1) {
            $("#3helper").prop("disabled", true).parent().parent().parent().addClass("disabled1");
        } else {
            $("#3helper").prop("disabled", false).parent().parent().parent().removeClass("disabled1");
        }

        if (helper >= 3) {

            $("#travelWithVan").html('Based on your selection there won\'t be any room for passengers to travel in the van');
        } else {
            $("#travelWithVan").html('<i data-feather="bell"></i>' + 'Due to the Covid-19 outbreak, only one passenger will be allowed to travel in the van and this will be at the driver\'s discretion.');
        }
    }

    function getMiles() {

// show the div again
  


        //here we are sending the origin and destination to google maps and h
        var origin = $('#originPostCode').val();
 
        
        var destination = $('#destinationPostCode').val();
        origin = origin.toUpperCase();
        destination = destination.toUpperCase();

        if (!origin || !destination) {
            alert('Please fill both the inputs!');
            return false;
        }

        let ans = origin.includes("PO", 0) || origin.includes("BN", 0) || origin.includes("GU", 0) || origin.includes("RH", 0) || origin.includes("SO", 0);
        // let ans2 = destination.includes("PO", 0) || destination.includes("BN", 0) || destination.includes("GU", 0) || destination.includes("RH", 0) || destination.includes("SO", 0);

        if (!ans) {alert('only PO, BN, SO, RH, GU are covered');
            return false;}
        $('#deliveryPostCode').val(destination);
        $('#pickupPostCode').val(origin);

        // console.log(origin,destination);
        var service = new google.maps.DistanceMatrixService();

        service.getDistanceMatrix({
            origins: ['Po197bj', origin],
            destinations: [origin, destination],
            travelMode: google.maps.TravelMode.DRIVING,
            unitSystem: google.maps.TravelMode.IMPERIAL,
            avoidHighways: false,
            avoidTolls: false,
        }, callback);

        // function checking if the result came from google map or not
        function callback(response, status) {
            if (status === 'OK') {
                console.log(response);
                if (response.rows[0].elements[0].status === 'OK' && response.rows[1].elements[1].status === 'OK') {

                    var distance1 = response.rows[0].elements[0].distance.value;
                    var duration1 = response.rows[0].elements[0].duration.value;
                    var distance2 = response.rows[1].elements[1].distance.value;
                    var duration2 = response.rows[1].elements[1].duration.value;

                    if (!distance1 || !distance2) {
                        alert('Address Not Found!');
                        return false;}
                    var miles = (distance1 / 1609.34) + (distance2 / 1609.24);
                    var durationInMins = (duration1 + duration2)/60;
                    var durationInHours = durationInMins/60;



                    if (durationInHours > 3){

                        $('#vanTime').val(Math.ceil(durationInHours));

                    }
                    else{
                        $('#vanTime').val(3);

                    }
                    $("#durationInHours").text(Math.ceil(durationInHours) + " hours");
                    // we are calling find result function where all the calculations takes place and sending the miles value with it
                    let pickupAreas = response.destinationAddresses[0].split(',');
                    let deliveryAreas = response.destinationAddresses[1].split(',');
                    if (pickupAreas.length > 2){

                        $('#pickupStreet').val(pickupAreas[0]);
                        $('#pickupCity').val(pickupAreas[1]);

                    }
                    else{
                        $('#pickupCity').val(pickupAreas[0]);
                    }
                    if (deliveryAreas.length > 2){

                        $('#deliveryStreet').val(deliveryAreas[0]);
                        $('#deliveryCity').val(deliveryAreas[1]);
                        
                    }
                    else{
                        $('#deliveryCity').val(deliveryAreas[0]);
                    }


                    $('#miles').val(Math.round(miles));

                    $('#part2').show(750);
                    $('#btn').show(750);
             document.getElementById("dataBox").className="";
                } else {
                    alert('Wrong Postal Code!');
                    return false;
                }

            }
        }

    }

    function getQuotes() {

        $('.loading').text('Calculating...');
        let vanSize = $("input[name=\"van-size\"]:checked").val();
        let helpers = $("input[name=\"helper\"]:checked").val();
        let miles = parseFloat($("#miles").val());
        let name = $("#name").val();
        let email = $("#email").val();
        let phone = $("#phone").val();
        let deliveryStreet = $("#deliveryStreet").val();
        let deliveryCity = $("#deliveryCity").val();
        let pickupStreet = $("#pickupStreet").val();
        let pickupCity = $("#pickupCity").val();
        let moveDate = $("#moveDate").val();
        let moveTime = $("#moveTime").val();
        let origin = $('#originPostCode').val();
        let destination = $('#destinationPostCode').val();


        document.getElementById('van_size').value = vanSize;
        document.getElementById('man_required').value = helpers;
        document.getElementById('to').value = deliveryStreet + ' ' + deliveryCity + ' '  + destination;
        document.getElementById('from').value = pickupStreet + ' '+ pickupCity + ' '  + origin;
        document.getElementById('date').value = moveDate;
        document.getElementById('time').value = moveTime;

        document.getElementById('milesTotal').value = miles;

        if (!name) {
            alert('Please Enter The Name');
            return false;
        }
        if (!email) {
            alert('Please Enter The Email');
            return false;
        }
        if (!phone) {
            alert('Please Enter The Phone');
            return false;
        }
        if (!pickupStreet || !pickupCity) {
            alert('Please Enter The Pickup Address');
            return false;
        }
        if (!deliveryStreet || !deliveryCity) {
            alert('Please Enter The Delivery Address');
            return false;
        }
        // if (!c) {
        //     alert('Please Enter The Move Date');
        //     return false;
        // }


        let basePrice = 0;

        switch (vanSize) {
            case 'small' :
                basePrice = helpers < 2 ? 30 : 40;
                break;
            case 'medium' :
                basePrice = helpers < 2 ? 40 : 50;
                break;
            case 'large' :
                basePrice = helpers < 2 ? 50 : 60;
                break;
            case 'luton' :
                basePrice = helpers < 2 ? 50 : 60;
                break;

        }

        let hours = parseFloat($("#vanTime").val());

        let newPrice = basePrice * hours;
        let perStairCharge = 0.15 * newPrice;

        let collectionStairs = parseFloat($("#collection_stairs").val());
        let deliveryStairs = parseFloat($("#delivery_stairs").val());
        document.getElementById('from_stairs').value = collectionStairs;
        document.getElementById('to_stairs').value = deliveryStairs;


        newPrice += perStairCharge * helpers * (collectionStairs + deliveryStairs);

        let finalPrice = newPrice + miles;
        document.getElementById('price').value = finalPrice;
        // alert("hello");

        $('.loading').text('Get me the quote');

$('html, body').animate({
    scrollTop: $("#part3").show().offset().top
}, 2000);


$('#quote').text("£ " + finalPrice);

// $('#quoteStrip').value(finalPrice);
document.getElementById("quoteStrip").value = finalPrice*100;


    }

window.onload = (event) =>{

    let errorFound = localStorage.getItem('error');

    if (errorFound==""||errorFound==undefined){

    }
    else if (errorFound==1||errorFound=="1"){
        alert("Transaction Failed:"+localStorage.getItem('Massage'));
        localStorage.clear();
    }

};


</script>
</body>
</html>
