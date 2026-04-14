<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Integration Documentation</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/documentation.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css'>
</head>
<body>
    <div class="container">
        <header>
            <nav>
                <div class="logo">Paypal & Stripe Demo Integration</div>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="documentation.php" class="active">Documentation</a></li>
                    <li><a href="donate.php">Donate</a></li>
                </ul>
            </nav>
        </header>

        <main class="docs-main">
            <aside class="docs-sidebar">
                <h3>Integration Guide</h3>
                <ul class="docs-nav">
                    <li><a href="#purpose">Purpose</a></li>
                    <li><a href="#flow">Page Flow</a></li>
                    <li><a href="#prerequisites">Prerequisites</a></li>
                    <li><a href="#step1">Step 1: Setup</a></li>
                    <li><a href="#step2">Step 2: Configuration</a></li>
                    <li><a href="#step3">Step 3: Stripe</a></li>
                    <li><a href="#step4">Step 4: PayPal</a></li>
                    <li><a href="#step5">Step 5: Frontend</a></li>
                    <li><a href="#step6">Step 6: Testing</a></li>
                </ul>
            </aside>

            <div class="docs-content">
                <section id="overview" class="docs-section">
                    <div class="section-header">
                        <span class="icon"><i class="fi fi-sr-book-alt"></i></span>
                        <h1>Payment Integration Guide</h1>
                    </div>
                    <p class="lead">Step-by-step guide to integrate Stripe and PayPal</p>
                    <p>This guide walks you through the complete process of integrating both Stripe and PayPal payment gateways into a single donation page.</p>
                </section>

                <section id="purpose" class="docs-section">
                    <h2>Purpose of Integration</h2>
                    <p>This dual payment gateway integration was designed to provide users with maximum flexibility and choice when making donations or payments online.</p>
                    
                    <div class="purpose-grid">
                        <div class="purpose-card">
                            <div class="purpose-icon"><i class="fi fi-sr-target"></i></div>
                            <h3>User Choice</h3>
                            <p>Different users prefer different payment methods. By offering both Stripe (credit/debit cards) and PayPal, we ensure that every user can pay using their preferred method, reducing friction and increasing conversion rates.</p>
                        </div>
                        
                        <div class="purpose-card">
                            <div class="purpose-icon"><i class="fi fi-sr-lock"></i></div>
                            <h3>Security & Trust</h3>
                            <p>Both Stripe and PayPal are industry-leading payment processors with built-in fraud detection, PCI compliance, and secure payment handling. Users can trust that their payment information is safe.</p>
                        </div>
                        
                        <div class="purpose-card">
                            <div class="purpose-icon"><i class="fi fi-sr-world"></i></div>
                            <h3>Global Reach</h3>
                            <p>Stripe excels at card processing worldwide, while PayPal has a massive user base with existing accounts. Together, they provide comprehensive global payment coverage.</p>
                        </div>
                        
                        <div class="purpose-card">
                            <div class="purpose-icon"><i class="fi fi-sr-bolt"></i></div>
                            <h3>Seamless Experience</h3>
                            <p>The integration provides a unified interface where users can switch between payment methods without leaving the page, creating a smooth and professional checkout experience.</p>
                        </div>
                    </div>
                </section>

                <section id="flow" class="docs-section">
                    <h2>Page Flow</h2>
                    <p>Understanding how users navigate through the donation process helps clarify the integration architecture. Here's the complete user journey:</p>
                    
                    <div class="flow-steps">
                        <div class="flow-step">
                            <div class="flow-number"><i class="fi fi-sr-home"></i></div>
                            <div class="flow-content">
                                <h3>Landing Page (index.php)</h3>
                                <p>Users arrive at the homepage which introduces the donation platform. A prominent "Donate" button directs them to the payment page.</p>
                            </div>
                        </div>
                        
                        <div class="flow-arrow">↓</div>
                        
                        <div class="flow-step">
                            <div class="flow-number"><i class="fi fi-sr-credit-card"></i></div>
                            <div class="flow-content">
                                <h3>Donation Page (donate.php)</h3>
                                <p>Users see a clean interface with:</p>
                                <ul>
                                    <li>Amount selection (preset buttons or custom input)</li>
                                    <li>Payment method choice (Stripe or PayPal)</li>
                                    <li>Dynamic form that adapts based on selected method</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="flow-arrow">↓</div>
                        
                        <div class="flow-step">
                            <div class="flow-number"><i class="fi fi-sr-process"></i></div>
                            <div class="flow-content">
                                <h3>Payment Processing</h3>
                                <p><strong>If Stripe selected:</strong></p>
                                <ul>
                                    <li>Card input form appears (Stripe Elements)</li>
                                    <li>User enters card details</li>
                                    <li>Frontend sends amount to <code>stripe_payment.php</code></li>
                                    <li>Backend creates Payment Intent and returns client secret</li>
                                    <li>Frontend confirms payment with Stripe</li>
                                </ul>
                                <p><strong>If PayPal selected:</strong></p>
                                <ul>
                                    <li>PayPal Smart Button appears</li>
                                    <li>User clicks button and logs into PayPal</li>
                                    <li>PayPal handles entire payment flow</li>
                                    <li>Order is created and captured on PayPal's servers</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="flow-arrow">↓</div>
                        
                        <div class="flow-step">
                            <div class="flow-number"><i class="fi fi-sr-check-circle"></i></div>
                            <div class="flow-content">
                                <h3>Success Page (success.php)</h3>
                                <p>After successful payment, users are redirected to a confirmation page showing:</p>
                                <ul>
                                    <li>Success message with checkmark animation</li>
                                    <li>Thank you note</li>
                                    <li>Option to return to homepage</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="prerequisites" class="docs-section">
                    <h2>Prerequisites</h2>
                    <div class="info-box">
                        <p><i class="fi fi-sr-clipboard-list"></i> Before starting, make sure you have:</p>
                        <ul>
                            <li>PHP 7.4 or higher</li>
                            <li>Composer for dependency management</li>
                            <li>A Stripe account (sign up at <a href="https://stripe.com" target="_blank">stripe.com</a>)</li>
                            <li>A PayPal Developer account (sign up at <a href="https://developer.paypal.com" target="_blank">developer.paypal.com</a>)</li>
                        </ul>
                    </div>
                </section>

                <section id="step1" class="docs-section">
                    <h2>Step 1: Install Dependencies</h2>
                    <p>First, install the Stripe PHP library using Composer:</p>
                    
                    <div class="code-example">
                        <pre><code>composer require stripe/stripe-php</code></pre>
                    </div>
                    
                    <p>This will create a <code>vendor</code> folder with the Stripe SDK and a <code>composer.json</code> file.</p>
                </section>

                <section id="step2" class="docs-section">
                    <h2>Step 2: Configuration File</h2>
                    <p>Create a <code>config.php</code> file to store your API credentials:</p>
                    
                    <div class="code-example">
                        <h4>config.php</h4>
                        <pre><code>&lt;?php
