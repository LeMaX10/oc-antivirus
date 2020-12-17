<?php declare(strict_types=1);

namespace LeMaX10\Antivirus\Classes\Contracts;


/**
 * Interface Diff
 * @package LeMaX10\Antivirus\Classes\Contracts
 */
interface Diff
{
    /**
     * @return bool
     */
    public function isNew(): bool;

    /**
     * @return bool
     */
    public function isDelete(): bool;

    /**
     * @return bool
     */
    public function isModify(): bool;

    /**
     * @return bool
     */
    public function isChangeMTime(): bool;

    /**
     * @return bool
     */
    public function isChangeSize(): bool;

    /**
     * @return bool
     */
    public function isChangeChmod(): bool;

    /**
     * @return ModifyParam
     */
    public function getMTime(): ModifyParam;

    /**
     * @return ModifyParam
     */
    public function getSize(): ModifyParam;

    /**
     * @return ModifyParam
     */
    public function getChmod(): ModifyParam;

    /**
     * @param bool $value
     * @return $this
     */
    public function setIsNew(bool $value): self;

    /**
     * @param bool $value
     * @return $this
     */
    public function setIsDelete(bool $value): self;

    /**
     * @param string|null $fromSnapshot
     * @param string|null $current
     * @return $this
     */
    public function setMTime(?string $fromSnapshot, ?string $current): self;

    /**
     * @param string|null $fromSnapshot
     * @param string|null $current
     * @return $this
     */
    public function setChmod(?string $fromSnapshot, ?string $current): self;

    /**
     * @param string|null $fromSnapshot
     * @param string|null $current
     * @return $this
     */
    public function setSize(?string $fromSnapshot, ?string $current): self;
}
