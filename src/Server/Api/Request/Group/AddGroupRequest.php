<?php

declare(strict_types=1);

namespace App\Server\Api\Request\Group;

use App\Server\Api\Request\ApiRequestInterface;
use Symfony\Component\Validator\Constraints as Assert;

class AddGroupRequest implements ApiRequestInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3)
     */
    private $name;

    /**
     * @param array|null $data
     */
    private function __construct(?array $data)
    {
        $this->name = $data['name'] ?? null;;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function fromRequestData(array $requestData): self
    {
        $data = $requestData['data'] ?? null;

        return new static($data);
    }

}