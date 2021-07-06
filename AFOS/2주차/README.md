# 2주차 - 컴퓨팅 서비스

:ballot_box_with_check: <u>AWS 글로벌 인프라</u>?

:ballot_box_with_check: <u>AWS 리전 & 가용영역 & 엣지</u>

:ballot_box_with_check: <u>EC2 소개</u>

:ballot_box_with_check: <u>실습</u>

### 1. AWS 글로벌 인프라

AWS 는 전 세계적으로 분포한 데이터센터에서 다양한 서비스를 제공하는 있는 클라우드 플랫폼입니다. 현재 AWS는 **25개의 리전과 80개의 가용영역, 230개이상의 POP**을 운영하고 있습니다. 각각의 리전은 이중화된 100G 케이블(해저 광케이블)로 연결되어 있으며, 암호화되어 전달되고 있습니다.

![](C:\Users\sha08\OneDrive\바탕 화면\AFOS\img\AWS_Infra.png)

### 2. AWS 리전 & 가용영역 & 엣지

- #### Region

  - 해당 지리적인 영역 내에서 격리되고 물리적으로 분리된 여러 개의 가용 영역(AZ)의 모음
  - 리전은 최소 2개의 가용 영역으로 구성되고 최대 6개의 가용 영역으로 구성된 리전도 존재

- #### AZ(Availability Zone)

  - 한 개 이상의 데이터 센터들의 모음
  - 각 센터는 광통신 전용망으로 연결
  - 가용 영역과 인터넷 연결을 위해 이중화된 트랜짓 센터가 존재
  - AWS 사용자는 서비스 구성 시 여러 가용 영역에 분산하여 처리할 수 있도록 구성을 권장

  ![](C:\Users\sha08\OneDrive\바탕 화면\AFOS\img\AZ.png)

- #### Edge

  - 외부 인터넷과 AWS 글로벌 네트워크망과 연결하는 별도의 센터
  - 엣지는 엣지 로케이션과 리전별 엣지 캐시로 구성되며, CloudFront 와 같은 CDN 서비스의 데이터 캐시 기능을 제공

### 3. EC2 소개

- Amazon Elastic Compute Cloud(Amazon EC2)는 Amazon Web Services(AWS) 클라우드에서 확장 가능 컴퓨팅 용량을 제공
- Amazon EC2를 통해 원하는 만큼 가상 서버(=인스턴스 Instance)를 구축하고 보안 및 네트워크 구성과 스토리지 관리 가능

### 4. 실습

AWS EC2 인스턴스를 배포 후 해당 인스턴스에 SSH Client 로 접속을 하고 기본 동작들을 확인합니다.

![](C:\Users\sha08\OneDrive\바탕 화면\AFOS\img\week_2.png)