// Stripe API Configuration
// These keys let us talk to Stripe's servers
// Get them from your Stripe Dashboard → Developers → API keys
define('STRIPE_SECRET_KEY', 'sk_test_your_key_here');
define('STRIPE_PUBLISHABLE_KEY', 'pk_test_your_key_here');

// PayPal API Configuration
// Similar deal - these authenticate us with PayPal
// Find them in PayPal Developer Dashboard → My Apps & Credentials
define('PAYPAL_CLIENT_ID', 'your_client_id_here');
define('PAYPAL_CLIENT_SECRET', 'your_secret_here');

// This tells PayPal whether we're testing or going live
// Use 'sandbox' while developing, switch to 'live' when ready for real money
define('PAYPAL_MODE', 'sandbox'); // 'sandbox' or 'live'
?&gt;</code></pre>
                    </div>
                    
                    <div class="info-box">
                        <p><strong>Where to get the API keys:</strong></p>
                        <ul>
                            <li><strong>Stripe:</strong> Dashboard → Developers → API keys</li>
                            <li><strong>PayPal:</strong> Developer Dashboard → My Apps & Credentials</li>
                        </ul>
                    </div>
                </section>

                <section id="step3" class="docs-section">
                    <h2>Step 3: Stripe Backend Integration</h2>
                    <p>Create <code>stripe_payment.php</code> to handle Stripe payment intents:</p>
                    
                    <div class="code-example">
                        <h4>stripe_payment.php</h4>
                        <pre><code>&lt;?php
