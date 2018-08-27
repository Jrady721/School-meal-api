<?php
// include library
include 'include/simple_html_dom.php';

// 특정 날짜의 특정 타입의 급식 가져오기
function getMeal($date = null, $type = null, $office = null, $school = null, $level = null)
{
    Header('Content-Type: application/json');

    // check data
    if ($date === null || $type === null || $office === null || $school === null || $level === null) {
        return json_encode(array('status' => '400', 'message' => '모든 데이터를 올바르게 입력해주세요.'), JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
    }

    if($date === null) $date = date('Y.m.d');

    // type setting
    $mealType = array('아침식사', '점심식사', '저녁식사');

    // create json object
    $meals = array('status' => '200', 'type' => $mealType[$type - 1]);

    // get data
    $url = 'http://stu.' . $office . '/sts_sci_md01_001.do?schulCode=' . $school . '&schulCrseScCode=' . $level . '&schMmealScCode=' . $type . '&schYmd=' . $date;

    // parsing
    $html = file_get_html($url);
    foreach ($html->find('table tr', 0)->find('th') as $index => $element) {
        // 요소가 현재 날짜이면.
        if (preg_replace("/\(.*\)/iU", "", $element->plaintext) == $date) {
            foreach ($html->find('table tr', 2)->find('td') as $index2 => $element2) {
                // 현재 날짜에 맞는 메뉴를 가져옴
                if ($index - 1 === $index2) {
                    $data = preg_replace("/\(.*\)/iU", "", $element2->plaintext);

                    // create menus
                    $menus = array();
                    preg_match_all("|(?<hangul>[가-힣]+)|u", $data, $out);
                    foreach ($out['hangul'] as $menu) {
                        array_push($menus, $menu);
                    }
                    $meals['menus'] = $menus;
//                    Header('Content-Type: application/json');
                    return json_encode($meals, JSON_UNESCAPED_UNICODE + JSON_PRETTY_PRINT);
                }
            }
        }
    }
    // error
    return -1;
}

// getMeal
echo getMeal('2018.08.16', '1', 'dge.go.kr', 'D100000282', '4');