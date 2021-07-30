# 8주차 -EC2 Auto Scaling

[요약]

- AWS 탄력성의 이해와 EC2 Auto Scaling 서비스에 대해서 알아보자 

1. ## AWS EC2 Auto Scaling 소개

> Amazon EC2 Auto Scaling를 통해 애플리케이션의 로드를 처리할 수 있게 **동적으로 EC2 인스턴스를 유지**하도록 보장할 수 있다. 
>
> *Auto Scaling 그룹*이라는 EC2 인스턴스 모음을 생성한다.

- 각 Auto Scaling 그룹의 **최소 인스턴스 수**를 지정할 수 있으며, Amazon EC2 Auto Scaling에서는 그룹의 크기가 이 값 아래로 내려가지 않는다. 
- 각 Auto Scaling 그룹의 **최대 인스턴스 수**를 지정할 수 있으며, Amazon EC2 Auto Scaling에서는 그룹의 크기가 이 값을 넘지 않습니다.
- 원하는 용량을 지정한 경우 그룹을 생성한 다음에는 언제든지 Amazon EC2 Auto Scaling에서 해당 그룹에서 이만큼의 인스턴스를 보유할 수 있다. 조정 정책을 지정했다면 Amazon EC2 Auto Scaling에서는 애플리케이션의 늘어나거나 줄어드는 수요에 따라 인스턴스를 시작하거나 종료할 수 있다.
  - 예를 들어, 아래의 그림의 경우 최소 인스턴스 수 1개, 희망 인스턴스 용량 2개, 최대 인스턴스 수 4개가 된다. 



