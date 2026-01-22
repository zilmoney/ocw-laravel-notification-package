# OnlineCheckWriter Document Mailing for Laravel

A Laravel package for mailing PDF documents via the OnlineCheckWriter API.

## Installation

Add the repository to your `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../../packages/onlinecheckwriter"
        }
    ]
}
```

Then install the package:

```bash
composer require zilmoney/onlinecheckwriter-notifications
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=onlinecheckwriter-config
```

Add your OnlineCheckWriter API key to your `.env` file:

```env
ONLINECHECKWRITER_API_KEY=your-api-key
ONLINECHECKWRITER_BASE_URL=https://api.onlinecheckwriter.com/api/v3

# Default sender information
ONLINECHECKWRITER_SENDER_NAME="Your Company Name"
ONLINECHECKWRITER_SENDER_COMPANY="Your Company LLC"
ONLINECHECKWRITER_SENDER_ADDRESS_1="123 Main Street"
ONLINECHECKWRITER_SENDER_CITY="New York"
ONLINECHECKWRITER_SENDER_STATE="NY"
ONLINECHECKWRITER_SENDER_ZIP="10001"
ONLINECHECKWRITER_SENDER_PHONE="1234567890"
```

## Usage

### Option 1: Upload File and Mail in One Step

```php
use Zilmoney\OnlineCheckWriter\OnlineCheckWriter;
use Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterDocumentMail;

$mail = OnlineCheckWriterDocumentMail::create()
    ->file('/path/to/invoice.pdf')
    ->documentTitle('Invoice 1244')
    ->name('New Payee')
    ->company('Tyler Payment Technologist')
    ->address1('5007 richmond rd')
    ->city('Tyler')
    ->state('TX')
    ->zip('75701')
    ->phone('111111111')
    ->email('support@onlinecheckwriter.com')
    ->shippingType(3)
    ->from([
        'name' => 'David Abraham',
        'company' => 'David LLC',
        'address1' => '450 Sutter Street',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '94108',
        'phone' => '987564128',
    ]);

$response = OnlineCheckWriter::send($mail);
```

### Option 2: Use Pre-uploaded Attachment URL

```php
$mail = OnlineCheckWriterDocumentMail::create()
    ->attachmentUrl('https://your-s3-url.com/document.pdf')
    ->documentTitle('Invoice 1244')
    ->name('New Payee')
    ->company('Tyler Payment Technologist')
    ->address1('5007 richmond rd')
    ->city('Tyler')
    ->state('TX')
    ->zip('75701')
    ->phone('111111111')
    ->email('support@onlinecheckwriter.com')
    ->shippingType(3)
    ->from([
        'name' => 'David Abraham',
        'company' => 'David LLC',
        'address1' => '450 Sutter Street',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '94108',
        'phone' => '987564128',
    ]);

$response = OnlineCheckWriter::send($mail);
```

### Upload Document Only

```php
use Zilmoney\OnlineCheckWriter\OnlineCheckWriter;

$response = OnlineCheckWriter::uploadDocumentForMailing(
    '/path/to/document.pdf',
    'Document Title'
);

// Response structure:
// {
//     "success": true,
//     "data": {
//         "id": "mQaMdjZlmGzLxbW",
//         "document_title": "Document Title",
//         "file_url": "https://...",
//         "count_page": 1
//     }
// }

$attachmentUrl = $response['data']['file_url'];
```

## Available Methods

### OnlineCheckWriterDocumentMail

| Method | Description |
|--------|-------------|
| `file($path)` | Set the PDF file path to upload |
| `attachmentUrl($url)` | Set pre-uploaded attachment URL |
| `documentTitle($title)` | Set the document title |
| `name($name)` | Set recipient name |
| `company($company)` | Set recipient company |
| `address1($address)` | Set recipient address line 1 |
| `address2($address)` | Set recipient address line 2 |
| `city($city)` | Set recipient city |
| `state($state)` | Set recipient state |
| `zip($zip)` | Set recipient zip code |
| `phone($phone)` | Set recipient phone |
| `email($email)` | Set recipient email |
| `shippingType($id)` | Set shipping type (1=Standard, 2=Express, 3=Priority) |
| `from($address)` | Set sender/return address array |
| `to($address)` | Set recipient address array |

## Error Handling

```php
use Zilmoney\OnlineCheckWriter\Exceptions\OnlineCheckWriterException;

try {
    $response = OnlineCheckWriter::send($mail);
} catch (OnlineCheckWriterException $e) {
    if ($e->isValidationError()) {
        // Handle validation errors (422)
        $errors = $e->getResponse();
    } elseif ($e->isAuthenticationError()) {
        // Handle auth errors (401)
    } elseif ($e->isRateLimitError()) {
        // Handle rate limiting (429)
    }

    logger()->error('OnlineCheckWriter error: ' . $e->getMessage());
}
```

## License

MIT License.
