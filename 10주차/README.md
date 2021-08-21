# 10주차 - Wordpress 이중화 구성 :family:

1. ### 실습 소개

   - Best Practice for WordPress on AWS

     > - 권장 구성 아키텍쳐

     ![](https://github.com/HYEONAH-SONG/AFOS/blob/master/img/week10/1.png?raw=true)

     > - WordPress HA 권장 구성

     ![](https://github.com/HYEONAH-SONG/AFOS/blob/master/img/week10/2.png?raw=true)
     - CloudFront는 CDN 서비스와 기본 보안 기능(Anti-DDos)을 제공합니다. 
     - S3는 Wordpress의 Upload 파일들을 저장하고 사용자에게 제공한다.
     - HA(고가용성) 구성으로 되어 있으며 일부 장애(AZ, RDS, Web EC2 장애 등)에 대응이 가능하다.

   - CloudFormation 실습 기본 환경 배포

     - CloudFormation 스택 생성 - [링크](https://console.aws.amazon.com/cloudformation/home?region=ap-northeast-2#/stacks/new?stackName=WPHALab&templateURL=https:%2F%2Fs3.ap-northeast-2.amazonaws.com%2Fcloudformation.cloudneta.net%2FWordpress%2Faws-wp.yaml) 클릭 후 템플릿 파일로 기본 환경 자동 배포 됩니다.
       - 파라미터(KeyName - 자신의 SSH 키 선택) `다음` 클릭 →  `다음` 클릭 → `스택 생성` 클릭
       - 맨 하단에 아래 **IAM 리소스 생성 승인** `체크` 후 스택 `생성` 클릭

   - 2번 실습을 위한 RDS 배포

     - WebSrv-Leader 인스턴스를 이용하여 Wordpress 초기 구성 :) RDS DB 생성은 미리하기!
     - RDS → 데이터베이스 생성 클릭 → 데이터베이스 생성 12분 정도 소요! ⇒ 백업본 생성에 4분 정도 소요!

     ```
     # DB 디폴트 설정
     생성 방식 : 표준 생성
     엔진 옵션 : MySQL
     템플릿 : 개발/테스트
     DB 인스턴스 식별자 : wpdb (현재 AWS 리전에서 AWS 계정이 소유하는 모든 DB 인스턴스에 대해 유일, 각자 편하게 설정)
     마스터 사용자 이름 : root
     마스터 암호(암호확인) : qwe12345
     DB 인스턴스 클래스 : 버스터블 클래스(t 클래스 포함) db.t2.micro (이전 세대 클래스 포함)
     다중 AZ 배포 : 대기 인스턴스 생성 
     VPC : WP-VPC1
     퍼블릭 액세스 가능 : 아니요
     VPC 보안 그룹 : -VPC1SG3- 포함된것 선택 , 기본 default 는 제거
     추가 구성 : 클릭
     - 초기 데이터베이스 이름 : wordpressdb
     - DB 파라미터 그룹 : -mydbparametergroup- 포함된것 선택 
     - 백업 보존 기간 : 1일
     - Enhanced 모니터링 활성화 (Uncheck)
     ```

     

2. ### AllInOne 인스턴스를 이용하여 CloudFront, ELB, S3(WP Offload Media Lite) 활용

   - CloudFront → Domain Name 확인

   - EC2 → AllInOne 인스턴스 확인 , IAM Profile(IAM Role - S3FullAccess)

     - IAM Role → WPLabInstanceRole ⇒ 정책 이름 AmazonS3FullAccess

     ```json
     {
         "Version": "2012-10-17",
         "Statement": [
             {
                 "Effect": "Allow",
                 "Action": "s3:*",
                 "Resource": "*"
             }
         ]
     }
     ```
     - EC2 Public IP로 웹 접속 → Wordpress 관리자 계정 설정 → 로그인 → 설정(일반 설정)에 정보(워드프레스 주소, 사이트 주소) 확인 → http://CloudFrontDomainName
     - ALB & 대상 그룹 확인

   - CloudFront 의 Domain Name 으로 웹 접속

     - 블로그 주소 HTTP://CloudFrontDomainName/
     - 관리자 페이지 주소 http://CloudFrontDomainName/wp-admin/

   - AllInOne 인스턴스 SSH 접속 후 업로드 폴더 확인

     ```shell
     cd /var/www/html/wp-content/uploads
     
     # 업로드 폴더 정보 확인
     tree
     [root@AllInOne uploads]# tree
     .
     └── 2021
         └── 08
     ```

   - 워드프레스에 첫글 작성(이미지 포함)후 공개 → AllInOne 의 업로드 폴더 확인

     ```shell
     # 업로드 폴더 정보 확인
     [root@AllInOne uploads]# tree
     .
     └── 2021
         └── 08
             ├── icando-150x150.jpg
             ├── icando-300x300.jpg
             └── icando.jpg
     ```

   ##### [업로드 저장소를 S3 로 변경하여 저장 및 파일 제공 최적화]

   1. 워드프레스 → 플러그인 → 새로 추가 → 키워드 WP Offload Media Lite 검색 → 지금 설치 클릭 ⇒ 활성화 클릭 → Settings 클릭
   2. WP Offload Media Lite 설정
      - 아래 중간 선택 후 하단 Next 클릭
      - 하단 Create new bucket 클릭 → Region(서울), Bucket(유일한 버킷 이름 지정) → 하단 Create new bucket 클릭
      - 설정 정보 확인 : STORAGE → Path(wp-content/uploads/)
      - 설정 변경 : ADVANCED OPTIONS → Remove Files From Server: `ON`
      - 하단 `Save Changes` 클릭

   3. 워드프레스에 두번째글 작성(이미지 포함)후 공개
      - 글 보기 후 이미지 → 우클릭 후 새로운 탭에서 이미지 보기 → 상단에 접속 URL 주소 정보 확인
      - S3 버킷에 저장된 이미지 정보 확인
      - AllInOne 의 업로드 폴더에 이미지 존재 여부 확인

   ```
   # 기존 파일만 존재
   tree /var/www/html/wp-content/upload
   
   # S3 버킷에 저장된 내용 확인
   aws s3 ls s3://<자신의 버킷 이름> --recursive
   aws s3 ls s3://beaswp --recursive
   ```