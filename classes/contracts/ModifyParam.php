<?php declare(strict_types=1);

namespace LeMaX10\Antivirus\Classes\Contracts;


/**
 * Interface ModifyParam
 * @package LeMaX10\Antivirus\Classes\Contracts
 */
interface ModifyParam
{
    /**
     * set original value
     * @param string|null $value
     * @return $this
     */
    public function setOriginal(?string $value): self;

    /**
     * Set current value
     * @param string|null $value
     * @return $this
     */
    public function setCurrent(?string $value): self;

    /**
     * Old value
     * @return string|null
     */
    public function getOriginal(): ?string;

    /**
     * Current Value
     * @return string|null
     */
    public function getCurrent(): ?string;

    /**
     * Check is change original !== current
     * @return bool
     */
    public function isChange(): bool;
}
