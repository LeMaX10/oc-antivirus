<?php declare(strict_types=1);


namespace LeMaX10\Antivirus\Classes\Entities;

use Illuminate\Support\Arr;
use LeMaX10\Antivirus\Classes\Contracts\ModifyParam as ModifyParamContract;

/**
 * Class ModifyParam
 * @package LeMaX10\Antivirus\Classes\Entities
 */
class ModifyParam extends Entity implements ModifyParamContract
{
    /**
     *
     */
    private const ORIGINAL_PARAM = 'original';
    /**
     *
     */
    private const CURRENT_PARAM = 'current';

    /**
     * @inheritDoc
     */
    public function setOriginal(?string $value): ModifyParamContract
    {
        $this->raw[static::ORIGINAL_PARAM] = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setCurrent(?string $value): ModifyParamContract
    {
        $this->raw[static::CURRENT_PARAM] = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOriginal(): ?string
    {
        return (string) Arr::get($this->raw, static::ORIGINAL_PARAM);
    }

    /**
     * @inheritDoc
     */
    public function getCurrent(): ?string
    {
        return (string) Arr::get($this->raw, static::CURRENT_PARAM);

    }

    /**
     * @inheritDoc
     */
    public function isChange(): bool
    {
        return $this->getOriginal() !== $this->getCurrent();
    }
}
