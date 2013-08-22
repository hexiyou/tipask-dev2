<?php
echo '<title>no access!</title>';
echo $_SERVER['REMOTE_ADDR'];
echo '连接被防火墙阻断，该区域网络禁止向外提供HTTP服务';
exit(0);

?>