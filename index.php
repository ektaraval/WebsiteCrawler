<?php
/*
	-------------------------------------------------------------------------------------
	Date Created 			:	1/12/2022
	Date Last Modified 		:
	=====================================================================================
	SCREEN					:	index.php
	DESCRIPTION				:	Website Crawler
	-------------------------------------------------------------------------------------
*/

include_once "index.class.php";
$crawlCnt = 5;
$objScr = new CRAWL();
$objScr->crawl_page('https://www.complyworks.com', $crawlCnt);
?>

<!doctype html>
<html lang="en">
 <head>
    <title>Web Crawler</title>
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
 </head>
 <body>
	<div class="container-fluid">
		<div><h1>Test web crawler</h1></div>
		<div class="row">
			<div class="col-8 left">
				<table class="table table-bordered" >
					<thead class="table-light"><tr>
						<th>Requested URL</th>
						<th>Status Code</th>
					</tr></thead>
					<tbody>
						<?php
						foreach($objScr->arrCrawledURLs as $UrlInfo){
							echo("<tr>");
								echo("<td>" . $UrlInfo['URL'] . "</td>");
								echo("<td>" . $UrlInfo['StatusCode'] . "</td>");
							echo("</tr>");
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
		<br><br>
		<div class="row">
			<div class="col-5 left">
				<table class="table table-striped" width=50% >
					<tbody>
						<tr>
							<td>Number of pages crawled</td>
							<td><?php echo(count($objScr->arrCrawledURLs)); ?></td>
						</tr>
						<tr>
							<td>Number of a unique images</td>
							<td><?php echo(count($objScr->arrImages)); ?></td>
						</tr>
						<tr>
							<td>Number of unique internal links</td>
							<td><?php echo(count($objScr->arrInternalLinks)); ?></td>
						</tr>
						<tr>
							<td>Number of unique external links</td>
							<td><?php echo(count($objScr->arrExternalLinks)); ?></td>
						</tr>
						<tr>
							<td>Average page load time in seconds</td>
							<td><?php echo(($objScr->totalPageLoadTime) / ($crawlCnt + 1)) ?></td>
						</tr>
						<tr>
							<td>Average word count</td>
							<td><?php echo(round((str_word_count($objScr->strContent)) / ($crawlCnt + 1)))?></td>
						</tr>
						<tr>
							<td>Average title length</td>
							<td><?php echo(round((strlen(trim($objScr->strTitles))) / ($crawlCnt + 1))) ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
 </body>
</html>


<?php
/*print('<br><br> <b>Crawled URLs : </b>');
print_r($objScr->arrCrawledURLs);
print('<br><br> <b>Crawled Images : </b>');
print_r($objScr->arrImages);
print('<br><br> <b>Crawled Internal Links : </b>');
print_r($objScr->arrInternalLinks);
print('<br><br> <b>Crawled External Links : </b>');
print_r($objScr->arrExternalLinks);
print('<br><br> <b>Total Page Load Time : </b>');
print_r($objScr->totalPageLoadTime);
print('<br><br> <b>Title : </b>');
print_r($objScr->strTitles);
print('<br><br> <b>Content : </b>');
print_r($objScr->strContent);*/

?>