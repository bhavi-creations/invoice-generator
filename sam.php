<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Test</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button id="printBtn" class="btn btn-primary">Print</button>
    </div>

    <div>
        <h1>Invoice Content</h1>
        <p>This is the content that will be printed.</p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const printButton = document.getElementById("printBtn");
            if (printButton) {
                printButton.addEventListener("click", function () {
                    window.print();
                });
            }
        });
    </script>

</body>
</html>
