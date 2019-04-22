<?php

// simple_html_dom V 1.8.1
include_once 'include/simple_html_dom.php';

// 전국 급식 API, 특정 날짜의 특정 타입의 급식 가져오기
function getMeal($date, $type, $office, $school, $level)
{
    // 헤더 설정
    Header('Content-Type: application/json');

    // 타입 설정
    $mealType = array('breakfast', 'lunch', 'dinner');

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
    return json_encode(array('status' => '400', 'message' => '데이터를 가져오는데 실패하였습니다.'), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
}

// 전국 급식 API, 특정 날짜의 급식 전부 가져오기 * 개인적으로 속도가 느려 사용 추천하지 않습니다.
function getMeals($date, $office, $school, $level)
{
    // 헤더 설정
    Header('Content-Type: application/json');

    // 타입 설정
    $mealType = array('breakfast', 'lunch', 'dinner');

    // JSON 객체 생성
    $meals = array('status' => '200');

    // 아침, 점심, 저녁 가져오기
    for ($i = 1; $i <= 3; $i++) {
        $json = getMeal($date, $i, $office, $school, $level);
        $meal = json_decode($json, true);
        $meals[$mealType[$i - 1]] = $meal['menus'];
    }

    // 결과 반환
    return json_encode($meals, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
}

// 다음 급식 가져오기
function nextMeal($office, $school, $level)
{
    // 시간 설정 -- 이 부분은 원래 스스로 설정해줘야합니다.
    date_default_timezone_set('Asia/Seoul');

    $date = date('Y.m.d');
    $time = date('H:i:s');

    // 시간 지정
    $breakfast = date('07:20:00');
    $lunch = date('12:30:00');
    $dinner = date('18:20:00');

    if ($time > $dinner) {
        $date = date('Y.m.d', strtotime(date('d.m.Y') . "+1 days"));
        $type = 1;
    } else if ($time > $lunch) {
        $type = 3;
    } else if ($time > $breakfast) {
        $type = 2;
    } else {
        $type = 1;
    }

    return getMeal($date, $type, $office, $school, $level);
}

// 사용
//echo getMeal('2019.04.22', '2', 'dge.go.kr', 'D100000282', '4');
//echo getMeals('2019.04.22', 'dge.go.kr', 'D100000282', '4');
//echo nextMeal('dge.go.kr', 'D100000282', '4');