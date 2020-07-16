<?php

namespace App\Doctrine;

use Decimal\Decimal;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class DecimalType extends Type
{
    const DECIMAL = 'DecimalObject'; // modify to match your type name

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : new Decimal($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value === null ? null : $value->toString();
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    public function getName()
    {
        return self::DECIMAL;
    }
}
