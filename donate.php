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
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.591-7.305z"/>
                                    </svg>
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
                                    <svg viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M20.067 8.478c.492.88.556 2.014.3 3.327-.74 3.806-3.276 5.12-6.514 5.12h-.5a.805.805 0 00-.794.68l-.04.22-.63 3.993-.028.15a.805.805 0 01-.793.679H7.72a.483.483 0 01-.477-.558L7.418 21h1.518l.95-6.02h1.385c4.678 0 7.75-2.203 8.796-6.502z"/>
                                        <path d="M2.379 0C1.94 0 1.6.358 1.549.793L.051 11.637a.668.668 0 00.66.764h4.145l1.04-6.58L5.85 6.4h2.695c4.011 0 6.753-1.528 7.572-4.72C16.595.56 15.395 0 13.878 0H2.379z"/>
                                    </svg>
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
