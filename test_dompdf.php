<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// This path MUST be correct relative to test_dompdf.php
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

echo "Attempting to create Dompdf instance...<br>";

try {
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    echo "Dompdf Options and Dompdf class found successfully!<br>";

    $dompdf->loadHtml('<h1>Hello World!</h1><p>This is a test PDF.</p>');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $output = $dompdf->output();

    // Save the PDF to a file instead of forcing download for this test
    $filename = 'test_output.pdf';
    file_put_contents($filename, $output);
    echo "PDF generated and saved as " . $filename . " in the same directory.<br>";
    echo "If you see this message and a 'test_output.pdf' file, Dompdf is working.<br>";

} catch (Throwable $e) {
    echo "An error occurred: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "Trace: <pre>" . $e->getTraceAsString() . "</pre>";
}

echo "Script finished.";
?>