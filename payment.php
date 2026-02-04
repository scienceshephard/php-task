<!DOCTYPE html>
<html>

<head>
	<title>Payment-Method - Altech School Management System</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<link rel="stylesheet" type="text/css" media="all" href="css/bootstrap.min.css">
	<link rel="shortcut icon" href="img/logo icon.png" />
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="js/jquery-3.1.1.slim.min.js"></script>
	<script src="js/tether.min.js"></script>
	<script src="js/bootstrap.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"
		integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ=="
		crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<link rel="stylesheet" href="css/sign-up.css" rel="stylesheet" type="text/css" media="all">
	<link rel="stylesheet" href="css/owner.css" rel="stylesheet" type="text/css" media="all">
	<link href="css/animate.css" rel="stylesheet" type="text/css" media="all">

	<script src="js/wow.min.js"></script>
	<script>
		new WOW().init();
	</script>

	<style>
		#headVerify {
			width: 100%;
			background-repeat: no-repeat;
			background-color: rgb(255, 233, 0, 0);
		}

		#verifyHead {
			text-align: center;
			font-size: 18px;
		}

		.modal-title {
			font-weight: bold;
			color: #b500be;
		}

		.modal-header {
			background-color: lightgrey;
		}

		.modal-body {
			background-color: lightcyan;
			color: #8033cc;
			text-align: justify;
		}

		.modal-footer {
			background-color: lightgrey;
		}

		/* Email input styling */
		.email-group {
			margin-top: 15px;
		}

		.email-group label {
			font-weight: bold;
			margin-bottom: 5px;
		}
	</style>
</head>

