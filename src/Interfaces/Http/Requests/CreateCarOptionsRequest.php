<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Requests;

use App\Domain\Model\CarOption;
use yii\base\Model;

final class CreateCarOptionsRequest extends Model
{
    public mixed $brand = null;
    public mixed $model = null;
    public mixed $year = null;
    public mixed $body = null;
    public mixed $mileage = null;

    public function rules(): array
    {
        return [
            [['brand', 'model', 'year', 'body', 'mileage'], 'required'],
            [['brand', 'model', 'body'], 'string', 'max' => 255],
            ['year', 'integer', 'min' => 1900, 'max' => (int) date('Y') + 1],
            ['mileage', 'integer', 'min' => 0],
        ];
    }

    public function toValueObject(): CarOption
    {
        return new CarOption(
            brand: (string) $this->brand,
            model: (string) $this->model,
            year: (int) $this->year,
            body: (string) $this->body,
            mileage: (int) $this->mileage,
        );
    }
}
