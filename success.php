<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Donation Successful</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .success-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            animation: scaleIn 0.5s ease-out;
        }
        .success-icon svg {
            width: 50px;
            height: 50px;
            color: #4caf50;
        }
        .success-container h1 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .success-container p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        .home-button {
            padding: 1rem 2.5rem;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: bold;
            transition: transform 0.3s;
        }
        .home-button:hover {
            transform: translateY(-2px);
        }
        @keyframes scaleIn {
            from {
                transform: scale(0);
            }
            to {
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <nav>
                <div class="logo">Paypal & Stripe Demo Integration</div>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="documentation.php">Documentation</a></li>
                    <li><a href="nonprofit-demopage/donate.php">Donate</a></li>
                </ul>
            </nav>
        </header>

        <main class="success-container">
            <div class="success-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1>Thank You!</h1>
            <p>Your donation has been processed successfully.<br>We truly appreciate your generous support.</p>
            <a href="index.php" class="home-button">Return Home</a>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> YourBrand. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>
