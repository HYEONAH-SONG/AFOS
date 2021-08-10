# 9주차. Wordpress & WooCommerce 

##### [차례]

1. Wordpress 소개
2. [1안] 단일 서버 구성
3. [2안] 웹서버 & DB 서버 구성 + WooCommerce 설정
4. [3안] 웹서버(EFS) & AWS RDS 구성

##### [요약]

- Wordpress를 이용한 웹사이트를 제작하여 기존 인프라의 동작을 확인해보자



1. ## Wordpress 소개​ :globe_with_meridians:

   - ### 워드프레스란?

     > 워드프레스는 누구나 손쉽게 블로그, 웹사이트, 쇼핑몰 등을 구축하는 **웹사이트 제작 도구**이다. 
     >
     > 워드 프레스는 수많은 써드파티 테마와 플러그인 제작자들에 의해 제공되는 무료, 유료 플러그인을 설치해 사이트 디자인을 바꾸고 기능을 확장시키는 것이 큰 장점이다. 
     >
     > - Open & Free Source
     > - Blogging Tool & CMS(Content Management System, 콘텐츠 플랫폼/시스템) & E-COMMERCE
     > - PHP & MySQL

     - ##### 워드프레스 구성 환경 :credit_card:

       ```
       t2.micro(vCPU 1, Mem 1GiB) 는 WooCommerce 플러그인 설치 시 메모리 부족 등으로 설치 자체가 제대로 진행이 되지 않는다.
       따라서 설치가 가능한 최소의 성능인 t3.medium(vCPU 2, Mem 4GiB)로 배포가 된다. 
       # 1시간당 0.052 USD(60원) 비용이 부가됨
       ```

   - ### 워드프레스 구성 

     > #### Web + PHP + DB
     >
     > 1. #### 1대 서버에 모든 서비스 구성
     >
     >    - 1대의 인스턴스에 Web + PHP + DB 구성 → 부하 문제 발생 가능
     >      ![]()
     >
     > 2. #### 2대 EC2로 구성
     >
     >    - Web/PHP 인스턴스 + MariaDB 인스턴스 구성
     >
     > 3. #### 1대의 EC2와 AWS RDS로 구성
     >
     >    - Web/PHP 인스턴스(EFS) + AWS RDS(DB)로 구성

   - ### WooCommerce :shopping_cart:

     세계에서 가장 많이 사용하는 **워드프레스 쇼핑몰 결제 플러그인**으로, 국내 쇼핑몰의 다양한 결제 플러그인도 우커머스 플러그인을 기반으로 제작되고 있다

