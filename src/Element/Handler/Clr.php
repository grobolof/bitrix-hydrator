<?php

declare(strict_types=1);

namespace BxEmHydrator\Element\Handler;

readonly class Clr
{
    /**
     * Очистить пустые и невалидные поля элемента
     */
    public static function fields(array $fields): array
    {
        return array_filter(
            array: $fields,
            callback: function ($value, $key) {
                if (str_starts_with($key, '~')) {
                    return false;
                }

                if ($value === null || $value === '' || $value === false) {
                    return false;
                }

                return true;
            },
            mode: ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Очистить пустые и невалидные поля элемента
     */
    public static function properties(array $properties, array $fields): array
    {
        $list = [];
        $propertiesSlc = [];

        foreach ($properties as $field => $property) {
            if ($property['VALUE'] === '') {
                continue;
            }

            // Перезаписываем VALUE на ~VALUE (~VALUE исходное значение из БД, без преобразований Битрикса)
            $property['VALUE'] = $property['~VALUE'];

            $list[$field] = self::fields(fields: $property);
        }

        return $list;

        // TODO: Проблема логики bitrix.
        // Если есть PROPERTY_ как множественное свойство,
        // то при GetList Bitrix дублирует элемент в выборке (влияет на пагинацию).
        // Решение 1: в arSelectFields для GetList передавать ['*'], это соберет все дефолтные поля,
        // все PROPERTY_ получаем отдельным запросом и НЕ соотносим из с select полей из GetFields
        // Решение 2: в настройках ИБ изменить место хранения свойств на отдельную таблицу.
        //foreach ($fields as $key => $value) {
        //    if (str_starts_with($key, 'PROPERTY_') && str_ends_with($key, '_VALUE')) {
        //        $propertiesSlc[substr(string: $key, offset: 9, length: -6)] = $value;
        //    }
        //}

        //return array_intersect_key($list, $propertiesSlc);
    }
}
