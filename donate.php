<?php
require_once 'config.php';

// Debug: Check if environment variables are loaded
if (!STRIPE_PUBLISHABLE_KEY || !PAYPAL_CLIENT_ID) {
    error_log("WARNING: API keys not loaded from environment variables");
    error_log("STRIPE_PUBLISHABLE_KEY: " . (STRIPE_PUBLISHABLE_KEY ? "SET" : "EMPTY"));
    error_log("PAYPAL_CLIENT_ID: " . (PAYPAL_CLIENT_ID ? "SET" : "EMPTY"));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Donation</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/donate.css">
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo PAYPAL_CLIENT_ID; ?>&currency=USD"></script>
</head>
<body>
    <div class="container">
        <header>
            <nav>
                <div class="logo">Paypal & Stripe Demo Integration</div>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="documentation.php">Documentation</a></li>
                    <li><a href="donate.php">Donate</a></li>
                </ul>
            </nav>
        </header>

        <main class="donate-main">
            <div class="donate-container">
                <h1>Demo Donation Page</h1>
                <p class="subtitle">This is a test donation page meant to test the stripe & paypal integration</p>
                <p class="developer-credit">Developed & Integrated by Cris Neil John D. Hulleza</p>

                <form id="donation-form" class="donation-form">
                    <!-- Amount Selection -->
                    <div class="amount-section">
                        <h3>Select Amount</h3>
                        <div class="amount-options">
                            <button type="button" class="amount-btn active" data-amount="10">$10</button>
                            <button type="button" class="amount-btn" data-amount="25">$25</button>
                            <button type="button" class="amount-btn" data-amount="50">$50</button>
                            <button type="button" class="amount-btn" data-amount="100">$100</button>
                        </div>
                        <div class="custom-amount">
                            <label for="custom-amount">Or enter custom amount:</label>
                            <input type="number" id="custom-amount" name="amount" min="1" step="0.01" placeholder="Enter amount">
                        </div>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="payment-method-section">
                        <h3>Choose Payment Method</h3>
                        <div class="payment-options">
                            <div class="payment-option" data-method="stripe">
                                <div class="payment-icon stripe-icon">
                                    <img src="https://cdn-icons-png.flaticon.com/512/349/349221.png" alt="Stripe" style="width: 48px; height: 48px;">
                                </div>
                                <div class="payment-info">
                                    <h4>Credit/Debit Card</h4>
                                    <p>Secure payment via Stripe</p>
                                </div>
                                <div class="payment-radio">
                                    <input type="radio" name="payment_method" value="stripe" id="stripe-radio">
                                </div>
                            </div>

                            <div class="payment-option" data-method="paypal">
                                <div class="payment-icon paypal-icon">
                                    <img src="https://cdn-icons-png.flaticon.com/512/174/174861.png" alt="PayPal" style="width: 48px; height: 48px;">
                                </div>
                                <div class="payment-info">
                                    <h4>PayPal</h4>
                                    <p>Pay with your PayPal account</p>
                                </div>
                                <div class="payment-radio">
                                    <input type="radio" name="payment_method" value="paypal" id="paypal-radio">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stripe Card Section (Hidden by default) -->
                    <div id="stripe-card-section" style="display: none;">
                        <h3>Card Details</h3>
                        <div id="card-element" class="stripe-element"></div>
                        <div id="card-errors" role="alert"></div>
                    </div>

                    <!-- PayPal Button Section (Hidden by default) -->
                    <div id="paypal-button-section" style="display: none;">
                        <h3>Complete Payment</h3>
                        <div id="paypal-button-container"></div>
                    </div>

                    <!-- Submit Button for Stripe -->
                    <button type="submit" id="submit-btn" class="donate-btn" disabled>
                        Donate Now
                    </button>

                    <p class="secure-note">🔒 Your payment information is secure and encrypted</p>
                </form>
            </div>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Paypal & Stripe Demo Integration. All rights reserved.</p>
        </footer>
    </div>

    <script>
        // Check if Stripe key is loaded
        const stripeKey = '<?php echo STRIPE_PUBLISHABLE_KEY; ?>';
        if (!stripeKey || stripeKey === '') {
            console.error('STRIPE_PUBLISHABLE_KEY is not set. Please check your environment variables in Railway.');
            alert('Payment system configuration error. Please contact the administrator.');
        }
        
        const stripe = Stripe(stripeKey);
        const elements = stripe.elements();
        const cardElement = elements.create('card');
        cardElement.mount('#card-element');

        let selectedAmount = 10;
        let selectedPaymentMethod = null;
        let paypalButtonRendered = false;

        // Amount selection
        document.querySelectorAll('.amount-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                selectedAmount = parseFloat(this.dataset.amount);
                document.getElementById('custom-amount').value = '';
                updateSubmitButton();
                if (selectedPaymentMethod === 'paypal') {
                    renderPayPalButton();
                }
            });
        });

        document.getElementById('custom-amount').addEventListener('input', function() {
            document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
            selectedAmount = parseFloat(this.value) || 0;
            updateSubmitButton();
            if (selectedPaymentMethod === 'paypal') {
                renderPayPalButton();
            }
        });

        // Payment method selection
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');
                
                const method = this.dataset.method;
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                selectedPaymentMethod = method;

                // Show/hide payment sections
                if (method === 'stripe') {
                    document.getElementById('stripe-card-section').style.display = 'block';
                    document.getElementById('paypal-button-section').style.display = 'none';
                    document.getElementById('submit-btn').style.display = 'block';
                } else if (method === 'paypal') {
                    document.getElementById('stripe-card-section').style.display = 'none';
                    document.getElementById('paypal-button-section').style.display = 'block';
                    document.getElementById('submit-btn').style.display = 'none';
                    renderPayPalButton();
                }

                updateSubmitButton();
            });
        });

        function updateSubmitButton() {
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = !(selectedAmount > 0 && selectedPaymentMethod);
        }

        // Stripe card errors
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Stripe form submission
        document.getElementById('donation-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            if (selectedPaymentMethod !== 'stripe') return;

            const submitBtn = document.getElementById('submit-btn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Processing...';

            try {
                const response = await fetch('stripe/stripe_payment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ amount: selectedAmount })
                });

                // Check if response is ok
                if (!response.ok) {
                    const text = await response.text();
                    console.error('Server response:', text);
                    throw new Error('Server error: ' + response.status);
                }

                // Try to parse JSON
                const text = await response.text();
                console.log('Server response:', text);
                
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    console.error('Failed to parse JSON:', text);
                    throw new Error('Invalid server response. Check console for details.');
                }

                if (data.error) {
                    throw new Error(data.error);
                }

                const result = await stripe.confirmCardPayment(data.clientSecret, {
                    payment_method: { card: cardElement }
                });

                if (result.error) {
                    document.getElementById('card-errors').textContent = result.error.message;
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Donate Now';
                } else {
                    window.location.href = 'success.php?amount=' + selectedAmount + '&method=stripe';
                }
            } catch (error) {
                console.error('Payment error:', error);
                document.getElementById('card-errors').textContent = error.message;
                submitBtn.disabled = false;
                submitBtn.textContent = 'Donate Now';
            }
        });

        // PayPal button rendering
        function renderPayPalButton() {
            if (selectedAmount <= 0) return;

            const container = document.getElementById('paypal-button-container');
            container.innerHTML = '';

            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: selectedAmount.toFixed(2)
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        window.location.href = 'success.php?amount=' + selectedAmount + '&method=paypal&transaction=' + details.id;
                    });
                },
                onError: function(err) {
                    alert('Payment failed. Please try again.');
                    console.error(err);
                }
            }).render('#paypal-button-container');
        }
    </script>
</body>
</html>
