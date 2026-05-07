<?php

declare(strict_types=1);

namespace BxEmHydrator\Element\Handler;

readonly class Attachment
{
    public static function file(int $id): array
    {
        $fields = \CFile::GetByID(fileId: $id)->GetNext();
        $fields = Clr::fields(fields: $fields);
        $fields['EXTENSION'] = \GetFileExtension(path: $fields['SRC']);

        return $fields;
    }

    public static function section(int $id): \_CIBElement
    {
        $section = \CIBlockSection::GetByID(ID: $id)->Fetch();

        return \CIBlockSection::GetList(
            arFilter: ['ID' => $id, 'IBLOCK_ID' => $section['IBLOCK_ID'], 'CNT_ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y'],
            arSelect: ['*', 'UF_*'],
            bIncCnt: true
        )->GetNextElement();
    }
}