require_once 'config.php';
require_once 'vendor/autoload.php';

// Tell Stripe who we are with our secret key
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

// We're sending back JSON, so let's tell the browser
header('Content-Type: application/json');

try {
    // Get the data the frontend sent us
    $input = json_decode(file_get_contents('php://input'), true);
    $amount = floatval($input['amount']);
    
    // Make sure they're not trying to donate $0 or negative amounts
    if ($amount < 1) {
        throw new Exception('Amount must be at least $1');
    }
    
    // Create a Payment Intent - this is Stripe's way of tracking a payment
    // We multiply by 100 because Stripe works in cents, not dollars
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount * 100, // $10 becomes 1000 cents
        'currency' => 'usd',
        'description' => 'Donation',
    ]);
    
    // Send back the client secret - the frontend needs this to confirm payment
    echo json_encode([
        'clientSecret' => $paymentIntent->client_secret
    ]);
    
} catch (Exception $e) {
    // Something went wrong - send back an error message
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?&gt;</code></pre>
                    </div>
                    
                    <div class="info-box">
                        <p><strong>Key Points:</strong></p>
                        <ul>
                            <li>Payment Intent is created on the server for security</li>
                            <li>Amount is converted to cents (Stripe requirement)</li>
                            <li>Client secret is returned to frontend for confirmation</li>
                        </ul>
                    </div>
                </section>

                <section id="step4" class="docs-section">
                    <h2>Step 4: PayPal Integration</h2>
                    <p>PayPal handles most of the payment flow on the frontend using their Smart Buttons SDK. No backend file is required for basic integration.</p>
                    
                    <div class="info-box">
                        <p><strong>How PayPal Works:</strong></p>
                        <ul>
                            <li>PayPal SDK creates and captures orders entirely on their servers</li>
                            <li>Your frontend just renders the button and handles callbacks</li>
                            <li>Optional: Create <code>paypal_verify.php</code> for server-side verification</li>
                        </ul>
                    </div>
                </section>

                <section id="step5" class="docs-section">
                    <h2>Step 5: Frontend Integration</h2>
                    
                    <h3>5.1 Load Payment SDKs</h3>
                    <p>In your <code>donate.php</code> file, load both payment SDKs in the <code>&lt;head&gt;</code> section:</p>
                    
                    <div class="code-example">
                        <pre><code>&lt;!-- Load Stripe's JavaScript library - this handles all the card stuff --&gt;
&lt;script src="https://js.stripe.com/v3/"&gt;&lt;/script&gt;

&lt;!-- Load PayPal's SDK with our client ID so they know it's us --&gt;
&lt;!-- We're also telling them we want to work with USD --&gt;
&lt;script src="https://www.paypal.com/sdk/js?client-id=&lt;?php echo PAYPAL_CLIENT_ID; ?&gt;&currency=USD"&gt;&lt;/script&gt;</code></pre>
                    </div>
                    
                    <h3>5.2 Initialize Stripe Elements</h3>
                    <div class="code-example">
                        <pre><code>// Set up Stripe with our publishable key (safe to expose to users)
const stripe = Stripe('&lt;?php echo STRIPE_PUBLISHABLE_KEY; ?&gt;');

// Create the Elements instance - this handles the card input styling
const elements = stripe.elements();

// Create a card element - this is the actual card input field
// Stripe handles all the validation and formatting automatically
const cardElement = elements.create('card');

// Mount it to our page - it'll appear wherever we put the #card-element div
cardElement.mount('#card-element');</code></pre>
                    </div>
                    
                    <h3>5.3 Handle Stripe Payment</h3>
                    <div class="code-example">
                        <pre><code>// Listen for when the user submits the donation form
document.getElementById('donation-form').addEventListener('submit', async function(e) {
    e.preventDefault(); // Stop the form from doing a regular submit
    
    // First, we need to create a payment intent on our server
    // This is like telling Stripe "hey, someone wants to pay this amount"
    const response = await fetch('stripe_payment.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ amount: selectedAmount })
    });
    
    const data = await response.json();
    
    // Now we confirm the payment with the card details the user entered
    // Stripe handles all the security stuff here - pretty neat!
    const result = await stripe.confirmCardPayment(data.clientSecret, {
        payment_method: { card: cardElement }
    });
    
    if (result.error) {
        // Oops, something went wrong - let's show the user what happened
        console.error(result.error.message);
    } else {
        // Success! Time to celebrate and redirect to the success page
        window.location.href = 'success.php';
    }
});</code></pre>
                    </div>
                    
                    <h3>5.4 Handle PayPal Payment</h3>
                    <div class="code-example">
                        <pre><code>// PayPal makes this super easy with their Smart Buttons
