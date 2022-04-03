<?php

declare(strict_types=1);

namespace App\Server\Api\Request\User;

use App\Server\Api\Request\ApiRequestInterface;
use App\Server\Api\Request\User\VariableField\FieldsModel\AddFieldsModel;
use App\Server\Api\Request\VariableField\VariableField;

use Symfony\Component\Validator\Constraints as Assert;

class AddUserRequest implements ApiRequestInterface
{
    /**
     * @Assert\NotBlank
     * @Assert\Type("string")
     * @Assert\Length(min=3)
     */
    private $name;

    /**
     * @Assert\NotBlank
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /** @var  VariableField */
    private $group;

    /**
     * @param array|null $data
     */
    private function __construct(?array $data, VariableField $group)
    {
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->group = $group;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getGroup(): VariableField
    {
        return $this->group;
    }

    public static function fromRequestData(array $requestData): self
    {
        $data = $requestData['data'] ?? null;

        $group = new VariableField($data['group'] ?? []);

        if (!in_array($group->getField(), AddFieldsModel::ALLOWED_GROUP_FIELDS)) {
            throw new \RuntimeException('Unknown group field');
        }

        return new static($data, $group);
    }
}