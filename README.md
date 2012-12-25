PHP軽量フレームワーク　akatsuki
=======
# 概要
ゲームに特化したPHPのフレームワークです。  
LAMP+Memcacheにのみ対応しております。  
レスポンスの形式は現在のところJSONのみです。  
近日中にJSONPには対応する予定ですが、XMLなどは対応するつもりはありません。  
基本的なポリシーは以下のとおりです。

* 軽量
* ソースを書くことによって処理が早くなるのであればソースを書く
* フレームワーク内のモジュールが独立しており、一部のみを利用することもできる

# ライセンス
GPLです。  
で、どう書けばいいんでしょう・・・

# マニュアル

## AK_core
ルーティングをしてくれるベースとなる機能です。  
### ルーティング
**index.php**  

     require_once 'AK_Core.php';
     // インスタンス取得
     $akCoreClass = AK_Core::getInstance();
     // コントローラディレクトリ設定
     $akCoreClass -> setControllerDir( '/hoge/application/controllers' );
     // 処理開始
     $akCoreClass -> run();

*/user/index*  
が叩かれた場合  
/hoge/application/controllers/UserController.phpのindexActionが呼ばれる。

### 前処理・後処理
以下の順番に処理が呼ばれる。  

1. beforeRun
2. hogeAction
3. afterRun

### パラメータ
GETパラメータは以下のように取得できる。

	$param = $this -> getGetParam( 'key' );

POSTパラメータは以下のように取得できる。

	$param = $this -> getPostParam( 'key' );

以下のようなパラメータはユーザパラメータとして取得可能  
*/user/index/hoge/fuga*

	$paramArray = $this -> getAllUserParam();
	echo( $paramArray[0] );  // hogeが出力される
	echo( $paramArray[1] );  // fugaが表示される
	


