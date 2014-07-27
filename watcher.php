<pre><meta http-equiv="refresh" content="10; URL=http://localhost/bitcoinWatcher/watcher.php">
<?php

echo 'Last refresh: '.date('d.m.Y H:i:s');

$src = file_get_contents('https://www.bitcoin.de/de');

preg_match('!<img[\s]*alt="EUR".*?>[\s]*<b>(.*?)</b>!is', $src, $res);

$bitcoinValue = trim($res[1]);
$bitcoinValue = cleanNumber($bitcoinValue);

$src = file_get_contents('https://www.bitcoin.de/de/offerSearch');

preg_match_all('!<tr>.*?<td.*?>(.*?)</td>.*?<td.*?>(.*?)</td>.*?<td.*?>(.*?)</td>.*?<td.*?>(.*?)</td>.*?<td.*?>(.*?)</td>.*?<td.*?>(.*?)</td>.*?<td.*?>(.*?)</td>.*?</tr>!is', $src, $res);

foreach ($res[0] as $key => $value)
{
	$currentValue = cleanNumber($res[2][$key]);
	
	preg_match('!href="(/de/buyOffer.*?)"!is', $res[7][$key], $buyLinkRes);
	
	$buyUrl = 'https://bitcoin.de/'.$buyLinkRes[1];
	
	// var_dump($currentValue);	
	// var_dump($bitcoinValue / 2);	
	
	$foundCount = 0;
	
	$goal = $bitcoinValue * 0.8;
	
	echo '<br>';
	echo $currentValue.' <= '.$goal;
	
	if ($currentValue <= $goal)
	{
		system('explorer.exe /n /root,'.$buyUrl);
		// mail('bitcoin@yourdomain.de', 'Cheap bitcoin detected!', 'Gogogo! Buy it! '.$buyUrl);
		// echo 'Mail sent for '.$buyUrl,'<br />';
		++$foundCount;
	}	
}

echo '<br>';
echo 'Found count: '.$foundCount;

function cleanNumber ($input)
{
	return floatval(str_replace(',', '.', preg_replace('![^0-9,.]!is', '', $input)));
}