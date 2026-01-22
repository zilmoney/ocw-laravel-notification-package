<?php

namespace Zilmoney\OnlineCheckWriter\Message;

class OnlineCheckWriterLetter extends OnlineCheckWriterMessage
{
    protected ?string $documentId = null;
    protected ?string $documentUrl = null;
    protected ?string $htmlContent = null;
    protected ?string $template = null;
    protected array $templateData = [];
    protected bool $color = false;
    protected bool $doubleSided = false;
    protected ?string $mailClass = 'first_class';
    protected ?string $envelope = 'standard';
    protected array $extraDocuments = [];

    /**
     * Create a new letter message instance.
     */
    public static function create(): static
    {
        return new static();
    }

    /**
     * Set the document ID (pre-uploaded document).
     */
    public function document(string $documentId): static
    {
        $this->documentId = $documentId;
        return $this;
    }

    /**
     * Set a URL to the document.
     */
    public function documentUrl(string $url): static
    {
        $this->documentUrl = $url;
        return $this;
    }

    /**
     * Set HTML content for the letter.
     */
    public function html(string $html): static
    {
        $this->htmlContent = $html;
        return $this;
    }

    /**
     * Use a template.
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
     * Enable color printing.
     */
    public function color(bool $color = true): static
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Enable double-sided printing.
     */
    public function doubleSided(bool $doubleSided = true): static
    {
        $this->doubleSided = $doubleSided;
        return $this;
    }

    /**
     * Set the mail class.
     */
    public function mailClass(string $class): static
    {
        $this->mailClass = $class;
        return $this;
    }

    /**
     * Set the envelope type.
     */
    public function envelope(string $envelope): static
    {
        $this->envelope = $envelope;
        return $this;
    }

    /**
     * Add extra documents to include.
     */
    public function attachDocument(string $documentId): static
    {
        $this->extraDocuments[] = $documentId;
        return $this;
    }

    /**
     * Convert the letter to an array for the API.
     */
    public function toArray(): array
    {
        $data = [
            'recipient' => $this->formatAddress($this->recipient),
            'sender' => $this->getSender(),
            'color' => $this->color,
            'double_sided' => $this->doubleSided,
            'mail_class' => $this->mailClass,
            'envelope' => $this->envelope,
        ];

        if ($this->documentId) {
            $data['document_id'] = $this->documentId;
        } elseif ($this->documentUrl) {
            $data['document_url'] = $this->documentUrl;
        } elseif ($this->htmlContent) {
            $data['html'] = $this->htmlContent;
        } elseif ($this->template) {
            $data['template_id'] = $this->template;
            $data['merge_variables'] = $this->templateData;
        }

        if (!empty($this->extraDocuments)) {
            $data['extra_documents'] = $this->extraDocuments;
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
