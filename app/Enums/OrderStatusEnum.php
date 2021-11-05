<?php


namespace App\Enums;


class OrderStatusEnum extends BaseEnum
{
    public const NEW = 'new';
    public const PENDING = 'pending';
    public const SHIPPED = 'shipped';
    public const CANCELLED = 'cancelled';
    public const FINISHED = 'finished';
}