paypal.Buttons({
    // This runs when the user clicks the PayPal button
    // We're creating an order with the amount they selected
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: { value: selectedAmount.toFixed(2) }
            }]
        });
    },
    // This runs after the user logs into PayPal and approves the payment
    // We capture the payment (actually charge them) and redirect to success
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            // Payment went through! Let's send them to the success page
            window.location.href = 'success.php';
        });
    },
    // If something goes wrong, we let the user know
    // Could be they cancelled, or their payment method failed
    onError: function(err) {
        alert('Payment failed. Please try again.');
    }
}).render('#paypal-button-container'); // Put the button in our page</code></pre>
                    </div>
                </section>

                <section id="step6" class="docs-section">
                    <h2>Step 6: Testing the integration</h2>
                    
                    <h3>Stripe Test Cards</h3>
                    <div class="test-cards">
                        <div class="test-card">
                            <strong>Successful Payment:</strong>
                            <code>4242 4242 4242 4242</code>
                            <p>Use any future expiry date and any 3-digit CVC</p>
                        </div>
                        
                        <div class="test-card">
                            <strong>Card Declined:</strong>
                            <code>4000 0000 0000 0002</code>
                        </div>
                        
                        <div class="test-card">
                            <strong>3D Secure Required:</strong>
                            <code>4000 0027 6000 3184</code>
                        </div>
                    </div>
                    
                    <h3>PayPal Sandbox Testing</h3>
                    <div class="test-cards">
                        <div class="test-card">
                            <strong>Test Business Account:</strong>
                            <code>sb-0sfgj50536141@business.example.com</code>
                            <p>Password: <code>S0LqV20%</code></p>
                        </div>
                    </div>
                    <div class="info-box">
                        <p><i class="fi fi-sr-info"></i> How to test PayPal payments:</p>
                        <ol>
                            <li>Click the PayPal button on the donation page</li>
                            <li>Log in using the test account credentials above</li>
                            <li>Complete the payment flow</li>
                            <li>You'll be redirected to the success page</li>
                        </ol>
                    </div>
                </section>

                <section class="docs-section footer-section">
                    <p><strong>Developed by:</strong> Cris Neil John D. Hulleza</p>
                    <p>I used the official documentation when developing this integration demo: <a href="https://stripe.com/docs" target="_blank">Stripe Docs</a> | <a href="https://developer.paypal.com/docs" target="_blank">PayPal Docs</a></p>
                </section>
            </div>
        </main>

        <footer>
            <p> Paypal & Stripe Demo Integration</p>
        </footer>
    </div>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('.docs-nav a').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
                // Update active state
                document.querySelectorAll('.docs-nav a').forEach(link => link.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Highlight current section on scroll
        window.addEventListener('scroll', () => {
            const sections = document.querySelectorAll('.docs-section');
            const navLinks = document.querySelectorAll('.docs-nav a');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (scrollY >= sectionTop - 100) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
