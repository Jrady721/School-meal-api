<?php
// include library
include 'include/simple_html_dom.php';

// 전국 급식 API, 특정 날짜의 특정 타입의 급식 가져오기
function getMeal($date = null, $type = null, $office = null, $school = null, $level = null)
{
    Header('Content-Type: application/json');

    // check data
    if ($date === null || $type === null || $office === null || $school === null || $level === null) {
        return json_encode(array('status' => '400', 'message' => '모든 데이터를 올바르게 입력해주세요.'), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    }

    if ($date === null) {
        $date = date('Y.m.d');
    }

    // type setting
    $mealType = array('아침식사', '점심식사', '저녁식사');

    // create json object
    $meals = array('status' => '200', 'type' => $mealType[$type - 1]);

    // get data
    $url = "http://stu.$office/sts_sci_md01_001.do?schulCode=$school&schulCrseScCode=$level&schMmealScCode=$type&schYmd=$date";
    $table = file_get_html($url)->find('table', 0);

    // 현재 날짜를 구한다.
    foreach ($table->find('thead th') as $index => $element) {
        // 요소가 현재 날짜이면 메뉴를 JSON 객체로 생성
        if (preg_replace("/\(.*\)/iU", "", $element->plaintext) === $date) {
            // 현재날짜의 메뉴를 구함.
            if ($table->find('tbody tr', 2)->find('td', $index - 1)) {
                preg_match_all("|(?<menus>[가-힣]+)|u", preg_replace("/\(.*\)/iU", "", $table->find('tbody tr', 2)->find('td', $index - 1)->plaintext), $out);

                // create menus
                $menus = array();
                foreach ($out['menus'] as $menu) {
                    array_push($menus, $menu);
                }
                $meals['menus'] = $menus;
                return json_encode($meals, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
            }
        }
    }
    // error
    return json_encode(array('status' => '400', 'message' => '실패!'), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
}

// 전국 급식 API, 특정 날짜의 급식 전부 가져오기 * 개인적으로 속도가 느려 사용 비추!
function getMeals($date = null, $office = null, $school = null, $level = null)
{
    Header('Content-Type: application/json');

    // check data
    if ($date === null || $office === null || $school === null || $level === null) {
        return json_encode(array('status' => '400', 'message' => '모든 데이터를 올바르게 입력해주세요.'), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    }

    if ($date === null) {
        $date = date('Y.m.d');
    }

    // type setting
    $mealType = array('breakfast', 'lunch', 'dinner');

    // create json object
    $meals = array('status' => '200');

    for ($i = 1; $i <= 3; $i++) {
        $url = "http://jrady721.cafe24.com/api/meal/$date/type/$i/office/$office/school/$school/level/$level";
        $json = file_get_contents($url);
        $meal = json_decode($json, true);
        $meals[$mealType[$i - 1]] = $meal;
    }

    return json_encode($meals, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
}

// 특정 날짜 및 특정 타입의 식사메뉴
function dgswGetMeal($date, $type = null)
{
    Header('Content-Type: application/json');

    $date = preg_replace("/\./", '-', $date);

    if ($type === null) {
        $type = 1;
    }

    $mealType = array('아침식사', '점심식사', '저녁식사');

    if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
        return -1;
    }

    if (!($type >= 1 && $type <= 3)) {
        return -1;
    }

    $mealData = array('status' => '200', 'type' => $mealType[$type - 1]);

    $url = "http://www.dgsw.hs.kr/user/carte/list.do?menuCd=&startDate=$date&endDate=$date";

    $html = file_get_html($url);
    $meal = htmlspecialchars($html->find('tr', $type + 1)->find('td', 0)->find('dd', 0));
    $meal = preg_replace("/\(.*\)/iU", "", $meal);

    preg_match_all("|(?<menus>[가-힣]+)|u", $meal, $out);

    $menus = array();

    foreach ($out['menus'] as $key => $menu) {
        array_push($menus, $menu);
    }

    $mealData['menus'] = $menus;

    return json_encode($mealData, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
}

// getMeal
echo getMeal('2018.08.27', '1', 'dge.go.kr', 'D100000282', '4');
