<?php

namespace Zilmoney\OnlineCheckWriter\Message;

abstract class OnlineCheckWriterMessage
{
    protected array $recipient = [];
    protected array $sender = [];
    protected ?string $description = null;
    protected array $metadata = [];

    /**
     * Set the recipient address.
     */
    public function to(array $address): static
    {
        $this->recipient = $address;
        return $this;
    }

    /**
     * Set the sender/return address.
     */
    public function from(array $address): static
    {
        $this->sender = $address;
        return $this;
    }

    /**
     * Set a description for tracking purposes.
     */
    public function description(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Add custom metadata.
     */
    public function metadata(array $metadata): static
    {
        $this->metadata = array_merge($this->metadata, $metadata);
        return $this;
    }

    /**
     * Check if a recipient has been set.
     */
    public function hasRecipient(): bool
    {
        return !empty($this->recipient);
    }

    /**
     * Get the sender, using defaults if not set.
     */
    protected function getSender(): array
    {
        if (!empty($this->sender)) {
            return $this->formatAddress($this->sender);
        }

        return $this->formatAddress(config('onlinecheckwriter.default_sender', []));
    }

    /**
     * Format an address array for the API.
     */
    protected function formatAddress(array $address): array
    {
        return array_filter([
            'name' => $address['name'] ?? null,
            'company' => $address['company'] ?? null,
            'address_line_1' => $address['address_line_1'] ?? $address['address1'] ?? null,
            'address_line_2' => $address['address_line_2'] ?? $address['address2'] ?? null,
            'city' => $address['city'] ?? null,
            'state' => $address['state'] ?? null,
            'zip' => $address['zip'] ?? $address['postal_code'] ?? null,
            'country' => $address['country'] ?? 'US',
        ]);
    }

    /**
     * Convert the message to an array for the API.
     */
    abstract public function toArray(): array;
}
