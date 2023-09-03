# shop

1 まず、自分用のデータベースを作成します。
2 db.sql ファイルをデータベースにインポートします。
3 include/config.php に移動し、要求された情報を入力します。
4 セットアップするbot.phpファイルを設定します。

以下のアドレスに 1 分間の cron ジョブを設定します
/usr/bin/php -q /home/userhost/public_html/source/checkStatus.php
