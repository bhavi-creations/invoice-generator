<?php
// PHP code to get the current date
$currentDate = date('Y-m-d');

// PHP data structure for services
$services = [
    'Website' => [
        'Type',
        'Technology',
        'User Interface',
        'Pages',
        'Admin Panel',
        'SSL Certificate',
        'Hosting',
        'Domain Registration',
        'Contact Forms / Lead Capture',
        'Delivery Timeline',
        'Support & Maintenance'
    ],
    'SEO' => [
        'On-Page Optimization',
        'Off-Page SEO',
        'Google My Business',
        'Keywords',
        'Monthly Reporting'
    ],
    'Social Media' => [
        'Platform',
        'Content Calendar',
        'Creative Designs',
        'Hashtag Strategy',
        'Ad Management',
        'Monthly Report'
    ],
    'Photoshoot' => [
        'No. of Sessions',
        'Shoot Duration',
        'Locations',
        'Shoot Type',
        'Post-Processing',
        'Deliverables',
        'Usage Rights',
        'Additional Charges',
        'Validity'
    ]
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bhavi Creations Letter</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        /* CSS Reset and Body Styles */
        html,
        body {
            margin: 0;
            padding: 0;
            background-color: #f3f4f6;
            font-family: 'Inter', sans-serif;
            -webkit-print-color-adjust: exact;
        }

        /* Container for all letter pages */
        .page-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Letter Page Styles for screen */
        .letter-page {
            width: 7.78in;
            height: 11in;
            margin: 20px auto;
            position: relative;
            box-sizing: border-box;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 160px 35px 100px 35px;
            background-image: url('img/letterhead.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center top;
            overflow: hidden;
        }

        .content-area {
            position: relative;
            z-index: 2;
        }

        .header-section {
            margin-bottom: 40px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .header-table td {
            padding: 0;
            vertical-align: top;
        }

        .date-container {
            width: 15%;
            text-align: center;
        }

        .client-details {
            width: 40%;
            /* This line was changed from text-align: right; */
            text-align: left;
        }

        .company-details,
        .client-details,
        .date-container {
            flex: 1;
            box-sizing: border-box;
        }

        .company-details {
            width: 40%;
            padding-right: 20px;
        }

        .client-details {
            width: 40%;
            /* This line was changed from text-align: right; */
            text-align: right ;
        }

        .client-details textarea {
            /* The width was changed from 90% to 100% to fill the space */
            width: 100%;
            min-height: 100px;
            padding: 5px;
            /* border: 1px solid #ccc; */
            resize: none;
            overflow-y: hidden;
            font-family: 'Inter', sans-serif;
            field-sizing: content;
            /* This line was added to ensure the text is aligned left inside the textarea */
            text-align: right;
        }

        .date-container input[type="date"] {
            padding: 0x;
            font-family: 'Inter', sans-serif;
        }

        .date-container p {
            font-weight: bold;
        }

        h4,
        p {
            margin: 0 0 5px 0;
            color: #333;
        }

        /* Button container styles */
        .button-container {
            margin: 20px auto;
            display: flex;
            justify-content: center;
            gap: 15px;
            width: 8.5in;
        }

        .button-container button {
            padding: 8px 16px;
            font-size: 14px;
            cursor: pointer;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            border-radius: 5px;
            transition: all 0.2s ease-in-out;
            color: #374151;
            font-weight: 500;
        }

        .button-container button:hover {
            background-color: #e5e7eb;
            color: #1f2937;
        }

        /* Services Section Styles */
        .services-section {
            padding: 20px 0;
        }

        .service-card {
            transition: all 0.3s ease-in-out;
            background-color: transparent;
        }

        .service-card.approved {
            border-color: #10B981;
        }

        .service-card-heading {
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
        }

        .service-card-heading.approved {
            border-color: #10B981;
        }

        .service-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
        }

       .service-card-body {
    background-color: rgba(255, 255, 255, 0.7);
    border-radius: 0.5rem;
    margin-top: 10px;
}

.service-card-body table {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid #d1d5db;
    table-layout: fixed;
}

.service-card-body table th,
.service-card-body table td {
    border-bottom: 1px solid #d1d5db;
    padding: 3px 12px;
    vertical-align: top;
    word-wrap: break-word;
    word-break: break-word;
}

.service-card-body table th:first-child,
.service-card-body table td:first-child {
    width: 30%;
    /* Corrected: Removed the dash between 1 and px */
    border-right: 1px solid #d1d5db;
}

.service-card-body table th:last-child,
.service-card-body table td:last-child {
    width: 70%;
}

.service-card-body table tbody tr:last-child td,
.service-card-body table tbody tr:last-child th {
    border-bottom: none;
}

        /* Styles for the signature section */
        .signature-section {
            width: 100%;
            position: absolute;
            bottom: 35px;
            left: 0;
            padding: 0 35px;
        }

        .signature-services-section {
            width: 100%;
            position: absolute;
            bottom: 65px;
            left: 0;
            padding: 0 35px;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .signature-table td {
            padding: 0;
            vertical-align: bottom;
        }

        .client-signature {
            width: 50%;
            text-align: left;
        }

        .company-signature {
            width: 50%;
            text-align: right;
            position: relative;
        }

        .signature-line {
            width: 80%;
            border-bottom: 1px solid #000;
            margin-top: 40px;
        }

        .company-sign {
            width: 150px;
            height: auto;
            display: block;
            margin-left: auto;
            margin-top: 10px;
            z-index: 2;
        }

        .company-stamp {
            width: 120px;
            height: auto;
            position: absolute;
            bottom: 26px;
            right: 0px;
            opacity: 0.9;
            z-index: 1;
        }

        /* CSS to give the last page extra space at the bottom for the fixed signature section */
        .letter-page.last-page-with-signature {
            padding-bottom: 150px;
        }

        /* Print-specific styles */
        @media print {


            .button-container,
            .flex.space-x-2,
            .add-feature-btn {
                display: none;
            }

            .letter-page {
                box-shadow: none;
                margin: 0 !important;
                padding: 160px 35px 100px 35px;
                width: 100%;
                height: auto;
                /* Allow content to flow and create new pages */
                overflow: visible;
                position: relative;
                /* Essential for the pseudo-element */
                page-break-after: always;
            }

            .letter-page:last-child {
                page-break-after: auto;
            }

            body {
                background-color: white;
            }
 
            .letter-page {
                width: 7.78in;
                height: 11in;

                position: relative;
                box-sizing: border-box;
                background-color: white;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                padding: 160px 35px 100px 35px;
                background-image: url('img/letterhead.png');
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center top;
                overflow: hidden;
            }

        }
    </style>
</head>

<body>

    <div class="button-container">
        <button type="button" onclick="addNewPage()">Add Custom Page</button>
        <button type="button" onclick="saveLetter()">Save (to PDF)</button>
        <button type="button" onclick="window.print()">Print</button>
        <button type="button" onclick="goBack()">Go Back</button>
    </div>

    <form id="pdf-form" action="generate_letterhead_pdf.php" method="post" target="_blank" style="display: none;">
        <input type="hidden" id="html_content" name="html_content">
    </form>

    <div class="page-wrapper">
        <div class="letter-page" id="firstPage">
            <div class="content-area">
                <div class="header-section">
                    <table class="header-table">
                        <tr>
                            <td class="company-details">
                                <h4>Prepared By:</h4>
                                <h4>Bhavi Creations Pvt Ltd</h4>
                                <p>Plot no 28, H No 70, 17-28, RTO Office Rd,</p>
                                <p>Opposite to New RTO Office, behind J.N.T.U</p>
                                <p>Engineering College Play Ground, Ranga Rao Nagar,</p>
                                <p>Kakinada, Andhra Pradesh 533003</p>
                            </td>
                            <td class="date-container">
                                <p> <input type="date" value="<?php echo $currentDate; ?>"></p>
                                <p style="margin-top: 10px;">QUOTATION</p>
                            </td>
                            <td class="client-details">
                                <h4>To,</h4>
                                <textarea placeholder="Client Name
Address
Phone Number
GSTIN"></textarea>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="main-body">
                    <h2 class="text-2xl font-bold text-blue-700 mb-6 border-b-2 border-blue-200 pb-2">Quotation Details</h2>
                    <p contenteditable="true" style="min-height: 200px;">
                        Dear Client,
                        <br><br>
                        Thank you for your interest in our services. This document outlines the proposed services and their features. Please review the following pages for a detailed breakdown of each service.
                        <br><br>
                        Sincerely,
                        <br>
                        Bhavi Creations
                    </p>
                </div>
            </div>
        </div>

        <?php foreach ($services as $serviceName => $features) : ?>
            <div class="letter-page service-page" id="<?php echo strtolower(str_replace(' ', '-', $serviceName)); ?>-page">
                <div class="content-area">
                    <div class="flex justify-between items-center border-gray-200 service-card-heading">
                        <h4 class="text-xl font-semibold text-gray-800"><?php echo $serviceName; ?></h4>
                        <div class="flex space-x-2">
                            <button type="button" class="approve-btn p-2 rounded-full bg-gray-300 text-gray-800 hover:bg-green-500 hover:text-white transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" data-service="<?php echo strtolower(str_replace(' ', '-', $serviceName)); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                            <button type="button" class="hide-btn p-2 rounded-full bg-gray-300 text-gray-800 hover:bg-red-500 hover:text-white transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" data-target-page="<?php echo strtolower(str_replace(' ', '-', $serviceName)); ?>-page">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="service-card-body overflow-x-auto rounded-lg shadow-sm hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Feature</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Details</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php foreach ($features as $feature) : ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4" contenteditable="true"><?php echo $feature; ?></td>
                                        <td class="px-6 py-4" contenteditable="true"></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="add-feature-btn mt-4 px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-150 ease-in-out shadow-md hidden" data-target="<?php echo strtolower(str_replace(' ', '-', $serviceName)); ?>-page">
                        <i class="fas fa-plus mr-2"></i>Add Feature
                    </button>

                </div>

                <div class="signature-section   signature-services-section  ">
                    <table class="signature-table">
                        <tr>
                            <td class="client-signature">
                                <h4>Client Signature:</h4>
                                <p class="signature-line"></p>
                            </td>
                            <td class="company-signature">
                                <img src="img/bhavi stamp.png" alt="Company Stamp" class="company-stamp">
                                <img src="img/signiture.png" alt="Company Signature" class="company-sign">
                                <h4>Bhavi Creations Pvt Ltd</h4>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="letter-page last-page-with-signature" id="signaturePage">
            <div class="content-area">
                <div class="main-body" contenteditable="true">
                    <p style="min-height: 200px;">
                        Refferal Links :
                        <br><br>
                        Thank you for your business.
                    </p>
                </div>
                <div class="signature-section">
                    <table class="signature-table">
                        <tr>
                            <td class="client-signature">
                                <h4>Client Signature:</h4>
                                <p class="signature-line"></p>
                            </td>
                            <td class="company-signature">
                                <img src="img/bhavi stamp.png" alt="Company Stamp" class="company-stamp">
                                <img src="img/signiture.png" alt="Company Signature" class="company-sign">
                                <h4>Bhavi Creations Pvt Ltd</h4>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function saveLetter() {
            const originalPageWrapper = document.querySelector('.page-wrapper');
            const interactiveElements = document.querySelectorAll('.button-container, .flex.space-x-2, .add-feature-btn');
            const form = document.getElementById('pdf-form');
            const htmlContentInput = document.getElementById('html_content');

            interactiveElements.forEach(el => el.style.display = 'none');

            const tempWrapper = originalPageWrapper.cloneNode(true);

            tempWrapper.querySelectorAll('textarea').forEach(textarea => {
                const value = textarea.value;
                const parent = textarea.parentNode;
                const newDiv = document.createElement('div');
                newDiv.innerHTML = value.replace(/\n/g, '<br>');
                parent.replaceChild(newDiv, textarea);
            });

            tempWrapper.querySelectorAll('input[type="date"]').forEach(input => {
                const value = input.value;
                input.setAttribute('value', value);
            });

            tempWrapper.querySelectorAll('[contenteditable="true"]').forEach(editableDiv => {
                editableDiv.removeAttribute('contenteditable');
            });

            let htmlToSave = '';
            const allPages = tempWrapper.querySelectorAll('.letter-page');

            allPages.forEach((page, index) => {
                htmlToSave += page.outerHTML;
                if (index < allPages.length - 1) {
                    htmlToSave += '<pagebreak />';
                }
            });

            interactiveElements.forEach(el => el.style.display = '');
            htmlContentInput.value = htmlToSave;
            form.submit();
        }

        function addNewPage() {
            const pageWrapper = document.querySelector('.page-wrapper');

            const signaturePage = document.getElementById('signaturePage');
            if (signaturePage) {
                signaturePage.remove();
            }

            const newPage = document.createElement('div');
            newPage.className = 'letter-page';
            newPage.innerHTML = `
                <div class="content-area">
                    <div class="main-body" contenteditable="true" style="min-height: 500px;">
                        <h2 class="text-2xl font-bold text-blue-700 mb-6 border-b-2 border-blue-200 pb-2">Custom Page</h2>
                        <p>Start typing your custom content here...</p>
                    </div>
                </div>
            `;
            pageWrapper.appendChild(newPage);

            const newSignaturePage = document.createElement('div');
            newSignaturePage.className = 'letter-page last-page-with-signature';
            newSignaturePage.id = 'signaturePage';
            newSignaturePage.innerHTML = `
                <div class="content-area">
                    <div class="main-body" contenteditable="true">
                        <p style="min-height: 200px;">
                            This is the final page of the quotation. You can add any last details or notes here.
                            <br><br>
                            Thank you for your business.
                        </p>
                    </div>
                    <div class="signature-section"  style = ''>
                        <table class="signature-table">
                            <tr>
                                <td class="client-signature">
                                    <h4>Client Signature:</h4>
                                    <p class="signature-line"></p>
                                </td>
                                <td class="company-signature">
                                <img src="img/bhavi stamp.png" alt="Company Stamp" class="company-stamp">
                                <img src="img/signiture.png" alt="Company Signature" class="company-sign">
                                <h4>Bhavi Creations Pvt Ltd</h4>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            `;
            pageWrapper.appendChild(newSignaturePage);
        }

        function goBack() {
            window.history.back();
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.approve-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const serviceName = button.dataset.service;
                    const card = document.getElementById(`${serviceName}-page`);
                    const tableBody = card.querySelector('.service-card-body');
                    const addFeatureBtn = card.querySelector('.add-feature-btn');
                    const heading = card.querySelector('.service-card-heading');

                    if (card) {
                        card.classList.toggle('approved');
                        heading.classList.toggle('approved');
                        button.classList.toggle('bg-green-500');
                        button.classList.toggle('text-white');
                        button.classList.toggle('bg-gray-300');
                        button.classList.toggle('text-gray-800');
                    }

                    if (tableBody) {
                        tableBody.classList.toggle('hidden');
                    }
                    if (addFeatureBtn) {
                        addFeatureBtn.classList.toggle('hidden');
                    }
                });
            });

            document.querySelectorAll('.hide-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const pageId = button.dataset.targetPage;
                    const pageToRemove = document.getElementById(pageId);
                    if (pageToRemove) {
                        pageToRemove.remove();
                    }
                });
            });

            document.querySelectorAll('.add-feature-btn').forEach(button => {
                button.addEventListener('click', () => {
                    const serviceCardId = button.dataset.target;
                    const tableBody = document.querySelector(`#${serviceCardId} .service-card-body tbody`);

                    if (tableBody) {
                        const newRow = document.createElement('tr');
                        newRow.classList.add('hover:bg-gray-50');

                        const featureCell = document.createElement('td');
                        featureCell.setAttribute('contenteditable', 'true');
                        featureCell.textContent = "New Feature";

                        const detailsCell = document.createElement('td');
                        detailsCell.setAttribute('contenteditable', 'true');
                        detailsCell.textContent = "Details here...";

                        newRow.appendChild(featureCell);
                        newRow.appendChild(detailsCell);
                        tableBody.appendChild(newRow);
                    }
                });
            });
        });
    </script>
</body>

</html>