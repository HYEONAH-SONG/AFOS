# 6주차 - 데이터베이스 서비스

> **SQL 기본 이해**와 **AWS RDS 서비스**에 대해 알아보기

1. ### DBMS

   1. ##### DBMS 개요

      - **데이터베이스** 
        '데이터의 집합' 혹은 '데이터의 저장 공간' 자체(주로 파일로 구성됨)를 의미

      - **DBMS** = 데이터베이스를 관리/운영하는 시스템

   2. ##### DBMS 특징

      - 데이터의 무결성(변경 x), 보안, 데이터 중복의 최소화, 프로그래밍 언어를 통하여 응용 프로그램 제작 및 수정이 쉬워짐(자동화 등)

   3. ##### 데이터베이스의 발전

      - 오프라인 - 파일시스템 사용 - DBMS

   4. ##### DBMS 분류

      - 계층형 DBMS, 망형 DBMS, **관계형 DBMS(MySQL, Oracle 등)**, 객체지향형 DBMS, 객체관계형 DBMS
      - **관계형(Relational) DBMS**는 '데이터베이스는 **테이블(Entity, Relation)**'이라 불리는 최소 단위로 구성되어 있고, 이 테이블은 하나 이상의 열로 구성되어 있다. 

   5. ##### SQL  = 데이터베이스를 조작하는 언어

      - DBMS 제작 회사와 독립적 (표준이 제공됨)
      - 다른 시스템으로 이식성이 좋다
      - 표준이 계속 발전한다 - SQL은 국제 표준화 기관에서 표준화된 내용을 계속 발표
      - 대화식 언어
      - 분산형 클라이언트/서버 구조

      <hr>

2. ### MariaDB 접속 및 기본 사용

![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/6%EC%A3%BC%EC%B0%A8%20%EC%8B%A4%EC%8A%B5(1).png?raw=true)

