<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Requests;

use App\Application\DTO\CreateCarCommand;
use yii\base\Model;

final class CreateCarRequest extends Model
{
    public mixed $title = null;
    public mixed $description = null;
    public mixed $price = null;
    public mixed $photo_url = null;
    public mixed $contacts = null;
    public mixed $options = null;

    private ?CreateCarOptionsRequest $_optionsRequest = null;

    public function rules(): array
    {
        return [
            [['title', 'description', 'price', 'photo_url', 'contacts'], 'required'],
            [['title', 'photo_url', 'contacts'], 'string', 'max' => 255],
            ['description', 'string'],
            ['price', 'number', 'min' => 0],
            ['photo_url', 'url'],
            ['options', 'validateOptions'],
        ];
    }

    public function validateOptions(string $attribute): void
    {
        if ($this->options === null) {
            return;
        }

        if (!is_array($this->options)) {
            $this->addError($attribute, 'Options must be an object or null.');

            return;
        }

        $request = new CreateCarOptionsRequest();
        $request->setAttributes($this->options);

        if (!$request->validate()) {
            foreach ($request->getErrors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError('options/' . $field, $message);
                }
            }

            return;
        }

        $this->_optionsRequest = $request;
    }

    public function toCommand(): CreateCarCommand
    {
        return new CreateCarCommand(
            title: (string) $this->title,
            description: (string) $this->description,
            price: (string) $this->price,
            photoUrl: (string) $this->photo_url,
            contacts: (string) $this->contacts,
            options: $this->_optionsRequest?->toValueObject(),
        );
    }
}
