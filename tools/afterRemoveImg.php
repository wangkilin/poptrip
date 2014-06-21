<?php
set_time_limit(0);

$server = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'pop';

$conn = mysql_connect($server, $username, $password);
mysql_select_db($databaseName, $conn);
mysql_query('set names "UTF8"');
$imgList = array(
"http://img1.qunarzz.com/travel/poi/201403/14/3dee78cb0064814fddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/178bd19fa71b993dddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201403/21/08d79da57fad192bddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/ca737381a8f95ab3ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/aa84c54924eab3c7ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d2/201402/08/c1a372cda4dda13fddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/01d0b849d7521325ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/272fa73b853e2e6dddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d3/201306/11/cc324b8a163d2f02ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d8/201306/11/bcc0c168778ee53eddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d3/201306/11/cc324b8a163d2f02ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d8/201306/11/51f4f7351ab09a49ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d8/201306/11/51f4f7351ab09a49ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/a9b4092df57d2a7fddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/a9b4092df57d2a7fddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/a9b4092df57d2a7fddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/a9b4092df57d2a7fddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d2/201403/25/65486245a8fd905fddb12cfb.jpg",
"http://img1.qunarzz.com/wugc/p207/201204/20/4d66f7f8b651a2c093835fbb.jpg",
"http://img1.qunarzz.com/wugc/p207/201204/20/4d66f7f8b651a2c093835fbb.jpg",
"http://img1.qunarzz.com/wugc/p207/201204/20/4d66f7f8b651a2c093835fbb.jpg",
"http://img1.qunarzz.com/wugc/p207/201204/20/4d66f7f8b651a2c093835fbb.jpg",
"http://img1.qunarzz.com/travel/d0/201401/29/e1d5dae7fc6c5eb5ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d6/201401/29/4ffa2d1085a475aaddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201401/29/03e95c6008ec9a74ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d4/201401/29/e1d5dae7fc6c5eb5ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d8/201401/29/4ffa2d1085a475aaddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d1/201312/12/48a3d63f514c90b7ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d4/201307/15/cb203f2c2de1ed4addb12cfb.jpg",
"http://img1.qunarzz.com/travel/d4/201401/14/2b28c1fddab2d44bddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/61212f5db3194a26ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/61212f5db3194a26ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/61212f5db3194a26ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/63d35bcbc5eb35bfddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d5/201306/08/7c7f641749eee1fcddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201312/30/c94997c953565baeddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201311/13/81161f0385dc31aeddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d7/201303/27/862e8abf4a90f5e3ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/4a36db9d5292b7bbddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d6/201403/18/dce27bb461dd596cddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d6/201311/13/81161f0385dc31aeddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/a9b4092df57d2a7fddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/21cd756c9f717446ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/21cd756c9f717446ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d1/201403/05/77ef8eb07187587eddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d4/201402/02/7e2a2c587d2cd0b5ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d6/201401/15/542933a6fc44ec76ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d8/201312/26/7a9a51e3bee1ceb0ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201206/11/b91e31abb4d2888c6886324a.jpg",
"http://img1.qunarzz.com/travel/d7/201308/06/5384b0d4a342cb5eddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/736032e09a61185bddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201206/11/938169a3728d752d6886324a.jpg",
"http://img1.qunarzz.com/travel/d5/201403/20/2772e95fd4798b32ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d0/201402/19/2228838d65758e0dddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/a919c3ceda5fe11addb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201310/12/ca25a0d1a3c42fb2ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d4/201403/20/894c0cf0324e0833ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201312/25/c006217b00899d52ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/d19c25cb94c52101ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201402/03/2dba4bee4951258eddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201402/03/233a2abf4f2a6e3dddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d5/201402/03/f2b2cc14b93e40e4ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d6/201402/03/e1bd79603808dd4fddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201312/09/dce54b9fa33949afddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201208/15/97f59c79367cf1b0ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/a9b4092df57d2a7fddb12cfb.jpg",
"http://img1.qunarzz.com/wugc/p150/201206/07/1b7b5d0e8b132cbf93835fbb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/405fd3c58e8b2ed0ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/f5ae31c7e605ae94ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d6/201312/06/41f398e597828e2addb12cfb.jpg",
"http://img1.qunarzz.com/travel/d1/201311/20/9e0523518f80eb81ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d5/201311/20/c1f2ff107f50f3feddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/362be10d06aaaf74ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/22748482da36499eddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/e48e444486f377beddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/354e2f03802e367fddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/85ee378b0e83d6d2ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/acd26abe2205a8afddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/fad3d192080fc8c7ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/61f1f409da7d09b0ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201206/11/2e59aeb1e9c4503d6886324a.jpg",
"http://img1.qunarzz.com/travel/d9/201309/13/8df8f8ea5bc22633ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201207/30/84d0933dd617adeeddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/fe30a809835e0431ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201312/06/52fbf83c5f41231cddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201403/14/eea1632de26c1c87ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/7c9d46475180f8baddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/24180cb9a1f951f8ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d1/201402/23/b76e8ff76be1d2c4ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d1/201402/14/4b04335cc96fd006ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201402/14/d92239e8ec1db82eddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d3/201402/14/1c85ad9d68018936ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201402/14/a76286251cb88922ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201402/14/a76286251cb88922ddb12cfb.jpg_r_80x80_2e1f5403.jpg",
"http://img1.qunarzz.com/travel/d3/201401/04/02ea171e7b1af827ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d3/201401/04/02ea171e7b1af827ddb12cfb.jpg_r_80x80_ebf23255.jpg",
"http://img1.qunarzz.com/travel/d9/201401/04/4cd5eb61f4e5ef05ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201401/04/4cd5eb61f4e5ef05ddb12cfb.jpg_r_80x80_b2084bbe.jpg",
"http://img1.qunarzz.com/travel/d1/201401/04/271e7e75c0268423ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d1/201401/04/271e7e75c0268423ddb12cfb.jpg_r_80x80_07e23a3d.jpg",
"http://img1.qunarzz.com/travel/d9/201312/18/4cf4bef57a8fdd55ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201312/18/4cf4bef57a8fdd55ddb12cfb.jpg_r_80x80_11076d07.jpg",
"http://img1.qunarzz.com/travel/d7/201312/18/10518fc4395c3008ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d7/201312/18/10518fc4395c3008ddb12cfb.jpg_r_80x80_088e33e2.jpg",
"http://img1.qunarzz.com/travel/d1/201312/18/6be67bb9c6e3afa2ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d1/201312/18/6be67bb9c6e3afa2ddb12cfb.jpg_r_80x80_f06a4390.jpg",
"http://img1.qunarzz.com/travel/d3/201312/18/f72a574f0d261becddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d3/201312/18/f72a574f0d261becddb12cfb.jpg_r_80x80_9e71bffa.jpg",
"http://img1.qunarzz.com/travel/d3/201307/19/5bf9962e76caf325ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d3/201307/19/5bf9962e76caf325ddb12cfb.jpg_r_80x80_2d977d34.jpg",
"http://img1.qunarzz.com/travel/poi/201208/15/e8b876f821151c7dddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201208/15/e8b876f821151c7dddb12cfb.jpg_r_80x80_1c53db3c.jpg",
"http://img1.qunarzz.com/wugc/p124/201204/20/55aa9bf3c233e43c93835fbb.jpg",
"http://img1.qunarzz.com/wugc/p124/201204/20/55aa9bf3c233e43c93835fbb.jpg_r_80x80_804055aa.jpg",
"http://img1.qunarzz.com/travel/poi/201206/11/b91e31abb4d2888c6886324a.jpg",
"http://img1.qunarzz.com/travel/poi/201206/11/b91e31abb4d2888c6886324a.jpg_r_80x80_a51c2a99.jpg",
"http://img1.qunarzz.com/travel/d9/201401/30/dccfb75bda75d726ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201401/30/dccfb75bda75d726ddb12cfb.jpg_r_80x80_343a3477.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/877c2c3432fc9ff3ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/877c2c3432fc9ff3ddb12cfb.jpg_r_80x80_1d12bf4e.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/2b7946fd4dfd5c34ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/2b7946fd4dfd5c34ddb12cfb.jpg_r_80x80_554ee6be.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/00a0fb5b8ebcbd48ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/00a0fb5b8ebcbd48ddb12cfb.jpg_r_80x80_d15306a1.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/9598a3d9e5c66b9eddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/9598a3d9e5c66b9eddb12cfb.jpg_r_80x80_a08e8010.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/234e34bd3bbd6bedddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/234e34bd3bbd6bedddb12cfb.jpg_r_80x80_139fedea.jpg",
"http://img1.qunarzz.com/travel/poi/201207/30/19453d3a99f4afc4ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201207/30/19453d3a99f4afc4ddb12cfb.jpg_r_80x80_faaec8ee.jpg",
"http://source.qunar.com/place/imgs/98/65298/orig.jpg",
"http://source.qunar.com/place/imgs/98/65298/orig.jpg",
"http://source.qunar.com/place/imgs/96/65296/orig.jpg",
"http://source.qunar.com/place/imgs/96/65296/orig.jpg",
"http://img1.qunarzz.com/travel/d6/201402/26/857e86d2badc33dcddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d6/201402/26/857e86d2badc33dcddb12cfb.jpg_r_80x80_1a7b8e2b.jpg",
"http://img1.qunarzz.com/travel/poi/201211/15/7b3401bfaad9101bddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201211/15/7b3401bfaad9101bddb12cfb.jpg_r_80x80_94d4e002.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/0df6ced0864589cfddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/0df6ced0864589cfddb12cfb.jpg_r_80x80_ae268d6f.jpg",
"http://img1.qunarzz.com/travel/poi/201208/15/a9b0f3c4cb758b8bddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201208/15/a9b0f3c4cb758b8bddb12cfb.jpg_r_80x80_c4fc09ff.jpg",
"http://img1.qunarzz.com/travel/poi/201206/11/3361bcf332bc1fd16886324a.jpg",
"http://img1.qunarzz.com/travel/poi/201206/11/3361bcf332bc1fd16886324a.jpg_r_80x80_512beb6b.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/0a3e328f288b493bddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/13/0a3e328f288b493bddb12cfb.jpg_r_80x80_b81a4b14.jpg",
"http://img1.qunarzz.com/travel/poi/201211/15/8392d751c7186250ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201211/15/8392d751c7186250ddb12cfb.jpg_r_80x80_a1c2e1c6.jpg",
"http://img1.qunarzz.com/travel/poi/201312/26/1cb847e21974434fddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201312/26/1cb847e21974434fddb12cfb.jpg_r_80x80_2648b29c.jpg",
"http://img1.qunarzz.com/travel/poi/201206/11/f1181ad0ae7aeb136886324a.jpg",
"http://img1.qunarzz.com/travel/poi/201206/11/f1181ad0ae7aeb136886324a.jpg_r_80x80_eee7374c.jpg",
"http://img1.qunarzz.com/travel/d7/201403/06/8bfe0ad59f2ef3ceddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d7/201403/06/8bfe0ad59f2ef3ceddb12cfb.jpg_r_80x80_3af6926d.jpg",
"http://img1.qunarzz.com/travel/poi/201206/11/891423d91ee716c96886324a.jpg",
"http://img1.qunarzz.com/travel/poi/201206/11/891423d91ee716c96886324a.jpg_r_80x80_026ffa55.jpg",
"http://img1.qunarzz.com/travel/poi/201211/15/d156cd8037592e4fddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201211/15/d156cd8037592e4fddb12cfb.jpg_r_80x80_ebd8b765.jpg",
"http://img1.qunarzz.com/travel/d6/201401/02/521379b5f589590cddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d6/201401/02/521379b5f589590cddb12cfb.jpg_r_80x80_5a287be5.jpg",
"http://img1.qunarzz.com/travel/d8/201401/02/a3a3dbcb8e8cac1bddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d8/201401/02/a3a3dbcb8e8cac1bddb12cfb.jpg_r_80x80_6eb57ee8.jpg",
"http://img1.qunarzz.com/travel/d1/201401/02/6b3b07e1538b7dbbddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d1/201401/02/6b3b07e1538b7dbbddb12cfb.jpg_r_80x80_52c31ba1.jpg",
"http://source.qunar.com/place/imgs/84/80684/orig.jpg",
"http://source.qunar.com/place/imgs/84/80684/orig.jpg",
"http://img1.qunarzz.com/travel/d1/201306/23/50510f728fef8cfdddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d1/201306/23/50510f728fef8cfdddb12cfb.jpg_r_80x80_785af1d0.jpg",
"http://img1.qunarzz.com/travel/d8/201306/23/1491dcbd8bfa5e7addb12cfb.jpg",
"http://img1.qunarzz.com/travel/d8/201306/23/1491dcbd8bfa5e7addb12cfb.jpg_r_80x80_6f878577.jpg",
"http://img1.qunarzz.com/travel/d5/201306/23/438dc7b5647e361bddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d5/201306/23/438dc7b5647e361bddb12cfb.jpg_r_80x80_c7e01502.jpg",
"http://img1.qunarzz.com/travel/d9/201306/23/3e94e566147e7529ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201306/23/3e94e566147e7529ddb12cfb.jpg_r_80x80_d065bf7f.jpg",
"http://img1.qunarzz.com/travel/d9/201306/23/1a9bc438efdb4424ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d9/201306/23/1a9bc438efdb4424ddb12cfb.jpg_r_80x80_2836160e.jpg",
"http://img1.qunarzz.com/travel/d7/201306/23/dd263905657f2ac5ddb12cfb.jpg",
"http://img1.qunarzz.com/travel/d7/201306/23/dd263905657f2ac5ddb12cfb.jpg_r_80x80_097bcf35.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/d5cb373d2496b79dddb12cfb.jpg",
"http://img1.qunarzz.com/travel/poi/201303/14/d5cb373d2496b79dddb12cfb.jpg_r_80x80_b702f74b.jpg",
);
$imgList = array_unique($imgList);
$imgRootDir = './';
foreach($imgList as $_imgUrl) {
$query = "SELECT i.*
          FROM scenery_img i
          WHERE img_url ='".$_imgUrl."'";
	$result = mysql_query($query);
	$tmpRow = null;
	while(($row=mysql_fetch_assoc($result))) {
	    if(!$tmpRow) {
		    $tmpRow = $row;
			continue;
		}
		
		if($tmpRow['img_url']==$row['img_url'] && $tmpRow['icon_url']==$row['icon_url'] && $tmpRow['small_url']==$row['small_url']
		    && $tmpRow['middle_url']==$row['middle_url'] && $tmpRow['big_url']==$row['big_url']) {
			unlink($row['img_path']);unlink($row['icon_path']);unlink($row['small_path']);unlink($row['middle_path']);unlink($row['big_path']);
			$query = "delete from scenery_img where img_id = " . $row['img_id'];
			mysql_query($query);
			continue;
		}
		echo $row['img_id'] ."\r\n";
		$flag = 0;
		if($tmpRow['img_url']==$row['img_url']) {
		    $query = 'update scenery_img set img_path = "'. $tmpRow['img_path'] . '" where img_id = ' . $row['img_id'];
			mysql_query($query);
			unlink($row['img_path']);
			$flag = $flag + 1;
		}
		if($tmpRow['icon_url']==$row['icon_url']) {
		    $query = 'update scenery_img set icon_path = "'. $tmpRow['icon_path'] . '" where img_id = ' . $row['img_id'];
			mysql_query($query);
			unlink($row['icon_path']);
			$flag = $flag + 1;
		}
		if($tmpRow['small_url']==$row['small_url']) {
		    $query = 'update scenery_img set small_path = "'. $tmpRow['small_path'] . '" where img_id = ' . $row['img_id'];
			mysql_query($query);
			unlink($row['small_path']);
			$flag = $flag + 1;
		}
		if($tmpRow['middle_url']==$row['middle_url']) {
		    $query = 'update scenery_img set middle_path = "'. $tmpRow['middle_path'] . '" where img_id = ' . $row['img_id'];
			mysql_query($query);
			unlink($row['middle_path']);
			$flag = $flag + 1;
		}
		if($tmpRow['big_url']==$row['big_url']) {
		    $query = 'update scenery_img set big_path = "'. $tmpRow['big_path'] . '" where img_id = ' . $row['img_id'];
			mysql_query($query);
			unlink($row['big_path']);
			$flag = $flag + 1;
		}
		
		
	}
}