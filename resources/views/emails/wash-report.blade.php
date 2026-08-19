<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wash Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 5px 5px;
        }
        .info-box {
            background-color: white;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 3px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>WASH REPORT-DAILY</h2>
    </div>
    
    <div class="content">
        <h3>Dear Respected All,</h3>
        <p>Please find attached the Wash Report for the period:</p>
        
        <div class="info-box">
            <strong>Report Date:</strong> {{ $formattedFrom }}<br>
            <!-- <strong>To:</strong> {{ $formattedTo }}<br> -->
            <strong>Generated On:</strong> {{ now()->format('d-m-Y H:i:s') }}
        </div>
        
        <p>Content includes:</p>
        <ul>
            <li>Unit Production Details</li>
            <li>1st Dry Process Data</li>
            <li>2nd Dry Process Data</li>
            <li>Machine Transfer Data</li>
            <li>Dryer Data</li>
            <li>Machine Performance Charts</li>
        </ul>
        
        <p>The PDF report is attached to this email.</p>
        
        <p style="margin-top: 30px; font-size: 14px; color: #6c757d;">
            <strong>Note:</strong> This is system generated report. Do not reply to this email.
        </p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} TUSUKA. All rights reserved.</p>
        <!-- <p>This email was sent automatically by the system.</p> -->
    </div>
</body>
</html>