![](https://github.com/HYEONAH-SONG/AFOS/blob/master/img/week8/1.png?raw=true)

- #### 구성 요소

  - **Groups**
    EC2 인스턴스는 조정 및 관리 목적의 논리 단위로 취급될 수 있도록 그룹으로 구성된다. 그룹을 생성할 때 EC2 인스턴스의 최소 및 최대 인스턴스와 원하는 인스턴스 수를 지정할 수 있다.

  - ##### 구성 템플릿

    그룹에 대한 구성 템플릿으로 **시작 템플릿(모든 기능 제공)** 또는 시작 구성(권장 되지 않음, 더 적은 기능 제공)을 사용한다. 

    > 인스턴스의 AMI ID, 인스턴스 유형, 키 페어, 보안 그룹, 블록 디바이스 매핑 등의 정보를 지정할 수 있다. 

  - **조정 옵션 ★★★**
    Amazon EC2 Auto Scaling 은 **Auto Scaling 그룹을 조정**하는 다양한 방법을 제공한다.

- #### 장점

  - ##### 가변 수요 허용 : 자원 낭비, 전기 소모 등을 줄일 수 있다

    ![](https://github.com/HYEONAH-SONG/AFOS/blob/master/img/week8/2.png?raw=true)

  - ##### 가용성과 내결함성 향상

    ![](https://github.com/HYEONAH-SONG/AFOS/blob/master/img/week8/3.png?raw=true)

  ![](https://github.com/HYEONAH-SONG/AFOS/blob/master/img/week8/4.png?raw=true)

  - 인스턴스 생명 주기
     Auto Scaling 그룹이 인스턴스를 시작하고 서비스에 들어갈 때 시작된다. 수명 주기는 인스턴스를 종료하거나 Auto Scaling 그룹이 인스턴스를 서비스에서 제외시키고 이를 종료할 때 끝난다.

  - 조정 정책(Scaling Policy) ★★★

    - 대상 추적 조정 : 특정 **지표의 목표**값을 기준으로 그룹의 현재 용량을 **알아서** 늘리거나 줄인다.
    - 단계 조정 : 그룹의 현재 용량을 **일련의 조정 조절**에 따라 늘리거나 줄이며 경보 위반의 크기에 따라 달라지는 단계 조절
    - 단순 조정 : 그룹의 현재 용량을 **단일 조정 조절**에 따라 늘리거나 줄인다. 

    > => Auto Scaling 그룹의 인스턴스 수에 비례하여 증가하거나 감소하는 사용률 수치를 기준으로 조정하는 경우 대상 추적 조정 정책이 좋다. 그렇지 않은 경우에는 단계 조정 정책을 사용하는 것이 좋다. 

2. ## [실습] AWS EC2 Auto Scaling 구성

![](https://github.com/HYEONAH-SONG/AFOS/blob/master/img/week8/6.png?raw=true)

- Cloudformation을 이용한 인스턴스 생성

- 기본 환경 검증 : MyEC2 SSH 접속 

  ```shell
  aws --version
  aws ec2 describe-instances
  aws ec2 describe-instances --no-cli-pager
  aws ec2 describe-instances --query "Reservations[*].Instances[*].InstanceId" --output text
  aws ec2 describe-instances --query 'Reservations[*].Instances[*].[InstanceId, State.Name]' --output text
  aws ec2 describe-instances --query 'Reservations[*].Instances[*].[InstanceId, State.Name, PrivateIpAddress]' --output text
  
  while true; do aws ec2 describe-instances --query 'Reservations[*].Instances[*].[InstanceId, State.Name, PrivateIpAddress]' --output text; date; sleep 1; done
  
  (아래는 Auto Scaling 배포 후)
  aws ec2 describe-instances --filter "Name=tag:Name,Values=MyEC2"
  aws ec2 describe-instances --filter "Name=tag:Lab,Values=ASLab"
  while true; do aws ec2 describe-instances --filter "Name=tag:Lab,Values=ASLab" --query 'Reservations[*].Instances[*].[InstanceId, State.Name, PrivateIpAddress]' --output text; date; sleep 1; done
  
  # ApachBench 확인
  ab -V
  
  # ALB DNS 이름 변수 지정
  ALB=ALB-TEST-1714841830.ap-northeast-2.elb.amazonaws.com
  dig +short $ALB
  while true; do curl $ALB --silent --connect-timeout 1; date; echo "---[AutoScaling]---"; sleep 1; done
  ```

- EC2 → EC2 시작 템플릿 생성 → 아래 내용 입력 후 **시작 템플릿** 생성 클릭 
  ![](https://github.com/HYEONAH-SONG/AFOS/blob/master/img/week8/lab4.PNG?raw=true)

  

  ![](https://github.com/HYEONAH-SONG/AFOS/blob/master/img/week8/lab5.PNG?raw=true)

  ```shell
  시작 템플릿 이름 : EC2LaunchTemplate
  설명 : EC2 Auto Scaling v1.0
  Auto Scaling 지침 : 체크
  AMI : Amazon Linux 2 AMI(HVM), SSD Volume Type - 아키텍처 : 64비트(x86)
  인스턴스 유형 : t2.micro
  키 페어 : (각자 자신의 SSH 키페어 선택)
  네트워킹 플랫폼 : VPC
  보안 그룹 : ###-VPC1SG-### 포함된것 선택
  리소스 태그 : 키(Lab) , 값(ASLab)
  고급 세부 정보 ← 클릭
  - 세부 CloudWatch 모니터링 : 활성화 (★★)
  - 사용자 데이터 : 아래 내용 복붙!
  ```

  ```shell
  #!/bin/bash
  RZAZ=`curl http://169.254.169.254/latest/meta-data/placement/availability-zone-id`
  IID=`curl 169.254.169.254/latest/meta-data/instance-id`
  LIP=`curl 169.254.169.254/latest/meta-data/local-ipv4`
  amazon-linux-extras install -y php7.2
  yum install httpd htop tmux -y
  systemctl start httpd && systemctl enable httpd
  echo "<h1>RegionAz($RZAZ) : Instance ID($IID) : Private IP($LIP) : Web Server</h1>" > /var/www/html/index.html
  echo "1" > /var/www/html/HealthCheck.txt
  curl -o /var/www/html/load.php https://cloudneta-book.s3.ap-northeast-2.amazonaws.com/chapter5/load.php --silent
  curl -o /var/www/html/cpuload.php https://cloudneta-book.s3.ap-northeast-2.amazonaws.com/chapter5/cpuload-aws.php --silent
  ```

  - 생성된 시작 템플릿 → Auto Scaling 그룹 생성 클릭 

    ```
    [1단계]
    Auto Scaling 그룹 이름 : FirstEC2AutoScalingGroup
    시작 템플릿 : EC2LaunchTemplate
    ```

    ```
    [2단계]
    인스턴스 구매 옵션 : 시작 템플릿 준수
    네트워크 - VPC : VPC1
    네트워크 - 서브넷 : VPC1-Public-SN-1 , VPC1-Public-SN-2
    ```

    ```
    [3단계]
    로드 밸런싱 : 기존 로드 밸런서에 연결
    로그 밸런서 대상 그룹에서 선택 : 선택
    기존 로드 밸런서 대상 그룹 : ALB-TG
    상태 확인 유형 : ELB (Check)
    상태 확인 유예 기간 : 60초
    모니터링 - CloudWatch 내에서 그룹 지표 수집 활성화 : 체크
    ```

    ```
    [4단계]
    원하는 용량 : 1
    최소 용량 : 1
    최대 용량 : 4
    조정 정책 : 대상 추척 조정 정책
    조정 정책 이름 : Scale Out Policy
    대상 값 : 80 → 3분 동안 3번 연속 CPU 80% 경우(1분 마다 기록)
    인스턴스 요구 사항 : 60초 → 지표에 포함하기 전 워밍업 시간(초)
    확대 정책만 생성하려면 축소 비활성화 : Check → 축소는 직접 정책 추가 예정
    인스턴스 축소 보호 활성화 : UnCheck
    ```

    ```
    [5단계] → [6단계] 태그 : 키(Name) , 값(WebServers) → [7단계] ⇒ Auto Scaling 그룹 생성 클릭
    ```

  - 생성된 Auto Scaling 그룹 클릭 → 세부 정보 → 하단 고급 구성 편집 클릭 → 업데이트

    ```
    종료 정책 : Newest Instance → 기본 Default 는 제거
    기본 휴지 기간 : 180초
    ```

  - 축소 조정 정책을 추가: 생성된 Auto Scaling 그룹 클릭 → 자동 조정 → 정책 추가 클릭⇒ 정책 유형(`단순 조정`) 선택 후 `CloudWatch 경보 생성` 클릭 → `다음` 클릭

    `단계1` 지표 선택 → EC2 → Auto Scaling 그룹별 → ' ' 의 CPUUtilization 선택 후 `지표 선택` 클릭 ⇒ 기간(**1분**)

    `조건`  정적 → 보다 작음 → ...보다(**10**) → 추가 → 경보를 알릴 데이터 포인트( **2 / 2** )

    `단계2` 경보 상태 트리거 (**제거**) → 하단 `다음` 클릭

    `단계3` 경보 이름(ASG-CpuLow) → 하단 `다음` 클릭

    `단계4` → 하단 `경보 생성` 클릭

    ⇒ 정책 유형(`단순 조정`) 선택 상태

    조정 정책 이름 : **Scale In Policy**

    CloudWatch 경보 : *(위 생성된 경보 선택)*

    작업 수행 : 제거 , 1(용량단위)

    그런 다음 대기 : 60초 ⇒ 하단 `생성` 클릭

  - 중요하게 알아야 할 Scale !!!

    - Scale In / Out : 인스턴스의 갯수를 증가 / 감소
    - Scale Up / Down : 성능의 향상 / 저하 

