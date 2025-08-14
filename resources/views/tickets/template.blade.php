<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI & Machine Learning Workshop Ticket</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            background-color: #f3f4f6;
            padding: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            max-width: 1024px;
            width: 100%;
        }

        .ticket-card {
            background: white;
            border: 2px solid #d97706;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .ticket-main {
            display: flex;
        }

        .ticket-left {
            flex: 1;
            padding: 32px;
            position: relative;
        }

        .ticket-header {
            padding: 0 0 24px 0;
        }

        .header-content {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 16px;
        }

        .title-section h1 {
            font-size: 24px;
            color: #111827;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .location {
            display: flex;
            align-items: center;
            color: #4b5563;
            margin-bottom: 8px;
        }

        .location-icon {
            width: 16px;
            height: 16px;
            margin-right: 8px;
        }

        .location span {
            font-size: 14px;
        }

        .status-section {
            text-align: right;
        }

        .status-badge {
            background-color: #d97706;
            color: white;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 8px;
        }

        .ticket-number {
            font-size: 12px;
            color: #6b7280;
        }

        .divider {
            border-bottom: 2px dashed #d1d5db;
        }

        .ticket-content {
            padding: 0;
        }

        .datetime-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        .field-group {
            display: flex;
            flex-direction: column;
        }

        .field-label {
            font-size: 12px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4px;
        }

        .field-value {
            font-size: 18px;
            font-family: 'Courier New', monospace;
            color: #111827;
            font-weight: normal;
        }

        .total-section {
            border-top: 2px dashed #d1d5db;
            padding-top: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 12px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .total-amount {
            font-size: 30px;
            font-weight: bold;
            color: #d97706;
        }

        .perforation {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 24px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .perf-hole {
            width: 16px;
            height: 16px;
            background-color: #f3f4f6;
            border-radius: 50%;
            margin-bottom: 8px;
        }

        .ticket-right {
            width: 192px;
            background-color: #f9fafb;
            padding: 24px;
            border-left: 2px dashed #d1d5db;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .admit-section {
            position: absolute;
            top: 33%;
            left: 50%;
            transform: translate(-33%, -50%) rotate(90deg);
            transform-origin: center;
            z-index: 20;
            pointer-events: none;
            user-select: none;
        }

        .admit-content {
            text-align: center;
        }

        .admit-text {
            font-size: 12px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 8px;
        }

        .admit-badge {
            display: inline-block;
            padding: 2px 8px;
            border: 1px solid #d97706;
            border-radius: 4px;
            color: #d97706;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .admit-date {
            font-size: 12px;
            color: #4b5563;
        }

        .qr-section {
            position: absolute;
            bottom: 24px;
            left: 24px;
            right: 24px;
            z-index: 10;
        }

        .qr-container {
            width: 144px;
            height: 144px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #e5e7eb;
            border-radius: 4px;
            background: white;
        }

        .qr-code {
            width: 140px;
            height: 140px;
            background: #000;
            position: relative;
            background-image:
                repeating-linear-gradient(0deg,
                    #000,
                    #000 4px,
                    #fff 4px,
                    #fff 8px),
                repeating-linear-gradient(90deg,
                    #000,
                    #000 4px,
                    #fff 4px,
                    #fff 8px);
            background-size: 8px 8px, 8px 8px;
        }

        .qr-label {
            text-align: center;
            margin-top: 12px;
            font-size: 14px;
            font-weight: 500;
            color: #4b5563;
        }

        .ticket-footer {
            border-top: 2px dashed #d1d5db;
            background-color: #f9fafb;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: #4b5563;
        }

        .footer-item {
            display: flex;
            align-items: center;
        }

        .scissors-icon {
            width: 12px;
            height: 12px;
            margin-right: 8px;
        }

        .alert {
            margin-top: 16px;
            background: transparent;
            border: none;
        }

        .alert-content {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
        }

        .alert-content p {
            margin-bottom: 4px;
        }

        /* SVG Icons */
        .icon {
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="ticket-card">
            <div class="ticket-main">
                <div class="ticket-left">
                    <div class="ticket-header">
                        <div class="header-content">
                            <div class="title-section">
                                <h1>AI & Machine Learning Workshop</h1>
                                <div class="location">
                                    <svg class="location-icon icon" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span>Festville</span>
                                </div>
                            </div>
                            <div class="status-section">
                                <div class="status-badge">CONFIRMED</div>
                                <div class="ticket-number">#TKT-000005</div>
                            </div>
                        </div>
                        <div class="divider"></div>
                    </div>

                    <div class="ticket-content">
                        <div class="datetime-grid">
                            <div class="field-group">
                                <label class="field-label">Start Date</label>
                                <div class="field-value">Aug 17, 2025</div>
                            </div>
                            <div class="field-group">
                                <label class="field-label">End Date</label>
                                <div class="field-value">Aug 17, 2025</div>
                            </div>
                            <div class="field-group">
                                <label class="field-label">Start Time</label>
                                <div class="field-value">6:05 PM</div>
                            </div>
                            <div class="field-group">
                                <label class="field-label">End Time</label>
                                <div class="field-value">10:05 PM</div>
                            </div>
                        </div>

                        <div class="info-grid">
                            <div class="field-group">
                                <label class="field-label">Ticket Category</label>
                                <div class="field-value">VIP Pass</div>
                            </div>
                            <div class="field-group">
                                <label class="field-label">Quantity</label>
                                <div class="field-value">1 TICKET</div>
                            </div>
                            <div class="field-group">
                                <label class="field-label">Price Each</label>
                                <div class="field-value">৳500</div>
                            </div>
                        </div>

                        <div class="total-section">
                            <span class="total-label">Total Amount</span>
                            <span class="total-amount">৳500</span>
                        </div>
                    </div>

                    <div class="perforation">
                        <div class="perf-hole"></div>
                        <div class="perf-hole"></div>
                        <div class="perf-hole"></div>
                        <div class="perf-hole"></div>
                        <div class="perf-hole"></div>
                        <div class="perf-hole"></div>
                        <div class="perf-hole"></div>
                        <div class="perf-hole"></div>
                        <div class="perf-hole"></div>
                        <div class="perf-hole"></div>
                        <div class="perf-hole"></div>
                        <div class="perf-hole"></div>
                    </div>
                </div>

                <div class="ticket-right">
                    <div class="admit-section">
                        <div class="admit-content">
                            <div class="admit-text">Admit One</div>
                            <div class="admit-badge">#TKT-000005</div>
                            <div class="admit-date">Aug 17, 2025</div>
                        </div>
                    </div>

                    <div class="qr-section">
                        <div class="qr-container">
                            <div class="qr-code"></div>
                        </div>
                        <div class="qr-label">SCAN AT VENUE</div>
                    </div>
                </div>
            </div>

            <div class="ticket-footer">
                <div class="footer-item">
                    <svg class="scissors-icon icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="6" cy="6" r="3"></circle>
                        <circle cx="6" cy="18" r="3"></circle>
                        <line x1="20" y1="4" x2="8.12" y2="15.88"></line>
                        <line x1="14.47" y1="14.48" x2="20" y2="20"></line>
                        <line x1="8.12" y1="8.12" x2="12" y2="12"></line>
                    </svg>
                    <span>DETACH AT VENUE</span>
                </div>
                <div>Valid for: 1 person</div>
                <div>Keep this portion</div>
            </div>
        </div>

        <div class="alert">
            <div class="alert-content">
                <p>This ticket is non-refundable and non-transferable. Please arrive 30 minutes before event start time.
                </p>
                <p>For support, contact us at support@tapkori.com</p>
            </div>
        </div>
    </div>
</body>

</html>