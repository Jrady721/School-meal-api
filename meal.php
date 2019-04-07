<?php
// include library
include 'include/simple_html_dom.php';

// 전국 급식 API, 특정 날짜의 특정 타입의 급식 가져오기
function get_meal($date = null, $type = null, $office = null, $school = null, $level = null)
{
    Header('Content-Type: application/json');

    // check data
    if ($date === null || $type === null || $office === null || $school === null || $level === null) {
        return json_encode(array('status' => '400', 'message' => '모든 데이터를 올바르게 입력해주세요.'), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    }

    if ($date === null) $date = date('Y.m.d');

    // type setting
    $mealType = array('아침식사', '점심식사', '저녁식사');

    // get data
    $url = "http://stu.$office/sts_sci_md01_001.do?schulCode=$school&schulCrseScCode=$level&schMmealScCode=$type&schYmd=$date";
    $table = file_get_html($url)->find('table', 0);

    // create json object
    $meals = array('status' => '200', 'type' => $mealType[$type - 1]);

    // 현재 날짜를 구한다.
    foreach ($table->find('thead th') as $index => $element) {
        // 요소가 현재 날짜이면 메뉴를 JSON 객체로 생성

        // 공백제거
        $this_date = preg_replace("/\(.*\)/iU", "", $element->plaintext);
        if ($this_date === $date) {
            // 현재날짜의 메뉴를 구함.
            if ($table->find('tbody tr', 2)->find('td', $index - 1)) {
                $this_menus = preg_replace("/\(.*\)/iU", "", $table->find('tbody tr', 2)->find('td', $index - 1)->plaintext);
                // 혼합 10곡 같은 메뉴도 제대로 파싱해옵니다.
                preg_match_all("/[가-힣].+[가-힣]+/", $this_menus, $menus);

                // create menus
                $meals['menus'] = $menus[0];
                return json_encode($meals, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
            }
        }
    }
    // error
    return json_encode(array('status' => '400', 'message' => '실패!', 'url' => $url), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
}

// 전국 급식 API, 특정 날짜의 급식 전부 가져오기 * 개인적으로 속도가 느려 사용 비추!
function get_meals($date = null, $office = null, $school = null, $level = null)
{
    Header('Content-Type: application/json');

    // check data
    if ($date === null || $office === null || $school === null || $level === null) {
        return json_encode(array('status' => '400', 'message' => '모든 데이터를 올바르게 입력해주세요.'), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    }

    if ($date === null) $date = date('Y.m.d');

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