<body>
	<div id="registrationHeader"></div>
	<div class="clearfix"> </div>
	<div class="container-fluid">
		<div class="row row-verify">
			<div id="headVerify">
				<div class="col-xs-12 col-sm-6 col-lg-6 offset-lg-3">
					<h2 class="text-center text-danger top-social wow bounceInLeft" data-wow-delay="0.3s">Expired!<i
							class="fa-solid fa-hourglass"></i></h2>

					<p>
					<h2 class="text-center text-success top-social wow bounceInLeft" data-wow-delay="0.3s">Your trial
						period or subscription has expired. Please activate your account to regain access to your dashboard.
					</h2>
					</p>
					<h3 id="verifyHead" class="wow zoomIn" data-wow-delay="0.3s">To reactivate your account, simply
						follow the process and get back on track. All your information and data are intact, ready for you to
						continue from where you left off</h3>
				</div>
			</div>
			<div class=" col-xs-12 col-sm-6 col-lg-6 offset-lg-3 makePayment" id="make_pyment">
				<h4 class="text-center">If you are still seeing this page after activation contact support to access your dashboard. 
					<button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal"
						data-bs-target="#staticBackdrop">Activation</button>
				</h4>
				<p class="d-flex justify-content-center">
					<button class="d-flex align-items-lg-center btn btn-success btn-lg" onclick="return payment();">
						make your payment
					</button>
				</p>
			</div>

			<!-- Modal -->
			<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
				aria-labelledby="staticBackdropLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title fs-5" id="staticBackdropLabel">Onboarding Process</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<p>Having experienced the benefits of the application and how it enhances the smooth running of your organization, 
								you can now chat directly with the admin to activate or renew your subscription. 
								Click <a href="https://wa.link/ch2f9b">HERE</a> to call or quickly activate your subscription, 
								depending on the duration you prefer.
							</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-lg-6 offset-lg-3">
					<div class="button-group text-center btn-inline " id="pay_method">
						<button class="btn btn-danger button-control" id="onlin_pay" onclick="return pay1();">
							online payment
						</button>
						<button class="btn btn-warning button-control" onclick="return pay2();" id="account_pay">
							pay to account
						</button>
					</div>

					<div class="justify-content-center" id="select-pay">
						<!-- Online Payment Section -->
						<div id="online-payment-section" style="display: none;">
							<div class="form-group">
								<select id="Subscription" name="Subscription" class="form-control form-control-lg" required>
									<option value="" selected>Select Subscription Types</option>
									<option value="6 months">6 months</option>
									<option value="1 year">1 year</option>
									<option value="2 years">2 years</option>
									<option value="3 years">3 years</option>
									<option value="4 years">4 years</option>
									<option value="5 years">5 years</option>
								</select>
								<p class="" id="errorfeed"></p>
							</div>

							<!-- Email Input -->
							<div class="form-group email-group">
								<label for="payment-email">Email Address *</label>
								<input type="email" id="payment-email" name="payment-email" 
									class="form-control form-control-lg" 
									placeholder="Enter your email address" required>
								<small class="form-text text-muted">Payment receipt will be sent to this email</small>
							</div>

							<!-- Hidden fields for account and user ID -->
							<input type="hidden" id="accountId" name="accountId" value="<?php echo isset($account_id) ? $account_id : ''; ?>">
							<input type="hidden" id="userId" name="userId" value="<?php echo isset($user_id) ? $user_id : ''; ?>">

							<p class="form-group">Amount to pay: 
								<span class="form-group text-danger font-weight-bold" id="amount"></span> NGN
							</p>

							<div class="d-flex justify-content-center mt-4">
								<button class="btn btn-success btn-lg" onclick="return handleOnlinePayment();" id="pay-now-btn">
									<i class="fa fa-credit-card"></i> Pay Now with Card
								</button>
							</div>
						</div>
					</div>

					<!-- Bank Transfer Section -->
					<div class="row-detail" id="detail">
						<div class="form-group">
							<select id="Subscription-bank" name="Subscription" class="form-control form-control-lg" required>
								<option value="" selected>Select Subscription Types</option>
								<option value="6 months">6 months</option>
								<option value="1 year">1 year</option>
								<option value="2 years">2 years</option>
								<option value="3 years">3 years</option>
								<option value="4 years">4 years</option>
								<option value="5 years">5 years</option>
							</select>
							<span class="text-danger">
								<?php if (isset($Subscription_error)) echo $Subscription_error; ?>
							</span>
							<p class="" id="errorfeed-bank"></p>
						</div>

						<p class="form-group">you will pay this amount
							<span class="form-group text-danger" id="amount-bank"></span>
							into the Account select the Fetch account button
						</p>
						<div style="margin: 40ms;">
							<button class="btn btn-warning" id="view" name="view">Fetch Account</button>
						</div>
					</div>

					<div class="" id="viewAccount" style="display: none;">
						<p>Account Name: <span style="color: #FF0000; font-weight: bold;">
								<font size="3">ALTECH SERVICES LTD</font>
							</span></p>
						<p>Account Number:<span style="color: #FF0000; font-weight: bold;">
								<font size="3">0017076235</font>
							</span></p>
						<p>Bank Name:<img src="img/gt_logo.jpg" width="50" height="50"><span>
								<font size="3" style="color: #FF0000; font-weight: bold;">GTB BANK</font>
							</span></p>

						<div class="col-xs-12 col-md-8 ">
							<form action="" method="POST">
								<input type="hidden" name="subscription" id="hiddenSubscription">
								<input type="hidden" name="amount" id="hiddenAmount">
								<p style="font-size: 20px;">
									<b>
										if you have made your payment click
										<button type="submit" name="paymentdet" value="1" 
											style="background:none;border:none;color:#FF0000;cursor:pointer;text-decoration:underline;">
											here
										</button>
										to send your payment details
									</b>
								</p>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="clearfix" style="padding: 20px;"></div>
	<div class="copy-right fixed-bottom">
		<div class="container-fluid">
			<div class="d-flex justify-content-center copy-rights-main wow zoomIn" data-wow-delay="0.3s">
				<p>&copy;
					<script>
						document.write(new Date().getFullYear());
					</script> Altech. All Rights Reserved
				</p>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>

	<!-- Squad Payment API Script -->
	<script src="payment_api.js"></script>

	<script>
		// Values coming from PHP
		const amountCharge = <?php echo json_encode($Amountcharge ?? 40000); ?>;
		const studentCount = <?php echo json_encode($StudentCount ?? 400); ?>;

		// Map subscription text to number of years
		const subscriptionYears = {
			"6 months": 0.5,
			"1 year": 1,
			"2 years": 2,
			"3 years": 3,
			"4 years": 4,
			"5 years": 5
		};

		// Calculate amount for online payment
		function calculateAmount() {
			const subscription = document.getElementById("Subscription").value;
			const amountSpan = document.getElementById("amount");

			if (subscription && subscriptionYears[subscription]) {
				const years = subscriptionYears[subscription];
				const total = amountCharge * studentCount * years;
				amountSpan.innerText = total.toLocaleString();
			} else {
				amountSpan.innerText = "";
			}
		}

		// Calculate amount for bank transfer
		function calculateAmountBank() {
			const subscription = document.getElementById("Subscription-bank").value;
			const amountSpan = document.getElementById("amount-bank");
			const hiddenSubscription = document.getElementById("hiddenSubscription");
			const hiddenAmount = document.getElementById("hiddenAmount");

			if (subscription && subscriptionYears[subscription]) {
				const years = subscriptionYears[subscription];
				const total = amountCharge * studentCount * years;

				amountSpan.innerText = total.toLocaleString();
				hiddenSubscription.value = subscription;
				hiddenAmount.value = total;
			} else {
				amountSpan.innerText = "";
				hiddenSubscription.value = "";
				hiddenAmount.value = "";
			}
		}

		// Attach listeners
		document.getElementById("Subscription").addEventListener("change", calculateAmount);
		document.getElementById("Subscription-bank").addEventListener("change", calculateAmountBank);

		// Handle payment method selection
		function pay1() {
			// Show online payment section
			document.getElementById("online-payment-section").style.display = "block";
			document.getElementById("detail").style.display = "none";
			document.getElementById("viewAccount").style.display = "none";
			return false;
		}

		function pay2() {
			// Show bank transfer section
			document.getElementById("online-payment-section").style.display = "none";
			document.getElementById("detail").style.display = "block";
			return false;
		}

		function payment() {
			document.getElementById("pay_method").style.display = "block";
			return false;
		}

		// Show account info for bank transfer
		document.querySelector('button[name="view"]').addEventListener("click", function(e) {
			e.preventDefault();

			const subscription = document.getElementById("Subscription-bank").value;
			const amount = document.getElementById("amount-bank").innerText;

			if (!subscription || !amount) {
				alert("Please select a subscription type first!");
				return;
			}

			document.getElementById("viewAccount").style.display = "block";
		});

		// Initialize payment sections as hidden
		document.addEventListener("DOMContentLoaded", function() {
			document.getElementById("pay_method").style.display = "none";
			document.getElementById("detail").style.display = "none";
			document.getElementById("online-payment-section").style.display = "none";
		});
	</script>
</body>

</html>