CloudStack API Library for PHP
======================
CloudStack APIをPHPから利用する際に必要なシグネチャの生成をPHPで行うためのライブラリです。

ファイル構成
------
このライブラリは以下のファイル構成となっています。  
* api.php  
APIへのアクセスやその際に必要となるシグネチャの生成を行うライブラリ本体
* index.html  
api.phpを使用したCloudStack APIコンソールのサンプルアプリケーション
* README.md

使い方
------
api.phpをエディタで開き、以下の部分を環境に合わせて編集します。

```
// 各種設定ここから
define("config_callurlmethod", "curl");	// APIを呼び出す方法を指定。現在はcurlを指定した場合はcurlを使用し、それ以外が指定された場合はfile_get_contentsを使用
define("config_cloudstack_api_baseurl", "http://examples.com/"); // baseURLを設定
define("config_cloudstack_api_apipath", "/client/api?"); // APIパスを設定（デフォルトでは/client/api?）
define("config_cloudstack_api_key", ""); // APIキーを設定
define("config_cloudstack_api_secret", ""); // APIシークレットを設定
// 各種設定ここまで
```

編集が終わったら、index.htmlとapi.phpを同一のディレクトリ内に設置し、ブラウザからindex.htmlにアクセスします。  
画面上部に表示されているテキストボックスに実行したいコマンドやそのパラメータ（いわゆるCommand String）を入力し、「API実行」をクリックすることで、画面下部の「API実行結果（XML）」と表示されている部分にAPI実行結果のXMLが表示されます。  
api.phpを直接使う場合は、コード中のコメントを参考にしてください。

ライセンス
------

```
Copyright 2012 Muzeria Lushe

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
```
