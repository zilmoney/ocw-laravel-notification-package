<?php

namespace Zilmoney\OnlineCheckWriter;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array send(\Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterMessage $message)
 * @method static array sendDocumentMail(\Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterDocumentMail $documentMail)
 * @method static array sendCheck(\Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterCheck $check)
 * @method static array sendLetter(\Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterLetter $letter)
 * @method static array sendPostcard(\Zilmoney\OnlineCheckWriter\Message\OnlineCheckWriterPostcard $postcard)
 * @method static array uploadDocumentForMailing(string $filePath, string $documentTitle = null, string $idempotencyKey = null)
 * @method static array mailPdf(array $data)
 * @method static array uploadDocument(string $filePath, string $fileName = null)
 * @method static array getStatus(string $type, string $id)
 * @method static array cancel(string $type, string $id)
 * @method static array verifyAddress(array $address)
 *
 * @see \Zilmoney\OnlineCheckWriter\OnlineCheckWriterClient
 */
class OnlineCheckWriter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return OnlineCheckWriterClient::class;
    }
}
