<?php declare(strict_types=1);
namespace LeMaX10\Antivirus\Classes\Enums;


use LeMaX10\Enums\Enum;

/**
 * Class IncidentResolveStrategyEnum
 * @package LeMaX10\Antivirus\Classes\Enums
 */
final class IncidentResolveStrategyEnum extends Enum
{
    /**
     *
     */
    private const ADD_TO_SNAPSHOT = 'addToSnapshot';

    /**
     *
     */
    private const DELETE_FILE = 'deleteFile';

    /**
     *
     */
    private const IGNORE = 'ignoreFile';
}
