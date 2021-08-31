<?php

namespace App\Http\Controllers\Admin;

use Aspera\Spreadsheet\XLSX\Reader;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (!@copy('https://kanstik.uz/upload/export-kanstik.xlsx', 'kanstik.xlsx')) {
            $errors = error_get_last();
            echo "COPY ERROR: " . $errors['type'];
            echo "<br />\n" . $errors['message'];
        } else {
            echo "File copied from remote!";
        }
        $reader = $this->readerXls('kanstik.xlsx');
        $category = '';
        $categories = [];
        foreach ($reader as $key => $row) {
            if ($key === 1) continue;

            $product = array_filter($row, function ($k) {
                return in_array($k, [1, 2, 3, 4, 5, 6]);
            }, ARRAY_FILTER_USE_KEY);


            if (empty(array_filter($product)))
                $category = $row[0];
            else
                $categories[$category][] = $product;
        }
        $db_bitrix = [];
        $reader = $this->readerXls('Категории для заливки в Канстик2.xlsx');
        foreach ($reader as $key => $row) {
            $db_bitrix [$row[0]] = $row[2];
        }

        foreach ($categories as $key => $products) {
            if (array_key_exists($key, $db_bitrix))
                {
                    dump($db_bitrix[$key]);
                    continue;
                }
            else {
                $k = array_filter($db_bitrix, function ($db_key) use ($key) {
                    $percent=0;
                    similar_text($db_key, $key, $percent);
                    return ($percent >69.0);

                }, ARRAY_FILTER_USE_KEY);


                    //dump(array_shift($k));
            }


        }

        //dd($key,$db_bitrix);

        // return view('welcome');
    }

    public function readerXls($filename)
    {

        $reader = new Reader();

        $reader->open($filename);
        $result = $reader;
        // $reader->close();
        return $result;
    }
}
