# 1주차 - AWS & 가상서버

##### [목차]

[:ballot_box_with_check: <u>클라우드란</u>?](##1.-클라우드란?)

[:ballot_box_with_check: <u>AWS 클라우드</u>](##2.-AWS-클라우드)

[:ballot_box_with_check: <u>실습</u>](##3.-실습)

##### [요약]

- 클라우드 & AWS 소개
- IaaS 중 EC2(가상서버)를 배포하고 사용

<hr>
## 1. 클라우드란?


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

## 2. AWS 클라우드

- AWS(Amazon Web Services)는 전 세계적으로 분포한 데이터 센터에서 다양한 서비스를 제공하고 있는 클라우드 플랫폼이다. 
- AWS 자원 사용 방법 
  - AWS Web Console (=> 이번 실습)
  - AWS CLI
  - AWS SDK



## 3. 실습

- ##### AWS EC2 인스턴스를 배포 후 해당 인스턴스에 웹을 통하여 SSH 접속을 하고, 웹 서비스를 설치 및 확인하는 실습 ⇒ AWS에서 제공하는 SSH 사용

![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/week_1.png?raw=true)

1. #### AWS 관리 콘솔에 접속 후 EC2 배포하기

   - ##### EC2 서비스 → '인스턴스 시작' 클릭

     ![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/week_1(%EC%8B%A4%EC%8A%B51).png?raw=true)

   - ##### **Amazon Machine Image(AMI)** 선택 → 'Amazon Linux 2 AMI : 64bit(x86)' 선택

     > AMI : 인스턴스를 시작하는 데 필요한 소프트웨어 구성(운영체제, 애플리케이션 서버, 애플리케이션)이 포함된 템플릿

   - ##### **인스턴스 유형** 선택 (사양) → 't2.micro vCPU(1) MEM(1GiB)' 선택

     > 인스턴스 : 애플리케이션을 실행할 수 있는 가상 서버
     >
     > 인스턴스에는 CPU, 메모리, 스토리지, 네트워킹 용량의 다양한 조합이 존재하여 선택이 가능함

   - ##### 인스턴스 세부 정보 구성 → 대부분 Default 설정

     - 인스턴스 개수(1) , 네트워크, **퍼블릭 IP 자동 할당(서브넷 사용 설정 - 활성화)**

     ![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/week_1(%EC%8B%A4%EC%8A%B54).PNG?raw=true)

   - ##### 스토리지 추가 선택 

     > 인스턴스에 사용되는 스토리지 디바이스 설정

   - ##### 태그 추가 

     > 태그 지정 시 인스턴스, 이미지 및 기타 Amazon EC2 리소스를 쉽게 관리할 수 있습니다. 지정한 태그에 따라 특정 리소스를 빠르게 식별할 수 있다.
     >
     > 키 : Name & 값 : FirstServer

   - **보안 그룹 구성** ★★★

     > 보안 그룹은 인스턴스에 대한 트래픽을 제어하는 방화벽 규칙 세트(허용 규칙)이다.

     - 서버의 구별 : 인터넷 통신으로 상대방 목적지까지 도달하기 위해 필요 → **IP 정보**
     - 서버 내의 서비스 구별 : 특정 서버의 2가지 서비스(웹 서버, 파일 서버)를 동시에 제공 시 구별하기 위해 필요 → **Port 정보**(0~65535)
     - HTTP(80) , SSH(22)

   - ##### 시작하기 ⇒ (SSH 키 관련 선택) 기존 키 페어 선택 또는 새 키 페어 생성 : 기존 키 페어 선택 후 자신의 SSH 키 페어 선택 후 체크 후 인스턴스 시작 클릭

     - 키 페어 관리  : EC2 서비스 → '키 페어' 카테고리

   ![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/week_1(%EC%8B%A4%EC%8A%B53).PNG?raw=true)

2. AWS 관리 콘솔에서 생성된 EC2 정보 확인

   - 리눅스의 기초 명령어들을 활용하여 배포한 EC2의 기본 정보 확인

   ~~~shell
   ```bash
   # 현재 접속한 사용자 확인
   [ec2-user@ip-172-31-46-223 ~]$ **whoami**
   **ec2-user**
   
   # 현재 Linux 버전 정보 확인
   [ec2-user@ip-172-31-46-223 ~]$ **cat /etc/system-release**
   Amazon Linux release 2 (Karoo)
   
   # CPU 확인
   [root@ip-172-31-43-250 ~]# **cat /proc/cpuinfo |egrep '(processor|name)'**
   processor	: 0
   model name	: Intel(R) Xeon(R) CPU E5-2676 v3 @ 2.40GHz
   
   # 메모리 확인
   [root@ip-172-31-43-250 ~]# **cat /proc/meminfo |grep MemTotal**
   MemTotal:        1006900 kB
   
   # 다양한 시스템 관련 부하 확인 툴 설치
   # sudo 는 슈퍼유저의 권한으로 실행
   [ec2-user@ip-172-31-46-223 ~]$ **sudo yum -y install dstat htop**
   
   # dstat 로 시스템 관련 부하 확인
   # 예시) dstat -t(일시)l(평균부하)m(메모리)a(cdngy CPU Disk Network Paging System) --output (출력을 csv파일로 저장) 3(초마다)
   [ec2-user@ip-172-31-46-223 ~]$ **dstat -tlma 1**
   ----system---- ---load-avg--- ------memory-usage----- ----total-cpu-usage---- -dsk/total- -net/total- ---paging-- ---system--
        time     | 1m   5m  15m | used  buff  cach  free|usr sys idl wai hiq siq| read  writ| recv  send|  in   out | int   csw
   13-02 06:16:22|   0    0    0| 110M 2088k  384M  487M|  1   0  98   0   0   0| 227k  335k|   0     0 |   0     0 |  38   131
   13-02 06:16:23|   0    0    0| 110M 2088k  384M  487M|  0   0 100   0   0   0|   0     0 |  52B 1412B|   0     0 |  32    59
   **CTRL + C 로 빠져나오기**
   
   # htop 으로 CPU Memory 등 시스템 자원 확인
   [ec2-user@ip-172-31-46-223 ~]$ **htop**
   
   # 프라이빗 IP 정보 확인
   [ec2-user@ip-172-31-46-223 ~]$ **ip -br -c addr show**
   lo               UNKNOWN        127.0.0.1/8 ::1/128
   **eth0**             UP             **172.31.46.223**/20 fe80::880:baff:fe0f:2598/64
   
   [ec2-user@ip-172-31-46-223 ~]$ **ip a**
   
   # 퍼블릭 IP 정보 확인
   [ec2-user@ip-172-31-46-223 ~]$ **curl ipinfo.io**
   
   # 스토리지 확인 : 부트볼륨(EBS) 정보 확인
   [ec2-user@ip-172-31-46-223 ~]$ **lsblk**
   NAME    MAJ:MIN RM SIZE RO TYPE MOUNTPOINT
   **xvda**    202:0    0   8G  0 disk
   └─**xvda1** 202:1    0   8G  0 part /
   
   [ec2-user@ip-172-31-46-223 ~]$ **df -h**
   Filesystem      Size  Used Avail Use% Mounted on
   devtmpfs        482M     0  482M   0% /dev
   tmpfs           492M     0  492M   0% /dev/shm
   tmpfs           492M  460K  492M   1% /run
   tmpfs           492M     0  492M   0% /sys/fs/cgroup
   **/dev/xvda1      8.0G  1.4G  6.7G  18% /**
   tmpfs            99M     0   99M   0% /run/user/0
   tmpfs            99M     0   99M   0% /run/user/1000
   
   # 서비스 제공 확인(포트 Listen) : SSH(서버 서비스 중, TCP 22 포트 사용)
   # 예시) ss -t(TCP) -l(Listen) -n(숫자로 출력)
   [ec2-user@ip-172-31-46-223 ~]$ **ss -tl**
   State           Recv-Q           Send-Q                      Local Address:Port                       Peer Address:Port
   **LISTEN          0                128                               0.0.0.0:ssh                             0.0.0.0:***
   ...
   
   [ec2-user@ip-172-31-46-223 ~]$ **ss -tln**
   State           Recv-Q           Send-Q                      Local Address:Port                       Peer Address:Port
   **LISTEN          0                128                               0.0.0.0:22                              0.0.0.0:***
   ...
   
   # -t(TCP 세션 연결)
   [ec2-user@ip-172-31-46-223 ~]$ **ss -ttn**
   State        Recv-Q         Send-Q                  Local Address:Port                 Peer Address:Port          
   ESTAB        0              0                       172.31.46.223:22                    13.209.1.57:53559         
   
   # -p(프로세스 정보, 관리자 권한 필요)
   [ec2-user@ip-172-31-46-223 ~]$ **sudo ss -ttnp**
   State        Recv-Q         Send-Q                  Local Address:Port                 Peer Address:Port          
   ESTAB        0              0                       172.31.46.223:22                    13.209.1.57:53559         
    **users:(("sshd",pid=4446,fd=3),("sshd",pid=4044,fd=3))**
   ~~~

3. EC2 인스턴스에 웹 서비스 설치

   - 아래 처럼 웹 서비스를 설치하고 index.html 파일을 생성

   ~~~shell
   ```bash
   # 실습의 편리를 위해서 root 계정으로 전환합니다.
   # 실제 현업에서 root(관리자, 수퍼유저)로 사용은 되도록 금합니다. 하지만 실습의 경우이니 편리성을 위해서 관리자로 전환하여 진행합니다.
   [ec2-user@ip-172-31-46-221 ~]$ sudo su -
   
   # Web 서비스를 설치합니다.
   [root@ip-172-31-46-221 ~]# yum install httpd -y
   
   # Web 서비스를 실행합니다.
   [root@ip-172-31-46-221 ~]# systemctl start httpd
   
   # 웹 페이지를 구성합니다.
   # 기본 웹 페이지 디렉터리는 /var/www/html 이다.
   # 아래 명령어는 본문 내용을 담아서 index.html 파일을 생성한다
   ## (심화) Apache는 파일에 접근 시에 디렉토리만 지정될 경우에 기본으로 반환하는 파일을 "DirectoryIndex"로 지정하며 보통 'index.html' 포함됨
   [root@ip-172-31-46-221 ~]# echo "<h1>Test Web Server</h1>" > /var/www/html/index.html
   
   # ls 로 파일 생성 확인
   [root@ip-172-31-46-223 ~]# ls /var/www/html/
   index.html
   
   # cat 로 생성된 파일 내용 확인
   [root@ip-172-31-46-223 ~]# cat /var/www/html/index.html
   <h1>Test Web Server</h1>
   
   # curl(CLI 웹 요청 명령어) 명령어로 웹 접속을 확인합니다. 
   [root@ip-172-31-46-221 ~]# curl localhost
   <h1>Test Web Server</h1>
   
   # 웹 서비스 동작 확인 - TCP 80(HTTP)를 사용
   [root@ip-172-31-46-223 ~]# ss -tl
   State           Recv-Q           Send-Q                      Local Address:Port                       Peer Address:Port
   ...
   LISTEN          0                128                                     *:http                                  *:*
   
   [root@ip-172-31-46-223 ~]# ss -tln
   State           Recv-Q           Send-Q                      Local Address:Port                       Peer Address:Port
   ...
   LISTEN          0                128                                     *:80                                    *:*
   ~~~

   ```shell
   sudo su -
   yum install httpd -y
   systemctl start httpd
   echo "<h1>Test Web Server</h1>" > /var/www/html/index.html
   ls /var/www/html/
   cat /var/www/html/index.html
   curl localhost
   ss -tl
   ss -tln
   ```

4. 웹 브라우저에서 해당 EC2의 퍼블릭 IP로 접속하여 웹 서비스 접속 확인

   - 웹 브라우저에서 해당 EC2의 퍼블릭 IP로 접속 시도 → 접속 실패

   :question: Shell 에서는 curl 로 웹 접속이 되는데 외부에서 웹 접속이 안되는 이유는 무엇을까요? (1) 그냥 (2) 퍼블릭 IP 라서 **(3) 보안그룹**

   - Solution

     1. EC2 → 하단 보안 탭 메뉴 클릭 → 아래 보안 그룹 클릭
        ![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/week_1(%EC%8B%A4%EC%8A%B56).png?raw=true)

     2. 인바운드 규칙 편집 클릭 → 규칙 추가 → HTTP , 위치 무관 → 하단 '규칙 저장' 클릭

        ![](https://github.com/HYEONAH-SONG/AWS_Practice/blob/master/img/week1/week_1(%EC%8B%A4%EC%8A%B57).png?raw=true)

     3. 재접속하여 웹 서비스 정상 접속 확인 👏 👍 🤗

 