2. ### [1안] 단일 서버 구성

   - CloudFormation 스택 생성 - [링크](https://console.aws.amazon.com/cloudformation/home?region=ap-northeast-2#/stacks/new?stackName=WPLab&templateURL=https:%2F%2Fs3.ap-northeast-2.amazonaws.com%2Fcloudformation.cloudneta.net%2FWordpress%2Faws-wordpress-db2.yaml) 클릭 후 템플릿 파일로 기본 환경 자동 배포 된다
     - 파라미터(KeyName - 자신의 SSH 키 선택) **다음** 클릭 →  **다음** 클릭 → **스택 생성** 클릭
   - 1대의 인스턴스에 Web + PHP + DB 구성

   1. 웹서버 설치

      ```shell
      # 관리자 전환
      sudo su -
      
      # 설치
      yum install httpd -y
      
      # 서비스 실행
      systemctl start httpd && systemctl enable httpd
      
      # 웹서버 버전 확인 -> 2.4 이상
      httpd -v
      
      #웹 접속하여 확인(EC2 Public IP 입력)
      curl EC2_Public_IP
      ```

   2. PHP 설치 → PHP Extensions 설치

      ```shell
      # 설치(AWS 내부 존재)
      amazon-linux-extras install php7.4 -y
      
      # PHP 버전 확인
      php -v
      
      # PHP Extensions 설치
      yum install gcc php-xml php-mbstring php-sodium php-devel php-pear ImageMagick-devel ghostscript -y
      
      # PHP Extensions 정보 확인
      php --ini
      
      # PHP Extensions - imagick 설치
      ## imagick 관련 ini 파일 생성
      cat <<EOT> /etc/php.d/40-imagick.ini
      ; Enable imagick extension module
      extension = imagick.so
      EOT
      
      ## pecl 로 imagick 설치
      printf "\n" | pecl install imagick
      
      # php-fpm 재시작으로 imagick 적용
      systemctl restart php-fpm
      systemctl restart httpd
      
      # PHP Extensions 정보 확인
      php --ini
      
      # php info 페이지 생성
      echo "<?php phpinfo(); ?>" > /var/www/html/info.php
      
      # phpinfo.php 웹 접속하여 확인
      http://EC2_PublicIP/info.php
      ```

      - 2M 이상 크기  파일 업로드 관련 설정 및 메모리 상향 설정

      ```shell
      # php.ini 파일 수정
      sed -i 's/^upload_max_filesize = 2M/upload_max_filesize = 64M/g' /etc/php.ini
      sed -i 's/^post_max_size = 8M/post_max_size = 64M/g' /etc/php.ini
      sed -i 's/^max_execution_time = 30/max_execution_time = 300/g' /etc/php.ini
      sed -i 's/^memory_limit = 128M/memory_limit = 256/g' /etc/php.ini
      
      # php-fpm 재시작으로 적용
      systemctl restart php-fpm
      
      ```

   3. Maria DB 설치

      ```shell
      # 설치
      amazon-linux-extras install mariadb10.5 -y
      
      # 서비스 시작
      systemctl start mariadb && systemctl enable mariadb
      
      # DB root 계정 설정 및 권장 설정
      echo -e "\n Y\n n\n Y\n Y\n Y\n Y\n" | /usr/bin/mysql_secure_installation
      
      
      # DB 에 한글 입력을 위한 설정
      sed -i'' -r -e "/\[mysqld\]/a\character-set-server=utf8" /etc/my.cnf.d/mariadb-server.cnf
      sed -i'' -r -e "/\[mysqld\]/a\collation-server=utf8_general_ci" /etc/my.cnf.d/mariadb-server.cnf
      sed -i'' -r -e "/\[mysqld\]/a\init_connect=\"SET NAMES utf8\"" /etc/my.cnf.d/mariadb-server.cnf
      sed -i'' -r -e "/\[mysqld\]/a\init_connect=\"SET collation_connection = utf8_general_ci\"" /etc/my.cnf.d/mariadb-server.cnf
      sed -i'' -r -e "/\[client\]/a\default-character-set=utf8" /etc/my.cnf.d/client.cnf
      sed -i'' -r -e "/\[mysql\]/a\default-character-set=utf8" /etc/my.cnf.d/mysql-clients.cnf
      sed -i'' -r -e "/\[mysqldump\]/a\default-character-set=utf8" /etc/my.cnf.d/mysql-clients.cnf
      
      # root 계정을 외부에서도 접속 가능하게 설정
      mysql -e "set password = password('qwe123');"
      mysql -uroot -pqwe123 -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'qwe123';"
      
      # wordpressdb 데이터베이스 생성 및 확인
      mysql -uroot -pqwe123 -e "CREATE DATABASE wordpressdb"
      mysql -uroot -pqwe123 -e "show databases;"
      
      # 서비스 재시작
      systemctl restart mariadb
      
      # 버전 확인
      mysql --version
      ```

   4. Wordpress v5.8 (KR) 설치

      ```shell
      # 다운로드
      wget https://ko.wordpress.org/wordpress-latest-ko_KR.zip
      wget https://ko.wordpress.org/wordpress-5.8-ko_KR.zip
      
      
      # 압축 풀기
      unzip wordpress-latest-ko_KR.zip
      
      # wp-config.php 파일 복사
      cp wordpress/wp-config-sample.php wordpress/wp-config.php
      
      # wp-config.php 파일에 db 접속을 위한 정보 입력
      sed -i "s/database_name_here/wordpressdb/g" wordpress/wp-config.php
      sed -i "s/username_here/root/g" wordpress/wp-config.php
      sed -i "s/password_here/qwe123/g" wordpress/wp-config.php
      
      # wp-config.php 파일에 메모리 상향 설정
      cat <<EOT>> wordpress/wp-config.php
      define('WP_MEMORY_LIMIT', '256M');
      EOT
      
      # 압축 푼 wordpress 파일을 웹 디렉터리에 복사
      cp -r wordpress/* /var/www/html/
      
      # 사용자와 권한 설정
      chown -R apache /var/www
      chgrp -R apache /var/www
      chmod 2775 /var/www
      find /var/www -type d -exec chmod 2775 {} \;
      find /var/www -type f -exec chmod 0664 {} \;
      
      # 서비스 재시작
      systemctl restart httpd
      ```

   5. Wordpress 웹 접속하여 관리자 계정 정보 입력 http://EC2_Public_IP (인스턴스 공인 IP로 웹 접속)

   6. [Wordpress] 첫 글 작성(새로 추가) - 이미지 삽입  → 발행 후 글 확인

   7. [Wordpress] 외모 - 새로운 테마 추가 → 시드니(sydney) 설치 후 활성화 2~3분 정도 소요 ⇒ 웹 접속 확인

      - Starter Sites → Plumber 클릭 후 Import → Import 클릭

3. ### [2안] 웹서버 & DB 서버 구성 + WooCommerce 설정

   - 2대 EC2로 구성 : Web/PHP 인스턴스  + MariaDB 인스턴스 구성
     - WebSrv : 10.1.1.10 (프라이빗 IP)
     - DBSrv : 10.1.2.20 (프라이빗 IP)

   1. CloudFormation으로 배포 - Web/PHP, DB 설정 완료

      - UserData 참고

        - WebSrv

        ```
        #!/bin/bash
        hostnamectl --static set-hostname WebSrv
        amazon-linux-extras install lamp-mariadb10.2-php7.2 php7.2 -y
        yum install httpd htop -y
        systemctl start httpd && systemctl enable httpd
        echo "<?php phpinfo(); ?>" > /var/www/html/phpinfo.php
        yum install gcc php-xml php-mbstring php-sodium php-devel php-pear ImageMagick-devel ghostscript -y
        cat <<EOT> /etc/php.d/40-imagick.ini
        ; Enable imagick extension module
        extension = imagick.so
        EOT
        printf "\n" | pecl install imagick
        sed -i 's/^upload_max_filesize = 2M/upload_max_filesize = 64M/g' /etc/php.ini
        sed -i 's/^post_max_size = 8M/post_max_size = 64M/g' /etc/php.ini
        sed -i 's/^max_execution_time = 30/max_execution_time = 300/g' /etc/php.ini
        sed -i 's/^memory_limit = 128M/memory_limit = 256/g' /etc/php.ini
        wget https://ko.wordpress.org/wordpress-latest-ko_KR.zip
        unzip wordpress-latest-ko_KR.zip
        cp wordpress/wp-config-sample.php wordpress/wp-config.php
        sed -i "s/localhost/10.1.2.20/g" wordpress/wp-config.php
        sed -i "s/database_name_here/wordpressdb/g" wordpress/wp-config.php
        sed -i "s/username_here/root/g" wordpress/wp-config.php
        sed -i "s/password_here/qwe123/g" wordpress/wp-config.php
        cat <<EOT>> wordpress/wp-config.php
        define('WP_MEMORY_LIMIT', '256M');
        EOT
        cp -r wordpress/* /var/www/html/
        chown -R apache /var/www
        chgrp -R apache /var/www
        chmod 2775 /var/www
        find /var/www -type d -exec chmod 2775 {} \;
        find /var/www -type f -exec chmod 0664 {} \;
        systemctl restart php-fpm
        systemctl restart httpd
        ```

      - DBSrv

        ```
        #!/bin/bash
        hostnamectl --static set-hostname DBSrv
        amazon-linux-extras install lamp-mariadb10.2-php7.2
        yum install mariadb-server htop -y
        systemctl start mariadb && systemctl enable mariadb
        echo -e "\n\nqwe123\nqwe123\ny\nn\ny\ny\n" | /usr/bin/mysql_secure_installation
        sed -i'' -r -e "/\[mysqld\]/a\character-set-server=utf8" /etc/my.cnf.d/mariadb-server.cnf
        sed -i'' -r -e "/\[mysqld\]/a\collation-server=utf8_general_ci" /etc/my.cnf.d/mariadb-server.cnf
        sed -i'' -r -e "/\[mysqld\]/a\init_connect=\"SET NAMES utf8\"" /etc/my.cnf.d/mariadb-server.cnf
        sed -i'' -r -e "/\[mysqld\]/a\init_connect=\"SET collation_connection = utf8_general_ci\"" /etc/my.cnf.d/mariadb-server.cnf
        sed -i'' -r -e "/\[client\]/a\default-character-set=utf8" /etc/my.cnf.d/client.cnf
        sed -i'' -r -e "/\[mysql\]/a\default-character-set=utf8" /etc/my.cnf.d/mysql-clients.cnf
        sed -i'' -r -e "/\[mysqldump\]/a\default-character-set=utf8" /etc/my.cnf.d/mysql-clients.cnf
        mysql -uroot -pqwe123 -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'qwe123';"
        mysql -uroot -pqwe123 -e "CREATE DATABASE wordpressdb"
        systemctl restart mariadb
        ```

   2. Wordpress 웹 접속하여 관리자 계정 정보 입력 

   3. WooCommerce(v5.5.1) 다운로드 - [링크](https://downloads.wordpress.org/plugin/woocommerce.5.5.1.zip)

   4. [Wordresss] 플러그인 새로 추가 - 플러그인 업로드 - 다운 받은 WooCommerce 선택 → 지금 설치 → 플러그인 활성화

   5. [Wordresss - 우커머스] 상점 설정 → [테마] Storefront 선택 → Storefront 로 상점 디자인하기 시작해봅시다! → 시작해봅시다! 클릭

   6. [Wordress - 우커머스] 상품 추가

4. ### [3안] 웹서버(EFS) & AWS RDS 구성

   - 1대 EC2(EFS)와 AWS RDS(DB)로 구성 : Web/PHP 인스턴스 + AWS RDS(DB)로 구성
   - WebSrv2 : 10.1.1.20 + EFS(/var/www/wordpress)
   - AWS RDS

   1. AWS RDS(MySQL) 배포 : RDS → 데이터베이스 생성 클릭 → 데이터베이스 생성 4분 정도 소요

      ```
      # 별로 언급이 없는 부분은 기본값 설정입니다!
      생성 방식 : **표준 생성**
      엔진 옵션 : **MySQL**
      템플릿 : **프리 티어**
      DB 인스턴스 식별자 : ***wpdb** (현재 AWS 리전에서 AWS 계정이 소유하는 모든 DB 인스턴스에 대해 유일, 각자 편하게 설정)*
      마스터 사용자 이름 : **root**
      마스터 암호(암호확인) : **qwe12345**
      DB 인스턴스 클래스 : 버스터블 클래스(t 클래스 포함) **db.t2.micro**
      VPC : **WP-VPC1**
      퍼블릭 액세스 가능 : **아니요**
      VPC 보안 그룹 : ##-**VPC1SG3**-## 포함된것 선택 , 기본 default 는 제거
      추가 구성 : **클릭**
      - 초기 데이터베이스 이름 : **wordpressdb**
      - DB 파라미터 그룹 : ****##-**mydbparametergroup**-## 포함된것 선택
      - 자동 백업 활성화 : **Uncheck**
      ```

      ⇒ 생성 후 연결 & 보안 탭 메뉴에 엔드포인트(접속 주소) 메모!

   2. [WebSrv2] EFS 확인 및 RDS 엔드포인트 주소 설정 및 확인

      ```shell
      # 관리자 전환
      sudo su -
      
      # EFS 확인
      df |grep efs
      df -hT |grep efs
      
      # RDS 엔드포인트를 변수에 지정
      RDS=wpdb.cfd4iq95pfdk.ap-northeast-2.rds.amazonaws.com
      
      # wp-config.php 파일에 DB 주소를 RDS 엔드포인트로 설정
      sed -i "s/localhost/$RDS/g" /var/www/wordpress/wp-config.php
      
      # DB 접속 테스트
      mysql -h $RDS -uroot -pqwe12345 -e 'show databases;'
      mysql -h $RDS -uroot -pqwe12345
      ```

   3. Wordpress 웹에 접속하여 관리자 계정 정보 입력

