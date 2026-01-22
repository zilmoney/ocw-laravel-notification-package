<?php

namespace Zilmoney\OnlineCheckWriter\Message;

class OnlineCheckWriterPostcard extends OnlineCheckWriterMessage
{
    protected ?string $frontImageUrl = null;
    protected ?string $frontImageId = null;
    protected ?string $frontHtml = null;
    protected ?string $backHtml = null;
    protected ?string $backMessage = null;
    protected ?string $template = null;
    protected array $templateData = [];
    protected string $size = '4x6';
    protected ?string $mailClass = 'first_class';

    /**
     * Create a new postcard message instance.
     */
    public static function create(): static
    {
        return new static();
    }

    /**
     * Set the front image URL.
     */
    public function frontImageUrl(string $url): static
    {
        $this->frontImageUrl = $url;
        return $this;
    }

    /**
     * Set the front image document ID.
     */
    public function frontImage(string $documentId): static
    {
        $this->frontImageId = $documentId;
        return $this;
    }

    /**
     * Set the front HTML content.
     */
    public function frontHtml(string $html): static
    {
        $this->frontHtml = $html;
        return $this;
    }

    /**
     * Set the back HTML content.
     */
    public function backHtml(string $html): static
    {
        $this->backHtml = $html;
        return $this;
    }

    /**
     * Set a simple back message.
     */
    public function message(string $message): static
    {
        $this->backMessage = $message;
        return $this;
    }

    /**
     * Use a template for the postcard.
     */
    public function template(string $templateId, array $data = []): static
    {
        $this->template = $templateId;
        $this->templateData = $data;
        return $this;
    }

    /**
     * Set template merge data.
     */
    public function mergeData(array $data): static
    {
        $this->templateData = array_merge($this->templateData, $data);
        return $this;
    }

    /**
     * Set the postcard size (4x6, 6x9, 6x11).
     */
    public function size(string $size): static
    {
        $this->size = $size;
        return $this;
    }

    /**
     * Set mail class.
     */
    public function mailClass(string $class): static
    {
        $this->mailClass = $class;
        return $this;
    }

    /**
     * Convert the postcard to an array for the API.
     */
    public function toArray(): array
    {
        $data = [
            'recipient' => $this->formatAddress($this->recipient),
            'sender' => $this->getSender(),
            'size' => $this->size,
            'mail_class' => $this->mailClass,
        ];

        // Front content
        if ($this->frontImageUrl) {
            $data['front_image_url'] = $this->frontImageUrl;
        } elseif ($this->frontImageId) {
            $data['front_image_id'] = $this->frontImageId;
        } elseif ($this->frontHtml) {
            $data['front_html'] = $this->frontHtml;
        }

        // Back content
        if ($this->backHtml) {
            $data['back_html'] = $this->backHtml;
        } elseif ($this->backMessage) {
            $data['message'] = $this->backMessage;
        }

        // Template
        if ($this->template) {
            $data['template_id'] = $this->template;
            $data['merge_variables'] = $this->templateData;
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
