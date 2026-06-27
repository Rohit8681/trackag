<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Trackag</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <style>
        body {
            font-family: "Source Sans 3", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f8fafc;
            color: #172033;
            line-height: 1.8;
        }
        .navbar {
            background: linear-gradient(135deg, #0f4c81 0%, #1c9e85 100%);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: #fff !important;
        }
        .content-wrapper {
            max-width: 900px;
            margin: 40px auto;
            padding: 40px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(15, 76, 129, 0.08);
        }
        .content-wrapper h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #0f4c81;
            margin-bottom: 32px;
            border-bottom: 3px solid #1c9e85;
            padding-bottom: 16px;
        }
        .contact-card {
            background: linear-gradient(135deg, #0f4c81 0%, #1c9e85 100%);
            color: #ffffff;
            padding: 32px;
            border-radius: 12px;
            margin-bottom: 32px;
            box-shadow: 0 8px 20px rgba(15, 76, 129, 0.16);
        }
        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 24px;
        }
        .contact-item:last-child {
            margin-bottom: 0;
        }
        .contact-icon {
            font-size: 28px;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            flex-shrink: 0;
        }
        .contact-info h3 {
            font-weight: 700;
            margin-bottom: 4px;
            font-size: 1.1rem;
        }
        .contact-info p {
            margin: 0;
            opacity: 0.95;
            font-size: 1rem;
        }
        .contact-info a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
        }
        .contact-info a:hover {
            text-decoration: underline;
        }
        .form-section {
            background: #f0f7ff;
            padding: 32px;
            border-radius: 12px;
            border-left: 4px solid #1c9e85;
        }
        .form-section h2 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f4c81;
            margin-bottom: 24px;
        }
        .form-group label {
            font-weight: 600;
            color: #0f4c81;
            margin-bottom: 8px;
        }
        .form-control {
            border: 1.5px solid #dbe5ef;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: #1c9e85;
            box-shadow: 0 0 0 0.2rem rgba(28, 158, 133, 0.15);
        }
        .btn-submit {
            background: linear-gradient(135deg, #0f4c81 0%, #1c9e85 100%);
            border: 0;
            color: #ffffff;
            font-weight: 700;
            padding: 12px 32px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(15, 76, 129, 0.24);
        }
        .footer {
            background: #1a1f36;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            margin-top: 60px;
            border-top: 1px solid rgba(148, 163, 184, 0.1);
        }
        .back-link {
            color: #0f4c81;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            display: inline-block;
        }
        .back-link:hover {
            color: #1c9e85;
        }
        @media (max-width: 768px) {
            .content-wrapper {
                margin: 20px 16px;
                padding: 24px 16px;
            }
            .content-wrapper h1 {
                font-size: 1.8rem;
                margin-bottom: 24px;
            }
            .contact-card {
                padding: 24px 16px;
            }
            .contact-item {
                margin-bottom: 20px;
            }
            .form-section {
                padding: 24px 16px;
            }
            .form-section h2 {
                font-size: 1.2rem;
                margin-bottom: 16px;
            }
            .navbar-brand {
                font-size: 1.2rem;
            }
        }
        @media (max-width: 480px) {
            body {
                font-size: 0.95rem;
            }
            .content-wrapper {
                margin: 16px 12px;
                padding: 16px 12px;
                border-radius: 8px;
            }
            .content-wrapper h1 {
                font-size: 1.5rem;
                margin-bottom: 16px;
            }
            .contact-card {
                padding: 16px 12px;
                margin-bottom: 24px;
            }
            .contact-item {
                gap: 12px;
                margin-bottom: 16px;
            }
            .contact-icon {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
            .contact-info h3 {
                font-size: 0.95rem;
            }
            .contact-info p {
                font-size: 0.9rem;
            }
            .form-section {
                padding: 16px 12px;
                border-left: 3px solid #1c9e85;
            }
            .form-section h2 {
                font-size: 1.1rem;
                margin-bottom: 12px;
            }
            .form-control {
                padding: 10px 12px;
                font-size: 0.95rem;
            }
            .btn-submit {
                width: 100%;
                padding: 12px 16px;
                font-size: 0.95rem;
            }
            .footer {
                padding: 16px 12px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Trackag</a>
        </div>
    </nav>

    <div class="container">
        <a href="/" class="back-link">← Back to Home</a>
        
        <div class="content-wrapper">
            <h1>Contact Us</h1>

            <div class="contact-card">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <div class="contact-info">
                        <h3>Jaishnavi Infotech</h3>
                        <p>Ahemdabad, Gujarat, India</p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="bi bi-telephone"></i>
                    </div>
                    <div class="contact-info">
                        <h3>Call Us</h3>
                        <p><a href="tel:+918238000935">8238000935</a></p>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="contact-info">
                        <h3>Email Us</h3>
                        <p><a href="mailto:sales@trackag.in">sales@trackag.in</a></p>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h2>Send us a Message</h2>
                <form>
                    <div class="form-group mb-3">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Your full name" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="your.email@example.com" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="Your phone number" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="subject">Subject</label>
                        <input type="text" class="form-control" id="subject" name="subject" placeholder="What is this regarding?" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="message">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" placeholder="Your message here..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Send Message</button>
                </form>
            </div>

        </div>
    </div>

    <div class="footer">
        <p>&copy; 2026 Trackag. All rights reserved. | <a href="/privacy-policy" style="color: #1c9e85;">Privacy Policy</a> | <a href="/contact-us" style="color: #1c9e85;">Contact Us</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
