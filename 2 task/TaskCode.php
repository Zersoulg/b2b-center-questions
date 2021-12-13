<?php

/**
 * Задача №2
Имеется строка:
https://www.somehost.com/test/index.html?param1=4&param2=3&param3=2&param4=1&param5=3
Напишите функцию, которая:
1. удалит параметры со значением “3”;
2. отсортирует параметры по значению;
3. добавит параметр url со значением из переданной ссылки без параметров (в примере: /test/index.html);
4. сформирует и вернёт валидный URL на корень указанного в ссылке хоста.
В указанном примере функцией должно быть возвращено:
https://www.somehost.com/?param4=1&param3=2&param1=4&url=%2Ftest%2Findex.html
 *
 *
 */
class TaskCode {
    /**
     * Введём данный URL в const, т.к. мы не знаем, откуда он будет приходить, но точно знаем его содержимое
     */
    public const URL = 'https://www.somehost.com/test/index.html?param1=4&param2=3&param3=2&param4=1&param5=3';
    public const NEEDLE_VALUE = 3;

    /**$key
     * @return string
     */
    public function prettyURL(): string {
        // Удалим содержимое со значением 3
        $parsedString = [];

        // Первое, что приходит на ум: PHP - язык, заточенный под веб разработку, значит у него много "родных" функций для
        // работы с URL. Первое, что пришло на ум - метод parse_url
        // В ответе он выдаёт, в том числе с ключом query целиком строку параметров, что идёт после знака "?"
        // Здесь нужна проверка на isset, потому что, если нет параметров, то такого ключа не будет
        $parsedURL = parse_url(self::URL);
        $parsedQuery = $parsedURL['query'] ?? [];

        // Собираем в массив $parsedString полученные параметры
        if ($parsedQuery !== null) {
            parse_str($parsedQuery, $parsedString);
        }


        // Ищем в полученном массиве параметр со значением 3; если параметров нет, то foreach просто будет искать пустой массив,
        // следовательно, ничего не удалит
        $key = null;
        foreach ($parsedString as $k => $needle) {
            if ($needle === self::NEEDLE_VALUE) {
                $key = $k;
            }
        }

        // убираем найденный ключ, в цикле это делать не желательно
        unset($parsedString[$key]);

        // Теперь нам нужно отсортировать все элементы массива по значению. Функции asort достаточно
        asort($parsedString);

        // В конце нам нужно вернуть валидную строку, а пока что у нас массив. Переведём массив в строку, а затем
        // будем работать с ней, но предварительно добавим параметр

        // Для этого нам нужно сначала вытащить искомое значение, оно хранится в $parsedURL
        $parsedPath = $parsedURL['path'] ?? null;

        if ($parsedPath) {
            $parsedString['url'] = $parsedPath;
        }

        // Соберём query-параметры заново. Не могу сказать, что метод я использовал правильно, т.к. я использовал для теста
        // https://sandbox.onlinephpfunctions.com/ этот ресурс, а там не установлен php_pecl
        return http_build_query(['host' => $parsedURL['host'], 'query' => $parsedString]);
    }
}