# devcon-php

これは Docker Hub の Official Image をベースとする PHP 用の開発環境を Dev Container 環境として用意したものです。

PHP は [php](https://hub.docker.com/_/php) の `php:8.1-apache`、DB サーバーは [mysql](https://hub.docker.com/_/mysql) の `mysql:8.0.32-debian` を使っています。
また、DB サーバーのデータ操作用に [adminer](https://hub.docker.com/_/adminer) の `adminer:4.8.1-standalone` も使えるようにしています。
開発するアプリは devcon-php コンテナーの `/var/www/html` （つまり、`devcon-php:/var/www/html`）へ置くことを想定しています。

## 必要なもの

devcon-php を動作をさせるには、Docker、Docker Compose、Visual Studio Code (VS Code) 、Dev Containers 拡張機能が必要です。

### Docker

- [Docker Engine](https://docs.docker.com/engine/)
- [Docker Compose](https://docs.docker.com/compose/)

これらは [Docker Desktop](https://docs.docker.com/desktop/) をインストールしてあれば使えます。
Linux では Docker Desktop をインストールしなくても Docker Engine と Docker Compose だけをインストールして使えます。
例えば、Ubuntu を使っているなら [Install Docker Engine on Ubuntu](https://docs.docker.com/engine/install/ubuntu/) を参照してインストールしておいてください。

### Visual Studio Code

- [Visual Studio Code](https://code.visualstudio.com/)
- [Dev Containers 拡張機能](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers)

VS Code の拡張機能である Dev Containers を VS Code へインストールしておく必要があります。

### 動作確認済みの環境

次の環境で動作確認をしてあります。Windows や macOS でも動作するはずです。

```console
$ cat /etc/os-release
PRETTY_NAME="Ubuntu 22.04.1 LTS"
NAME="Ubuntu"
VERSION_ID="22.04"
VERSION="22.04.1 LTS (Jammy Jellyfish)"
VERSION_CODENAME=jammy
ID=ubuntu
ID_LIKE=debian
HOME_URL="https://www.ubuntu.com/"
SUPPORT_URL="https://help.ubuntu.com/"
BUG_REPORT_URL="https://bugs.launchpad.net/ubuntu/"
PRIVACY_POLICY_URL="https://www.ubuntu.com/legal/terms-and-policies/privacy-policy"
UBUNTU_CODENAME=jammy

$ docker version
Client: Docker Engine - Community
 Cloud integration: v1.0.29
 Version:           23.0.1
 API version:       1.42
 Go version:        go1.19.5
 Git commit:        a5ee5b1
 Built:             Thu Feb  9 19:47:01 2023
 OS/Arch:           linux/amd64
 Context:           default

Server: Docker Engine - Community
 Engine:
  Version:          23.0.1
  API version:      1.42 (minimum version 1.12)
  Go version:       go1.19.5
  Git commit:       bc3805a
  Built:            Thu Feb  9 19:47:01 2023
  OS/Arch:          linux/amd64
  Experimental:     false
 containerd:
  Version:          1.6.18
  GitCommit:        2456e983eb9e37e47538f59ea18f2043c9a73640
 runc:
  Version:          1.1.4
  GitCommit:        v1.1.4-0-g5fd4c4d
 docker-init:
  Version:          0.19.0
  GitCommit:        de40ad0

$ docker compose version
Docker Compose version v2.15.1
```

## ファイルの構成

ファイルの構成は次のようになっています。

```text
devcon-php/
├── .devcontainer/ ... Dev Container 用のディレクトリー
│   └── devcontainer.json
├── README.md ... このファイル
├── build/ ... カスタム Docker イメージのビルド用
│   ├── Dockerfile
│   ├── docker-compose.yml
│   └── sample.env
├── docker-compose.yml ... Dev Container 用 docker-compose.yml
├── sample.env ... Dev Container 用 docker-compose.yml 用 env ファイル
└── workspace/
    ├── share/ ... Dev Container へコピーするファイルを置くためのディレクトリー
    │   ├── htdocs/
    │   │   └── index.php
    │   └── html.code-workspace
    └── usr_local_etc_php/
        └── php.ini ... Dev Container からバインドマウントする php.ini
```

この後、リポジトリをクローンもしくはアーカイブファイルを展開した `devcon-php` ディレクトリーのパスを `${REPO_DIR}` と表現します。

## 使い方

起動の仕方は、次のどちらかを想定しています。

- Dev Container として開かずに通常のコンテナーとして起動
- Dev Container として起動

起動したら、`devcon-php:/var/www/` ディレクトリーにワークスペースファイル、`devcon-php:/var/www/html/` ディレクトリーに PHP ファイルをおいて利用することを想定しています。

慣れないうちは通常のコンテナーとして起動して使ってください。
慣れてきて、PHP の開発専用で使うようになったら Dev Container として起動して使うのが良いです。

### 通常のコンテナーとして起動

先に「ビルド」を参照して Docker イメージを作成してください。
ビルド時とは別に `docker-compose.yml` ファイル用の「環境変数」を `.env` ファイルで指定できます。
必要なら「環境変数」を参考にして `.env` ファイルを用意してください。

それから、docker-compose.yml を使って devcon-php コンテナーを起動します。

```console
cd `${REPO_DIR}`
docker compose up -d
```

VS Code の Docker 拡張機能画面で devcon-php コンテナーのコンテキストメニューで `Attach Visual Studio Code` を選択して、アタッチします。
すると devcon-php コンテナー用の VS Code の画面が開いて使えるようになります。

### Dev Container として起動

先に「ビルド」を参照して Docker イメージを作成してください。
ビルド時とは別に `docker-compose.yml` ファイル用の「環境変数」を `.env` ファイルで指定できます。
必要なら「環境変数」を参考にして `.env` ファイルを用意してください。

それから、F1 キーを入力して VS Code のコマンドパレットを表示してから、「Dev Containers: Open Folder in Container...」をクリックします。
フォルダーを選択する画面になるので `${REPO_DIR}` を指定して開きます。
すると `${REPO_DIR}/.devcontainer/devcontainer.json` の指定にしたがって、devcon-php コンテナーが Dev Container として起動します。
このとき、拡張機能なども追加されます。
それから、devcon-php コンテナー用の VS Code の画面となります。

つまり、開いている VS Code の画面が、そのまま devcon-php コンテナー用の VS Code の画面として開き直されます。
devcon-php コンテナーでは Docker ホストのファイルを間違えて操作しないように、`${REPO_DIR}` は見えないようにしてあります。
慣れないうちは、`${REPO_DIR}` が見える VS Code の画面もあった方がわかりやすいと思います。
こちらは、慣れてから使うようにすると良いでしょう。

### コンテナー内に開発で使うファイルを用意

次に、コンテナー内に開発で使うファイルを用意します。
`${REPO_DIR}/workspace/share` 内に使うファイルを用意すると、コンテナー内では `devcon-php:/workspace/share/` 内のファイルとして見えるようになります。

ここでは、サンプルとして `devcon-php:/workspace/share/sample` を用意してあるので、それを使います。
devcon-php コンテナーの VS Code のターミナルを使う場合は、次のようにファイルをコピーします。

```console
www-data@devcon-php:~$ cd /var/www
www-data@devcon-php:~$ cp /workspace/share/sample/html.code-workspace .
www-data@devcon-php:~$ cp /workspace/share/sample/htdocs/index.php html/
```

`/var/www/html.code-workspace` は VS Code のワークスペースファイルです。
これを開いて、PHP プログラムの開発ができます。

```console
www-data@devcon-php:~$ code /var/www/html.code-workspace
```

ワークスペースでは VS Code の「実行とデバッグ」画面で下記の3つのデバッグ構成が使えるようにしてあります。

- Listen for Xdebug
- Launch currently open script
- Launch Built-in web server

動作しない場合は `./workspace/user_local_etc_php/php.ini` の `xdebug.client_host` を見直してください。

### Listen for Xdebug

Listen for Xdebug を使う場合は、PHP のソースコードの `/var/www/html/index.php` にブレイクポイントを設定してから、<http://localhost:8080/> へアクセスすると、ブレイクポイントでプログラムが止まります。
Web ブラウザでも `curl` コマンドでも動作します。

`curl` コマンドを使う場合は、コンテナー内であれば下記のようにします。

```console
curl http://localhost:8080/
```

Docker ホストであれば下記のようにします。

```console
curl http://127.0.0.1:8080/
```

### Launch currently open script

Launch currently open script を使うと、エディタで開いている PHP ファイルを対象として、ターミナルで `php` コマンドがデバッグモードで実行されます。

### Launch Built-in web server

Launch Built-in web server を使うと `php -S 0.0.0.0:10080` を実行して、PHP ビルトインの Web サーバーを使ってデバッグすることができます。いまのところ、Web ブラウザからアクセスしても受け付けませんが、ターミナルからのアクセスだとデバッグが動きます。

コンテナー内、Docker ホストで下記でアクセスできます。

```console
curl http://localhost:10080/
```

localhost でうまく動作しない場合は 127.0.0.1 を使います。
127.0.0.1 ではなく、devcon-php コンテナーをインスペクトすることでわかるコンテナーの IP か ゲートウェイの IP を指定することもできます。

## データベースサーバー関連

MySQL はポート番号 13306 で Dokcer ホストから接続できるようにしてあります。
Adminer はポート番号 5080 で Dokcer ホストから接続できるようにしてあり、<http://localhost:5080> で利用できます。

## 使用している Docker ボリューム

下記の Docker ボリュームを自動で作成して利用するようになっています。

- devcon-php-data ... `devcon-php:/var/www` 用のボリューム
- devcon-php-vscode-server-extensions ... VS Code の拡張機能のダウンロードしたものを保持するためのボリューム
- devcon-php-mysql-data ... MySQL データベース用のボリューム

## ビルド

最初にビルドが必要です。
Dev Container 環境を起動する度に自動でビルドを実行する必要はないので、ビルド作業を別にしてあります。
実行時用のものと似たような `docker-compose.yml` を用意することになりますが、こうしておいた方が Docker イメージのタグ名指定が設定ファイルで明示的にわかるようになります。また、意図しない更新も入りにくくなり、利用時に安定します。

なお、macOS を Docker ホストとする場合など、ユーザーの UID と GID を調整したい場合があるでしょう。
`.env` で指定できるようにしてあります。
sample.env を参考にして `.env` を作成してからビルドしてください。

ビルドは次のように実行します。

```console
cd ${REPO_DIR}
docker compose -f build/docker-compose.yml build --no-cache
```

ビルドが成功すると devcon-php-build コンテナーが起動します。次のようにして削除します。

```console
docker compose -f build/docker-compose.yml down
```

## 環境変数

`.env` ファイルを用意すると `docker-compose.yml` 内の `${変数名}` で指定されているものを、`.env` で指定したものへ変更できます。
具体的な指定方法は `sample.env` ファイルを参考にしてください。
特にポート番号の衝突を避ける必要がある場合に利用してください。

Listen for Xdebug で使うポート番号は `PHP_PORT`、Launch Built-in web server で使うポート番号は `PHP_BUILT_IN_PORT` で変更可能です。
これらを変更した場合は、``${REPO_DIR}/workspace/share/html.code-workspace` の対応する `port:` の変更も必要です。

`MYSQL_` で始まるものは devcon-php-mysql サービス用です。MySQL が使うポート番号は `MYSQL_PORT` で指定できます。

`ADMINER_PORT` は devcon-php-adminer サービス用で、Adminer が使うポート番号を指定できます。
