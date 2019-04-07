# School-meal-api - 전국 급식 API

### API 설명
```
이 API는 특정날짜의 특정타입의 급식을 메뉴별로 가지고 올 수 있습니다.   
api서버를 개인적으로 열긴 했으나 meal.php로 개별적 구현을 추천드립니다.
```

### 버전 관리 (현재버전: V1.01)
```
V1.00
 - 전국 유치원, 초,중,고등학교의 영양소 정보를 제거한 급식 메뉴를 가져옵니다.
V1.01
 - 기존의 메뉴중 혼합10곡이 있을경우 혼합, 곡으로 메뉴를 가져오는 문제 해결.
```

### GET (메뉴별 급식 가져오기)
```
http://jrady721.cafe24.com/api/meal/날짜/type/타입/office/교육청/school/학교코드/level/학교분류 (GET)
```

> **날짜 (date)**
```
형식: YYYY.mm.dd**   
ex 2019.04.07
```

> **타입 (type)**  
```
1: 조식, 2: 중식, 3: 석식
```
> **교육청 (office)**  
```
서울특별시 교육청 : sen.go.kr  
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
세종특별자치시 교육청 : sje.go.kr  
```
> **학쿄코드 (school)**  

### School API - 전국 학교 API
https://github.com/Jrady721/School-search-api
 

> **학교분류 (level)**  
```
유치원: 1, 초등학교 2, 중학교 3, 고등학교 4
```
> **예시 (example)**  

http://jrady721.cafe24.com/api/meal/2019.04.05/type/2/office/dge.go.kr/school/D100000282/level/4  

> Result:
```json
{
    "status": "200",
    "type": "점심식사",
    "menus": [
        "비빔밥",
        "다슬기아욱된장국",
        "고추장볶음",
        "북성로불고기",
        "계란후라이",
        "배추김치",
        "팥소앙금절편",
        "쌀밥"
    ]
}
```

> **활용**

웹 사이트: http://jrady721.cafe24.com/meal  
구글(웨일) 확장프로그램: https://github.com/Jrady721/School-meal-extension
