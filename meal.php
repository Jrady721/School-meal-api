<?php

// simple_html_dom V 1.8.1
include_once 'simple_html_dom.php';

// 전국 급식 API, 특정 날짜의 특정 타입의 급식 가져오기
function getMeal($date = null, $type = null, $office = null, $school = null, $level = null)
{
    // 헤더 설정
    Header('Content-Type: application/json');

    // 인자 확인
    if ($date == null || $type == null || $office == null || $school == null || $level == null) {
        return json_encode(array('status' => '400', 'message' => '모든 데이터를 올바르게 입력해주세요.'), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    }

    // 오늘 날짜 생성
    if ($date == null) $date = date('Y.m.d');
    // 타입 설정
    $mealType = array('아침식사', '점심식사', '저녁식사');
    // URL 생성
    $url = "https://stu.$office/sts_sci_md01_001.do?schulCode=$school&schulCrseScCode=$level&schMmealScCode=$type&schYmd=$date";

    // 테이블 가져오기
    $table = file_get_html($url)->find('table', 0);

    // JSON 객체 생성
    $meals = array('status' => '200', 'type' => $mealType[$type - 1]);

    // 현재 날짜를 구한다.
    foreach ($table->find('thead th') as $index => $element) {
        // 현재날짜 공백제거
        $this_date = preg_replace("/\(.*\)/iU", "", $element->plaintext);

        // 요소가 현재 날짜이면 메뉴를 JSON 객체로 생성
        if ($this_date == $date) {
            // 객체가 있을 때..
            if ($table->find('tbody tr', 1)->find('td', $index - 1)) {
                // 현재날짜의 메뉴를 구함.
                $menu = $table->find('tbody tr', 1)->find('td', $index - 1)->plaintext;

                $this_menus = preg_replace("/\(.*\)/iU", "", $menu);
                preg_match_all("/[가-힣].+[가-힣]+/", $this_menus, $menus);

                // JSON 데이터에 포함
                $meals['menus'] = $menus[0];
            } else {
                $meals['menus'] = [];
            }

            // 결과 반환
            return json_encode($meals, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
        }
    }
    // 에러 반환
    return json_encode(array('status' => '400', 'message' => '실패!'), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
}

// 전국 급식 API, 특정 날짜의 급식 전부 가져오기 * 개인적으로 속도가 느려 사용 비추!
function getMeals($date = null, $office = null, $school = null, $level = null)
{
    // 헤더 설정
    Header('Content-Type: application/json');

    // 인자 확인
    if ($date == null || $office == null || $school == null || $level == null) {
        return json_encode(array('status' => '400', 'message' => '모든 데이터를 올바르게 입력해주세요.'), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    }

    // 날짜 생성
    if ($date == null) $date = date('Y.m.d');

    // 타입 설정
    $mealType = array('breakfast', 'lunch', 'dinner');

    // JSON 객체 생성
    $meals = array('status' => '200');

    // 아침, 점심, 저녁 가져오기
    for ($i = 1; $i <= 3; $i++) {
        $url = "http://jrady721.cafe24.com/api/meal/$date/type/$i/office/$office/school/$school/level/$level";
        $json = file_get_contents($url);
        $meal = json_decode($json, true);
        $meals[$mealType[$i - 1]] = $meal;
    }

    // 결과 반환
    return json_encode($meals, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
}