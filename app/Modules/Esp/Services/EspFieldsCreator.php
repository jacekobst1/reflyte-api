<?php

declare(strict_types=1);

namespace App\Modules\Esp\Services;

use App\Modules\Esp\Dto\EspFieldDto;
use App\Modules\Esp\Integration\EspClientFactory;
use App\Modules\Esp\Integration\EspClientInterface;
use App\Modules\Newsletter\Vo\NewsletterEspConfig;
use App\Shared\RltFields;
use Spatie\LaravelData\DataCollection;

class EspFieldsCreator
{
    private EspClientInterface $espClient;

    public function __construct(private readonly EspClientFactory $espClientFactory)
    {
    }

    public function createFieldsIfNotExists(NewsletterEspConfig $espConfig): void
    {
        $this->setEspClient($espConfig);
        $espFields = $this->getAllFields();

        foreach (RltFields::getFieldsStructure() as $field) {
            $espField = $espFields->where('name', $field['name'])->first();

            if (!$espField) {
                $this->createField($field);
            }
        }
    }

    private function setEspClient(NewsletterEspConfig $espConfig): void
    {
        $this->espClient = $this->espClientFactory->make($espConfig);
    }

    /**
     * @return DataCollection<array-key, EspFieldDto>
     */
    private function getAllFields(): DataCollection
    {
        return $this->espClient->getAllFields();
    }

    /**
     * @param array{name: string, type: string} $field
     * @return void
     */
    private function createField(array $field): void
    {
        $this->espClient->createField($field['name'], $field['type']);
    }
}
