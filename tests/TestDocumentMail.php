<?php

/**
 * Standalone test script for OnlineCheckWriter Document Mail API
 *
 * Usage:
 *   php tests/TestDocumentMail.php <api_key> <pdf_file_path>
 *
 * Example:
 *   php tests/TestDocumentMail.php "your_bearer_token" "C:/path/to/sample.pdf"
 */

require_once __DIR__ . '/../vendor/autoload.php';

use YourVendor\OnlineCheckWriter\OnlineCheckWriterClient;
use YourVendor\OnlineCheckWriter\Message\OnlineCheckWriterDocumentMail;

// Get arguments
$apiKey = $argv[1] ?? null;
$pdfPath = $argv[2] ?? null;

if (!$apiKey) {
    echo "Usage: php tests/TestDocumentMail.php <api_key> [pdf_file_path]\n";
    echo "  api_key: Your OnlineCheckWriter Bearer token\n";
    echo "  pdf_file_path: (optional) Path to PDF file to upload and mail\n";
    exit(1);
}

// Use test API base URL
$baseUrl = 'https://test.onlinecheckwriter.com/api/v3';

echo "=== OnlineCheckWriter Document Mail Test ===\n\n";
echo "Base URL: {$baseUrl}\n";
echo "API Key: " . substr($apiKey, 0, 20) . "...\n\n";

try {
    $client = new OnlineCheckWriterClient($apiKey, $baseUrl);

    // Test 1: Upload document (if PDF provided)
    if ($pdfPath && file_exists($pdfPath)) {
        echo "--- Test 1: Upload Document ---\n";
        echo "Uploading: {$pdfPath}\n";

        $uploadResponse = $client->uploadDocumentForMailing($pdfPath, 'Test Invoice Document');
        echo "Upload Response:\n";
        print_r($uploadResponse);
        echo "\n";

        // Extract URL from response
        $attachmentUrl = $uploadResponse['data']['url'] ?? $uploadResponse['url'] ?? null;

        if ($attachmentUrl) {
            echo "Attachment URL: {$attachmentUrl}\n\n";

            // Test 2: Mail the PDF
            echo "--- Test 2: Mail PDF ---\n";

            $documentMail = OnlineCheckWriterDocumentMail::create()
                ->attachmentUrl($attachmentUrl)
                ->documentTitle('Invoice 1244')
                ->name('New Payee')
                ->company('Tyler Payment Technologist')
                ->address1('5007 richmond rd')
                ->address2('')
                ->city('Tyler')
                ->state('TX')
                ->zip('75701')
                ->phone('111111111')
                ->email('support@onlinecheckwriter.com')
                ->shippingType(3)
                ->from([
                    'name' => 'David Abraham',
                    'company' => 'David LLC',
                    'address_line_1' => '450 Sutter Street',
                    'address_line_2' => '',
                    'city' => 'San Francisco',
                    'state' => 'CA',
                    'zip' => '94108',
                    'phone' => '987564128',
                ]);

            $mailResponse = $client->sendDocumentMail($documentMail);
            echo "Mail Response:\n";
            print_r($mailResponse);
            echo "\n";
        } else {
            echo "WARNING: Could not extract attachment URL from upload response\n";
        }
    } else {
        echo "--- Test: Mail PDF with existing URL ---\n";
        echo "No PDF file provided. Testing with direct mailPdf call...\n\n";

        // Test direct mailPdf with a sample structure
        $data = [
            'name' => 'Test Payee',
            'company' => 'Test Company',
            'address1' => '123 Test Street',
            'address2' => '',
            'city' => 'Test City',
            'state' => 'TX',
            'zip' => '75001',
            'phone' => '1234567890',
            'email' => 'test@example.com',
            'shippingTypeId' => 3,
            'attachmentUrl' => 'https://example.com/test.pdf', // This would fail without real URL
            'attachmentTitle' => 'Test Document',
            'fromAddress' => [
                'name' => 'Sender Name',
                'company' => 'Sender Company',
                'address1' => '456 Sender St',
                'address2' => '',
                'city' => 'Sender City',
                'state' => 'CA',
                'zip' => '90001',
                'phone' => '0987654321',
            ],
        ];

        echo "Request Data:\n";
        print_r($data);
        echo "\nNote: This test will likely fail without a valid attachment URL.\n";
        echo "Please provide a PDF file path to test the full upload+mail flow.\n";
    }

    echo "\n=== Test Complete ===\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Code: " . $e->getCode() . "\n";

    if (method_exists($e, 'getMessage') && $e->getMessage()) {
        echo "Response: " . print_r($e->getMessage(), true) . "\n";
    }

    exit(1);
}
