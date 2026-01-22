<?php

namespace Zilmoney\OnlineCheckWriter\Message;

class OnlineCheckWriterCheck extends OnlineCheckWriterMessage
{
    protected ?string $bankAccountId = null;
    protected float $amount = 0;
    protected ?string $payeeName = null;
    protected ?string $memo = null;
    protected ?string $checkNumber = null;
    protected ?string $checkDate = null;
    protected bool $mailCheck = true;
    protected ?string $mailClass = 'first_class';
    protected array $attachments = [];

    /**
     * Create a new check message instance.
     */
    public static function create(): static
    {
        return new static();
    }

    /**
     * Set the bank account ID.
     */
    public function bankAccount(string $bankAccountId): static
    {
        $this->bankAccountId = $bankAccountId;
        return $this;
    }

    /**
     * Set the check amount.
     */
    public function amount(float $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Set the payee name.
     */
    public function payee(string $name): static
    {
        $this->payeeName = $name;
        return $this;
    }

    /**
     * Set the memo line.
     */
    public function memo(string $memo): static
    {
        $this->memo = $memo;
        return $this;
    }

    /**
     * Set a specific check number.
     */
    public function checkNumber(string $number): static
    {
        $this->checkNumber = $number;
        return $this;
    }

    /**
     * Set the check date.
     */
    public function date(string $date): static
    {
        $this->checkDate = $date;
        return $this;
    }

    /**
     * Set whether to mail the check.
     */
    public function mail(bool $mail = true): static
    {
        $this->mailCheck = $mail;
        return $this;
    }

    /**
     * Set the mail class (first_class, priority, etc.).
     */
    public function mailClass(string $class): static
    {
        $this->mailClass = $class;
        return $this;
    }

    /**
     * Add an attachment/document to include with the check.
     */
    public function attach(string $documentId): static
    {
        $this->attachments[] = $documentId;
        return $this;
    }

    /**
     * Add multiple attachments.
     */
    public function attachments(array $documentIds): static
    {
        $this->attachments = array_merge($this->attachments, $documentIds);
        return $this;
    }

    /**
     * Convert the check to an array for the API.
     */
    public function toArray(): array
    {
        $data = [
            'bank_account_id' => $this->bankAccountId ?? config('onlinecheckwriter.default_bank_account_id'),
            'amount' => $this->amount,
            'payee' => $this->payeeName ?? ($this->recipient['name'] ?? ''),
            'recipient' => $this->formatAddress($this->recipient),
            'sender' => $this->getSender(),
            'mail' => $this->mailCheck,
        ];

        if ($this->memo) {
            $data['memo'] = $this->memo;
        }

        if ($this->checkNumber) {
            $data['check_number'] = $this->checkNumber;
        }

        if ($this->checkDate) {
            $data['check_date'] = $this->checkDate;
        }

        if ($this->mailClass) {
            $data['mail_class'] = $this->mailClass;
        }

        if (!empty($this->attachments)) {
            $data['attachments'] = $this->attachments;
        }

        if ($this->description) {
            $data['description'] = $this->description;
        }

        if (!empty($this->metadata)) {
            $data['metadata'] = $this->metadata;
        }

        return $data;
    }
}
