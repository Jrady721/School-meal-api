# School-meal-api (전국 급식 API)

### API 설명
```
이 API는 특정날짜의 특정타입의 급식을 메뉴별로 가지고 올 수 있습니다.   
api서버를 열긴 했는데 개인적으로 meal.php 만 있으면 따로 구현할 수 있기에 그 쪽을 추천드립니다..
개인 서버라 터질 수도 있어서, 추가로 simple_html_dom을 사용하였습니다
```

### GET (메뉴별 급식 가져오기)
**http://jrady721.cafe24.com/api/jmeal/날짜/type/타입/office/교육청/school/학교코드/level/학교분류 (GET)**

> **날짜 (date)  
```
형식: YYYY.mm.dd**   
ex 2018.08.16
```

> **타입 (type)**  
```
1: 조식, 2: 중식, 3: 석식
```
> **교육청 (office)**  
```
서울시 교육청 : sen.go.kr  
경기도 교육청 : goe.go.kr  
강원도 교육청 : kwe.go.kr  
전라남도 교육청 : jne.go.kr  
전라북도 교육청 : jbe.go.kr  
경상남도 교육청 : gne.go.kr  
경상북도 교육청 : kbe.go.kr  
부산광역시 교육청 : pen.go.kr  
제주자치도 교육청 : jje.go.kr  
충청남도 교육청 : cne.go.kr  
충청북도 교육청 : cbe.go.kr  
광주광역시 교육청 : gen.go.kr  
울산광역시 교육청 : use.go.kr  
대전광역시 교육청 : dje.go.kr  
인천광역시 교육청 : ice.go.kr  
대구광역시 교육청 : dge.go.kr  
```
> **학쿄코드 (school)**  
```
학교코드 검색: https://www.meatwatch.go.kr/biz/bm/sel/schoolListPopup.do
```

> **학교분류 (level)**  
```
유치원: 1, 초등학교 2, 중학교 3, 고등학교 4
```
> **예시 (example)**  

http://jrady721.cafe24.com/api/jmeal/2018.08.16/type/1/office/dge.go.kr/school/D100000282/level/4  

> Result:
```json
{  
    "status": "200",  
    "type": "아침식사",  
    "menus": [  
        "크로크무슈",  
        "코코볼시리얼",  
        "우유",  
        "쇠고기퀴노아죽",  
        "깍두기"  
    ]  
}  
```