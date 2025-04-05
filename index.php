<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Initializing...</title>
    <link rel="stylesheet" href="./Style/ImportantImport.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg,rgb(78, 195, 249),rgb(85, 225, 204));
            color: white;
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            animation: slideUp 1s ease-out;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top: 5px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .status {
            font-size: 1.2rem;
            opacity: 0;
            animation: fadeText 1s forwards;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        @keyframes slideUp {
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeText {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="spinner"></div>
        <h1>WELCOME TO SUAST!...</h1>
        <p id="status-text" class="status">Initializing...</p>
    </div>

    <script>
        const statusMessages = [
            "Initializing database...",
            "Establishing security link...",
            "Verifying credentials...",
            "Verified✅ ..."
        ];

        let index = 0;
        const statusText = document.getElementById("status-text");

        function updateStatus() {
            if (index < statusMessages.length) {
                statusText.textContent = statusMessages[index];
                statusText.style.animation = "none";
                void statusText.offsetWidth; 
                statusText.style.animation = "fadeText 1s forwards";
                index++;
                setTimeout(updateStatus, 500);
            } else {
                const randomToken = Math.random().toString(36).substr(2, 16);
                const redirectUrl = `././php/landing_page.php?welcome=Please login to access this page&token=${randomToken}`;
                window.location.href = redirectUrl;
            }
        }

        setTimeout(updateStatus, 1000);
    </script>
</body>

</html>
