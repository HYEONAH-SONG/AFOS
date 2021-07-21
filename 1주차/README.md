# 1주차 - AWS & 가상서버

#### [목차]

:ballot_box_with_check: <u>클라우드란</u>?

:ballot_box_with_check: <u>AWS 클라우드</u>

:ballot_box_with_check: <u>실습</u>

<hr>

### 1.클라우드란?

- 클라우드는 인터넷을 통해서 언제 어디서든지 원하는 때 원하는 만큼의 **IT리소스**(컴퓨팅, 스토리지, 네트워크)를 손쉽게 사용할 수 있게 하는 서비스를 의미한다.

  ![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/Cloud.png?raw=true)

- #### 클라우드 구현 모델

  - ##### 퍼블릭 클라우드

    **클라우드 서비스 제공 업체가 운영 관리**하며, 사용자는 해당 클라우드의 리소스를 사용하는 모델

    ![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/Public%20Cloud.png?raw=true)

  - ##### 프라이빗 클라우드

    사용자가 **자신의 온프레미스 내에** 클라우드 플랫폼을 구축하여 직접 사용하는 모델

    ![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/Private.png?raw=true)

  - ##### 하이브리드 클라우드 

    퍼블릭 클라우드와 온프레미스 모두에 서비스하는 모델
    ![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/Hybrid.png?raw=true)

  - ##### 멀티 클라우드

    네트워크 연결에 상관없이 2개 이상의 클라우드를 포함한 IT 시스템

  

- #### 클라우드 서비스의 종류

  - ##### IaaS(Infrastructure as a Service)

    - 인터넷을 통해 사용자에게 IT인프라를 제공하는 클라우드 컴퓨팅
    - 운영체제, 데이터 미들웨어, 런타임, 애플리케이션을 사용자가 관리

  - ##### PaaS(Platform as a Service)

    - 제공 업체를 통해 하드웨어, 애플리케이션 소프트웨어 플랫폼이 제공되는 클라우드 컴퓨팅
    - 인프라를 구축 및 유지 관리할 필요 없음

  - ##### SaaS(Software as a Service)

    - 클라우드 애플리케이션 및 기본 IT 인프라, 플랫폼을 사용자에게 제공하는 클라우드 컴퓨팅
    - 사용자가 관리할 필요 없음

  - ##### Severless

    - 개발자가 서버를 관리할 필요가 없음
    - 클라우드 제공 업체가 서버 인프라에 대한 프로비저닝, 유지 관리, 스케일링 등 작업 처리
  

![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/Cloud%20Service%20Catagory.png?raw=true)

### 2. AWS 클라우드

- AWS(Amazon Web Services)는 전 세계적으로 분포한 데이터 센터에서 다양한 서비스를 제공하고 있는 클라우드 플랫폼이다. 
- AWS 자원 사용 방법 
  - AWS Web Console (=> 이번 실습)
  - AWS CLI
  - AWS SDK

### 3. 실습

- ##### AWS EC2 인스턴스를 배포 후 해당 인스턴스에 웹을 통하여 SSH 접속을 하고, 웹 서비스를 설치 및 확인하는 실습

![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/week_1.png?raw=true)

1. AWS 관리 콘솔에 접속 후 EC2 배포하기
   - EC2 서비스 → '인스턴스 시작' 클릭