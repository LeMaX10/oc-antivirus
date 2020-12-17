<?php declare(strict_types=1);

namespace LeMaX10\Antivirus\Classes\Entities;


use Illuminate\Support\Arr;
use LeMaX10\Antivirus\Classes\Contracts\Diff;
use LeMaX10\Antivirus\Classes\Contracts\ModifyParam as ModifyParamContract;

/**
 * Class ItemChange
 * @package LeMaX10\Antivirus\Classes\Entities
 */
class ItemChange extends Entity implements Diff
{
    /**
     *
     */
    private const ISNEW_PARAM = 'isNew';
    /**
     *
     */
    private const ISDELETE_PARAM = 'isDelete';
    /**
     *
     */
    private const MTIME_PARAM = 'mtime';
    /**
     *
     */
    private const SIZE_PARAM = 'size';
    /**
     *
     */
    private const CHMOD_PARAM = 'chmod';

    /**
     * @inheritDoc
     */
    public function isNew(): bool
    {
        return (bool) Arr::get($this->raw, static::ISNEW_PARAM) === true;
    }

    /**
     * @inheritDoc
     */
    public function isDelete(): bool
    {
        return (bool) Arr::get($this->raw, static::ISDELETE_PARAM) === true;
    }

    /**
     * @inheritDoc
     */
    public function isModify(): bool
    {
        return $this->getMTime()->isChange() || $this->getSize()->isChange() || $this->getChmod()->isChange();
    }

    /**
     * @inheritDoc
     */
    public function isChangeMTime(): bool
    {
        return $this->getMTime()->isChange();
    }

    /**
     * @inheritDoc
     */
    public function isChangeSize(): bool
    {
        return $this->getSize()->isChange();
    }

    /**
     * @inheritDoc
     */
    public function isChangeChmod(): bool
    {
        return $this->getChmod()->isChange();
    }

    /**
     * @inheritDoc
     */
    public function getMTime(): ModifyParamContract
    {
         return $this->makeModifyParam(static::MTIME_PARAM);
    }

    /**
     * @inheritDoc
     */
    public function getSize(): ModifyParamContract
    {
        return $this->makeModifyParam(static::SIZE_PARAM);
    }

    /**
     * @inheritDoc
     */
    public function getChmod(): ModifyParamContract
    {
        return $this->makeModifyParam(static::CHMOD_PARAM);
    }

    /**
     * @inheritDoc
     */
    public function setIsNew(bool $value): Diff
    {
        $this->raw[static::ISNEW_PARAM] = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setIsDelete(bool $value): Diff
    {
        $this->raw[static::ISDELETE_PARAM] = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setMTime(?string $fromSnapshot, ?string $current): Diff
    {
        $this->raw[static::MTIME_PARAM] = $this->makeValue($fromSnapshot, $current);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setChmod(?string $fromSnapshot, ?string $current): Diff
    {
        $this->raw[static::CHMOD_PARAM] = $this->makeValue($fromSnapshot, $current);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setSize(?string $fromSnapshot, ?string $current): Diff
    {
        $this->raw[static::SIZE_PARAM] = $this->makeValue($fromSnapshot, $current);
        return $this;
    }


    /**
     * Make Modify Param From Param Value
     * @param string $param
     * @return ModifyParamContract
     */
    protected function makeModifyParam(string $param): ModifyParamContract
    {
        $paramValue = Arr::get($this->raw, $param);
        if (!$paramValue instanceof ModifyParamContract) {
            $paramValue = (new ModifyParam)
                ->setOriginal(Arr::get($this->raw, $param.'.original'))
                ->setCurrent(Arr::get($this->raw, $param.'.current'));
        }

        return $paramValue;
    }

    /**
     * @param string|null $fromSnapshot
     * @param string|null $current
     * @return null[]|string[]
     */
    protected function makeValue(?string $fromSnapshot, ?string $current): array
    {
        return [
            'original' => $fromSnapshot,
            'current'  => $current
        ];
    }
}
