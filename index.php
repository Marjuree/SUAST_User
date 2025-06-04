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
            background: linear-gradient(135deg, rgb(78, 195, 249), rgb(85, 225, 204));
            color: white;
            text-align: center;
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
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-top: 5px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="spinner"></div>
        <h1>Welcome to UniReserve!...</h1>
    </div>

    <script>
        const token = Math.random().toString(36).substr(2, 16);
        const redirectUrl = `./php/landing_page.php?welcome=Please login to access this page&token=${token}`;
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 1500); // Adjust delay here if needed
    </script>
</body>

</html>
