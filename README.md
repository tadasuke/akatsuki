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

# AK_core
ルーティングをしてくれるベースとなる機能です。  
## ルーティング
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