- CloudFormation 스택 생성 - **[링크](https://console.aws.amazon.com/cloudformation/home?region=ap-northeast-2#/stacks/new?stackName=DBLab&templateURL=https:%2F%2Fs3.ap-northeast-2.amazonaws.com%2Fcloudformation.cloudneta.net%2FDB%2Faws-db.yaml)** 클릭 후 템플릿 파일로 기본 환경 자동 배포 됩니다.

  파라미터(KeyName - 자신의 SSH 키 선택) `다음` 클릭 →  `다음` 클릭 → `스택 생성` 클릭

- 구성 : 1개의 웹서버 인스턴스 + 1개의 DB 서버 인스턴스

  - #### WebSrv 인스턴스 연결

    ```sh
    # IP확인 및 DBSrv ping 테스트
    ip a
    ping 10.1.2.10
    # DBSrv 로 MySQL 접속 (계정 정보: root/qwe123)
    mysql -h 10.1.2.10 -uroot -pqwe123
    ```

  - #### DBSrv 인스턴스 연결

    ```sh
    # IP확인
    ip a
    
    # MySQL 접속 (계정 정보: root/qwe123)
    mysql -uroot -pqwe123
    ```

  - #### MariaDB monitor 에서 기본적인 SQL 문 사용 (WebSrv 인스턴스)

    > DBMS 내부에는 여러개의 데이터베이스(스키마) 존재함! 

    ~~~sh
    # DB 서버의 상태 정보
    MariaDB [(none)]> status;
    
    # 데이터베이스(=스키마) 확인
    SHOW DATABASES;
    
    # employees 데이터베이스 선택 하기
    USE employees;
    MariaDB [(none)]> **USE employees;**
    MariaDB [**employees**]>
    
    # 테이블 확인
    SHOW TABLES;
    
    # 테이블 필드와 타입 등 정보 확인
    DESC employees;
    
    # employees 테이블 조회 하기
    SELECT * FROM employees;
    SELECT * FROM employees LIMIT 10;
    SELECT * FROM employees LIMIT 100;
    
    # 특정 열(컬럼=필드) 기준 오름/내림차순으로 정렬 조회 하기
    # -- 공백이 있는 개체의 이름 사용 시는 백틱(backtick) `` 으로 묶어줘야 하나의 이름으로 인식함
    SELECT * FROM employees ORDER BY `emp_no` DESC LIMIT 100;
    SELECT * FROM employees ORDER BY `birth_date` ASC LIMIT 100;
    
    # 특정 열(컬럼) 만 출력
    SELECT first_name, last_name, gender FROM employees LIMIT 50;
    
    # 특정 행(=로우=레코드)만 출력 - Mary 이름(first_name) , Baba 성(last_name)
    SELECT * FROM employees WHERE first_name = 'Mary';
    SELECT * FROM employees WHERE last_name = 'Baba';
    ```
    ~~~

  - #### 생성 및 데이터(레코드) 생성/변경/삭제 실습

    ~~~sh
    # 스키마(=데이터베이스) 생성
    CREATE SCHEMA `shopdb`;
    **SHOW DATABASES;**
    USE shopdb;
    
    # 테이블 생성 - (Workbench) GUI 설정 보여주기 - 아래 복붙해서 적용하기
    CREATE TABLE `shopdb`.`memberTBL` (
      `memberID` CHAR(8) NOT NULL,
      `memberName` CHAR(5) NOT NULL,
      `memberAddress` CHAR(20) NULL,
      `age` INT NOT NULL,
      PRIMARY KEY (`memberID`));
    
    # 테이블 정보 확인
    DESC memberTBL;
    
    # 데이터 넣기 - (Workbench) GUI 설정 보여주기 - (카톡 멤버 정보 받기) - 아래 복붙해서 적용하기
    SELECT * FROM memberTBL;
    
    # 행(=데이터=레코드) 1개 넣기
    INSERT INTO memberTBL VALUES ('Beas', '베아스', '인천 연수구 송도동', '77');
    -- 위 아래 명령어 동일
    INSERT INTO `shopdb`.`memberTBL` (`memberID`, `memberName`, `memberAddress`, `age`) VALUES ('Beas', '베아스', '인천 연수구 송도동', '77');
    
    ## 카톡 멤버 정보 받아서 넣기
    INSERT INTO memberTBL VALUES ('Gasida', '가시다', '서울 잠실동 분당구', '30');
    INSERT INTO memberTBL VALUES ('User4', '유나', '경기 성남시 분당구', '27');
    INSERT INTO memberTBL VALUES ('Minyoung', '민영', '경기도 부천시 중동', '30'); 
    INSERT INTO memberTBL VALUES ('Eunji', '은지', '인천 남구 주안동', '28');
    INSERT INTO memberTBL VALUES ('Yujeong', '유정', '서울 은평구 증산동', '29'); 
    
    # 조회
    SELECT * FROM memberTBL;
    SELECT memberName, memberAddress FROM memberTBL;
    SELECT * FROM memberTBL ORDER BY `age` ASC;
    SELECT * FROM memberTBL WHERE age = 27;
    
    # 행(=데이터=레코드) 삭제
    DELETE from memberTBL where memberID='Beas';
    SELECT * FROM memberTBL;
    
    # 행(=데이터=레코드) 변경(=업데이트)
    UPDATE memberTBL SET memberID='Yuna' WHERE memberName='유나';
    SELECT * FROM memberTBL;
    ```
    ~~~

    ### :upside_down_face: ​SQL을 배워야하는 이유 

    > 요즘 빅데이터 시대인 만큼 모든 기업에서 데이터의 분석을 필수이다. 사용자들의 행동을 측정할 수 있는 방법은 무공무진하고 관련된 데이터는 차고 넘친다. 이 중에서 필요한 데이터를 골라내고 데이터 뒤에 담긴 의미를 파악하는 일이 굉장히 중요해졌다. 이 일의 시작은 결국 SQL이 된다.  따라서 우리는 기본적으로 SQL을 익혀둬야 한다. 

- #### HTML과 PHP, MySQL 관계

  ![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week_6.png?raw=true)

  > 1. 사용자는 DB에 접근하기 위해서 HTML(웹브라우저)을 이용하게 된다. 
  >    HTML 파일에 <FORM> 태그 사용. 입력한 정보가 서버의 PHP 파일에 전달.
  >
  > 2. 사용자가 입력한 값은 웹 서버(PHP)로 전달이 된다. 
  >    MySQL 과 다른 응용 프로그램(PHP, C#, Java, Python 등)과 연계
  > 3. PHP 스크립트는 MySQL에 데이터로 입력이 된다.

