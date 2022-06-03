<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataProcessing extends Controller
{
    // Обновление страницы
    public function index()
    {
        $old_date = date("d/m/Y", strtotime("-1 days", strtotime("now")));
        $cbr_data = $this->get_cbr_data($old_date);

        return view('graph', array(
            "dates" => $cbr_data["date"],
            "values" => $cbr_data["value"],
        ));
    }

    // Ajax-подгрузка данных
    public function ajax_get_data(Request $request)
    {
        $days_diff = $request['days']; // Диапазон дней, выбирается пользователем
        $old_date = date("d/m/Y", strtotime("-$days_diff days", strtotime("now")));
        $cbr_data = $this->get_cbr_data($old_date);

        return json_encode($cbr_data); // Возвращает дату после вычитания дней дд.мм.гггг
    }

    // Общая функция для выборки данных курса доллара
    protected function get_cbr_data($old_date)
    {
        $today = date("d/m/Y", strtotime("now"));
        $url = "https://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=$old_date&date_req2=$today&VAL_NM_RQ=R01235";
        $cbr_data = simplexml_load_file($url); // Данные с сайта центробанка
        $result_data = array( // Результирующий объект с датами и значениями
            "date" => array(),
            "value" => array()
        );

        // Закачка данных в результирующий объект
        foreach ($cbr_data as $item)
        {
            $money = str_replace(",", ".", $item->Value->__toString()); // Значение курса
            array_push($result_data["value"], doubleval($money));
            array_push($result_data["date"], $item["Date"]->__toString());
        }

        return $result_data;
    }
}
