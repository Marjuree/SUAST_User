<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Footer</title>
    <style>
        /* Reset some default styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Ensure the page takes full height */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        /* Footer Styling */
        .footer {
    background-color: #002147; /* Dark blue to match the theme */
    color: white;
    text-align: center;
    padding: 15px;
    margin-top: auto;
    width: 100%;
    font-size: 16px;
}


        /* Responsive Design */
        @media (max-width: 600px) {
            .footer {
                font-size: 14px;
                padding: 12px;
            }
        }
    </style>
</head>
<body>

    <footer class="footer">
        <p>&copy; 2025 State University Aptitude and Scholarship Test (SUAST)</p>
    </footer>

</body>
</html>
