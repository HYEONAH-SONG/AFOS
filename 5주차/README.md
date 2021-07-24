# 5주차 - 보안 서비스(IAM)

##### [Why Security?]

- AWS를 사용하는 4가지 방법

  - **AWS Web Console** :  관리 콘솔로 리소스 만들기 
    - 장점 : 가장 접근이 쉽고, 사용하기 단순하다
    - 단점 : 반복 이나 대량 작업을 수행하기에는 비효율적이다
  - **CLI(Command Line Interface)** : 코드 형태의 명령형 인프라 (스크립트 기반)
    - 장점 : 반복 작업이나 수정이 용이하다
    - 단점 : 직접 스크립트는 작성해서 리소스를 관리해야 하고, 문제 발생 시 복원이 어렵다
  - **IaC(Infrastructure as a Code)** : 코드로서의 선언적 인프라
    - CloudFormation template
    - Terraform - HashiCorp Configuration Language(HCL)
  -  **AWS CDK** : 익숙한 프로그래밍 언어를 사용하여 클라우드 애플리케이션 리소스를 모델링 및 프로비저닝 할 수 있는 오픈 소스 소프트웨어 개발 프레임워크 

  > 코드를 통해서 인프라를 관리하기 위해서는 "API"가 필요
  >
  > → 이로 인해 API접근(보안)에 대한 중요도 증가

[ IAM(**Identify** and **Access** Management) ]

- AWS 전체의 권한 통제 시스템 
  - 인증(Identify) : 너는 누구니?
  - 권한(Access) : 너는 권한이 있니?

- **Root User** vs **IAM User**
  - Root User : AWS 모든 서비스에 대한 접근 권한을 가짐 
    - AWS 계정 생성 / IAM 생성 용도로만 사용을 권장 (모든 권한을 가지고 있기 때문)
  - IAM User

